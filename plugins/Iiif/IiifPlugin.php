<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
 /**
  * This class is to Cosign to admin page in Omeka
  */
class IiifPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
    * @var array Hooks for the plugin.
    */
    protected $_hooks = ['define_routes'];

    /**
    * Using this filter to pass the lgout through Cosign.
    */
    public function hookDefineRoutes($args)
    {
        $router = $args['router'];

        $route = new Zend_Controller_Router_Route(
            'iiif/:identifier/:region/:size/:rotation/:quality_format',
            [
                'module'     => 'iiif',
                'controller' => 'Iiif',
                'action'     => 'image'
            ]
        );
        $router->addRoute('iiif_image', $route);

        $route = new Zend_Controller_Router_Route(
            'iiif/:identifier/info.json',
            [
                'module'     => 'iiif',
                'controller' => 'Iiif',
                'action'     => 'info'
            ]
        );
        $router->addRoute('iiif_info', $route);
    }
}
