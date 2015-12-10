<?php
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
        $acl->allow('contributor', 'Items', array('makePublic'));
        $acl->allow('admin', 'CsvImport_Index');
    }
 }