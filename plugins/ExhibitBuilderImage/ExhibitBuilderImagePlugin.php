<?php
/**
* This class is to attach an image to an Exhibit.
* admin page
*/
class ExhibitBuilderImagePlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
    * @var array Hooks for the plugin.
    */
    protected $_hooks = array(
                    'install',
                    'before_delete_exhibit',
                    'after_save_exhibit',
    );

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

    public function hookBeforeDeleteExhibit($args)
    {
			//delete_exhibit
	  	  $exhibit = $args['record'];
         ImagBelongToExhibitRelationShip::findImageBelongToExhibit($exhibit->id)->delete();
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
