<?php head(array('bodyid'=>'home')); ?>    
            <!--About-->       
	<!-- Featured Item -->

<div id="primary">
	<div id="greeting" >
    	<h1>Online Exhibits</h1>
    	<h2>collected. curated. <span>celebrated.</span></h2>
	    <p>explore and discover inspiring collections of <strong>art</strong>, <strong>literature</strong>, <strong>culture</strong>, and <strong>history</strong>! Brought to you by the <a href="http://www.lib.umich.edu">University of Michigan Library</a>.</p>
    	<ul>

    		<li class="browse-icon">
    		<a href="<?php echo uri('exhibits'); ?>"><img src="<?php echo img('icon-browse.gif','images/layout') ?>" alt="browse icon" width="20" heigh="20"/></a>
    		<a href="<?php echo uri('exhibits'); ?>">browse</a> all the exhibits to find what inspires you! 
            </li>
    		
		    <li class="galleries-icon">
		    <a href="<?php echo uri('exhibits/show/galleries'); ?>"><img src="<?php echo img('icon-galleries.gif','images/layout') ?>" alt="galleries icon" width="20" heigh="20"/></a>    		
		    prefer all the images in one place? check out the <a href="<?php echo uri('exhibits/show/galleries'); ?>">galleries</a>! 
            </li>
		    
    		<li class="items-icon">
    		<a href="<?php echo uri('items'); ?>"><img src="<?php echo img('icon-archive.gif','images/layout') ?>" alt="archive icon" width="20" heigh="20"/></a>
    		flip through the <a href="<?php echo uri('items'); ?>">archive</a> to see the full listing of items! 
            </li>
        
       <!-- <li class="timeline-icon">
    		<a href="<?php echo uri('neatline-time/timelines'); ?>"><img src="<?php echo img('icon-archive.gif','images/layout') ?>" alt="archive icon" width="20" heigh="20"/></a>
    		flip through the <a href="<?php echo uri('neatline-time/timelines'); ?>">Time Line</a> to see the full listing of time lines! 
            </li>-->
    
    	</ul>
	</div><!-- end greeting -->	
    
<!-- Featured Exhibits -->
<h3 id="featured-title"><span>Featured</span> Exhibits</h3>
<div id="showcase" class="showcase">
<?php $featured_exhibits = exhibit_builder_get_exhibits(array('featured' => true)); 
$exhibitNum = 0;
foreach($featured_exhibits as $featured_exhibit){
	
	// skip exhibit if by some chance is a gallery
	$themeOptions = unserialize($featured_exhibit->theme_options);
//	$Exhibit_image = $themeOptions[mlibrary][exhibitimage];
	$Exhibit_image = get_image_attached_to_exhibits($featured_exhibit->id);	
	if($themeOptions[mlibrary][pages_in_section] == 'pages_3') { continue; };
	
	$exhibitNum++;
    if($exhibitNum > 4){ break; }; 

?>
	  
    <div class="showcase-slide">	
		<?php //$Exhibit_image =  mlibrary_exhibit_image($featured_exhibit);
    	echo '<a href="'.exhibit_builder_exhibit_uri($featured_exhibit).'"><img src="'.WEB_ARCHIVE.$Exhibit_image['image_name'].'" alt="'.$Exhibit_image['image_title'].'" /></a>';

   echo '<div class="showcase-caption">';
		echo '<h4>'.exhibit_builder_link_to_exhibit($featured_exhibit).'</h4>';
	   // echo '<div class="description">'. $featured_exhibit->description.'</div>';
	echo '</div>';?>
	
	<div class="showcase-thumbnail active" style="width:100px;">
	 	<?php //$Exhibit_image =  mlibrary_exhibit_image($featured_exhibit);
    	echo '<img  src="'.WEB_ARCHIVE.$Exhibit_image['image_name'].'" alt="'.$Exhibit_image['image_title'].'"  width="44" height="44"/>';?>
    </div>    			    	
	
	<?php echo '</div>';			        
}?>			
</div><!-- end featured -->


</div><!-- primary-->
<h3> Recent Exhibits </h3>
<div id="recent-exhibits"> 

<!--<div class="button"><a href="<?php echo uri('browse'); ?>">Browse Exhibits</a></div>-->
<div class="exhibits">	
 <?php 
	$exhibitCount = 0;	
	//$exhibitPage =  $_POST['expage'] ? $_POST['expage'] : 0;
	//$perPage = 6;
	//$start = $perPage * $exhibitPage + 1;	
	$first_exhibit='false';
	?>
	
    <?php 
   //$end = total_exhibits();
   //$recent_exhibits = exhibit_builder_recent_exhibits();
   //foreach($recent_exhibits as $exhibit) {
   // echo $exhibit->title;
