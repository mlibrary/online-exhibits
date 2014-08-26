<?php 
/**
 * Ldap_LdapController class
 * 
 * @version $Id$
 * @copyright 
 * @license 
 * @package 
 * @author 
 **/
 
/** Zend_Application */
require_once 'Zend/Application.php';  
require_once CONTROLLER_DIR.'/UsersController.php';

class Cosign_CosignController extends UsersController {

	 public function logoutAction() {  
     	Zend_Session::destroy();
	    session_destroy();
	  	if (isset($_SERVER['COSIGN_SERVICE'])) {
    	header(sprintf(
	      'Set-Cookie: %s=; expires=Thur, 01-Jan-1970 00:00:00:01 GMT; path=/; host=%s; secure',
    	$_SERVER['COSIGN_SERVICE'],
	    $_SERVER['HTTP_HOST']
      ));
	    } else {
    // I believe $_SERVER['COSIGN_SERVICE'] is set for all services in our environment, but just in case...
    	header(sprintf(
      'Set-Cookie: %s=; expires=Thur, 01-Jan-1970 00:00:00:01 GMT; path=/; host=%s; secure',
      'cosign-test.www.lib.umich.edu',
      $_SERVER['HTTP_HOST']
      ));
     }   
     // $this->redirect->gotoUrl('https://weblogin.umich.edu/cgi-bin/logout?http://www.lib.umich.edu/');
       $this->_helper->redirector->gotoUrl('https://weblogin.umich.edu/cgi-bin/logout?http://www.lib.umich.edu/');    
  } //LogoutAction
    
  public function addAction() {
      $user = new User();  
     
      if (!$this->getRequest()->isPost()) {
            return;
      }  
      $user->setPostData($_POST);  
      
      if ($user->save(false)) {  
	        if(!empty($user->group[0])) {
  		        $groups = $user->group;
      			  foreach($groups as $group) {
          	   	$newGrouping = new CosignGrouping();
            	 	$newGrouping->entity_id = $user->id;
             		$newGrouping->group_id = $group;
             		$newGrouping->save();
        	  	}
        	}
        	$this->_helper->flashMessenger(
         __('The user "%s" was successfully added!', $user->username),
                    'success'
            );           
       	 $this->_helper->redirector->gotoUrl('/users/browse');
      } else {
            $this->_helper->flashMessenger($user->getErrors());
        }
  } // addAction*/
    
  protected function _getUserForm(User $user) {
        $hasActiveElement = $user->exists()
            && $this->_helper->acl->isAllowed('change-status', $user);
            
        $form = new Omeka_Form_User(array(
            'hasRoleElement'    => $this->_helper->acl->isAllowed('change-role', $user),
            'hasActiveElement'  => $hasActiveElement,
            'user'              => $user
        ));
     //   fire_plugin_hook('users_form', array('form' => $form, 'user' => $user));
        return $form;
  }
  
 
        
  public function editAction(){
 
        $user = $this->_helper->db->findById();  
        $currentUser = $this->getCurrentUser();
        
        $changePasswordForm = new Omeka_Form_ChangePassword;
        $changePasswordForm->setUser($user);
        
        // Super users don't need to know the current password.
        if ($currentUser && $currentUser->role == 'super') {
//            $changePasswordForm->removeElement('current_password');
        }
        
        $form = $this->_getUserForm($user);

       // $form->setSubmitButtonText(__('Save Changes hello'));
        $form->setDefaults(array(
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'active' => $user->active,
             'group' => $user->group
        ));
        
        $keyTable = $this->_helper->db->getTable('Key');

    //    $this->view->passwordForm = $changePasswordForm;
        $this->view->user = $user;
        $this->view->currentUser = $currentUser;
       // $this->view->form = $form;
        $this->view->keys = $keyTable->findBy(array('user_id' => $user->id));
        	$values = $form->getValues();
        	        
        if ($this->getRequest()->isPost()) {
           $values['email'] = $_POST['email'];
			     $values['username']=$_POST['username'];
			     $values['active']=$_POST['active'];
			     $values['name'] = $_POST['name'];
			     $values['role'] = $_POST['role'];
			     $values['group']=$_POST['group'];
			     //$values['group']=$_POST['group'];
			     $_POST=$values;
			      $success = false;
        /*    if (isset($_POST['update_api_keys'])) {
                // Create a new API key.
                if ($this->getParam('api_key_label')) {
                    $key = new Key;
                    $key->user_id = $user->id;
                    $key->label = $this->getParam('api_key_label');
                    $key->key = sha1($user->username . microtime() . rand());
                    $key->save();
                    $this->_helper->flashMessenger(__('A new API key was successfully created.'), 'success');
                    $success = true;
                }
                // Rescend API keys.
                if ($this->getParam('api_key_rescind')) {
                    foreach ($this->getParam('api_key_rescind') as $keyId) {
                        $keyTable->find($keyId)->delete();
                    }
                    $this->_helper->flashMessenger(__('An existing API key was successfully rescinded.'), 'success');
                    $success = true;
                }
            } elseif (isset($_POST['new_password'])) {
                if (!$changePasswordForm->isValid($_POST)) {
                    $this->_helper->flashMessenger(__('There was an invalid entry on the form. Please try again.'), 'error');
                    return;
                }
                
                $values = $changePasswordForm->getValues();
                $user->setPassword($values['new_password']);
                $user->save();
                $this->_helper->flashMessenger(__('Password changed!'), 'success');
                $success = true;
            } else {
                if (!$form->isValid($_POST)) {
                    $this->_helper->flashMessenger(__('There was an invalid entry on the form. Please try again.'), 'error');
                    return;
                }*/

               try {
                   $user->setPostData($_POST);
                   if ($user->save(false)) {
                      $this->_helper->flashMessenger(
                        __('The user %s was successfully changed!', $user->username),
                        'success');
                      $grouping = new CosignGrouping;
                      print_r($user->id);
                    
                      $grouping->deleteGroupingRecords($user->id);
                    	// add groups selected for this user
               	      $groups = $user->group;    
               	   
                      foreach($groups as $group) {
            				    $newGrouping = new CosignGrouping;
				                $newGrouping->entity_id = $user->id;
            				    $newGrouping->group_id = $group;
				                $newGrouping->save();
              				}
                      $success = true;
                   } else {
                     $this->_helper->flashMessenger($user->getErrors());
                   }
              }catch (Omeka_Validate_Exception $e) {
                $this->_helper->flashMessenger($e);
            }          
               
            if ($success) {
                // Redirect to the current page
                $this->_helper->redirector->gotoRoute();
            }            
        }  
  }         
}