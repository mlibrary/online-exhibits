<?php
//require_once 'MyOmekaPosterItemTable.php';
class Omlibrarygroupsexhibitrelationship extends Omeka_Record{
    public $group_id;
    public $entity_id;




 public function deletegroupsexhibitrelationshipRecords($id)
    {
        // Delete entries from posters_items table
        $db = get_db();
        $grouping_records =  $db->getTable("Omlibrarygroupsexhibitrelationship")->fetchObjects("SELECT * 
                                                                    FROM {$db->prefix}groups_exhibit_relationship p
                                                                    WHERE p.entity_id = $id");
        foreach($grouping_records as $grouping_record){
            $grouping_record->delete();
        }
    }
    
}