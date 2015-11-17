<?php

class GroupAssertion extends Omeka_Acl_Assert_Ownership {

 public function assert(Zend_Acl $acl,
                           Zend_Acl_Role_Interface $role = null,
                           Zend_Acl_Resource_Interface $resource = null,
                           $privilege = null)
    {

      $allowed = parent::assert($acl,$role,$resource,$privilege);

      if (!$allowed) {
         $allowed = $this->_is_exhibit_link_to_group($resource->id);
      }

      return $allowed;
    } // end assert

//This function validate if the user with group relation is linked to an exhibit.
 private function _is_exhibit_link_to_group($exhibitId)
 {
     $flag = false;
		 $user = current_user();
		 $user_id = $user['id'];
		 $groups_in_exhibit = ExhibitGroupsRelationShip::findGroupsBelongToExhibit($exhibitId);
		 $user_groups = GroupUserRelationship::findUserRelationshipRecords($user_id);
		 foreach ($user_groups as $group) {
		   if((array_search($group,$groups_in_exhibit)!==false))
  			  return true;
		 }
		 return $flag;
 }
}
