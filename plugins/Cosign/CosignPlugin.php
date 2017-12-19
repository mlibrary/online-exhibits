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
    protected $_hooks = array('define_routes','config_form','config', 'initialize');

    protected $_filters = ['admin_whitelist'];

    public function __construct()
    {
        parent::__construct();
        if (!empty($_SERVER['HTTPS'])) {
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

    public function filterAdminWhitelist($list)
    {
      array_push($list, [
        'module' => 'admin',
        'controller' => 'redirector',
        'action' => 'index',
      ]);
      return $list;
    }

    public function hookInitialize() {
        if (!empty($_SERVER['REMOTE_USER'])) {
            $auth = Zend_Auth::getInstance();
            if (!$auth->hasIdentity()) {
                $adapter = new Omeka_Auth_Adapter_Cosign($_SERVER['REMOTE_USER'], '');
                $auth->authenticate($adapter);
            }
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
    * Using this filter to pass the lgout through Cosign.
    */
    public function hookDefineRoutes($args)
    {
        $router = $args['router'];

        $router->addRoute(
            'cosignUserLogin',
            new Zend_Controller_Router_Route(
                'users/login',
                array(
                    'module'     => 'admin',
                    'controller' => 'redirector',
                    'action'     => 'index',
                    'redirect_uri' => $this->redirectedURL(),
                )
            )
        );

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

    private function redirectedURL()
    {
        return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}/login?dest=/admin";
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
