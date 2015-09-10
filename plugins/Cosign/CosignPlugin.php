<?php
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

  //show plugin configuration page
    public function hookconfigform()
    {
        include('config_cosign_form.php');
    }

   //save plugin configurations in the database
    public function hookconfig($post)
    {
	      set_option('configuration_this_configuration',
	       trim($_POST['configuration_this_configuration']));
    }

    /**
    /* The purpose of this filter is to by pass the Omeka login form so it will not
    /* display to users. But still we need to pass a username and password that is expected
    /* from Omeka form. The user is authenticare through the cosign first then the filter
    /* for the login form is called.
    */
    public function filterLoginForm($loginform)
    {

        if((isset($_SERVER['REMOTE_USER']))) {
      	   $_POST['username'] = $_SERVER['REMOTE_USER'];
 	         $_POST['password'] = 'dd';
	         $_SERVER['REQUEST_METHOD'] = 'POST';
	         return $loginform;
	      } else {
           $url_pieces = explode('/',$_SERVER['REQUEST_URI']);
           //Add https to redirect to Cosign then the Omeka filter login form will be called
           // with remote user.
      	   $redirected_url = WEB_DIR;
           header('location: '.$redirected_url);
	      }
    }

    /**
    /* Using this filter to pass the lgout through Cosign.
    */
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

     /**
    /* After the login form filter is called, the login adapter filter used to override
    /* the default way Omeka
    /* authenticates users. It will be used to check if the username authenticate with
    /* the Cosign is available at Omeka user database or not.
    */
   function filterLoginAdapter($authAdapter,$loginForm)
    {
        if (isset($_SERVER['REMOTE_USER'])) {
            $authAdapter = new Omeka_Auth_Adapter_Cosign($loginForm
            ['login_form']->getValue('username'),
            $loginForm['login_form']->getValue('password'));
            return $authAdapter;

        } else {
            $url_pieces = explode('/',$_SERVER['REQUEST_URI']);
     	      $redirected_url = WEB_DIR;
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
             $validUser = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,
             $id,array("Success"));
             return $validUser;
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
