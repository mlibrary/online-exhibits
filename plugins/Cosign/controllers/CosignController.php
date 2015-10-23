<?php

/** Zend_Application */
require_once 'Zend/Application.php';

class Cosign_CosignController extends Omeka_Controller_AbstractActionController
{
     public function logoutAction()
     {
          Zend_Session::destroy();
	        session_destroy();
	        if (isset($_SERVER['COSIGN_SERVICE'])) {
          	   header(sprintf(
      	      'Set-Cookie: %s=; expires=Thur, 01-Jan-1970 00:00:00:01 GMT;' .
      	        'path=/; host=%s; secure',
    	         $_SERVER['COSIGN_SERVICE'],
	             $_SERVER['HTTP_HOST']
          ));
	       } else {
            // I believe $_SERVER['COSIGN_SERVICE'] is set for all services in our environment, but just in case...
    	      header(sprintf(
            'Set-Cookie: %s=; expires=Thur, 01-Jan-1970 00:00:00:01 GMT;' .
             'path=/; host=%s; secure',
             'cosign-test.www.lib.umich.edu',
             $_SERVER['HTTP_HOST']
            ));
        }
				// Have to be added to configuration form when plugin installed.
        $this->_helper->redirector->gotoUrl(get_option('configuration_this_configuration'));
 } //LogoutAction
}
