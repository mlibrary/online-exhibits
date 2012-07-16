<?php
if ($exhibit->title) {
    $exhibitTitle = __('Edit Exhibit: "%s"', $exhibit->title);
} else {
    $exhibitTitle = ($actionName == 'Add') ? __('Add Exhibit') : __('Edit Exhibit');
}
?>
<?php head(array('title'=> html_escape($exhibitTitle), 'bodyclass'=>'exhibits')); ?>
<?php echo js('listsort'); ?>

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
	background:url(data:image/gif;base64,R0lGODlhCAAIAIABAGRkZAAAACH5BAEAAAEALAAAAAAIAAgAAAIOTGBpgHrsGEyyrUktdQUAOw==) no-repeat left 4px;
	padding-left:16px;
}
.subject-parent .subject-parent {
	margin-left:-.9em;
}
.subject-parent ul {
	margin:1em;
	}
.subject-parent label {
	float:none;
	display:inline-block;
	font-size:1.4em;
	padding-left:.4em;
	width:auto;

}
.list-open {
	background:url(data:image/gif;base64,R0lGODlhCAAIAIABAGRkZAAAACH5BAEAAAEALAAAAAAIAAgAAAIMjI+pB+0dHjQvzWUKADs=) no-repeat left 4px;
}
.sub-list {
	padding:.5em;
	margin:.5em;
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

<script type="text/javascript" charset="utf-8"> 
//<![CDATA[
    var listSorter = {};
    
    function makeSectionListDraggable()
    {
        var sectionList = jQuery('.section-list');
        var sectionListSortableOptions = {axis:'y', forcePlaceholderSize: true};
        var sectionListOrderInputSelector = '.section-info input';
        
        var sectionListDeleteLinksSelector = '.section-delete a';
        var sectionListDeleteConfirmationText = <?php echo js_escape(__('Are you sure you want to delete this section?')); ?>;
        var sectionListFormSelector = '#exhibit-metadata-form';
        var sectionListCallback = Omeka.ExhibitBuilder.addStyling;
        makeSortable(sectionList, 
                     sectionListSortableOptions,
                     sectionListOrderInputSelector,
                     sectionListDeleteLinksSelector, 
                     sectionListDeleteConfirmationText, 
                     sectionListFormSelector, 
                     sectionListCallback);

        var pageListSortableOptions = {axis:'y', connectWith:'.page-list'};
        var pageListOrderInputSelector = '.page-info input';
        var pageListDeleteLinksSelector = '.page-delete a';
        var pageListDeleteConfirmationText = <?php echo js_escape(__('Are you sure you want to delete this page?')); ?>;
        var pageListFormSelector = '#exhibit-metadata-form';
        var pageListCallback = Omeka.ExhibitBuilder.addStyling;
        
        var pageLists = jQuery('.page-list');
        jQuery.each(pageLists, function(index, pageList) {
            makeSortable(jQuery(pageList), 
                         pageListSortableOptions,
                         pageListOrderInputSelector, 
                         pageListDeleteLinksSelector, 
                         pageListDeleteConfirmationText, 
                         pageListFormSelector, 
                         pageListCallback);
            
            // Make sure the order inputs for pages change the names to reflect their new
            // section when moved to another section           
            jQuery(pageList).bind('sortreceive', function(event, ui) {                
                var pageItem = jQuery(ui.item);
                var orderInput = pageItem.find(pageListOrderInputSelector);              
                var pageId = orderInput.attr('name').match(/(\d+)/g)[1];                
                var nSectionId = pageItem.closest('li.exhibit-section-item').attr('id').match(/(\d+)/g)[0];
                var nInputName = 'Pages['+ nSectionId + ']['+ pageId  + '][order]';
             
                orderInput.attr('name', nInputName);
            });
        });
    }
    
    
    jQuery(window).load(function() {
        Omeka.ExhibitBuilder.wysiwyg();
        Omeka.ExhibitBuilder.addStyling();
        
        makeSectionListDraggable(); 
        
        // Fixes jQuery UI sortable bug in IE7, where dragging a nested sortable would
        // also drag its container. See http://dev.jqueryui.com/ticket/4333
        jQuery(".page-list li").hover(
            function(){
        	    jQuery(".section-list").sortable("option", "disabled", true);
            },
            function(){
        	    jQuery(".section-list").sortable("option", "disabled", false);
            }
        );
    
    
    
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
		
      });
		
//]]>   
</script>

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


<h1><?php echo html_escape($exhibitTitle); ?></h1>

<div id="primary">
    <div id="exhibits-breadcrumb">
        <a href="<?php echo html_escape(uri('exhibits')); ?>"><?php echo __('Exhibits'); ?></a> &gt;
        <?php echo html_escape($exhibitTitle); ?>
    </div>

<?php echo flash();?>

    <form id="exhibit-metadata-form" method="post" class="exhibit-builder">

        <fieldset>
            <legend><?php echo __('Exhibit Metadata'); ?></legend>
            <div class="field">
            <?php echo text(array('name'=>'title', 'class'=>'textinput', 'id'=>'title'), $exhibit->title, __('Title')); ?>
            <?php echo form_error('title'); ?>
            </div>
            <div class="field">
            <?php echo text(array('name'=>'slug', 'id'=>'slug', 'class'=>'textinput'), $exhibit->slug, __('Slug')); ?>
            <p class="explanation"><?php echo __('No spaces or special characters allowed.'); ?></p>
            <?php echo form_error('slug'); ?>
            </div>
            <div class="field">
            <?php echo text(array('name'=>'credits', 'id'=>'credits', 'class'=>'textinput'), $exhibit->credits, __('Credits')); ?>
            </div>
            <div class="field">
            <?php echo textarea(array('name'=>'description', 'id'=>'description', 'class'=>'textinput','rows'=>'10','cols'=>'40'), $exhibit->description, __('Description')); ?>
            </div>   
             <?php $exhibitTagList = join('; ', pluck('name', $exhibit->Tags)); ?>
          
           <!-- <div class="field tag" style="border-bottom: 1px hidden #EEEEEE; padding: 18px 0; position: relative;">-->
           <div class="field">
            <?php echo text(array('name'=>'tags', 'id'=>'tags', 'class'=>'textinput'), $exhibitTagList, __('Tags')); ?>
          	<button type="button" id="lib-tag-update" class="configure-button button">Update Tags</button>
            
                      
           	<fieldset id="lib-tags">
            <legend>Add Library Tags:</legend>
            <p> After selecting, you must click <strong>update</strong> to add additional tags to the Exhibit. </p> 
            <?php 
              foreach ($hlplists['subject'] as $subjectvalue){
                          print "<li class='subject-parent'><input type='checkbox' name='".htmlspecialchars($subjectvalue['name'], ENT_QUOTES)."' id='".htmlspecialchars($subjectvalue['name'], ENT_QUOTES)."'/><label for='".htmlspecialchars($subjectvalue['name'], ENT_QUOTES)."'><a href='#' class='subjectshow_hide'>".htmlspecialchars($subjectvalue['name'], ENT_QUOTES)."</a></label>";
            ?>
                     <div class='internalslidingDiv'>
                 	<ul>
             <?php
               foreach ($subjectvalue->topic as $value) { 
			   			  
                    if ($value->xpath("sub-topic")){
                    
                           print "<li class='subject-parent '><input type='checkbox' name='".htmlspecialchars($value['name'], ENT_QUOTES)."' id='".htmlspecialchars($value['name'], ENT_QUOTES)."'/><label for='".htmlspecialchars($value['name'], ENT_QUOTES)."'><a href='#' class='subject-nested'>".htmlspecialchars($value['name'], ENT_QUOTES)."</a></label>"; ?>
                          <div class='subject-sub-internalslidingDiv'>
                          <ul>
                            <?php foreach ($value->xpath("sub-topic") as $subvalue) {
                                  print "<li class='sub-list'><input type='checkbox' name='".htmlspecialchars($subvalue['name'], ENT_QUOTES)."' id='".htmlspecialchars($subvalue['name'], ENT_QUOTES)."'/><label for='".htmlspecialchars($subvalue['name'], ENT_QUOTES)."'>".htmlspecialchars($subvalue['name'], ENT_QUOTES)."</label>";
                           }?> 
                           </ul>                              
                        </div>
                        </li>
                        
                     <?php }
                      else {
                        print "<li class='sub-list'><input type='checkbox' name='".htmlspecialchars($value['name'], ENT_QUOTES)."' id='".htmlspecialchars($value['name'], ENT_QUOTES)."'/><label for='".htmlspecialchars($value['name'], ENT_QUOTES)."'>".htmlspecialchars($value['name'], ENT_QUOTES)."</label>";
                      } 					                
             }           
             ?>
                    	</ul>
                      </div>
                      </li>
             <?php }?>
             		
             </fieldset>
               </div>
            	<!-- </div>-->
               
            <?php if (get_theme_option('View Items in Gallery')!='pages_2'):?>
            <div class="field">
                <label for="featured"><?php echo __('Featured'); ?></label>
                <div class="radio"><?php echo checkbox(array('name'=>'featured', 'id'=>'featured'), $exhibit->featured); ?></div>
            </div>
            <?php endif;?>
            <div class="field">
                <label for="featured"><?php echo __('Public'); ?></label>
                <div class="radio"><?php echo checkbox(array('name'=>'public', 'id'=>'public'), $exhibit->public); ?></div>
            </div>
            <div class="field">
                <label for="theme"><?php echo __('Theme'); ?></label>            
                <?php $values = array('' => __('Current Public Theme')) + exhibit_builder_get_ex_themes(); ?>
                <div class="select"><?php echo __v()->formSelect('theme', $exhibit->theme, array('id'=>'theme'), $values); ?>
                <?php if ($theme && $theme->hasConfig): ?>
                <a href="<?php echo html_escape(uri("exhibits/theme-config/$exhibit->id")); ?>" class="configure-button button" style="vertical-align: baseline; float:none;">
                <?php echo __('Configure'); ?>
                </a>
                <?php endif;?>
                </div>
            </div>
            
             
            <?php  $user = current_user();
            if ($actionName=='Add'){?>
            <div class="field">
                <label for="group-selection"><?php echo __('Select a Group'); ?></label>                              
                <?php //$values = array('' => __('Select group')) + get_user_groups(); ?>
                <div class="select"><?php //echo __v()->formSelect('group', array('id'=>'group'), $values); ?>
               <?php if (($user->role=='super') || ($user->role=='admin'))
               //$groupValue =  get_theme_option('exhibitgroup');
				       echo select(array('name'=>'group-selection','id'=>'group-selection'),get_groups_names());
				    else
				       echo select(array('name'=>'group-selection','id'=>'group-selection'),get_groups_names_belongto_user($user->entity_id,$user->role)); ?>
                </div>
            </div>  
                    <?php }?>  
            <?php
          if($actionName=='Edit'){
            if (($user->role=='super') || ($user->role=='admin')){?>
               <div class="field">
                <label for="group-selection"><?php echo __('Select a Group'); ?></label>                              
                <?php //$values = array('' => __('Select group')) + get_user_groups(); ?>
                <div class="select"><?php //echo __v()->formSelect('group', array('id'=>'group'), $values); ?>
               <?php //$groupValue =  get_theme_option('exhibitgroup');
                $groupValue =  get_groups_ids_attached_to_exhibits($exhibit->id);
     			  echo select(array('name'=>'group-selection','id'=>'group-selection'),get_groups_names(),$groupValue); ?>
                </div>
            </div> 
            <?php } else {?>
            <div class="field">
                <label for="group"><?php echo __('Groups'); ?></label>
                    <?php //$group_id = get_theme_option('exhibitgroup');
             		    $group_id = get_groups_ids_attached_to_exhibits($exhibit->id);
                    	//echo (get_group_by_group_id($group_id));//echo text(array('name'=>'group', 'id'=>'group', 'class'=>'textinput'), get_theme_option('exhibitgroup')); ?>
                <?php //$values = array('' => __('Select group')) + get_user_groups(); ?>
                <div class="select"><?php //echo __v()->formSelect('group', array('id'=>'group'), $values); ?>
               		<?php //echo (get_group_name_by_group_id($group_id)); 
               		 	echo select(array('name'=>'group-selection','id'=>'group-selection'),get_groups_names_belongto_user($user->entity_id,$user->role),$group_id); 
           		  ?>
                </div>
            </div>
            <?php }
            }?>
        
        </fieldset>
        
        
        <fieldset>
            <legend><?php echo __('Sections and Pages'); ?></legend>
            <div id="section-list-container">
                <?php if (!$exhibit->Sections): ?>
                    <p><?php echo __('There are no sections.'); ?></p>
                <?php else: ?>
                <p id="reorder-instructions"><?php echo __('To reorder sections or pages, click and drag the section or page up or down to the preferred location.'); ?></p>
                <?php endif; ?>
                <ul class="section-list">
                    <?php common('section-list', compact('exhibit'), 'exhibits'); ?>
                </ul>
            </div>
            <div id="section-add" style="float:left">
                  <input type="submit" name="add_section" id="add-section" value="<?php echo __('Add Section'); ?>" />
            </div>
            <p id="exhibit-builder-save-changes" style="float:right; margin-top:0;">
                <input type="submit" name="save_exhibit" id="save_exhibit" value="<?php echo __('Save Changes'); ?>" /> <?php echo __('or'); ?> 
                <a href="<?php echo html_escape(uri('exhibits')); ?>" class="cancel"><?php echo __('Cancel'); ?></a>
            </p>
          
        </fieldset>
        
    </form>     
</div>
<?php foot(); ?>
