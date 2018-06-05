<?php
 /**
  * Copyright (c) 2018, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */

class ExhibitGalleryPagePlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array('install');

    public function hookInstall() {
           $db = get_db();
          
           // add gallery page to all current exhibits.
           $sql = "SELECT id FROM `{$db->prefix}exhibits` ORDER BY id";
           $id_for_exhibits = $db->query($sql)->fetchAll();
           
           foreach ($id_for_exhibits as $exhibit_id) {
              $id = $exhibit_id['id'];
              $sql_order = "select MAX(`order`) FROM `{$db->prefix}exhibit_pages` b WHERE b.exhibit_id = $id";
              $max_order = $db->fetchOne($sql_order);
              // make the gallery page the last one in the navigation menue
              $newPageData = array (
                'parent_id' => null, 
                'exhibit_id' => $id,
                'title' => 'Gallery',
                'slug' => 'gallery',
                'order' =>$max_order+1,
              );
              $db->getAdapter()->insert($db->exhibit_pages,$newPageData); 
           }
   }
}
