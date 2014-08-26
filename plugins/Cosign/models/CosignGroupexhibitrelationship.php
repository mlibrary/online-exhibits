<?php
//require_once 'MyOmekaPosterItemTable.php';
class CosignGroupexhibitrelationship extends Omeka_Record_AbstractRecord{
    public $group_id;
    public $exhibit_id;




 public function deleteGroupexhibitrelationshipRecords($id)
    {
        // Delete entries from posters_items table
        $db = get_db();
        $grouping_records =  $db->getTable("CosignGroupexhibitrelationship")->fetchObjects("SELECT * 
                                                                    FROM {$db->prefix}group_exhibit_relationship p
                                                                    WHERE p.exhibit_id = $id");
        foreach($grouping_records as $grouping_record){
            $grouping_record->delete();
        }
    }
    
    
    
public function get_Cgroups_ids_attached_to_exhibits($id) {	
	 $value = array();	
	 $db = get_db();
	 if(!empty($id)) {
     $rows =  $db->getTable("CosignGroupexhibitrelationship")->fetchObjects("SELECT g.group_id FROM {$db->prefix}group_exhibit_relationship g where g.exhibit_id = $id"); 
		 if (!empty($rows)) {
		 	  foreach($rows as $row) {
			  	$value[]= $row['group_id'];
			  }
		 }
	 //  $value = plugin_is_active('ExhibitBuilder');
	 }
	 return $value;
}
    
}