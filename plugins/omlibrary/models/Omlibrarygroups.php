<?php
//require_once "MyOmekaPosterItem.php";
//require_once "MyOmekaNote.php";
//require_once 'MyOmekaPosterTable.php';
class Omlibrarygroups extends Omeka_Record
{
    public $name;
    public $group_id;
    
  //  protected $_related = array('Items'=>'getItems', 'User'=>'getUser');
    
   /* public function getItems()
    {
        return $this->getPosterItems($this->id);
    }
    
    public function getUser()
    {
        return $this->getTable('User')->find($this->user_id);
    }
    
    public function getPosterItems($poster_id){
        if(is_numeric($poster_id)){
            $db = get_db();
            $items = $db->getTable("Item")->fetchObjects("  SELECT i.*, pi.annotation, p.user_id
                                                            FROM {$db->prefix}posters_items pi 
                                                            JOIN {$db->prefix}items i ON i.id = pi.item_id
                                                            JOIN {$db->prefix}posters p ON pi.poster_id = p.id
                                                            WHERE pi.poster_id = $poster_id 
                                                            ORDER BY ordernum");
           
           // Go through the items and add in the notes (This could probably be done above in a single query)
           $noteObj = new MyOmekaNote();
           foreach($items as $item){
               $note = $noteObj->getItemNotes($item->user_id, $item->id);
               $item->itemNote = $note[0]->note;
           }
           
           return $items;
        }
    }
    
    public function updateItems(&$params)
    {   
        if(is_numeric($params['itemCount'])){
            $this->deletePosterItems();
            if ($params['itemCount'] > 0) {
                foreach(range(1, $params['itemCount']) as $ordernum){
                    $item = new MyOmekaPosterItem();
                    $item->annotation = $params['annotation-' . $ordernum];
                    $item->poster_id = $this->id;
                    $item->item_id = $params['itemID-' . $ordernum];
                    $item->ordernum = $ordernum;
                    $item->save();
                }
            }
        }        
    }
    
    private function deletePosterItems()
    {
        // Delete entries from posters_items table
        $db = get_db();
        $posters_items =  $db->getTable("MyOmekaPosterItem")->fetchObjects("SELECT * 
                                                                    FROM {$db->prefix}posters_items p
                                                                    WHERE p.poster_id = $this->id");
        foreach($posters_items as $poster_item){
            $poster_item->delete();
        }
    }
    
    public function _delete()
    {
        $this->deletePosterItems();
    }*/
}