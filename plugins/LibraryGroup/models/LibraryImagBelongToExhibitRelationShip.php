<?php
//require_once 'MyOmekaPosterItemTable.php';
// A record model that link an image with an Exhibit.
class LibraryImagBelongToExhibitRelationShip extends Omeka_Record_AbstractRecord {
   public $entity_id; // refer to exhibit id
   public $image_name;
   public $image_title;

//Find an image that belongs to an Exhibit.
 public static function findImageBelongToExhibit($id) {
  // Find entries from posters_items table
  $db = get_db();
  $where = "`entity_id` = ?";
	$row = $db->getTable("LibraryImagBelongToExhibitRelationShip")->findBySql($where,array($id));
	if (!empty($row)) {
				$imageExhibitRecords= $row[0];
	}
  return $imageExhibitRecords;
 } // end of function deleteImagexhibitrelationshipRecords

}