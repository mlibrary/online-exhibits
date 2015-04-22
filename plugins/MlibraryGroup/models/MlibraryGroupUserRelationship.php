<?php
//require_once 'MyOmekaPosterItemTable.php';
//Relationship between groups and users

class MlibraryGroupUserRelationship extends Omeka_Record_AbstractRecord {
  public $group_id;
  public $entity_id; // Refers to the user id.

// Each user id is linked to group-id.
function get_groups_user_belong_to($user_id) {
 $value = array();
 $db = get_db();
 if(!empty($user_id)) {
    $rows =  $db->getTable("MlibraryGroupUserRelationship")->fetchObjects("SELECT g.group_id FROM {$db->prefix}grouping_relationship g where g.entity_id = $user_id");
    if (!empty($rows)) {
	  	foreach($rows as $row) {
		  	$value[]= $row['group_id'];
			}
		}
	 }
	 return $value;
}

 /*function update_user_relationship($user,$group) {
//  foreach($user['group'] as $group) {
//   $grouping = new MlibraryGroupUserRelationship();
   $this->entity_id = $user->id;
	 $this->group_id = $group;
	 $this->save();
  //}
}*/

function is_user_in_group($exhibit_group, $user_id) {
 $db = get_db();
 if((!empty($exhibit_group)) and (!empty($user_id))) {
	  $rows =  $db->getTable("MlibraryGroupUserRelationship")->fetchObjects("SELECT * FROM {$db->prefix}grouping_relationship g where ((g.group_id=$exhibit_group) and (g.entity_id=$user_id))");
 }

 if (!empty($rows))
	  $result=1;
 else
  	$result=0;

 return $result;
}

 function delete_user_relationship_records($user_id) {
        // Delete entries from posters_items table
 $db = get_db();
 $grouping_records =  $db->getTable("MlibraryGroupUserRelationship")->fetchObjects("SELECT *
                                                                    FROM {$db->prefix}grouping_relationship p
                                                                   WHERE p.entity_id = $user_id");
 foreach($grouping_records as $grouping_record) {
    $grouping_record->delete();
   }

}

}