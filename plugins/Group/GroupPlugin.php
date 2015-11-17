<?php
/**
* This class is to create a group in User form and Exhibit Metadata form as part of Omeka
* admin page
*/
class GroupPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
    * @var array Hooks for the plugin.
    */
    protected $_hooks = array(
                    'initialize',
                    'install',
                    'define_acl',
                    'define_routes',
                    'before_delete_user',
                    'before_delete_exhibit',
                    'after_save_exhibit',
    );

    public function hookInitialize() {
			 get_view()->addHelperPath(dirname(__FILE__) . '/views/helpers', 'ExhibitBuilder_View_Helper_');
		}
  	/**
	  * $db->MlibraryGroupListOfGroups = omeka_groups table in db
	  * $db->MlibraryGroupUserRelationship = omeka_grouping_relationship table in db
	  */
	  public function hookInstall()
	  {
        $db = $this->_db;
        //list of groups in db (
       /* Learning & Teaching
           Library IT
           Collections
           Research
           Budget & Planning
           Taubman
           Operations
      */
        // List of Group Names.
        $sql = "
                   CREATE TABLE IF NOT EXISTS `$db->ListOfGroups` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `group_id` int(10) unsigned NOT NULL,
                  `name` text COLLATE utf8_unicode_ci,
                   PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

				//Relationship between Groups and Users. A user can belong to many groups.
        $sql = "
                   CREATE TABLE IF NOT EXISTS `$db->GroupUserRelationship` (
                   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `entity_id` int(10) unsigned NOT NULL,
                  `group_id` int(10) unsigned NOT NULL,
                   PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

        //Relationship between Groups and Exhibits. An Exhibit can have many groups belong to.
         $sql = "
                   CREATE TABLE IF NOT EXISTS `$db->ExhibitGroupsRelationShip` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `exhibit_id` int(10) unsigned NOT NULL,
                  `group_id` int(10) unsigned NOT NULL,
                   PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

				//Relationship between Exhibits and Images. Each Exhibit has one image.
        $sql = "
                   CREATE TABLE IF NOT EXISTS `$db->ImagBelongToExhibitRelationShip` (
                   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `entity_id` int(10) unsigned NOT NULL,
                  `image_name` text COLLATE utf8_unicode_ci,
                  `image_title` text COLLATE utf8_unicode_ci,
                   PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

    }

    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        require_once dirname(__FILE__).'/GroupAssertion.php';
        if ($acl->has('ExhibitBuilder_Exhibits')) {
            $acl->allow('contributor', 'ExhibitBuilder_Exhibits',
            array('showNotPublic'));
            $acl->allow(null, 'ExhibitBuilder_Exhibits', array('edit','delete'),
            new GroupAssertion);
        }
        $acl->allow('contributor', 'Items', array('makePublic'));
        $acl->allow('admin', 'CsvImport_Index');
    }

    public function hookBeforeDeleteExhibit($args)
    {
			//delete_exhibit
	  	  $exhibit = $args['record'];
         ImagBelongToExhibitRelationShip::findImageBelongToExhibit($exhibit->id)->delete();

       // delete all groups too.
	 		  $exhibitGroupsObjectRecords = ExhibitGroupsRelationShip::findGroupsBelongToExhibit($exhibit->id);
	 		  ExhibitGroupsRelationShip::deleteGroupsBelongToExhibit($exhibitGroupsObjectRecords);
		}

	  public function hookAfterSaveExhibit($args)
	  {
		  //save_exhibit
   	  $exhibit = $args['record'];
      $newExhibit_image = $this->imageOfExhibit($exhibit);
      if (!empty($newExhibit_image)) {
    	   $currentExhibitImageObject = ImagBelongToExhibitRelationShip::findImageBelongToExhibit($exhibit->id);
   	     ImagBelongToExhibitRelationShip::updateImageBelongToExhibit($currentExhibitImageObject,$newExhibit_image,$exhibit->id);
 		  }

		 //group selection then save to exhibit
		  if(!empty($args['record']['group-selection'])) {
    		 $exhibit = $args['record'];
    		 $exhibitGroupsObjectRecords = ExhibitGroupsRelationShip::findGroupsBelongToExhibit($exhibit->id);
  		   $goups = $args['record']['group-selection'];
	  	    ExhibitGroupsRelationShip::updateGroupsBelongToExhibit($exhibitGroupsObjectRecords,$goups,$exhibit->id);
	    }
	  }

	  public function hookBeforeDeleteUser($args)
	  {
   	   $user = $args['record'];
       $userGroupsObjects = GroupUserRelationship::findUserRelationshipRecords($user->id);
       GroupUserRelationship::deleteUserRelationshipRecords($userGroupsObjects);
	  }

    public function hookDefineRoutes($args)
    {
        // Don't add these routes on the admin side to avoid conflicts.
        $router = $args['router'];
        $route = new Zend_Controller_Router_Route(
   		           'users/add',
    	            array(
    			              'module'     => 'group',
        	              'controller' => 'Group',
       		              'action'     => 'add'
   		             ));
        $router->addRoute('addGroupUser', $route);

        $route = new Zend_Controller_Router_Route(
   				       'users/edit/:id',
    				      array(
    					          'module'       => 'group',
                				'controller' => 'Group',
       	        				'action'     => 'edit'
   		            ));
        $router->addRoute('editGroupUser', $route);
    }

    public function imageOfExhibit($exhibit)
    {
      $Exhibit_image = '';
      $topPages = $exhibit->getTopPages();
      if (count($topPages) > 0) {
     		   $exhibitPage = $topPages[0];
	    }
	    else
         return '';

	    while ($Exhibit_image == '') {
		     foreach ($exhibitPage->getPageEntries() as $pageEntry) {
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
					       $Exhibit_image = array(
					                                      'image'=>'/'.$imgurl,
					                                      'title'=>metadata($item, array('Dublin Core', 'Title'))
					                                   );
					        break;
			       }
				     if ($Exhibit_image != '') break;
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
		     if ($targetPage) {
			       $exhibitPage = $targetPage;
		     }
		     else {
			      break;
		     }
      }//while
      return $Exhibit_image;
   }
}
