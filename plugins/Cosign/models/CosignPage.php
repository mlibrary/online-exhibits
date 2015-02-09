<?php
//Groups table
class CosignPage extends Omeka_Record_AbstractRecord {
    public $name;
    public $group_id;


/*$iit = $this->getTable('CsvImport_ImportedItem');
        $sql = $iit->getSelectForCount()->where('`import_id` = ?');
        $importedItemCount = $this->getDb()->fetchOne($sql, array($this->id));
        return $importedItemCount;*/



function get_groups_names() {
  $db = get_db();
  $group_list='';
  $groups='';
  $groups = $this->getTable('CosignPage')->findAll();
  if(!empty($groups)) {
   	foreach($groups as $group) {
 	  	$group_list[$group['group_id']] = $group['name'];
	  }
  }
  return $group_list;
}


function get_groups_names_belongto_user($id, $role) {
  $db = get_db();
  $group_list='';
  $groups='';
   if (($role=='super') || ($role=='admin'))
       // $groups =  $db->getTable("CosignPage")->fetchObjects("SELECT * FROM {$db->prefix}groups");
        $groups = $this->getTable('CosignPage')->findAll();
   else
        $groups =  $db->getTable("CosignPage")->fetchObjects("SELECT * FROM {$db->prefix}groups g INNER JOIN {$db->prefix}grouping_relationship gr on g.group_id=gr.group_id where gr.entity_id=$id");

  if(!empty($groups)) {
    foreach($groups as $group) {
        $group_list[$group['group_id']] = $group['name'];
    }
  }
    return $group_list;
}

}