<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
  //require_once 'MyOmekaPosterItemTable.php';
  // A record model that link an image with an Exhibit.
class ImagBelongToExhibitRelationShip extends Omeka_Record_AbstractRecord {
   public $entity_id; // refer to exhibit id
   public $image_name;
   public $image_title;

//Find an image that belongs to an Exhibit.
 public static function findImageBelongToExhibit($exhibit_id) {
  // Find entries from posters_items table
 // $imageExhibitRecords = new LibraryImagBelongToExhibitRelationShip

  $db = get_db();
  $where = "`entity_id` = ?";
	$row = $db->getTable("ImagBelongToExhibitRelationShip")->findBySql($where,array($exhibit_id));

	if (!empty($row)) {
				$imageExhibitRecord = $row[0];
	} else {
	      $imageExhibitRecord = new ImagBelongToExhibitRelationShip($exhibit_id);
	      $imageExhibitRecord['entity_id'] = $exhibit_id;
	}

  return $imageExhibitRecord;
 } // end of function deleteImagexhibitrelationshipRecords

 public static function deleteImageBelongToExhibit($exhibitImageObjectRecords) {

     $exhibitImageObjectRecords->delete();

 }

 public static function updateImageBelongToExhibit($oldImage,$newImage,$exhibit_id) {
  ImagBelongToExhibitRelationShip::deleteImageBelongToExhibit($oldImage);

  ImagBelongToExhibitRelationShip::addNewImageBelongToExhibit($newImage,$exhibit_id);
 }

 public static function addNewImageBelongToExhibit($newImage,$exhibit_id) {
   $newImage_belong_to_exhibit_object = new ImagBelongToExhibitRelationShip;
   $newImage_belong_to_exhibit_object->entity_id = $exhibit_id;
	 $newImage_belong_to_exhibit_object->image_name = $newImage['image'];
	 $newImage_belong_to_exhibit_object->image_title = $newImage['title'];
	 $newImage_belong_to_exhibit_object->save();
 }

}
