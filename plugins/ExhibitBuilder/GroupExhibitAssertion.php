<?php 

class GroupExhibitAssertion extends Omeka_Acl_Assert_Ownership
{

 public function assert(Zend_Acl $acl,
                           Zend_Acl_Role_Interface $role = null,
                           Zend_Acl_Resource_Interface $resource = null,
                           $privilege = null)
    {
      
      $allowed = parent::assert($acl,$role,$resource,$privilege);
 
      if (!$allowed){
					if (($this->_istestexhibitbuilder($resource->id)))
	        return "allowed";	
	        else
	         return $allowed;     	        
      }
      else
	      return $allowed;
      
    } // end assert
    
    private function _istestexhibitbuilder($id) {
			$group_in_exhibit = get_groups_ids_attached_to_exhibits($id);
		
			$user = current_user();
			$user_id = $user['id'];
			$result=0;
			if(!empty($group_in_exhibit[0]))
					$result = test($group_in_exhibit[0],$user_id);
	
			return $result;
		}

}