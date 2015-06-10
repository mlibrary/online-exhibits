<?php

/**
* This class is to Cosign only to admin page in Omeka
*/

class CosignPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array('initialize','define_routes');

    protected $_filters = array('login_adapter','login_form');


    public function hookInitialize()
    {
	     // Zend_Controller_Front::getInstance()->registerPlugin(new CosignControllerPlugin);
    }

    public function filterLoginForm($loginform)
    {
        if((isset($_SERVER['REMOTE_USER']))) {
             // Leave those uncommented till need to be tested by Meghan & Nancy for different browsers.
             /*if($_SERVER['REMOTE_USER'] == 'musolffm') {
                 if(strpos($_SERVER['HTTP_USER_AGENT'],"Firefox") != false) {
                     $_SERVER['REMOTE_USER'] = 'user2';
                     $_SERVER['ORIGINAL_USER'] = 'musolffm';
                 }
                 if(strpos($_SERVER['HTTP_USER_AGENT'],"Safari") != false) {
                     $_SERVER['REMOTE_USER'] = 'user1';
                     $_SERVER['ORIGINAL_USER'] = 'musolffm';
                 }
             }*/
             /*if($_SERVER['REMOTE_USER'] == 'nancymou') {
                  if(strpos($_SERVER['HTTP_USER_AGENT'],"Firefox") != false) {
                      $_SERVER['REMOTE_USER'] = 'user3';
                      $_SERVER['ORIGINAL_USER'] = 'nancymou';
                  }*/
                 /* if(strpos($_SERVER['HTTP_USER_AGENT'],"Chrome") != false) {
                    $_SERVER['REMOTE_USER'] = 'jlausch';
                    $_SERVER['ORIGINAL_USER'] = 'nancymou';
                 }
             }*/

	    $_POST['username'] = $_SERVER['REMOTE_USER'];
	    $_POST['password'] = 'dd';
	    $_SERVER['REQUEST_METHOD'] = 'POST';
	    return $loginform;
	} else {
            $url_pecies = explode('/',$_SERVER['REQUEST_URI']);
	    $redirected_url = 'https://'.$_SERVER['SERVER_NAME'].'/'.$url_pecies[1].'/admin/';
            header('location: '.$redirected_url);
	  }
    }


    public function hookDefineRoutes($args)
    {
        // Don't add these routes on the admin side to avoid conflicts.
        $router = $args['router'];
        $route = new Zend_Controller_Router_Route('users/logout',
        	 array(
    	           'module'     => 'cosign',
        	   'controller' => 'Cosign',
       		   'action'     => 'logout'
   		  )
                );
        $router->addRoute('logoutCosignUser', $route);
    }


    function filterLoginAdapter($authAdapter,$loginForm)
    {
        if (isset($_SERVER['REMOTE_USER'])) {
            $username = $_SERVER['REMOTE_USER'];
            $pwd = '';
            $authAdapter = new Omeka_Auth_Adapter_Cosign($username,$pwd);
            return $authAdapter;
        } else {
            $url_pecies = explode('/',$_SERVER['REQUEST_URI']);
	    $redirected_url = 'https://'.$_SERVER['SERVER_NAME'].'/'.$url_pecies[1].'/admin/';
            header('location: '.$redirected_url);
        }
    }
}


 class Omeka_Auth_Adapter_Cosign implements Zend_Auth_Adapter_Interface
 {
     private $omeka_userid;
     public function __construct($username,$password)
     {
         $this->omeka_userid = $username;
     }

     public function authenticate()
     {
         // Omeka needs the user ID (not username)
         $omeka_user = get_db()->getTable('User')->findBySql("username = ?",
                         array(
                         $this->omeka_userid
                         ),
                         true
                     );
         if ($omeka_user) {
             $id = $omeka_user->id;
             $correctResult = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $id,array("Success"));
             return $correctResult;
         } else {
             $messages = array();
             $messages[] = 'Login information incorrect. Please try again.';
             $authResult = new Zend_Auth_Result(Zend_Auth_Result::
                               FAILURE_IDENTITY_NOT_FOUND, $this->omeka_userid,
                               $messages);
             return $authResult;
           }
     }
 }
