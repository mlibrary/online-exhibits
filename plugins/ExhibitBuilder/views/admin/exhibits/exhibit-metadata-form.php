<?php $request = Zend_Controller_Front::getInstance()->getRequest();

//$actionName = $request->getActionName();
?>

<style>
fieldset fieldset {
	font-size:80%;
	padding:10px 18px;
	border: 1px dotted #EDEDED;
	background: #FCFCFC;
}
fieldset fieldset legend {
	padding-bottom:0;
	}
.subject-parent {
	list-style:none;
	padding-bottom:.3em;
/*	background:url(data:image/gif;base64,R0lGODlhCAAIAIABAGRkZAAAACH5BAEAAAEALAAAAAAIAAgAAAIOTGBpgHrsGEyyrUktdQUAOw==) no-repeat left 4px;*/
	padding-left:16px;
}
.subject-parent .subject-parent {
	margin-left:-16px;
}
.subject-parent ul {
/*remove by nancy
	margin:1em;*/
	 list-style: none outside none;
	}
.subject-parent label {
	float:none;
/*	display:inline-block;*/
	font-size:1.4em;
	padding-left:.4em;
	/*added by nancy*/
  vertical-align: middle;
  width: 250px;
  margin-bottom: 0;

}
.list-open {
	background:url(data:image/gif;base64,R0lGODlhCAAIAIABAGRkZAAAACH5BAEAAAEALAAAAAAIAAgAAAIMjI+pB+0dHjQvzWUKADs=) no-repeat left 4px;
}
.sub-list {
/*	padding:.5em;
	margin:.5em;*/
}

#lib-tag-update {
	float:none;
	background-color:#AAA;
}
#lib-tag-update.enabled {
	background-color: #38C;
	cursor:pointer;
}
#lib-tag-update.enabled:hover {
	background-color: #369
}
</style>
<?php 
$url = 'http://www.lib.umich.edu/browse/categories/xml.php';
if ($xml = file_get_contents($url)) 
	{	
			$xml = utf8_encode($xml);
	}
		
		libxml_use_internal_errors(true);    
		$xml = simplexml_load_string($xml);  
		libxml_clear_errors();    
		$hlplists = (array) $xml;	
?>


