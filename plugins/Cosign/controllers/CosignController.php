<?php

  /** Zopyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
  
  require_once 'Zend/Application.php';

  class Cosign_CosignController extends Omeka_Controller_AbstractActionController
  {
    public function logoutAction()
    {
        Zend_Session::destroy();
        session_destroy();
        $cookieTemplate = 'Set-Cookie: %s=; expires=Thur,' .
          ' 01-Jan-1970 00:00:00:01 GMT;path=/;host=%s; secure';

        if (isset($_SERVER['COSIGN_SERVICE'])) {
            $cosignService = $_SERVER['COSIGN_SERVICE'];
        } else {
            // If COSIGN_SERVICE isn't set, then make a guess.
            $cosignService = 'cosign-' . $_SERVER['HTTP_HOST'];
        }

        header(
            sprintf(
                $cookieTemplate,
                $cosignService,
                $_SERVER['HTTP_HOST']
            )
        );

        // Have to be added to configuration form when plugin installed.
        $this->_helper->redirector->gotoUrl(get_option('cosign_logout_url'));
    } //LogoutAction
  }
