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

class MlibraryGroup_GroupController extends UsersController {
 protected $_autoCsrfProtection = true;

 public function addAction() {
		if (class_exists('Omeka_Form_SessionCsrf')) {
				$csrf = new Omeka_Form_SessionCsrf;
		} else {
				$csrf = '';
		}

    $user = new User();
	  $this->view->csrf = $csrf;

  if ($this->getRequest()->isPost()) {
       if (!($csrf === '' || $csrf->isValid($_POST))) {
					$this->_helper->_flashMessenger(__('There was an error on the User form. Please try again.'), 'error');
					return;
			}
      $user->setPostData($_POST);

      if ($user->save(false)) {
	        if(!empty($user->group[0])) {
  		        $groups = $user->group;
      			  foreach($groups as $group) {
          	   	$newGrouping = new MlibraryGroupUserRelationship();
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
  return $form;
 }


  public function editAction() {
		if (class_exists('Omeka_Form_SessionCsrf')) {
			$csrf = new Omeka_Form_SessionCsrf;
		} else {
			$csrf = '';
		}

		$this->view->csrf = $csrf;
    $user = $this->_helper->db->findById();
    $currentUser = $this->getCurrentUser();
  	$changePasswordForm = new Omeka_Form_ChangePassword;
    $changePasswordForm->setUser($user);

    $form = $this->_getUserForm($user);
    $form->setDefaults(array(
        'username' => $user->username,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'active' => $user->active,
        'group' => $user->group
    ));

    $keyTable = $this->_helper->db->getTable('Key');
    $this->view->user = $user;
    $this->view->currentUser = $currentUser;
    $this->view->keys = $keyTable->findBy(array('user_id' => $user->id));
    $values = $form->getValues();

    if ($this->getRequest()->isPost()) {
        if (!($csrf === '' || $csrf->isValid($_POST))) {
								$this->_helper->_flashMessenger(__('There was an error on the User form. Please try again.'), 'error');
								return;
				}
        $values['email'] = $_POST['email'];
			  $values['username']=$_POST['username'];
			  $values['active']=$_POST['active'];
			  $values['name'] = $_POST['name'];
			  $values['role'] = $_POST['role'];
			  $values['group']=$_POST['group'];
			  $_POST=$values;
			  $success = false;
        try {
            $user->setPostData($_POST);
            if ($user->save(false)) {
                  $this->_helper->flashMessenger(
                      __('The user %s was successfully changed!', $user->username),
                        'success');
                      $grouping = new MlibraryGroupUserRelationship;
                      $grouping->delete_user_relationship_records($user->id);
                    	// add groups selected for this user
               	      $groups = $user->group;

                      foreach($groups as $group) {
            				    $newGrouping = new MlibraryGroupUserRelationship;
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