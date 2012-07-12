<?php
//require_once 'MyOmekaPosterItemTable.php';
class Omlibraryimageexhibitrelationship extends Omeka_Record{
    public $image_name;
    public $entity_id;
    public $image_title;

 public function deleteimagexhibitrelationshipRecords($id)
    {
        // Delete entries from posters_items table
        $db = get_db();
        $grouping_records =  $db->getTable("Omlibraryimageexhibitrelationship")->fetchObjects("SELECT * 
                                                                    FROM {$db->prefix}image_exhibit_relationship p
                                                                    WHERE p.entity_id = $id");
        foreach($grouping_records as $grouping_record){
            $grouping_record->delete();
        }
    }
    
}