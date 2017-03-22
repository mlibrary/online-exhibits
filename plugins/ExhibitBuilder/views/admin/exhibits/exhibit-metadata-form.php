<?php
	$request = Zend_Controller_Front::getInstance()->getRequest();
	if (class_exists('ListOfGroups')) {
	    $groups_names_object = new ListOfGroups();
	}
	else
   	$groups_names_object = '';
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
    <section class="seven columns alpha">
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
                <?php $exhibitTagList = join('; ', pluck('name', $exhibit->Tags)); ?>
                <?php echo $this->formText('tags', $exhibitTagList); ?>
            </div>
        </div>

        <div class="two columns alpha">
            	 <?php echo $this->formLabel('add-library-tags:', __('Add Library Tags:')); ?>
            	 <?php echo $this->formButton('lib-tag-update','Update Tags'); ?>
        </div>
           <div class="five columns omega inputs">
             <fieldset id="lib-tags">
              <p>You must click <strong> Update Tags</strong> to add additional tags to the Exhibit.</p>
              <?php
              foreach ($hlplists['subject'] as $subjectvalue) {
              if (!empty($subjectvalue['name'])) {
                    echo "<li class='subject-parent'>". $this->formCheckbox($subjectvalue['name'])."<label for='".$subjectvalue['name']."'><a href='#' class='subjectshow_hide'>".$subjectvalue['name']."</a></label>";
              ?>
                    <div class='internalslidingDiv'>
                 	   <ul>
                      <?php foreach ($subjectvalue->topic as $value) {
                                   if ($value->xpath("sub-topic")) {
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
              <?php }}?>
              </fieldset>
         </div>

        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('theme', __('Theme')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php $values = array('' => __('Current Public Theme')) + exhibit_builder_get_themes(); ?>
                <?php echo get_view()->formSelect('theme', $exhibit->theme, array(), $values); ?>
                    <input type="submit" class="configure-button" name="configure-theme" value="<?php echo __('Configure'); ?>">
            </div>
        </div>

  <?php
            if ((!empty($groups_names_object)) and (class_exists('GroupUserRelationship')) and (class_exists('ExhibitGroupsRelationShip'))) {
            ?>
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
                <?php
                      $group_names = $groups_names_object->get_groups_names();
                      $acl = Zend_Registry::get('bootstrap')->getResource('Acl');
                      $groupExhibitValue[] = '';
                      $groupValue[] = '';

                      if (($request->getActionName()=='add') and (($user->role=='super') || ($user->role=='admin')))
                             echo $this->formSelect('group-selection','',array('multiple'=>'multiple'),$group_names);

                      elseif (($request->getActionName()=='add') and (($user->role=='contributor') || ($user->role=='researcher')))  {
                              $user_group_objects =  GroupUserRelationship::findUserRelationshipRecords($user->id);
                              if (!empty($user_group_objects)) {
                                   foreach($user_group_objects as $user_group_object) {
		  	   													  $groupValue[]= $user_group_object['group_id'];
	                         		     }
	                         		}
                    			    echo $this->formSelect('group-selection',$groupValue,array('multiple'=>'multiple'),$group_names);
                    	}

                      elseif ($request->getActionName()=='edit') {
                          if (($user->role=='super') || ($user->role=='admin')) {
                              $current_exhibitGroups =  ExhibitGroupsRelationShip::findGroupsBelongToExhibit($exhibit->id);
															if (!empty($current_exhibitGroups)) {
                                  foreach($current_exhibitGroups as $current_exhibitGroup) {
		  	   												 	  $groupExhibitValue[]= $current_exhibitGroup['group_id'];
	                         		    }
	                         		}

                              $exhibit_ownerId =  $exhibit['owner_id'];
                              $groupNames =  $groups_names_object->get_groups_names_using_role($user->id,$user->role);
                               echo $this->formSelect('group-selection',$groupExhibitValue,array('multiple'=>'multiple'),$groupNames);
                    		  }

                    		  if (($user->role=='contributor') || ($user->role=='researcher')) {
                              $current_exhibitGroups = ExhibitGroupsRelationShip::findGroupsBelongToExhibit($exhibit->id);
                              if (!empty($current_exhibitGroups)) {
                                  foreach($current_exhibitGroups as $current_exhibitGroup) {
		  	   												   	$groupExhibitValue[]= $current_exhibitGroup['group_id'];
	                         		    }
	                         		}

                              $exhibit_ownerId =  $exhibit['owner_id'];
                              if ($exhibit_ownerId == $user->id) {
				                          echo $this->formSelect('group-selection',$groupExhibitValue,array('multiple'=>'multiple'),$group_names);
				                      }
				                      else {
				                         $groupNames =  $groups_names_object->get_groups_names_using_role($user->id,$user->role);
				                         echo $this->formSelect('group-selection',$groupExhibitValue,array('multiple'=>'multiple'),$groupNames);
				                      }

                          }
                      }
                ?>
            </div>
        </div>
              <?php }
            ?>

        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('use_summary_page', __('Use Summary Page?')); ?>
            </div>
            <div class="five columns omega inputs">
                <p class="explanation"><?php echo __("Start the exhibit on the summary page. If unchecked, start on the first exhibit page if it exists."); ?></p>
                <?php echo $this->formCheckbox('use_summary_page', $exhibit->use_summary_page, array(), array('1', '0')); ?>
            </div>
        </div>
        <div id="cover-image-container" class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('cover_image', __('Cover Image')); ?>
            </div>
            <div class="five columns omega inputs">
                <p class="explanation">
                  <?php echo __('Choose a file to represent this exhibit. The selected file will serve as the thumbnail for the exhibit.'); ?>
                </p>
                <?php echo $this->partial('files/cover-image.php', array('file' => $exhibit->getCoverImage())); ?>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo __('Pages'); ?></legend>
        <div id="pages-list-container">
            <?php if (!$exhibit->TopPages): ?>
                <p><?php echo __('There are no pages.'); ?></p>
            <?php else: ?>
                <p class="instructions"><?php echo __('To reorder or nest pages, click and drag a page to the preferred location.'); ?></p>
                <?php echo common('page-list', array('exhibit' => $exhibit), 'exhibits'); ?>
            <?php endif; ?>
        </div>
        <div id="page-add">
            <input type="submit" name="add_page" id="add-page" value="<?php echo __('Add Page'); ?>" />
        </div>
    </fieldset>
    </section>
    <?php echo $csrf; ?>
    <section class="three columns omega">
        <div id="save" class="panel">
            <?php echo $this->formSubmit('save_exhibit', __('Save Changes'), array('class'=>'submit big green button')); ?>
            <?php if ($exhibit->exists()): ?>
                <?php echo exhibit_builder_link_to_exhibit($exhibit, __('View Public Page'), array('class' => 'big blue button', 'target' => '_blank')); ?>
                <?php if (is_allowed($exhibit, 'delete')): ?>
                    <?php echo link_to($exhibit, 'delete-confirm', __('Delete Exhibit'), array('class' => 'big red button delete-confirm')); ?>
                <?php endif; ?>
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
    </section>
</form>

<div id="cover-image-panel" title="<?php echo html_escape(__('Choose a Cover Image')); ?>">
    <div id="item-form">
        <button type="button" id="revert-selected-item">Revert to Selected Item</button>
        <button type="button" id="show-or-hide-search" class="show-form blue">
            <span class="show-search-label"><?php echo __('Show Search Form'); ?></span>
            <span class="hide-search-label"><?php echo __('Hide Search Form'); ?></span>
        </button>
        <a href="<?php echo url('exhibit-builder/items/browse'); ?>" id="view-all-items" class="green button"><?php echo __('View All Items'); ?></a>
        <div id="page-search-form" class="container-twelve">
        <?php
            $action = url(array('module' => 'exhibit-builder',
                'controller' => 'items', 'action' => 'browse'), 'default', array(), true);
            echo items_search_form(array('id' => 'search'), $action);
        ?>
        </div>
        <div id="item-select"></div>
    </div>
    <div id="cover-image-options">
      <button type="button" id="change-selected-item"><?php echo __('Change Selected Item'); ?></button>
      <div class="options">
        <div id="cover-image-item-options"></div>
      </div>
      <div id="attachment-save">
        <button type="submit" id="choose-cover-image"><?php echo __('Choose'); ?></button>
      </div>
    </div>
    <div id="cover-image-panel-loading"><span class="spinner"></span></div>
</div>
<script type="text/javascript" charset="utf-8">
//<![CDATA[
    jQuery(window).load(function() {
        Omeka.wysiwyg();
    });
    jQuery(document).ready(function(){
        Omeka.ExhibitBuilder.setUpCoverImageChooser(
          <?php echo json_encode(url('exhibit-builder/files/cover-image')); ?>,
          <?php echo js_escape(url('exhibits/attachment-item-options')); ?>
        );
        Omeka.ExhibitBuilder.setUpCoverImageSelect(<?php echo json_encode(url('exhibit-builder/items/browse'));
?>);
    });
//]]>
</script>
<script>
jQuery(window).load(function() {    
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
                                    }                                                                                                                                                                         }); // lib-tags
                          var tagsStr = tags.join(';');
                            tagsInput.val(tagsStr);
                          jQuery(this).removeClass('enabled');
                          tagsInput.focus(); //provide feedback by setting focus to the input
       });// lib-tag-update
    }); //jQuery(window).load(function() {
//]]>
</script>
