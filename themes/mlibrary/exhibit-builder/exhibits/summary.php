<?php echo head(array('title' => html_escape('Summary of ' . metadata('exhibit','title')),'bodyid'=>'exhibit','bodyclass'=>'summary')); ?>
<div id="primary">
 <?php echo mlibrary_header_banner(); ?>
  <?php echo queue_js_file('JwPlayer/jwplayer');?>
  

<?php //if (mlibrary_exhibit_gallery()!='gallery'){?>
	<div class="exhibit-overview active">
		<?php echo link_to_exhibit('Introduction'); ?>
	</div>	
	<?php //echo exhibit_builder_nested_nav(null,mlibrary_pages_in_section());?>	

    <!--<ul class="exhibit-section-nav">-->
       <ul id="exhibit-pages">
       
        <?php //set_exhibit_pages_for_loop_by_parent_page();
        set_exhibit_pages_for_loop_by_exhibit(); 
        $i=0;?>
        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
        <?php    //echo exhibit_builder_page_trail($exhibitPage); 
        echo exhibit_builder_page_summary($exhibitPage);
             // $html = html_escape($exhibitPage->title);
             // $html .=$i++.'<br>';
             // $html.= exhibit_builder_child_page_nav($exhibitPage).'<br>';
             // echo $html;
       ?>
        <?php endforeach; ?>
       
        </ul>
        
<!--    </ul>-->

<?php // }else{?>
	<!--<div class="exhibit-overview active">
		<?php// echo link_to_exhibit('Introduction'); ?>
	</div>	-->
	<?php //echo exhibit_builder_nested_nav(null,mlibrary_pages_in_section()); 

//	}?>
	
<div id="summary-view">
<?php //echo '<img src=https://dev.www.lib.umich.edu/exhibits/archive/theme_uploads/'.deco_exhibit_image().'/>'; ?>
<?php //if (mlibrary_exhibit_gallery()!='gallery'){?>
<div id="sharethis">
<span>Share this Exhibit!</span>
<div class="g-plusone" data-size="medium"></div>
<div class="fb-like" data-send="false" data-layout="button_count" data-show-faces="false" data-font="arial"></div>
<div class="twitter-share"><a href="https://twitter.com/share" class="twitter-share-button" data-text="I just saw '<?php echo metadata('exhibit','title',array('no_escape' => true)); ?>' at the MLibary Online Exhibits!">Tweet</a></div>
</div>

<div class="exhibit_image">
<?php   
			$exhibitimage = array(); 
			 $exhibit_record = get_current_record('exhibit', false); 
			$theme_options_array = $exhibit_record->getThemeOptions();
			
			$theme_options_array['exhibitimage'] = get_image_attached_to_exhibits($exhibit_record['id']); 
				$Exhibit_image = $theme_options_array['exhibitimage'];  
				
//			$exhibit_record_theme = exhibit_builder_theme_options($exhibit_record);			
		if ($Exhibit_image)         
    	echo '<img src="'.WEB_FILES.$Exhibit_image['image_name'].'" alt="'.$Exhibit_image['image_title'].'" />';
		else
			echo('<div><img src="'.img("mlibrary_galleryDefault.jpg").'" alt="Mlibrary default image"/></div>');  ?>
			

<?php  
	
	// if video, show video, else show exhibit image and possible sound
	
	/*if (mlibrary_exhibit_video()){
		$Exhibit_video = mlibrary_exhibit_video();                  
		echo('<iframe src="http://www.youtube.com/embed/'.$Exhibit_video.'?theme=light" frameborder="0" width="100%" height="300px"></iframe>');
	} else {
		//$Exhibit_image = mlibrary_exhibit_image();   
		$theme_options_array = get_current_exhibit()->getThemeOptions();  
		$theme_options_array['exhibitimage'] = get_image_attached_to_exhibits(get_current_exhibit()->id);      
			$Exhibit_image = $theme_options_array['exhibitimage'];     
		if ($Exhibit_image)         
			//echo('<img src="'.CURRENT_BASE_URL.'/archive/theme_uploads/'.$Exhibit_image.'"/>'); 
    		   		echo '<img src="'.WEB_ARCHIVE.$Exhibit_image['image_name'].'" alt="'.$Exhibit_image['image_title'].'" />';
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
	
	}*/
?>               
   
</div>

<div id="summary-sidebar">
<!-- <h2 class="desc">Description</h2> -->
<?php echo metadata('exhibit','description',array('no_escape' => true)); ?>
<h2 class="credits"><span>Curated by</span> <?php echo html_escape(metadata('exhibit','credits')); ?></h2> 
</div>
<?php //}?>
 </div>
 
 
</div>
<?php echo foot(); ?>