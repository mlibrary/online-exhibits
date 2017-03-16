<?php

class ReassignOwnershipPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
            'admin_items_panel_fields',
            'before_save_item',
            'admin_collections_panel_fields',
            'before_save_collection'
            );

    public function setUp()
    {
        parent::setUp();
       /* if(plugin_is_active('ExhibitBuilder')) {
           // $this->_hooks[] = 'before_save_exhibit';
        }*/
    }

    public function hookAdminItemsPanelFields($args)
    {
        $this->_echoPanel($args);
    }

    /* public function hookAdminUsersPanelFields($args)
    {
        $this->_echoPanel($args);
    }*/

    public function hookAdminCollectionsPanelFields($args)
    {

        $this->_echoPanel($args);

    }

    public function hookBeforeSaveItem($args)
    {

      	  $this->_reassignOwnership($args);

    }

    public function hookBeforeSaveCollection($args)
    {

        $this->_reassignOwnership($args);

    }

    private function _reassignOwnership($args)
    {

    		if (isset($args['post']['reassign_ownership_id'])) {
	        $newOwnerId = $args['post']['reassign_ownership_id'];
  	      $record = $args['record'];
    	    $newOwner = $this->_db->getTable('User')->find($newOwnerId);
      	  $record->setOwner($newOwner);
        }
    }

    private function _echoPanel($args)
    {

        $view = $args['view'];

        $record = $args['record'];

        $user = current_user();
        if( ($user->id == $record->isOwnedBy($user)) || $user->role == 'admin' || $user->role == 'super' ) {

            echo "<div class='field'>";

            echo "<label for='reassign_ownership_id'>" . __('Reassign Ownership') .  "</label>";

            // if it is a new record, user->id will be the default as an owner
						if (isset($record->getOwner()->id)) {
		         		echo $view->formSelect('reassign_ownership_id',$record->getOwner()->id ,
		         		array('id'=>'reassign-ownership'), get_table_options('User', null, array('sort_field' => 'name')));
         		}
         		else {
								echo $view->formSelect('reassign_ownership_id',$user->id , array('id'=>'reassign-ownership'),
								get_table_options('User', null, array('sort_field' => 'name')));
         		}
            echo "</div>";
        }
    }
}