<?php
//require_once 'MyOmekaPosterItemTable.php';
//Relationship between groups and users
//This is a model of the record that exists in a table omeka_grouping_relationship, that
//hold the user id and group id belong to the user.

class LibraryGroupUserRelationship extends Omeka_Record_AbstractRecord {
  public $group_id;
  public $entity_id; // Refers to the user id.

// Each user id is linked to group-id.
//list all groups related to an user. Each user can belong to one or many groups.
function get_groups_user_belong_to($user_id) {
 $value = array();
 $db = get_db();
 $where = "`entity_id` = ?";
 if(!empty($user_id)) {
	  $rows = $db->getTable("LibraryGroupUserRelationship")->findBySql($where,array($user_id));
    if (!empty($rows)) {
	  	foreach($rows as $row) {
		  	$value[]= $row['group_id'];
			}
		}
	 }
	 return $value;
}

//Remove the user from the Exhibit group.
 function delete_user_relationship_records($user_id) {
        // Delete entries from posters_items table
 $db = get_db();
 $where = "`entity_id` = ?";
 $user_groups_objects = $db->getTable("LibraryGroupUserRelationship")->findBySql($where,array($user_id));
 foreach($user_groups_objects as $user_groups_object) {
    $user_groups_object->delete();
   }

}

}