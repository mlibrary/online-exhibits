<?php
/**
 * Copyright (c) 2025, Regents of the University of Michigan.
 * All rights reserved. See LICENSE.txt for details.
 */

/**
 * This class is to Export Basic Exhibit Metadata in json format.
 */
class MetadataPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
    * @var array $_hooks for the plugin.
    */
    protected $_hooks = ['define_routes'];

    /**
    * The define routes hook.
    */
    public function hookDefineRoutes($args)
    {
        $router = $args['router'];

        $route = new Zend_Controller_Router_Route(
            'exhibit-metadata.json',
            [
                'module'     => 'metadata',
                'controller' => 'Metadata',
                'action'     => 'export'
            ]
        );
        $router->addRoute('metadata', $route);
    }
}
