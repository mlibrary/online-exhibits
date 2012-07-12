<?php 
/**
 * Omlibrary_OmlibraryController class
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

class Omlibrary_OmlibraryController extends UsersController {

protected $_publicActions = array('delete');

	
	public function addAction() {
        $user = new User();
       $arg = func_get_args();
        try {
            if ($user->saveForm($_POST)) {                
                $this->flashSuccess('The user "' . $user->username . '" was successfully added!');
            
                if(!empty($user->group[0])){
                 $groups = $user->group;
               foreach($groups as $group)
				{
				    $newGrouping = new Omlibrarygrouping;
				    $newGrouping->entity_id = $user->entity_id;
				    $newGrouping->group_id = $group;
				    $newGrouping->save();
				}
				}
                $this->_helper->redirector->gotoUrl('/users/browse');
            }
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
        }
    }
    	
    public function logoutAction()
    {
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
      'cosign-nancymou.www.lib.umich.edu',
      $_SERVER['HTTP_HOST']
    ));
  }
      $this->redirect->gotoUrl('https://weblogin.umich.edu/cgi-bin/logout?http://www.lib.umich.edu/');
    }
	
	
    /**
     * Similar to 'add' action, except this requires a pre-existing record.
     * 
     * The ID For this record must be passed via the 'id' parameter.
     *
     * @return void
     **/
    public function editAction() {    
     
        $user = $this->findById();        
        $changePasswordForm = new Omeka_Form_ChangePassword;
        $changePasswordForm->setUser($user);

        $currentUser = $this->getCurrentUser();
        // Super users don't need to know the current password.
        if ($currentUser && $currentUser->role == 'super') {
          //  $changePasswordForm->removeElement('current_password');
        }

        //$this->view->passwordForm = $changePasswordForm;
        $this->view->user = $user;        
                
        try {
  
            if ($user->saveForm($_POST)) {
                  
               $this->flashSuccess('The user "' . $user->username . '" was successfully changed!');
            	//delete all groups related to user with this entity_id
            	$grouping = new Omlibrarygrouping;
               	$grouping->deleteGroupingRecords($user['entity_id']);
               	// add groups selected for this user
               	$groups = $user->group;
               foreach($groups as $group)
				{
				    $newGrouping = new Omlibrarygrouping;
				    $newGrouping->entity_id = $user->entity_id;
				    $newGrouping->group_id = $group;
				    $newGrouping->save();
				}

                if ($user->id == $currentUser->id) {
                    $this->_helper->redirector->gotoUrl('/');
                } else {
                     $this->_helper->redirector->gotoUrl('/users/browse');
                }
            }
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
         
        } catch (Exception $e) {
            $this->flashError($e->getMessage());
            
        }     
    }
}
