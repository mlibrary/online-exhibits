<?php
//require_once 'MyOmekaPosterItemTable.php';
//Relationship between Exhibits and groups.
class ExhibitGroupsRelationShip extends Omeka_Record_AbstractRecord{
public $exhibit_id;
 public $group_id;


//Find all groups related to an Exhibit.
 public Static function findGroupsBelongToExhibit($exhibit_id) {
        // Delete entries from posters_items table
        $db = get_db();
        $where = "`exhibit_id` = ?";
	      $rows = $db->getTable("ExhibitGroupsRelationShip")->findBySql($where,array($exhibit_id));
	      if (!empty($rows)) {
	  			$exhibit_groups_object_records = $rows;
	      } else {
	        $exhibit_groups_object_records[] = new ExhibitGroupsRelationShip($exhibit_id);
	        $exhibit_groups_object_records[0]['exhibit_id'] = $exhibit_id;
	      }
        return $exhibit_groups_object_records;
 }

 public static function deleteGroupsBelongToExhibit($exhibitGroupsObjects) {
     foreach($exhibitGroupsObjects as $exhibitGroupsObject) {
         $exhibitGroupsObject->delete();
    }
 }

 public static function updateGroupsBelongToExhibit($oldGroups,$newGroups,$exhibit_id) {

    ExhibitGroupsRelationShip::deleteGroupsBelongToExhibit($oldGroups);
    ExhibitGroupsRelationShip::addNewGroupsBelongToExhibit($newGroups,$exhibit_id);
 }

 public static function addNewGroupsBelongToExhibit($groups,$exhibit_id) {
     foreach($groups as $group) {
        $newExhibit_group = new ExhibitGroupsRelationShip();
        $newExhibit_group->exhibit_id = $exhibit_id;
			  $newExhibit_group->group_id = $group;
			  $newExhibit_group->save();
			}
 }
}