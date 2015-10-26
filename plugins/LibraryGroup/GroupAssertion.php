<?php

class GroupAssertion extends Omeka_Acl_Assert_Ownership {

 public function assert(Zend_Acl $acl,
                           Zend_Acl_Role_Interface $role = null,
                           Zend_Acl_Resource_Interface $resource = null,
                           $privilege = null)
    {

      $allowed = parent::assert($acl,$role,$resource,$privilege);
      if (!$allowed){
					if ($this->_is_exhibit_link_to_group($resource->id)){
	        return "allowed";
	        }
	        else {
	         return $allowed;
	         }
      }
      else{
	      return $allowed;
	      }
    } // end assert

//This function validate if the user with group relation is linked to an exhibit.
 private function _is_exhibit_link_to_group($id)
 {
     $flag = false;
		 $user = current_user();
		 $user_id = $user['id'];
	   $newExhibit_groups_object = new LibraryExhibitGroupsRelationShip;
	   $newUser_groups_object = new LibraryGroupUserRelationship;
		 $groups_in_exhibit = $newExhibit_groups_object->get_groups_ids_attached_to_exhibit($id);

		 $user_groups = $newUser_groups_object->get_groups_user_belong_to($user_id);
		 foreach ($user_groups as $group) {
		   if((array_search($group,$groups_in_exhibit)!==false))
  			  return true;
		 }
		 return $flag;
 }
}