<?php

   //Define hooks
   add_plugin_hook('install', 'cosign_install');
   add_plugin_hook('initialize', 'cosign_initialize');
 ///Define filters
   add_filter('login_adapter', 'login');
   add_filter('login_form', 'loginf');
   add_filter('admin_whitelist','addToWhitelist');


function loginf($loginform){

if(isset($_SERVER['REMOTE_USER'])) {
  if($_SERVER['REMOTE_USER'] == 'nancymou') {
    if(strpos($_SERVER['HTTP_USER_AGENT'],"Safari") !== false) {
      $_SERVER['REMOTE_USER'] = 'moconway';
      $_SERVER['ORIGINAL_USER'] = 'nancymou';
    }
   if(strpos($_SERVER['HTTP_USER_AGENT'],"Chrome") !== false) {
      $_SERVER['REMOTE_USER']='jlbonnet';
      $_SERVER['ORIGINAL_USER'] = 'nancymou';
    }
  /*  else
    {
     $_SERVER['REMOTE_USER']='websystem';
      $_SERVER['ORIGINAL_USER'] = 'nancymou';
    }*/
  }
}  
	$_POST['username']= $_SERVER['REMOTE_USER'];
//	$_POST['username']= 'jlausch';//$_SERVER['REMOTE_USER'];
//	$_POST['username']= 'websystem';
	$_POST['password']='dd';
	$_SERVER['REQUEST_METHOD']='POST';
	return $loginform;
}

function login($authAdapter,$loginForm) {    

  // $username = 'jlausch';//$_SERVER['REMOTE_USER'];
//    if($_SERVER['REMOTE_USER'] == 'nancymou') 
  //  $_SERVER['REMOTE_USER'] = 'websystem';
/*if(isset($_SERVER['REMOTE_USER'])) {
  if($_SERVER['REMOTE_USER'] == 'nancymou') {
    if(strpos($_SERVER['HTTP_USER_AGENT'],"Safari") !== false) {
      $_SERVER['REMOTE_USER'] = 'moconway';
      $_SERVER['ORIGINAL_USER'] = 'nancymou';
    }
   if(strpos($_SERVER['HTTP_USER_AGENT'],"Chrome") !== false) {
      $_SERVER['REMOTE_USER']='jlausch';
      $_SERVER['ORIGINAL_USER'] = 'nancymou';
    }
  
  }
}  */

// print_r($_SERVER['REMOTE_USER']);
//   exit;
$username = $_SERVER['REMOTE_USER'];
//$username ='websystem';
$pwd = '';//$_SERVER['REMOTE_USER'];
$authAdapter = new Omeka_Auth_Adapter_Cosign($username,$pwd);

return $authAdapter;        
}

 function addToWhitelist($adminWhiteList){   	
	   array_push($adminWhiteList,array('controller' => 'cosign', 'action' => 'forgot-password'));
	   return $adminWhiteList;	   
    }


    function cosign_initialize(){
	   $front = Zend_Controller_Front::getInstance();
       Zend_Controller_Front::getInstance()->registerPlugin(new CosignControllerPlugin);
    
    }


 class Omeka_Auth_Adapter_Cosign implements Zend_Auth_Adapter_Interface {
	
	private $omeka_userid;
	
	public function __construct($username,$password) {
		$this->omeka_userid = $username;
	}
		
	public function authenticate() {
        // Omeka needs the user ID (not username)
        $omeka_user = get_db()->getTable('User')->findBySql("username = ?", array($this->omeka_userid), true);
        if ($omeka_user) {
        	$id = $omeka_user->id;
        	$correctResult = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $id,array("good job"));
        	return $correctResult;	
        }
        else {
        	$messages = array();
        	$messages[] = 'Login information incorrect. Please try again.';
        	$authResult = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->omeka_userid , $messages);
        	return $authResult;
        }
	}	
 }
	

 class CosignControllerPlugin extends Zend_Controller_Plugin_Abstract {
	
    public function routeStartup(Zend_Controller_Request_Abstract $request) {
    	 $router = Omeka_Context::getInstance()->getFrontController()->getRouter();
        
         $route = new Zend_Controller_Router_Route(
   				'users/forgot-password',
    				array(
    				  'module'     => 'cosign', 
        			  'controller' => 'cosign',
       				  'action'     => 'forgot-password'
   		 ));
 
		$router->addRoute('forgot', $route);
		
		$route = new Zend_Controller_Router_Route(
   				 'users/logout',
    				array(
    					'module'     => 'cosign', 
        				'controller' => 'cosign',
       					'action'     => 'logout'
   		 ));
 
		$router->addRoute('logoutCosignUser', $route);
      
		
    }
   
 }
