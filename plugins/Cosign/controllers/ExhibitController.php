<?php 
/**
 * Ldap_LdapController class
 * @version $Id$
 * @copyright 
 * @license 
 * @package 
 * @author 
 **/
 
/** Zend_Application */
require_once 'Zend/Application.php';  
//require_once CONTROLLER_DIR.'/ExhibitsController.php';

/*class Cosign_ExhibitController extends Omeka_Controller_AbstractActionController {
    
  public function editAction(){ 
   	        
        if ($this->getRequest()->isPost()) {
           $values['email'] = $_POST['email'];
			     $values['username'] = $_POST['username'];
			     $values['active'] = $_POST['active'];
			     $values['name'] = $_POST['name'];
			     $values['role'] = $_POST['role'];
			     $values['group'] = $_POST['group'];
			     $_POST = $values;
			     $success = false;
               try {
                   $user->setPostData($_POST);
                   if ($user->save(false)){
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
        }  //if
  } //edit action        
}//class*/