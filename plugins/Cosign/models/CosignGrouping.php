<?php
//require_once 'MyOmekaPosterItemTable.php';
//Relationship between groups and users
class CosignGrouping extends Omeka_Record_AbstractRecord {
  public $group_id;
  public $entity_id; // Refers to the user id.

// Each user id is linked to each group-id link.
public function get_groups_ids_selected($user_id) {

 $value = array();
 $db = get_db();
 if(!empty($user_id)) {
    $rows =  $db->getTable("CosignGrouping")->fetchObjects("SELECT g.group_id FROM {$db->prefix}grouping_relationship g where g.entity_id = $user_id");
    if (!empty($rows)) {
	  	foreach($rows as $row) {
		  	$value[]= $row['group_id'];
			}
		}
	 }
	 return $value;
}

public function updateGrouping($user) {
  foreach($user['group'] as $group) {
   $grouping = new CosignGrouping();
   $grouping->entity_id = $user->id;
	 $grouping->group_id = $group;
	 $grouping->save();
  }
}

public function get_user_ingroup($group_exhibit, $user_id) {
 $db = get_db();
 if((!empty($group_exhibit)) and (!empty($user_id))) {
	  $rows =  $db->getTable("CosignGrouping")->fetchObjects("SELECT * FROM {$db->prefix}grouping_relationship g where ((g.group_id=$group_exhibit) and (g.entity_id=$user_id))");
 }

 if (!empty($rows))
	  $result=1;
 else
  	$result=0;

 return $result;
}

public function deleteGroupingRecords($id) {
        // Delete entries from posters_items table
 $db = get_db();
 $grouping_records =  $db->getTable("CosignGrouping")->fetchObjects("SELECT *
                                                                    FROM {$db->prefix}grouping_relationship p
                                                                    WHERE p.entity_id = $id");
 foreach($grouping_records as $grouping_record) {
  $grouping_record->delete();
 }
}

}