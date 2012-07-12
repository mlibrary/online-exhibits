<?php
//require_once 'MyOmekaPosterItemTable.php';
class Omlibrarygrouping extends Omeka_Record{
    public $group_id;
    public $entity_id;


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
                $grouping = new Omlibrarygrouping();
    				 $grouping->entity_id = $user['entity_id'];
			    	 $grouping->group_id = $group;
			    	 //print_r($groupping);
			   	     $grouping->save();				   	     	     
    			} 
    		
               // }
            //}
        //}        
    }

 public function deleteGroupingRecords($id)
    {
   // print_r($id);
    
        // Delete entries from posters_items table
        $db = get_db();
        $grouping_records =  $db->getTable("Omlibrarygrouping")->fetchObjects("SELECT * 
                                                                    FROM {$db->prefix}grouping_relationship p
                                                                    WHERE p.entity_id = $id");
        foreach($grouping_records as $grouping_record){
            $grouping_record->delete();
        }
    }
    
  /*  public function _delete()
    {
        $this->deleteGroupingRecords();
    }*/
}