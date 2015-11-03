<?php
//Groups table
class LibraryListOfGroups extends Omeka_Record_AbstractRecord {
    public $name;
    public $group_id;

//list all groups available
function get_groups_names() {
  $db = get_db();
  $group_list='';
  $groups='';
  $groups = $this->getTable('LibraryListOfGroups')->findAll();
  if(!empty($groups)) {
   	foreach($groups as $group) {
 	  	$group_list[$group['group_id']] = $group['name'];
	  }
  }
  return $group_list;
}

// get groups related to specific role
function get_groups_names_using_role($id, $role) {
  $db = get_db();
  $group_list='';
  $groups='';

  $select = new Omeka_Db_Select();

  $select->from(array('g' => "{$db->prefix}groups"))
             ->join(array('gr' => "{$db->prefix}grouping_relationship"),
                    ('g.group_id = gr.group_id'))
             ->where('entity_id = ?',$id);

   if (($role=='super') || ($role=='admin'))
        $group_names = $this->getTable('LibraryListOfGroups')->findAll();
   else
        $group_names =  $db->getTable("LibraryListOfGroups")->fetchObjects($select);

  if(!empty($group_names)) {
    foreach($group_names as $group_name) {
        $group_list[$group_name['group_id']] = $group_name['name'];
    }
  }
    return $group_list;
}
}