<?php
//require_once 'MyOmekaPosterItemTable.php';
class CosignGrouping extends Omeka_Record_AbstractRecord{
    public $group_id;
    public $entity_id;

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


public function updateGrouping($user)
    {   
       // if(is_numeric($params['itemCount'])){
         //   $this->deletePosterItems();
           // if ($params['itemCount'] > 0) {
             //   foreach(range(1, $params['itemCount']) as $ordernum){
                 /*   $item = new MyOmekaPosterItem();
                    $item->annotation = $params['annotation-' . $ordernum];
                    $item->poster_id = $this->id;
                    $item->item_id = $params['itemID-' . $ordernum];
                    $item->ordernum = $ordernum;
                    $item->save();*/
                    
                    
                       foreach($user['group'] as $group){
                $grouping = new CosignGrouping();
    				 $grouping->entity_id = $user->id;
			    	 $grouping->group_id = $group;
			    	 //print_r($groupping);
			   	     $grouping->save();				   	     	     
    			} 
    		
               // }
            //}
        //}        
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

 public function deleteGroupingRecords($id)
    {
   // print_r($id);
    
        // Delete entries from posters_items table
        $db = get_db();
        $grouping_records =  $db->getTable("CosignGrouping")->fetchObjects("SELECT * 
                                                                    FROM {$db->prefix}grouping_relationship p
                                                                    WHERE p.entity_id = $id");
        foreach($grouping_records as $grouping_record){
            $grouping_record->delete();
        }
    }
    
  /*  public function _delete
    {
        $this->deleteGroupingRecords();
    }*/
}