<form id="exhibit-metadata-form" method="post" class="exhibit-builder">
    <div class="seven columns alpha">
    <fieldset>
        <legend><?php echo __('Exhibit Metadata'); ?></legend>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('title', __('Title')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formText('title', $exhibit->title); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('slug', __('Slug')); ?>
            </div>
            <div class="five columns omega inputs">
                <p class="explanation"><?php echo __('No spaces or special characters allowed'); ?></p>
                <?php echo $this->formText('slug', $exhibit->slug); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('credits', __('Credits')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formText('credits', $exhibit->credits); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('description', __('Description')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formTextarea('description', $exhibit->description, array('rows'=>'8','cols'=>'40')); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('tags', __('Tags')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php //$exhibitTagList = join(', ', pluck('name', $exhibit->Tags)); ?>                
                 <?php //Added by Nancy Moussa
                 $exhibitTagList = join('; ', pluck('name', $exhibit->Tags)); ?>
                <?php echo $this->formText('tags', $exhibitTagList); ?>
            </div>            	        	
           <div class="two columns alpha">
            	 <?php echo $this->formLabel('add-library-tags:', __('Add Library Tags:')); ?>
            	 <?php echo $this->formButton('lib-tag-update','Update Tags'); ?>
            </div>
            <div class="five columns omega inputs">
             <fieldset id="lib-tags">             
              <p>You must click <strong> Update Tags</strong> to add additional tags to the Exhibit.</p> 
              <?php foreach ($hlplists['subject'] as $subjectvalue){
                    echo "<li class='subject-parent'>". $this->formCheckbox($subjectvalue['name'])."<label for='".$subjectvalue['name']."'><a href='#' class='subjectshow_hide'>".$subjectvalue['name']."</a></label>";
              ?>
                    <div class='internalslidingDiv'>
                 	   <ul>
                      <?php foreach ($subjectvalue->topic as $value) { 			   			  
                                   if ($value->xpath("sub-topic")){                    
                                      print "<li class='subject-parent '>".$this->formCheckbox($value['name'])."<label for='".$value['name']."'><a href='#' class='subject-nested'>".$value['name']."</a></label>"; ?>
                                      <div class='subject-sub-internalslidingDiv'>
                                        <ul>
                                          <?php foreach ($value->xpath("sub-topic") as $subvalue) {
                                                   print "<li class='sub-list'>".$this->formCheckbox($subvalue['name'])."<label for='".$subvalue['name']."'>".$subvalue['name']."</label>";
                                           }?> 
                                        </ul>                              
                                      </div>
                                      </li>                        
                                    <?php }
                                    else {
                                       print "<li class='sub-list'>".$this->formCheckbox($value['name'])."<label for='".$value['name']."'>".$value['name']."</label>";
                                    } 					                
                         }           
                      ?>
                    </ul>
                    </div>
                    </li>
              <?php }?>             		
              </fieldset>
              </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('theme', __('Theme')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php $values = array('' => __('Current Public Theme')) + exhibit_builder_get_themes(); ?>
                <?php echo get_view()->formSelect('theme', $exhibit->theme, array(), $values); ?>
                <?php if ($theme && $theme->hasConfig): ?>
                    <a href="<?php echo html_escape(url("exhibits/theme-config/$exhibit->id")); ?>" class="configure-button button"><?php echo __('Configure'); ?></a>
                <?php endif;?>
            </div>
        </div>
     <div class="field">
        <?php  $user = current_user();?>
            <div class="two columns alpha">
                <?php if (($request->getActionName()=='add' || $request->getActionName()=='edit' ) and (($user->role=='contributor') || ($user->role=='researcher'))) 
                echo $this->formLabel('Your groups', __('Your groups:')); 
                else {               
                echo $this->formLabel('Select a Group', __('Select a Group')); 
                }
                ?>
            </div>         
            <div class="five columns omega inputs">
                <?php //$values = array('' => __('')) + exhibit_builder_get_themes(); ?>
                <?php //echo get_view()->formSelect('theme', $exhibit->theme, array(), $values); ?>
                <?php /*if ($theme && $theme->hasConfig): ?>
                    <a href="<?php echo html_escape(url("exhibits/theme-config/$exhibit->id")); ?>" class="configure-button button"><?php echo __('Configure'); ?></a>
                <?php endif;*/?>                
                <?php  
                      $group_names = get_groups_names(); 
                      $acl = Zend_Registry::get('bootstrap')->getResource('Acl');
                      //($acl->isAllowed('super') || $acl->isAllowed('admin')) 
                    
                      if (($request->getActionName()=='add') and (($user->role=='super') || ($user->role=='admin')))                                                                                                        
                             echo $this->formSelect('group-selection','','',$group_names);	
                      elseif (($request->getActionName()=='add') and (($user->role=='contributor') || ($user->role=='researcher')))  {                     
                              $groupValue =  get_groups_names_belongto_user($user->id,$user->role); 
                    			    echo $this->formSelect('group-selection',$groupValue,'',$groupValue);	     
                    	}                                                                                                                          			                                           				              				         
                      elseif ($request->getActionName()=='edit'){
                          if (($user->role=='super') || ($user->role=='admin')) {                                                                          
                              $groupValue =  get_groups_ids_attached_to_exhibits($exhibit->id);                      
                              echo $this->formSelect('group-selection',$groupValue,'',$group_names);	
                    		  }
                    		  if (($user->role=='contributor') || ($user->role=='researcher')) {  
                    		                                               
                              $group_exhibit_id =  get_groups_ids_attached_to_exhibits($exhibit->id);    
                              $groupValue =  get_groups_names_belongto_user($user->id,$user->role);  
                              echo $this->formSelect('group-selection',$group_exhibit_id,'',$groupValue);	
                    		  }
                      }	
                    //else{
              		       //$group_id = get_groups_ids_attached_to_exhibits($exhibit->id);                    
                    		 //echo select(array('name'=>'group-selection','id'=>'group-selection'),get_groups_names_belongto_user($user->entity_id,$user->role),$group_id); 
                    //}
                ?>               
            </div> 
        </div>    
    </fieldset>
    <fieldset>
        <legend><?php echo __('Pages'); ?></legend>
        <div id="pages-list-container">
            <?php if (!$exhibit->TopPages): ?>
                <p><?php echo __('There are no pages.'); ?></p>
            <?php else: ?>
                <p id="reorder-instructions"><?php echo __('To reorder pages, click and drag the page up or down to the preferred location.'); ?></p>
                <?php echo common('page-list', array('exhibit' => $exhibit), 'exhibits'); ?>
            <?php endif; ?>
        </div>
        <div id="page-add">
            <input type="submit" name="add_page" id="add-page" value="<?php echo __('Add Page'); ?>" />
        </div>
    </fieldset>
    </div>
    <div id="save" class="three columns omega panel">
        <?php echo $this->formSubmit('save_exhibit', __('Save Changes including delete pages'), array('class'=>'submit big green button')); ?>
        <?php if ($exhibit->exists()): ?>
            <?php echo exhibit_builder_link_to_exhibit($exhibit, __('View Public Page'), array('class' => 'big blue button', 'target' => '_blank')); ?>
            <?php echo link_to($exhibit, 'delete-confirm', __('Delete The Exhibit'), array('class' => 'big red button delete-confirm')); ?>
        <?php endif; ?>
        <div id="public-featured">
            <div class="public">
                <label for="public"><?php echo __('Public'); ?>:</label> 
                <?php echo $this->formCheckbox('public', $exhibit->public, array(), array('1', '0')); ?>
            </div>
            <div class="featured">
                <label for="featured"><?php echo __('Featured'); ?>:</label> 
                <?php echo $this->formCheckbox('featured', $exhibit->featured, array(), array('1', '0')); ?>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript" charset="utf-8">
    jQuery(window).load(function() {
        Omeka.ExhibitBuilder.wysiwyg();
        //////////////////////////////        
        jQuery.noConflict();      
		    jQuery(".internalslidingDiv").hide();
        jQuery(".subject-sub-internalslidingDiv").hide();
      
		    jQuery('.subjectshow_hide').click(function(e){
			    e.preventDefault();
			    jQuery(".internalslidingDiv",jQuery(this).parents('li')).toggle();
			    jQuery(this).children(".subject-sub-internalslidingDiv").hide();
			    jQuery(this).parents('li').toggleClass('list-open');
			    return false;
		    });
    
		    jQuery('.subject-nested').click(function(e){
		  	  e.preventDefault();
			    e.stopPropagation();
			    jQuery(".subject-sub-internalslidingDiv",jQuery(this).closest('li')).toggle();
			    jQuery(this).parents('li').toggleClass('list-open');
		  	  return false;
		    });
		 			
    	  jQuery('#lib-tags input[type=checkbox]').click(function(e){
		      if(!jQuery('#lib-tag-update').hasClass('enabled')){
			      jQuery('#lib-tag-update').addClass('enabled');
		      }
	      }); // enable update button 		
	   
	      jQuery('#lib-tag-update').click(function(){
			    var tags = [];
			    var tagsInput = jQuery('#tags');
			    jQuery.each(tagsInput.val().split(';'),function(){ // grab current tag list
			      	tags.push(jQuery.trim(this)); //trim whitespace
			    });
      
			    jQuery('#lib-tags input[type=checkbox]').each(function() { // cycle and add new tags to list
				    if(jQuery(this).is(':checked')){
					  //console.log(jQuery(this));
					    if(jQuery.inArray(this.name,tags) == -1){ // check if already a tag
						    tags.push(this.name);	
					    }
					    jQuery(this).removeAttr("checked"); //clear checkboxes
				    }
			    }); // lib-tags      					  
  			  var tagsStr = tags.join(';'); 
			    tagsInput.val(tagsStr);
  			  jQuery(this).removeClass('enabled');
	  		  tagsInput.focus(); //provide feedback by setting focus to the input      
       });// lib-tag-update      
    }); //jQuery(window).load(function() {      
</script>

