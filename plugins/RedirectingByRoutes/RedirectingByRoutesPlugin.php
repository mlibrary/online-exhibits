<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
 /**
 * This class will redirect pages using Router class
 */

class RedirectingByRoutesPlugin extends Omeka_Plugin_AbstractPlugin
 {
    /**
    * @var array Hooks for the plugin.
    */

    protected $_hooks = array(
                    'define_routes'
    );

    protected $_filters = ['admin_whitelist'];
   
    public function filterAdminWhitelist($list)
    {
        array_push(
            $list,
            [
                'module' => 'admin',
                'controller' => 'redirector',
                'action' => 'index',
            ]
        );
        return $list;
    }

    public  function hookDefineRoutes($args)
    {
        $router = $args['router'];
          $paths = ['collections','collections/browse','items', 'items/browse', 'items/search', 'items/tags'];

        if (!is_admin_theme()) {
          foreach ($paths as $path)
           {
              $route = new Zend_Controller_Router_Route(
                       $path,
                       array(
                'module'     => 'admin',
                'controller' => 'redirector',
                'action'     => 'index',
                 'redirect_uri' => WEB_ROOT,
                )
              );

             $router->addRoute($path,$route);
           }
        }
 }
}
    
