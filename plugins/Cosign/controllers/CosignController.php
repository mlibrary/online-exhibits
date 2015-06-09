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

class Cosign_CosignController extends Omeka_Controller_AbstractActionController
{
    //protected $_autoCsrfProtection = true;

     public function logoutAction()
     {
          Zend_Session::destroy();
	        session_destroy();
	        if (isset($_SERVER['COSIGN_SERVICE'])) {
          	   header(sprintf(
      	      'Set-Cookie: %s=; expires=Thur, 01-Jan-1970 00:00:00:01 GMT;
      	       path=/; host=%s; secure',
    	         $_SERVER['COSIGN_SERVICE'],
	             $_SERVER['HTTP_HOST']
          ));
	       } else {
            // I believe $_SERVER['COSIGN_SERVICE'] is set for all services in our environment, but just in case...
    	      header(sprintf(
            'Set-Cookie: %s=; expires=Thur, 01-Jan-1970 00:00:00:01 GMT;
             path=/; host=%s; secure',
             'cosign-test.www.lib.umich.edu',
             $_SERVER['HTTP_HOST']
            ));
        }
        $this->_helper->redirector->gotoUrl('https://weblogin.umich.edu/cgi-bin/logout?http://www.lib.umich.edu/');
 } //LogoutAction

}