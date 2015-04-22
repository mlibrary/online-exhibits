<?php

class MlibraryGroupPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array('install','initialize','define_acl','define_routes',
    'after_save_exhibit','before_delete_user','after_delete_exhibit');

    /**
     * @var array Filters for the plugin.
     */
  /*  protected $_filters = array('admin_navigation_main',
        'public_navigation_main', 'search_record_types', 'page_caching_whitelist',
        'page_caching_blacklist_for_record',
	'api_resources');*/

	/**
	* $db->MlibraryGroupListOfGroups = omeka_groups table in db
	* $db->MlibraryGroupUserRelationship = omeka_grouping_relationship table in db
	*/
	 public function hookInstall() {
        $db = $this->_db;
        //list of groups in db (
      /* 1 | Hatcher-Shapiro-AAEL
         2 | Special Collections
         3 | Taubman
         4 | MPublishing
         5 | Clark
         6 | Digital Media Commons
         7 | Bentley
        11 | workshop */

        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->MlibraryGroupListOfGroups` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `group_id` int(10) unsigned NOT NULL,
        `name` text COLLATE utf8_unicode_ci,
         PRIMARY KEY (`id`)
       ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

				//Relationship between groups and users
        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->MlibraryGroupUserRelationship` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `entity_id` int(10) unsigned NOT NULL,
        `group_id` int(10) unsigned NOT NULL,
         PRIMARY KEY (`id`)
       ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);
    }

    function hookDefineAcl($args) {
      $acl = $args['acl'];
       require_once dirname(__FILE__).'/GroupMlibraryGroupAssertion.php';
       if ($acl->has('ExhibitBuilder_Exhibits')) {
          $acl->allow('contributor', 'ExhibitBuilder_Exhibits',
          array('showNotPublic'));
          $acl->allow(null, 'ExhibitBuilder_Exhibits', array('edit','delete'),
          new GroupMlibraryGroupAssertion);
       }
      $acl->allow('contributor', 'Items', array('makePublic'));
    }

    function hookInitialize() {
	 //	  Zend_Controller_Front::getInstance()->registerPlugin(new MlibraryGroupControllerPlugin);
		}

		function hookAfterDeleteExhibit($args) {
			//delete_exhibit
	  /*	$exhibit = $args['record'];
	  	$newimageexhibitrelationship = new CosignImagexhibitrelationship;
	  	$newimageexhibitrelationship->deleteImagexhibitrelationshipRecords($exhibit->id);
	 		$newgroupsexhibitrelationship = new CosignGroupexhibitrelationship;
			$newgroupsexhibitrelationship->deleteGroupexhibitrelationshipRecords($exhibit->id);*/
		}

	  function hookBeforeDeleteUser($args) {
	  //delete_user_from_group
   		$user = $args['record'];
   		$newGrouping = new MlibraryGroupUserRelationship();
    	$newGrouping->delete_user_relationship_records($user->id);
		}

		function hookAfterSaveExhibit($args) {
		//save_exhibit
  	/*	$exhibit = $args['record'];
  		$Exhibit_image = MlibraryGroup_exhibit_image_file($exhibit);

		  $newimageexhibitrelationship = new CosignImagexhibitrelationship;
		  $newimageexhibitrelationship->deleteImagexhibitrelationshipRecords($exhibit->id);
		  if (!empty($Exhibit_image)) {
					$newimageexhibitrelationship->entity_id = $exhibit->id;
					$newimageexhibitrelationship->image_name = $Exhibit_image['image'];
					$newimageexhibitrelationship->image_title = $Exhibit_image['title'];
					$newimageexhibitrelationship->save();
			}

		 //group selection then save to exhibit
		 	if(!empty($_POST['group-selection'][0])) {
    		  $exhibit = $args['record'];
				  $newgroupsexhibitrelationship = new CosignGroupexhibitrelationship;
				  $newgroupsexhibitrelationship->deleteGroupexhibitrelationshipRecords($exhibit->id);
		  		$newgroupsexhibitrelationship->exhibit_id = $exhibit->id;
	  			$newgroupsexhibitrelationship->group_id = $_POST['group-selection'][0];
				  $newgroupsexhibitrelationship->save();
			}*/
		}

	  function MlibraryGroup_exhibit_image_file($exhibit) {
    	$Exhibit_image = "";
    	$topPages = $exhibit->getTopPages();
    	if (count($topPages) > 0) {
     		$exhibitPage = $topPages[0];
	    }
	    else
      	return '';

	    while ($Exhibit_image == "") {
			   foreach ($exhibitPage->getPageEntries() as $pageEntry){
		     // retrieve the image file that is stored and check if there is a thumbnail available, if not, check the next pageEntry.
		  	 if ($pageEntry->file_id) {
			    	$file = get_db()->getTable('File')->find($pageEntry->file_id);
				    $item = get_db()->getTable('Item')->find($pageEntry->item_id);
			   } elseif ($item = $pageEntry->Item) {
						if (isset($item->Files[0])) {
								$file = $item->Files[0];
		        }
				 }

				 if ((!empty($file)) and ($file->hasThumbnail())) {
				  	$imgurl = $file->getStoragePath('fullsize');
					  $Exhibit_image = array('image'=>'/'.$imgurl,'title'=>metadata($item, array('Dublin Core', 'Title')));
					  break;
				}
				if ($Exhibit_image != "") break;
			}//for each

			// if page object exists, grab link to the first child page if exists. If it doesn't, grab
			// a link to the next page
  		$targetPage = null;

	  	if ($nextPage = $exhibitPage->firstChildOrNext()) {
				  $targetPage = $nextPage;
		  }
		  elseif ($exhibitPage->parent_id) {
			  $parentPage = $exhibitPage->getParent();
			  $nextParentPage = $parentPage->next();
			  if ($nextParentPage) {
				  $targetPage = $nextPage;
			  }
		  } // elseif
		  if ($targetPage){
			  $exhibitPage = $targetPage;
		  }
		  else{
			  break;
		  }
	}//while

return $Exhibit_image;
}

 public function hookDefineRoutes($args)
    {
        // Don't add these routes on the admin side to avoid conflicts.
        $router = $args['router'];

         $route = new Zend_Controller_Router_Route(
   		'users/add',
    	array(
    			'module'     => 'mlibrary-group',
        	'controller' => 'Group',
       		'action'     => 'add'
   		));

      $router->addRoute('addGroupUser', $route);

      $route = new Zend_Controller_Router_Route(
   				 'users/edit/:id',
    				array(
    					'module'       => 'mlibrary-group',
        				'controller' => 'Group',
       					'action'     => 'edit'
   		      ));

        $router->addRoute('editGroupUser', $route);
    }
}
