<?php

  if(!isset($user)) {
     $user = new User;
     $user->setArray($_POST);
  }

    $user_group_object = new MlibraryGroupUserRelationship;
    $group_names_object = new MlibraryGroupListOfGroups;
?>

<?php echo flash(); ?>

<fieldset>
 <div class="field">
   <div class="two columns alpha">
     <?php echo $this->formLabel('username', __('username')); ?>
   </div>
   <div class="two columns omega inputs">
    <?php $Username = ((!empty($user->username)) ? $user->username:"");
          echo $this->formText('username',$Username,array('size' => '40'));?>
   </div>
 </div>

 <div class="field">
   <div class="two columns alpha">
     <?php echo $this->formLabel('name','Display Name'); ?>
   </div>
   <div class="five columns omega inputs">
      <?php $Namevalue = ((!empty($user->name)) ? $user->name:"");
            echo $this->formtext('name', $Namevalue); ?>
   </div>
 </div>

 <div class="field">
   <div class="two columns alpha">
      <?php echo $this->formLabel('email','Email'); ?>
   </div>
   <div class="five columns omega inputs">
      <?php $emailValue = ((!empty($user->email)) ? $user->email :"");
            echo $this->formtext('email',$emailValue); ?>
   </div>
</div>

<?php //$acl = Zend_Registry::get('bootstrap')->getResource('Acl');
//if($acl->isAllowed('super')){?>
<div class="field">
    <div class="two columns alpha">
         <?php echo $this->formLabel('role','Role');?>
    </div>
    <div class="two columns omega inputs">
         <?php $roleValue = ((!empty($user->role)) ? $user->role : '');
          echo $this->formSelect('role',$roleValue,array(),get_user_roles()); ?>
    </div>
</div>

<div class="field">
    <div class="two columns alpha">
       <?php echo $this->formLabel('group','Group'); ?>
    </div>
    <div class="two columns omega inputs">
         <?php $groupValue = ((!empty($user->id)) ? $user_group_object->get_groups_user_belong_to($user->id)  : '');
               $group_names = $group_names_object->get_groups_names();


          echo $this->formSelect('group',$groupValue,array('multiple'=>'multiple'),$group_names);?>
    </div>
</div>

<div class="field">
        <div class="two columns alpha">
             <?php if(isset($user->username)) {
                      echo $this->formLabel('activity','Activity'); ?>
        </div>
        <div class="five columns omega inputs">
              <?php $userActive = ((!empty($user['active'])) ? $user->active  : '');
				      			echo $this->formCheckbox('active',$userActive,array(),array('1','0')); ?>
        </div>
              <?php }?>
</div>
</fieldset>

<?php print $csrf; ?>
