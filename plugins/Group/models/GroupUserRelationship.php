<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
//require_once 'MyOmekaPosterItemTable.php';
//Relationship between groups and users
//This is a model of the record that exists in a table omeka_grouping_relationship, that
//hold the user id and group id belong to the user.

class GroupUserRelationship extends Omeka_Record_AbstractRecord {
 public $entity_id; // Refers to the user id.
  public $group_id;


// Each user id is linked to one or more group-id.
//list all groups related to an user.
 public static function findUserRelationshipRecords($entity_id) {
        // Delete entries from posters_items table
   $db = get_db();
   $where = "`entity_id` = ?";
   $row = $db->getTable("GroupUserRelationship")->findBySql($where,array($entity_id));
   if (!empty($row)) {
				$userGroupsObjects = $row;
   } else {
	      $userGroupsObjects[] = new GroupUserRelationship($entity_id);
	      $userGroupsObjects[0]['entity_id'] = $entity_id;
	 }

    return $userGroupsObjects;
 }

 public static function deleteUserRelationshipRecords($userGroupsObjects) {
     foreach($userGroupsObjects as $userGroupsObject) {
         $userGroupsObject->delete();
    }
 }

 public static function updateUserRelationshipRecords($oldGroups,$newGroups,$user_id) {
    GroupUserRelationship::deleteUserRelationshipRecords($oldGroups);
    GroupUserRelationship::addNewUserRelationshipRecords($newGroups,$user_id);
 }

 public static function addNewUserRelationshipRecords($groups,$entity_id) {
     foreach($groups as $group) {
        $newUser_group = new GroupUserRelationship();
        $newUser_group->entity_id = $entity_id;
			  $newUser_group->group_id = $group;
			  $newUser_group->save();
			}
 }

}
