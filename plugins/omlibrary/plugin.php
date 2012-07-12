<?php
add_plugin_hook('install', 'omlibrary_install');
add_plugin_hook('initialize', 'omlibrary_initialize');
add_plugin_hook('after_save_form_exhibit', 'omlibrary_save_exhibit_item_image');
//add_plugin_hook('before_save_form_exhibit', 'omlibrary_save_exhibit_group');
add_plugin_hook('before_delete_user','omlibrary_delete_user_from_group');
add_plugin_hook('define_acl', 'omlibrary_setup_acl');
 add_filter('admin_whitelist','omlibrary_addToWhitelist');
//add_plugin_hook('after_save_config_form','omlibrary_after_save_config');

//add_filter('login_adapter', 'omlibrary_login');
//add_filter('login_form', 'omlibrary_loginf');


//delete user entity id from grouping_relationship
function omlibrary_delete_user_from_group($user){
	$grouping = new Omlibrarygrouping;
	$grouping->deleteGroupingRecords($user['entity_id']);
}

function omlibrary_save_form_user($user){
	$tempuser= $user;
	//$user->Entity->delete();
	$db = get_db();
	//if ($_POST['submit']!='Save Changes'){
		//$db->query("INSERT INTO `{$db->prefix}groups` (`user_id`,`name`) VALUES ('".$tempuser['id']."','".$tempuser['username']."')");
		/*  $group = new Omlibrarygroups();
                    $group->name = $tempuser['username'];
                    $group->user_id = $tempuser['entity_id'];
                    $group->save();*/
		//}		
//	else {
		//$db->query("INSERT INTO `{$db->prefix}groupping` (`entity_id`,`group_id`) VALUES ('".$tempuser['entity_id']."','".$tempuser['group'][0]."')");
		// $groupping = new Omlibrarygroupping();
		
		 /*foreach($tempuser['group'] as $group){
    		 $groupping->entity_id = $tempuser['entity_id'];
		     $groupping->group_id = $group;
		     $groupping->save();		     
    	}  */  
    	//exit;
  //  }
}


function omlibrary_install(){
   $db = get_db();
   $db->query("CREATE TABLE IF NOT EXISTS `{$db->prefix}groups` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `group_id` int(10) unsigned NOT NULL,
       `name` text collate utf8_unicode_ci,
      PRIMARY KEY (`id`)    
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
    
      $db->query("CREATE TABLE IF NOT EXISTS `{$db->prefix}grouping_relationship` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
       `entity_id` int(10) unsigned NOT NULL,
      `group_id` int(10) unsigned NOT NULL,
      PRIMARY KEY (`id`)  
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");     
}


function omlibrary_setup_acl($acl){
    $acl->deny('contributor', 'ExhibitBuilder_Exhibits',array('editAll','deleteAll'));
  	$acl->allow('admin', 'CsvImport_Index');
}


function omlibrary_loginf($loginform){
	if ($_SERVER['REMOTE_USER']=='nancymou')
	$_SERVER['REMOTE_USER']= 'websystem';
//	print_r('in loginform'.$_SERVER['REMOTE_USER']);
//	exit;
	$_POST['username']= $_SERVER['REMOTE_USER'];
//	$_POST['username']= 'jlausch';//$_SERVER['REMOTE_USER'];
//	$_POST['username']= 'websystem';
	$_POST['password']='dd';
	$_SERVER['REQUEST_METHOD']='POST';
	return $loginform;
}

function omlibrary_login($authAdapter,$loginForm) {   
// print_r('from cosign'.$_SERVER['REMOTE_USER']);
//exit;
if(isset($_SERVER['REMOTE_USER'])) {
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
}  
  // $username = 'jlausch';//$_SERVER['REMOTE_USER'];
  //  if($_SERVER['REMOTE_USER'] == 'nancymou') 
//    $_SERVER['REMOTE_USER'] = 'websystem';

$username = $_SERVER['REMOTE_USER'];
//$username = 'jlausch';//$_SERVER['REMOTE_USER'];
//$username ='websystem';
$pwd = '';//$_SERVER['REMOTE_USER'];
$authAdapter = new Omeka_Auth_Adapter_omlibrary($username,$pwd);
return $authAdapter;        
}


function omlibrary_addToWhitelist($adminWhiteList){    	
	   array_push($adminWhiteList,array('controller' => 'omlibrary', 'action' => 'forgot-password'));
	   return $adminWhiteList;	   
    }


function omlibrary_initialize(){	
	   $front = Zend_Controller_Front::getInstance();
       Zend_Controller_Front::getInstance()->registerPlugin(new OmlibraryControllerPlugin);
}


function omlibrary_save_exhibit_group($exhibit,$post){    
  require_once HELPERS;    
	$data = unserialize($exhibit->theme_options);
	if(!empty($post['group-selection']))
		$data[mlibrary]['exhibitgroup']= $post['group-selection'];	
	$exhibit->theme_options = serialize($data);	
}


function omlibrary_save_exhibit_item_image($exhibit,$post){    
  require_once HELPERS;    
  $items = get_items(array('exhibit' => $exhibit['id']));
  
   if ($items!=null){
	  set_items_for_loop($items);        
	  	 while(loop_items()):
	        if ($item_found!=true){
        	      $index = 0;
			           	while (loop_files_for_item()):
        		    		$file = get_current_file();                   	
        		    		if ($file->hasThumbnail()):                
    	                		if ($index == 0):                        
 $exhibit_image_setting = array('image'=>'/'.$file->getStoragePath('fullsize'),'title'=>item('Dublin Core','Title'));
 
       	         				  $index++;
                        	      $item_found=true;
                	    		endif;
                    		endif;
		               endwhile;
            }
        endwhile;      
	}        	
	
	
	$data = unserialize($exhibit->theme_options);
	$data[mlibrary]['exhibitimage']= $exhibit_image_setting;
if(!empty($post['group-selection']))
	$data[mlibrary]['exhibitgroup']= $post['group-selection'];	
	$exhibit->theme_options = serialize($data);
	$exhibit->save();
}

  
class OmlibraryControllerPlugin extends Zend_Controller_Plugin_Abstract {
	
    public function routeStartup(Zend_Controller_Request_Abstract $request) {
    	$router = Omeka_Context::getInstance()->getFrontController()->getRouter();

	/*	$route = new Zend_Controller_Router_Route(
   				 'users/logout',
    				array(
    					'module'     => 'omlibrary', 
        				'controller' => 'omlibrary',
       					'action'     => 'logout'
   		 ));
 
		$router->addRoute('logoutOmlibraryUser', $route);*/
		
		$route = new Zend_Controller_Router_Route(
   				 'users/add/',
    				array(
    					'module'       => 'omlibrary', 
        				'controller' => 'omlibrary',
       					'action'     => 'add'
   		 ));
 
		$router->addRoute('addOmlibraryUser', $route);
				
		$route = new Zend_Controller_Router_Route(
   				 'users/edit/:id',
    				array(
    					'module'       => 'omlibrary', 
        				'controller' => 'omlibrary',
       					'action'     => 'edit'
   		 ));
 
		$router->addRoute('editOmlibraryUser', $route);
    }   
 }


class Omeka_Auth_Adapter_omlibrary implements Zend_Auth_Adapter_Interface {
	
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
