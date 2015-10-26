<?php
//require_once 'MyOmekaPosterItemTable.php';
//Relationship between Exhibits and groups.
class LibraryExhibitGroupsRelationShip extends Omeka_Record_AbstractRecord{
 public $group_id;
 public $exhibit_id;

//Delete all groups related to an Exhibit.
 public function delete_groups_in_exhibit_records($id) {
        // Delete entries from posters_items table
        $db = get_db();
        $where = "`exhibit_id` = ?";
	      $exhibit_groups_object_records = $db->getTable("LibraryExhibitGroupsRelationShip")->findBySql($where,array($id));
        foreach($exhibit_groups_object_records as $exhibit_groups_object_record){
            $exhibit_groups_object_record->delete();
        }
 }

//List group ids attached to an Exhibit. An Exhibit can have one or more than one group attached to it
function get_groups_ids_attached_to_exhibit($id) {
	 $value = array();
	 $iit = $this->getTable('LibraryExhibitGroupsRelationShip');
   $sql = $iit->getSelectForFindBy(array(`group_id`))->where('`exhibit_id` = ?');
	 if(!empty($id)) {
	   $rows = $iit->fetchObjects($sql,array($id));
		 if (!empty($rows)) {
		 	  foreach($rows as $row) {
			  	$value[]= $row['group_id'];
			  }
		 }
	 }
	 return $value;
}

}