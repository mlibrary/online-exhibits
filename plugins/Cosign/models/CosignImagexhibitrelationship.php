<?php
//require_once 'MyOmekaPosterItemTable.php';
class CosignImagexhibitrelationship extends Omeka_Record_AbstractRecord{
//    public $image_id;
    //public $exhibit_id;
   public $image_name;
   public $entity_id;
   public $image_title;



 public function deleteImagexhibitrelationshipRecords($id) 
 {
        // Delete entries from posters_items table       
        $db = get_db();
        $where = "`entity_id` = ?";
			 	$image_exhibit_records = $db->getTable("CosignImagexhibitrelationship")->findBySql($where,array($id));
        if (!empty($image_exhibit_records)) {
       // $image_exhibit_records =  $db->getTable("CosignImagexhibitrelationship")
         // ->find($id);
        //->fetchObjects("SELECT * FROM {$db->prefix}image_exhibit_relationship p WHERE p.entity_id = $id");
         
        // print_r($image_exhibit_records);
        // exit;
          foreach($image_exhibit_records as $image_exhibit_record){
            $image_exhibit_record->delete();
          }
        }
  } // end of function deleteImagexhibitrelationshipRecords
    
}