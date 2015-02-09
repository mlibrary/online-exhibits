<?php
//require_once 'MyOmekaPosterItemTable.php';
//Relationship between Exhibits and groups.
class CosignGroupexhibitrelationship extends Omeka_Record_AbstractRecord{
 public $group_id;
 public $exhibit_id;

 public function deleteGroupexhibitrelationshipRecords($id) {
        // Delete entries from posters_items table
        $db = get_db();
        $grouping_records =  $db->getTable("CosignGroupexhibitrelationship")->fetchObjects("SELECT *
                                                                    FROM {$db->prefix}group_exhibit_relationship p
                                                                    WHERE p.exhibit_id = $id");
        foreach($grouping_records as $grouping_record){
            $grouping_record->delete();
        }
 }

function get_groups_ids_attached_to_exhibits($id) {
	 $value = array();
	 $iit = $this->getTable('CosignGroupexhibitrelationship');
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