<?php
//require_once 'MyOmekaPosterItemTable.php';
//Relationship between Exhibits and groups.
class LibraryExhibitGroupsRelationShip extends Omeka_Record_AbstractRecord{
 public $group_id;
 public $exhibit_id;

//Find all groups related to an Exhibit.
 public Static function findGroupsBelongToExhibit($id) {
        // Delete entries from posters_items table
        $db = get_db();
        $where = "`exhibit_id` = ?";
	      $exhibit_groups_object_records = $db->getTable("LibraryExhibitGroupsRelationShip")->findBySql($where,array($id));
        return $exhibit_groups_object_records;
 }
}