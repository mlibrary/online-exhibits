<?php
//require_once 'MyOmekaPosterItemTable.php';
class CosignImagexhibitrelationship extends Omeka_Record_AbstractRecord {
   public $image_name;
   public $entity_id; // refer to exhibit id
   public $image_title;

 public function deleteImagexhibitrelationshipRecords($id) {
  // Delete entries from posters_items table
  $db = get_db();
  $where = "`entity_id` = ?";
	$image_exhibit_records = $db->getTable("CosignImagexhibitrelationship")->findBySql($where,array($id));
  if (!empty($image_exhibit_records)) {
      foreach($image_exhibit_records as $image_exhibit_record) {
        $image_exhibit_record->delete();
      }
  }
 } // end of function deleteImagexhibitrelationshipRecords


function get_image_attached_to_exhibits($id){
	 $value = '';
	 $db = get_db();
	 if(!empty($id)){
	 	$where = "`entity_id` = ?";
	 	$row = $db->getTable("CosignImagexhibitrelationship")->findBySql($where,array($id));
    if (!empty($row)){
				$value= $row[0];
		}
	}
	return $value;
}

}