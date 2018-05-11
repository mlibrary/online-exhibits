<?php
 /**
  * Copyright (c) 2018, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */

class BlogLayoutPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_filters = array('exhibit_layouts');
    protected $_hooks = array('install');


    public function filterExhibitLayouts($layouts)
    {

       $new_layouts = array();
       $new_layouts['text'] = $layouts['text'];
        $new_layouts['single-image'] = array(
            'name' => 'Single Image',
            'description' => 'Display single image centered with caption under text.'
        );

        $new_layouts['double-image'] = array(
            'name' => 'Double Images',
            'description' => 'Display double images centered with caption under text.'
        );

        return $new_layouts;
    }


    public function hookInstall() {
           $db = get_db();
           $update_new_theme = array(
                      'theme' => 'mlibrary_new'
           );         
           
           $db->update($db->exhibits, $update_new_theme,
                array('theme != ?' => 'mlibrary_new'));
                
           $default_options = array(
            'file-position' => 'center',
            'file-size' => 'fullsize',
            'captions-position' => 'left'
           );
           
           $encodedOptions = json_encode($default_options);
           $update_file_text = array(
             'options' => $encodedOptions,
              'layout' => 'single-image'
           ); 
           $update_file = array(
             'options' => $encodedOptions,
              'layout' => 'single-image'
           );
    
          $update_gallery = array(
             'options' => $encodedOptions,
             'layout' => 'double-image'
          );

         // update layout pages with new pages
          $db->update($db->exhibit_page_blocks, $update_file_text,
                array('layout = ?' =>'file-text'));
          
          $db->update($db->exhibit_page_blocks, $update_file,
                array('layout = ?' =>'file'));
  
          $db->update($db->exhibit_page_blocks, $update_gallery,
                array('layout = ?' =>'gallery'));

    }
}
