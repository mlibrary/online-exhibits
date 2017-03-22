<?php head(array('title'=>html_escape('Browse Exhibits'),'bodyid'=>'exhibit','bodyclass'=>'browse')); ?>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{

  	jQuery("#showcase").awShowcase(
	{
		width:					460,
		height:					370,
		auto:					true,
		interval:				6500,
		continuous:				true,
		loading:				true,
		tooltip_width:			200,
		tooltip_icon_width:		32,
		tooltip_icon_height:	32,
		tooltip_offsetx:		18,
		tooltip_offsety:		0,
		arrows:					false, 
		buttons:				true,
		btn_numbers:			false,
		keybord_keys:			true,
		mousetrace:				false,
		pauseonover:			true,
		transition:				'fade', /* vslide/hslide/fade */
		transition_speed:		500,
		show_caption:			'onload', /* onload/onhover/show */
		thumbnails:				false,
		thumbnails_position:	'outside-last', /* outside-last/outside-first/inside-last/inside-first */
		thumbnails_direction:	'horizontal', /* vertical/horizontal */
		thumbnails_slidex:		0 /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */
	});
  
	jQuery('#primary .pagination a').click(function(){
		var ahref = jQuery(this).attr('href').split('/');
		var page = ahref[ahref.length-1]-1;
		jQuery('#exhibits').load(location.href+' #exhibits>*',{expage: page});
		return false;
	});
});
</script>

<?php $awkward_gallery_setting=get_theme_option('Featured Image Gallery') ? get_theme_option('Featured Image Gallery') : 'yes'; 
//if ($awkward_gallery_setting=='yes') {?>
<?php //}
		//else
		//{?>
     <h1>Browse Exhibits </h1>   
<div id="primary" class="browse">
    
	<?php if (count($exhibits) > 0): ?>
    
	<ul class="navigation" id="secondary-nav">
	    <?php echo nav(array('Browse All' => uri('exhibits'), 'Browse by Tag' => uri('exhibits/tags'))); ?>
    </ul>	
	
	
    <div id="exhibits">	
    <?php 
	$exhibitCount = 0;
	$exhibitPage =  $_POST['expage'] ? $_POST['expage'] : 0;
	$perPage = 6;
	$start = $perPage * $exhibitPage + 1;
	?>
    <?php while(loop_exhibits()): ?>
    	<?php $exhibitCount++; ?>
        <?php if($exhibitCount >= $start && $exhibitCount < $start+$perPage): ?>
    	<div class="exhibit <?php if ($exhibitCount==$start) echo 'first';  ?>">
    		<h2><?php echo link_to_exhibit(); ?></h2> 
    		<p class="tags"><?php echo tag_string(get_current_exhibit(), uri('exhibits/browse/tag/')); ?></p>
    	</div>
        <?php endif; ?>
    <?php endwhile; ?>
   
    
    <div class="pagination"><?php echo pagination_links($options=array('per_page'=>6,'page'=>$exhibitPage+1)); ?></div>
 </div>
    <?php else: ?>
	<p>There are no exhibits available yet.</p>
	<?php endif; ?>
</div>





<div id="secondary" class="browse">
		<?php //deco_awkward_gallery();?>
     <div id="showcase" class="showcase">
     <?php $featured_exhibits = exhibit_builder_get_exhibits(array('featured' => true)); 
		foreach($featured_exhibits as $featured_exhibit){			
        	$theme_options = $featured_exhibit->getThemeOptions();
         
				if ($theme_options['exhibit_image'])          
						echo('<div> <img src="'.CURRENT_BASE_URL.'/archive/theme_uploads/'.$theme_options['exhibit_image'].'"'.' style="width:525px; height:440px;"/>'); 
					else
						echo('<div> <img src="'.CURRENT_BASE_URL.'/themes/deco-edit/images/mlibrary_galleryDefault.jpg'.'"'.' style="width:525px; height:440px;"/>');
       
				echo '<div class="showcase-caption">';
        echo '<h3>'.exhibit_builder_link_to_exhibit($featured_exhibit).'</h3>';
           // echo '<div class="description">'. $featured_exhibit->description.'</div>';
        echo '<p>'.snippet($featured_exhibit->description, 0, 200).'</p>';
        echo '</div></div>';			        
			}
		  ?>				

</div>
<div id="featured-ribbon"></div>
</div>

        
<?php// }?>
<?php foot(); ?>