<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
 /**
  * This class is to provide contributors a privilege to publish item as public and allow admin role to use CsvImport.
  * admin page
 */

 class AccessPlugin extends Omeka_Plugin_AbstractPlugin
 {
    /**
    * @var array Hooks for the plugin.
    */
    protected $_hooks = array(
                    'define_acl'
    );

    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        if (!$acl->has('CsvImport_Index')) {
            $acl->addResource('CsvImport_Index');
        }
        $acl->allow('contributor', 'Items', array('makePublic'));
        $acl->allow('admin', 'CsvImport_Index');
    }
 }
