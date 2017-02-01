<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
 /**
  * This class is to Cosign to admin page in Omeka
  */
class CosignPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
    * @var array Hooks for the plugin.
    */
    protected $_hooks = array('define_routes','config_form','config');

    protected $_filters = array('login_adapter','login_form');

    public function __construct()
    {
        parent::__construct();
        if (isset($_SERVER['REMOTE_USER'])) {
            /**
            * When logged in, we want to ensure that we're using secure cookies.
            *
            * We also want to separate the cookie name, so that http, and https
            * pages do not keep clobbering session ids.
            *
            * The problem is, Zend_Registry::get('bootstrap')
            *   ->getResource('Config') is read-only.  So we copy it, change the
            * copy and replace it in the Zend_Registry::get('bootstrap')
            * container.
            *
            * Preferred: A filter on the thing that initializes the Zend_Config.
            */
            ini_set('session.cookie_secure', TRUE);
            $bootstrap = Zend_Registry::get('bootstrap');
            $config = $bootstrap->getResource('Config');
            $newConfig = new Zend_Config($config->toArray(), TRUE);
            $newConfig->session->name = 'S' . md5(BASE_DIR);
            $bootstrap->getContainer()->config = $newConfig;
        }
    }

    //show plugin configuration page
    public function hookConfigForm()
    {
        include('config_cosign_form.php');
    }

    //save plugin configurations in the database
    public function hookConfig($post)
    {
        set_option('cosign_logout_url', trim($_POST['cosign_logout_url']));
    }

    /**
    * The purpose of this filter is to by pass the Omeka login form so it will
    * not display to users. But still we need to pass a username and password
    * that is expected from Omeka form. The password generated here is not stored in the database.
    * The passowrd stored in db came from UserControl where secure password is created.
    * The user is authenticare through the
    * cosign first then the filter for the login form is called.
    */
    public function filterLoginForm($loginform)
    {
        if ((isset($_SERVER['REMOTE_USER']))) {
            $length = 16;
            $_POST['username'] = $_SERVER['REMOTE_USER'];
            if (function_exists('openssl_random_pseudo_bytes')) {
                $_POST['password'] = openssl_random_pseudo_bytes($length);
            } else {
                $characters = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()" .
                    "-_<>,./:;'{[}]\\|~`";
                $_POST['password'] = substr(
                    str_shuffle( str_repeat($characters, $length)),
                    0,
                    $length
                );
            }
            $_SERVER['REQUEST_METHOD'] = 'POST';
            return $loginform;
        } else {
           //Add https to redirect to Cosign then the Omeka filter login
           // form will be called with remote user.
           header('location: ' . $this->redirectedURL());
        }
    }

    /**
    * Using this filter to pass the lgout through Cosign.
    */
    public function hookDefineRoutes($args)
    {
        // Don't add these routes on the admin side to avoid conflicts.
        $router = $args['router'];
        $route = new Zend_Controller_Router_Route(
            'users/logout',
            array(
                'module'     => 'cosign',
                'controller' => 'Cosign',
                'action'     => 'logout'
            )
        );
        $router->addRoute('logoutCosignUser', $route);
    }

    /**
    * After the login form filter is called, the login adapter filter used to
    * override the default way Omeka authenticates users. It will be used to
    * check if the username authenticate with the Cosign is available at Omeka
    * user database or not.
    */
    public function filterLoginAdapter($authAdapter,$loginForm)
    {
        if (isset($_SERVER['REMOTE_USER'])) {
            Zend_Session::regenerateId();
            return new Omeka_Auth_Adapter_Cosign(
                $loginForm['login_form']->getValue('username'),
                $loginForm['login_form']->getValue('password')
            );
        } else {
            header('location: ' . $this->redirectedURL());
        }
    }

    private function redirectedURL()
    {
        return "https://{$_SERVER['HTTP_HOST']}/login?dest=" . rawurlencode($_SERVER['REQUEST_URI']);
    }
}

class Omeka_Auth_Adapter_Cosign implements Zend_Auth_Adapter_Interface
{
    private $_userId;

    public function __construct($username, $password)
    {
        $this->_userId = $username;
    }

    public function authenticate()
    {
        // Omeka needs the user ID (not username)
        $omekaUser = get_db()->getTable('User')->findBySql(
            "username = ?",
            array($this->_userId),
            true
        );
        if ($omekaUser) {
            $id = $omekaUser->id;
            return new Zend_Auth_Result(
                Zend_Auth_Result::SUCCESS,
                $id,
                array("Success")
            );
        } else {
            $messages = array();
            $messages[] = 'Login information incorrect. Please try again.';
            return new Zend_Auth_Result(
                Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                $this->_userId,
                $messages
            );
        }
    }
}