//}
   // set_exhibits_for_loop(exhibit_builder_get_exhibits(array('recent'=>true)));
    set_exhibits_for_loop(exhibit_builder_recent_exhibits());
    while(loop_exhibits()): ?>
    	<?php 
    	$item_found=false;
		if($exhibitCount > 4){ break; };    
    	$currentexhibit = get_current_exhibit();
    	
      ?>
        <?php //if($exhibitCount >= 1 && $exhibitCount <= $end): 
          $theme_options_array = $currentexhibit->getThemeOptions();
        	if ($theme_options_array['view_items_in_gallery']!='pages_2'){ 
        	$exhibitCount++;   ?>
	 	    	<div class="exhibit <?php if ($first_exhibit=='false') echo 'first';  ?>">
	 	    	<?php $first_exhibit='true';?>
	    			<h4><?php echo link_to_exhibit(); ?></h4> 
	    		   <?php //$Exhibit_image =  mlibrary_exhibit_image($currentexhibit);
	    		   $Exhibit_image = get_image_attached_to_exhibits($currentexhibit->id);
	    		   if (!empty($Exhibit_image))
	    		  // $Exhibit_image = $theme_options_array['exhibitimage'];
    			   		echo '<img src="'.WEB_ARCHIVE.$Exhibit_image['image_name'].'" alt="'.$Exhibit_image['image_title'].'" />';
    		   		else
    		   			echo('<img src="'.img("mlibrary_galleryDefault.jpg").'" alt="Mlibrary default image"/>');  
    		   		
					echo '<p class="description">'.snippet($currentexhibit->description, 0, 300).'</p>';
		    		echo '<p class="tags">'.tag_string($currentexhibit, uri('exhibits/browse?tags=')).'</p>';?>
    		    </div>
    		<?php }
       // endif;         
     endwhile; 
    // print_r($exhibitCount);?>
</div>
<div class="button-wrap"><div class="button"><a href="<?php echo uri('exhibits'); ?>">Browse All Exhibits</a></div></div>
</div><!-- end recent -->


<!--<div id="legacy-exhibits">
<h2> Looking for an older exhibit?</h2>
<p> The University of Michigan Library frequently puts on exhibits based on its rich and unique collections. Some had even been online before this website was site was around! Check them out below!</p>
<ul>
<li><a href="http://www.lib.umich.edu/exhibits/diversity-desert/">Diversity in the Desert</a></li>
<li><a href="http://www.lib.umich.edu/exhibits/divine-sky-artistry-astronomical-maps/">Divine Sky: The Artistry of Astronomical Maps</a></li>
<li><a href="http://www.lib.umich.edu/exhibits/enchanting-ruin-tintern-abbey-romantic-tourism-wales/">Enchanting Ruin: Tintern Abbey and Romantic Tourism in Wales</a></li>
<li><a href="http://www.lib.umich.edu/exhibits/special-collections-library/galileo-manuscript">The Galileo Manuscript</a></li>
<li><a href="http://www.lib.umich.edu/exhibits/netherlandic-treasures/">Netherlandic Treasures</a></li>
<li><a href="http://www.lib.umich.edu/exhibits/st-petersburg/">St. Petersburg: Window on the West - Window on the East</a></li>
<li><a href="http://www.lib.umich.edu/exhibits/writing-graeco-roman-egypt/">Writing in Graeco-Roman Egypt</a></li>
</ul>
</div>-->
    
 <!-- Start Awkward Gallery load/config -->
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{
	jQuery("#showcase").awShowcase(
	{
		width:					475,
		height:					393,
		auto:					false,
		interval:				6500,
		continuous:				false,
		loading:				true,
		tooltip_width:			200,
		tooltip_icon_width:		32,
		tooltip_icon_height:	32,
		tooltip_offsetx:		18,
		tooltip_offsety:		0,
		arrows:					true, 
		buttons:				false,
		btn_numbers:			true,
		keybord_keys:			true,
		mousetrace:				false,
		pauseonover:			true,
		transition:				'fade', /* vslide/hslide/fade */
		transition_speed:		250,
		show_caption:			'onload', /* onload/onhover/show */
		thumbnails:				true,
		thumbnails_position:	'inside-last', /* outside-last/outside-first/inside-last/inside-first */
		thumbnails_direction:	'horizontal', /* vertical/horizontal */
		thumbnails_slidex:		0 /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */
	});
});
</script>
<!-- end Awkward Gallery load/config -->
   
    
<?php foot(); ?>