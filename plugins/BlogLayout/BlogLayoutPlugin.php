<?php
 /**
  * Copyright (c) 2018, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */

class BlogLayoutPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_filters = array('exhibit_layouts');

    public function filterExhibitLayouts($layouts)
    {
        $layouts['single-image'] = array(
            'name' => 'Single Image',
            'description' => 'Display single image centered with caption under text.'
        );

        $layouts['double-image'] = array(
            'name' => 'Double images',
            'description' => 'Display double images centered with caption under text.'
        );

        return $layouts;
    }

}
