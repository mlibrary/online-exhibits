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
   // $form = $this->_getUserForm($user);

    $form = new Omeka_Form_Csrf(array('hashName' => 'user_csrf'));
    $this->view->csrf = $form->getElement('user_csrf')
      ->removeDecorator('Fieldtag')
      ->removeDecorator('InputsTag');
  

    try {
      if ($this->getRequest()->isPost() && $form->isValid($_POST) && $user->saveForm($_POST)) {
        $this->flashSuccess('The user "' . $user->username . '" was successfully added!');

        if(!empty($user->group[0])) {
          $groups = $user->group;
          foreach($groups as $group) {
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

  public function logoutAction() {
    Zend_Session::destroy();
    session_destroy();
    if (isset($_SERVER['COSIGN_SERVICE'])) {
      header(sprintf(
        'Set-Cookie: %s=; expires=Thur, 01-Jan-1970 00:00:00:01 GMT; path=/; host=%s; secure',
        $_SERVER['COSIGN_SERVICE'],
        $_SERVER['HTTP_HOST']
      ));
    }
    else {
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
  private function _getUserForm(User $user) {

    $hasActiveElement = $user->exists()
      && $this->_helper->acl->isAllowed('change-status', $user);

    $form = new Omeka_Form_User(array(
      'hasRoleElement'    => $this->_helper->acl->isAllowed('change-role', $user),
      'hasActiveElement'  => $hasActiveElement,
      'user'              => $user
    ));

    fire_plugin_hook('admin_append_to_users_form', $form, $user);
    return $form;
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
    $form = $this->_getUserForm($user);
    $form->setDefaults(array(
      'username' => $user->username,
      'first_name' => $user->first_name,
      'last_name' => $user->last_name,
      'email' => $user->email,
      'institution' => $user->institution,
      'role' => $user->role,
      'active' => $user->active,
      'group' => $user->group
    ));
    $form->addElement('hash', 'user_csrf');

    $this->view->csrf = $form->getElement('user_csrf')
      ->removeDecorator('Fieldtag')
      ->removeDecorator('InputsTag');

    $this->view->user = $user;

    try {
      if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
        if ($currentUser->role == 'super' && $currentUser->id == $user->id) {
          $values = $form->getValues();
          $values['email']       = $_POST['email'];
          $values['username']    = $_POST['username'];
          $values['first_name']  = $_POST['first_name'];
          $values['last_name']   = $_POST['last_name'];
          $values['institution'] = $_POST['institution'];
          $values['active']      = $_POST['active'];
          $values['group']       = $_POST['group'];
          $_POST = $values;
        }
        if ($user->saveForm($_POST)) {
          $this->flashSuccess('The user "' . $user->username . '" was successfully changed!');
          //delete all groups related to user with this entity_id
          $grouping = new Omlibrarygrouping;
          $grouping->deleteGroupingRecords($user['entity_id']);
          // add groups selected for this user
          $groups = $user->group;

          foreach($groups as $group) {
            $newGrouping = new Omlibrarygrouping;
            $newGrouping->entity_id = $user->entity_id;
            $newGrouping->group_id = $group;
            $newGrouping->save();
          }

          if ($user->id == $currentUser->id) {
            $this->_helper->redirector->gotoUrl('/users/browse');
          }
          else {
            $this->_helper->redirector->gotoUrl('/users/browse');
          }
        }
      }
    }
    catch (Omeka_Validator_Exception $e) {
      $this->flashValidationErrors($e);
    }
    catch (Exception $e) {
      $this->flashError($e->getMessage());
    }
  }
}
