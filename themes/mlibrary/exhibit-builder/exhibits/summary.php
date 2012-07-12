<?php head(array('title' => html_escape('Summary of ' . exhibit('title')),'bodyid'=>'exhibit','bodyclass'=>'summary')); ?>
<div id="primary">
 <?php echo mlibrary_header_banner(); ?>
  <?php echo js('JwPlayer/jwplayer');?>
  

<?php if (mlibrary_exhibit_gallery()!='gallery'){?>
	<div class="exhibit-overview active">
		<?php echo link_to_exhibit('Introduction'); ?>
	</div>	
	<?php echo exhibit_builder_nested_nav(null,mlibrary_pages_in_section());

}else{?>
	<div class="exhibit-overview active">
		<?php echo link_to_exhibit('Introduction'); ?>
	</div>	
	<?php echo exhibit_builder_nested_nav(null,mlibrary_pages_in_section()); 
	}?>
	
	<?php //print_r(abs_uri());
	//(exhibit_builder_exhibit_uri(get_current_exhibit()));
	?>
	

<div id="summary-view">

<?php //echo '<img src=https://dev.www.lib.umich.edu/exhibits/archive/theme_uploads/'.deco_exhibit_image().'/>'; ?>
<?php if (mlibrary_exhibit_gallery()!='gallery'){?>
<div id="sharethis">
<span>Share this Exhibit!</span>
<div class="g-plusone" data-size="medium"></div>
<div class="fb-like" data-send="false" data-layout="button_count" data-show-faces="false" data-font="arial"></div>
<div class="twitter-share"><a href="https://twitter.com/share" class="twitter-share-button" data-text="I just saw '<?php echo exhibit('title'); ?>' at the MLibary Online Exhibits!">Tweet</a></div>
</div>
<div class="exhibit_image">

<?php  
	
	// if video, show video, else show exhibit image and possible sound
	
	if (mlibrary_exhibit_video()){
		$Exhibit_video = mlibrary_exhibit_video();                  
		echo('<iframe src="http://www.youtube.com/embed/'.$Exhibit_video.'?theme=light" frameborder="0" width="100%" height="300px"></iframe>');
	} else {
		//$Exhibit_image = mlibrary_exhibit_image();   
		$theme_options_array = get_current_exhibit()->getThemeOptions();        
			$Exhibit_image = $theme_options_array['exhibitimage'];     
		if ($Exhibit_image)         
			//echo('<img src="'.CURRENT_BASE_URL.'/archive/theme_uploads/'.$Exhibit_image.'"/>'); 
    		   		echo '<img src="'.WEB_ARCHIVE.$Exhibit_image['image'].'" alt="'.$Exhibit_image['title'].'" />';
		else
			echo('<div><img src="'.img("mlibrary_galleryDefault.jpg").'" alt="Mlibrary default image"/></div>');  
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
   
</div>

<div id="summary-sidebar">
<!-- <h2 class="desc">Description</h2> -->
<?php echo exhibit('description'); ?>
<h2 class="credits"><span>Curated by</span> <?php echo html_escape(exhibit('credits')); ?></h2> 
</div>
<?php }?>

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