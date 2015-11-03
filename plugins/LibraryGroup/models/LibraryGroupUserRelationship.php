<?php
//require_once 'MyOmekaPosterItemTable.php';
//Relationship between groups and users
//This is a model of the record that exists in a table omeka_grouping_relationship, that
//hold the user id and group id belong to the user.

class LibraryGroupUserRelationship extends Omeka_Record_AbstractRecord {
  public $group_id;
  public $entity_id; // Refers to the user id.

// Each user id is linked to one or more group-id.
//list all groups related to an user.
 public static function findUserRelationshipRecords($user_id) {
        // Delete entries from posters_items table
   $db = get_db();
   $where = "`entity_id` = ?";
   $userGroupsObjects = $db->getTable("LibraryGroupUserRelationship")->findBySql($where,array($user_id));
   return $userGroupsObjects;
 }

}