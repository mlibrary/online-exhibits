<?php
//require_once 'MyOmekaPosterItemTable.php';
// A record model that link an image with an Exhibit.
class LibraryImagBelongToExhibitRelationShip extends Omeka_Record_AbstractRecord {
   public $entity_id; // refer to exhibit id
   public $image_name;
   public $image_title;

//Delete an image that belongs to an Exhibit.
 public function deleteImage_belong_to_exhibit($id) {
  // Delete entries from posters_items table
  $db = get_db();
  $where = "`entity_id` = ?";
	$image_exhibit_records = $db->getTable("LibraryImagBelongToExhibitRelationShip")->findBySql($where,array($id));
  if (!empty($image_exhibit_records)) {
      foreach($image_exhibit_records as $image_exhibit_record) {
        $image_exhibit_record->delete();
      }
  }
 } // end of function deleteImagexhibitrelationshipRecords

//Retrieve an image related to an Exhibit.
function get_image_attached_to_exhibits($id) {
	 $value = '';
	 $db = get_db();
	 if(!empty($id)) {
	 	$where = "`entity_id` = ?";
	 	$row = $db->getTable("LibraryImagBelongToExhibitRelationShip")->findBySql($where,array($id));
    if (!empty($row)) {
				$value= $row[0];
		}
	}
	return $value;
}

}