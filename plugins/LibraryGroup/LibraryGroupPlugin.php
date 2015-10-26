<?php
/**
* This class is to create a group in User form and Exhibit Metadata form as part of Omeka
* admin page
*/
class LibraryGroupPlugin extends Omeka_Plugin_AbstractPlugin
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
                    'after_delete_exhibit',
                    'after_save_exhibit',
    );

    public function hookInitialize() {
			 //Zend_Controller_Front::getInstance()->registerPlugin(new MlibraryGroupControllerPlugin);
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
      /* 1 | Hatcher-Shapiro-AAEL
         2 | Special Collections
         3 | Taubman
         4 | MPublishing
         5 | Clark
         6 | Digital Media Commons
         7 | Bentley
        11 | workshop */

        // List of Group Names.
        $sql = "
                   CREATE TABLE IF NOT EXISTS `$db->LibraryListOfGroups` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `group_id` int(10) unsigned NOT NULL,
                  `name` text COLLATE utf8_unicode_ci,
                   PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

				//Relationship between Groups and Users
        $sql = "
                   CREATE TABLE IF NOT EXISTS `$db->LibraryGroupUserRelationship` (
                   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `entity_id` int(10) unsigned NOT NULL,
                  `group_id` int(10) unsigned NOT NULL,
                   PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

        //Relationship between Groups and Exhibits
         $sql = "
                   CREATE TABLE IF NOT EXISTS `$db->LibraryExhibitGroupsRelationShip` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `exhibit_id` int(10) unsigned NOT NULL,
                  `group_id` int(10) unsigned NOT NULL,
                   PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

				//Relationship between Exhibits and Images
        $sql = "
                   CREATE TABLE IF NOT EXISTS `$db->LibraryImagBelongToExhibitRelationShip` (
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

    public function hookAfterDeleteExhibit($args)
    {
			//delete_exhibit
	  	$exhibit = $args['record'];
	  	$newImage_belong_to_exhibit_object = new LibraryImagBelongToExhibitRelationShip;
	  	$newImage_belong_to_exhibit_object->deleteImage_belong_to_exhibit(
	  	                                                   $exhibit->id);
	 		$newExhibitr_group_object = new LibraryExhibitGroupsRelationShip();
			$newExhibitr_group_object->delete_groups_in_exhibit_records(
			                                                   $exhibit->id);
		}

	public function hookAfterSaveExhibit($args)
	{
		 //save_exhibit
   	 $exhibit = $args['record'];
     $Exhibit_image = $this->imageOfExhibit($exhibit);
		 $newImage_belong_to_exhibit_object = new LibraryImagBelongToExhibitRelationShip;
		 $newImage_belong_to_exhibit_object->deleteImage_belong_to_exhibit(
		                                                    $exhibit->id);
		 if (!empty($Exhibit_image)) {
		     $newImage_belong_to_exhibit_object->entity_id = $exhibit->id;
				 $newImage_belong_to_exhibit_object->image_name = $Exhibit_image['image'];
				 $newImage_belong_to_exhibit_object->image_title = $Exhibit_image['title'];
				 $newImage_belong_to_exhibit_object->save();
		 }
		 //group selection then save to exhibit

		 if(!empty($_POST['group-selection'])) {
    		 $exhibit = $args['record'];
    		  $newExhibit_group_object = new LibraryExhibitGroupsRelationShip();
    		 $newExhibit_group_object->delete_groups_in_exhibit_records(
				                                                      $exhibit->id);
				  $goups = $_POST['group-selection'];
				 foreach($goups as $group) {
				 $newExhibit_group_object = new LibraryExhibitGroupsRelationShip();
		  	 $newExhibit_group_object->exhibit_id = $exhibit->id;
	  		 $newExhibit_group_object->group_id = $group;
				 $newExhibit_group_object->save();
				 }
		 }
	}

	public function hookBeforeDeleteUser($args)
	{
   	 $user = $args['record'];
   	 $newUser_groups_object = new LibraryGroupUserRelationship();
     $newUser_groups_object->delete_user_relationship_records(
                                                                   $user->id);
	}

 public function hookDefineRoutes($args)
 {
        // Don't add these routes on the admin side to avoid conflicts.
        $router = $args['router'];
        $route = new Zend_Controller_Router_Route(
   		           'users/add',
    	            array(
    			              'module'     => 'library-group',
        	              'controller' => 'Group',
       		              'action'     => 'add'
   		             ));
        $router->addRoute('addGroupUser', $route);

        $route = new Zend_Controller_Router_Route(
   				       'users/edit/:id',
    				      array(
    					          'module'       => 'library-group',
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
