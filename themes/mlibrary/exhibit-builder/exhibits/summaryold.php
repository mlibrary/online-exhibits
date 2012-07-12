<?php head(array('title' => html_escape(' of ' . exhibit('title')),'bodyid'=>'exhibit','bodyclass'=>'summary')); ?>
<div id="primary">
 <?php echo mlibrary_header_banner(); ?>
  <?php echo js('JwPlayer/jwplayer');?>
  
<?php //echo html_escape(exhibit('title')); ?>

<?php echo exhibit_builder_nested_nav(null,mlibrary_pages_in_section()); ?>


<?php //echo '<img src=https://dev.www.lib.umich.edu/exhibits/archive/theme_uploads/'.deco_exhibit_image().'/>'; ?>
<div class="exhibit_image">

<?php  
	
	// if video, show video, else show exhibit image and possible sound
	
	if (mlibrary_exhibit_video()){
		$Exhibit_video = mlibrary_exhibit_video();                  
		echo('<iframe src="http://www.youtube.com/embed/'.$Exhibit_video.'?theme=light" frameborder="0" width="200"></iframe>');
	} else {
		$Exhibit_image = mlibrary_exhibit_image();                
		if ($Exhibit_image)         
			echo('<img src="'.CURRENT_BASE_URL.'/archive/theme_uploads/'.$Exhibit_image.'"/>'); 
		else
			echo('<div><img src="'.img("mlibrary_galleryDefault.jpg").'"/></div>');  
	}
	if (mlibrary_exhibit_audio()){ 
		$Exhibit_audio = mlibrary_exhibit_audio();
		//echo(CURRENT_BASE_URL.'/archive/theme_uploads/'.$Exhibit_audio); 
		$file = CURRENT_BASE_URL.'/archive/theme_uploads/'.$Exhibit_audio;
		// echo $file;
		$htmlscript= '<div id="mediaspace_0">This text will be replaced</div>
		<script type="text/javascript">
		jwplayer("mediaspace_0").setup({
		"flashplayer": "/exhibits/themes/deco-edit/javascripts/JwPlayer/jplayer/player.swf",
		"skin": "/exhibits/themes/deco-edit/javascripts/mlibrary/mlibrary.zip",
		"dock": "false",
		"backcolor": "FFFFFF",
		"frontcolor": "FFFFFF",
		"lightcolor": "FFFFFF",
		"screencolor": "FFFFFF",
		"file": "'.$file.'",
		"controlbar": "bottom",
		"width": "200",
		"height": "23"
		
		});
		</script>';
		
		echo '<div>'.$htmlscript.'</div>';        
	
	}
?>               
<h2 class="credits"><span>Curated by</span> <?php echo html_escape(exhibit('credits')); ?></h2>    
</div>
<div id="summary-sidebar">
<!-- <h2 class="desc">Description</h2> -->
<?php echo exhibit('description'); ?>




<div id="exhibit-sections">	
	<?php set_exhibit_sections_for_loop_by_exhibit(get_current_exhibit()); ?>
	<!-- <h2>Sections</h2> -->
	<?php while(loop_exhibit_sections()): ?>
	<?php if (exhibit_builder_section_has_pages()): ?>
    <h3><a href="<?php echo exhibit_builder_exhibit_uri(get_current_exhibit(), get_current_exhibit_section()); ?>"><?php echo html_escape(exhibit_section('title')); ?></a></h3>
	<?php echo exhibit_section('description'); ?>
	<?php endif; ?>
	<?php endwhile; ?>
</div>
</div>
 
</div>
<?php foot(); ?>