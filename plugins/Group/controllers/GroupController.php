<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
 
 /** Zend_Application */
require_once 'Zend/Application.php';
require_once CONTROLLER_DIR.'/UsersController.php';

class Group_GroupController extends UsersController {

 protected $_autoCsrfProtection = true;

 public function init()
 {
    $this->newGroups_names_object = new ListOfGroups;
    $this->_helper->db->setDefaultModelName('User');
 }

 public function addAction()
 {
        // Create a new page.
           $user = new User();
	   $userForm = $this->_getUserForm($user);
	   $groupUserObjects = GroupUserRelationship::findUserRelationshipRecords($user->id);
	   $userForm = $this->_addElement($userForm, $user, $groupUserObjects);
           $this->view->form = $userForm;
	   if (!$this->getRequest()->isPost()) {
				 		return;
	      }

	      if (!$userForm->isValid($_POST)) {
				   		$this->_helper->flashMessenger(__('There was an invalid entry on the form. Please try again.'), 'error');
              return;
        }

        $user->setPostData($_POST);

        if ($user->save(false)) {
        $success = true;
         if ($user->role != 'super') {
	           if(!empty($user->group)) {
  		          $groups = $user->group;
  		          GroupUserRelationship::addNewUserRelationshipRecords($groups,$user->id);
        	  }
       }
       if ($success) {
          	$this->_helper->flashMessenger(
            __('The user "%s" was successfully added!', $user->username),
                    'success'
              );
       	   $this->_helper->redirector->gotoUrl('/users/browse');
      }
      } else {
            $this->_helper->flashMessenger($user->getErrors());
        }
   }

 public function editAction()
 {
    $user = $this->_helper->db->findById();
    $currentUser = $this->getCurrentUser();
    $groupUserValue='';
    $groupUserObjects = GroupUserRelationship::findUserRelationshipRecords($user->id);
    $changePasswordForm = new Omeka_Form_ChangePassword;
    $changePasswordForm->setUser($user);
    $userForm = $this->_getUserForm($user);

    if ($currentUser->role == 'super') {
       $userForm =  $this->_addElement($userForm, $user, $groupUserObjects);
    }

    $userForm->setDefaults(array(
         'username' => $user->username,
         'name' => $user->name,
         'email' => $user->email,
         'role' => $user->role,
         'active' => $user->active,
    ));

   $this->view->user = $user;
   $this->view->currentUser = $currentUser;
   $this->view->form = $userForm;

   $keyTable = $this->_helper->db->getTable('Key');
   $this->view->keys = $keyTable->findBy(array('user_id' => $user->id));
   $values = $userForm->getValues();
	 $success = false;

    // API keys can be generated by individual user, but permission to use the
   // Keys are the same as permission for User role. This permission is granted only by Super User.
    if ($this->getRequest()->isPost()) {
 		    if (isset($_POST['update_api_keys'])) {
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
        } else {

              if  (!$userForm->isValid($_POST)) {
				       		$this->_helper->flashMessenger(__('There was an invalid entry on the form. Please try again.'), 'error');
                  return;
              }
              $user->setPostData($userForm->getValues());
              if ($user->save(false)) {
                  if ($currentUser->role=='super') {
                  		 if(!empty($user->group)) {
                  		      $currentUserGroups = GroupUserRelationship::findUserRelationshipRecords($user->id);
                  		      $newUserGroups = $user->group;
                  		      GroupUserRelationship::updateUserRelationshipRecords($currentUserGroups, $newUserGroups, $user->id);
				               }
                  }
                  $success = true;
                  $this->_helper->flashMessenger(
                      __('The user %s was successfully changed!', $user->username),
                          'success');
              } else {
                  $this->_helper->flashMessenger($user->getErrors());
              }
        } //$_POST['update_api_keys']

        if ($success) {
                // Redirect to the current page
				      $this->_helper->redirector->gotoRoute();
     	  }
    } // isPost()
} //edit


private function _addElement($userForm, $user, $groupUserObjects) {
   foreach($groupUserObjects as $groupUserObject) {
		  	      $groupUserValue[] = $groupUserObject['group_id'];
	 }
	 $userForm->addElement('Multiselect', 'group', array(
                'label' => __('Group'),
                'description' => __("Select the unit in the Library the user belong to."),
                'multiOptions' => $this->newGroups_names_object->get_groups_names(),
                'value' => ((!empty($user->id)) ? $groupUserValue : ''),
                'class' => 'field',
                'order' => 3
    ));

   return $userForm;
}

}// class