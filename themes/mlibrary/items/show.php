<?php $item_title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
  if ($item_title == '') {
    $item_title = __('[Untitled]');
  }
?>
<?php echo head(array('title'=> $item_title, 'bodyid'=>'items', 'bodyclass' => 'show item')); ?>
<h1><?php echo $item_title; ?></h1>
<?php queue_js_file('mdcollapse');?>	
<?php //queue_js_file('JwPlayer/jwplayer');?>
<?php queue_js_file('fancybox/source/fancybox-init-config');?>
<?php echo head_js(); ?>
<!--<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{
	jQuery("#showcase").awShowcase(
	{
		width:					650,
		height:					450,
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
		btn_numbers:			true,
		keybord_keys:			true,
		mousetrace:				false,
		pauseonover:			true,
    transition:				'vslide', /* vslide/hslide/fade */
		transition_speed:		0,
		show_caption:			'onload', /* onload/onhover/show */
		thumbnails:				false,
		thumbnails_position:	'outside-last', /* outside-last/outside-first/inside-last/inside-first */
		thumbnails_direction:	'horizontal', /* vertical/horizontal */
		thumbnails_slidex:		0 /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */
	});
});
</script>-->
<div id="primary">	
<!-- display files -->	
  <?php if(!empty($item->getItemType()->name)){
				  $item_type = $item->getItemType()->name;
				}
        else
          $item_type ='Image';          
			  $html_thumnailsize_image = "";
			  $html_fullsize_image = "";
        if (!isset($exhibit->theme))
		        $theme_name= 'mlibrary';
        else
	        	$theme_name= $exhibit->theme;
	      $image_index=0;
    	  $audio = array('application/ogg','audio/aac','audio/aiff','audio/midi','audio/mp3','audio/mp4','audio/mpeg','audio/mpeg3','audio/mpegaudio','audio/mpg','audio/ogg','audio/x-mp3','audio/x-mp4','audio/x-mpeg','audio/x-mpeg3','audio/x-midi','audio/x-mpegaudio','audio/x-mpg','audio/x-ogg','application/octet-stream');
    		    //Three types of doc, image, sound or video
		    set_loop_records('files', get_current_record('item')->Files);
    		if ($item_type!='Video') {	
				      // Either image or sound  
				      //start the loop of item files
			  $fullsizeimage=false;
      	$audio_file=false;      
			  foreach(loop('files') as $file):          
      		$mime = $file['mime_type'];
		      $image_index++;
    		  if (in_array($mime,$audio))
        		  $audio_file=true;    
        	$extension = pathinfo($file->filename, PATHINFO_EXTENSION);
    		  $filename = basename($file->filename,'.'.$extension);	       							  
          if($file->hasThumbnail()) {  
          	  if ($fullsizeimage==false){	 
                	  $file_metadata = '<div class="file-metadata img'.$image_index.'">'.strip_formatting(metadata('file',array('Dublin Core', 'Title')))."</div>";                         	  
    		            $html_thumnailsize_image = file_markup($file, array('imageSize'=>'square_thumbnail','imgAttributes'=>array('alt'=>strip_formatting(metadata('item', array('Dublin Core', 'Title'))).' '.'image'.' '.$image_index),'linkToFile'=>false),array('class' => 'square_thumbnail img'.$image_index ));    		           
    		           	 if(file_exists('files/zoom_tiles/'.$filename.'_zdata')){     		  
										      $html_fullsize_image= '<div class="zoom img'.$image_index.' swf-zoom"><OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="750" HEIGHT="450" ID="theMovie">
										      <PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.url('').'files/zoom_tiles/'.$filename.'_zdata&zoomifyX=0.0&zoomifyY=0.0&zoomifyZoom=-1&zoomifyToolbar=1&zoomifyNavWindow=0">
										       <PARAM NAME="MENU" VALUE="FALSE">
										       <PARAM NAME="SRC" VALUE="'.url('').'themes/'.$theme_name.'/javascripts/ZoomifyViewer.swf">
									         	<param NAME=wmode VALUE=opaque> 
								         		<EMBED FlashVars="zoomifyImagePath='.url('').'files/zoom_tiles/'.$filename.'_zdata&zoomifyX=0.0&zoomifyY=0.0&zoomifyZoom=-1&zoomifyToolbar=1&zoomifyNavWindow=0" SRC="'.url('').'themes/'.$theme_name.
										       '/javascripts/ZoomifyViewer.swf" wmode=opaque MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="550" HEIGHT="450" NAME="theMovie"></EMBED></OBJECT></div>';
			                  }else{
          		                $html_fullsize_image = file_markup($file, array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>strip_formatting(metadata('item',array('Dublin Core', 'Title')))),'linkAttributes'=>array('rel'=>'group-fancy-image','class'=>'fancyitem','title'=>strip_formatting(metadata('item',array('Dublin Core', 'Title'))))),array('class' => 'fullsize img'.$image_index, 'id' => 'item-image'));
          		          }
      		          $fullsizeimage=true;
      		          $json_fullsize['img'.$image_index] = $html_fullsize_image;
          		}
          		elseif ($fullsizeimage==true){ 
          			  $file_metadata .= '<div class="file-metadata img'.$image_index.'" style="display:none">'.strip_formatting(metadata('file',array('Dublin Core', 'Title')))."</div>";   
				      		$html_thumnailsize_image .= file_markup($file,array('imageSize'=>'square_thumbnail','imgAttributes'=>array('alt'=>strip_formatting(metadata('item', array('Dublin Core', 'Title'))).' '.'image'.' '.$image_index),'linkToFile'=>false),array('class' => 'square_thumbnail img'.$image_index ));          		  	 
						 			if(file_exists('files/zoom_tiles/'.$filename.'_zdata')){   
        							$json_fullsize['img'.$image_index] = '<div class="zoom img'.$image_index.' swf-zoom"><OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="750" HEIGHT="450" ID="theMovie">
											<PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.url('').'files/zoom_tiles/'.$filename.'_zdata&zoomifyToolbar=1&zoomifyNavWindow=0">
											<PARAM NAME="MENU" VALUE="FALSE">
											<PARAM NAME="SRC" VALUE="'.url('').'themes/'.$theme_name.'/javascripts/ZoomifyViewer.swf">
											<param NAME=wmode VALUE=opaque> 
											<EMBED FlashVars="zoomifyImagePath='.url('').'files/zoom_tiles/'.$filename.'_zdata&zoomifyToolbar=1&zoomifyNavWindow=0" SRC="'.url('').'themes/'.$theme_name.
											'/javascripts/ZoomifyViewer.swf" wmode=opaque MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="550" HEIGHT="450" NAME="theMovie"></EMBED></OBJECT></div>';
			      			}else {          		  		  
          		    		$html_fullsize_image .= file_markup($file, array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>strip_formatting(metadata('item',array('Dublin Core', 'Title')))),'linkAttributes'=>array('rel'=>'group-fancy-image','class'=>'fancyitem','title'=>strip_formatting(metadata('item',array('Dublin Core', 'Title'))))),array('style' => 'display:none','class' => 'fullsize img'.$image_index, 'id' => 'item-image'));  	        		            		    		
          		    		 $json_fullsize['img'.$image_index] = file_markup($file, array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>strip_formatting(metadata('item',array('Dublin Core', 'Title')))),'linkAttributes'=>array('rel'=>'group-fancy-image','class'=>'fancyitem','title'=>strip_formatting(metadata('item',array('Dublin Core', 'Title'))))),array('class' => 'fullsize img'.$image_index, 'id' => 'item-image'));
          		   }            		     
          		
             }	
          }		                          
		    endforeach;
		    if (!empty($json_fullsize)){
		    	echo '<script type="text/javascript"> var imagesJSON ='.json_encode($json_fullsize).'</script>';	
			    echo'<div id="item-images">';	
			    echo '<div id="fsize_images">'.$html_fullsize_image.'</div>';			 
			    echo $file_metadata;
		  	  echo $html_thumnailsize_image;    	      
		  	}
    		if (($fullsizeimage!=true) and (($audio_file==true) || ($item_type=='Sound')))
        // if first file is an audio file then display a default image for sound file.
             echo '<img src="'.img('audio_default02.gif').'" alt="Oops" />';     
        echo '</div>';  ?><!--item-images -->
        <?php	} elseif ($item_type=='Video') {
	      // videos is displayed by using embeded tag from youtube	             
       	echo'<div id="item-video">';
        $elementvideos = metadata('item', array('Item Type Metadata', 'Video_embeded_code'), array('no_escape'=>true,'all'=>true));    
        $elementtitles = metadata('item',array('Item Type Metadata', 'video_title'), array('no_escape'=>true,'all'=>true));
        //elementvideos_VCM (Kaltura videos)
        $elementvideos_VCM = metadata('item',array('Item Type Metadata', 'video_embeded_code_VCM'), array('no_escape'=>true, 'all'=>true));      
       if (!empty($elementvideos_VCM)) { ?>
              <div id="showcase" class="showcase">
              <?php $i=0;
                foreach($elementvideos_VCM as $elementvideo_VCM ) {          
                //   $elementvideo_VCM = str_replace( $remove, "", $elementvideo_VCM );            
                   echo '<div>';            
                   echo $elementvideo_VCM; ?>
                   <div class="showcase-caption">
                       <?php echo'<h3>'.$elementtitles[$i].'</h3>';?>
                   </div>             
                   <?php echo '</div>'; 
                    $i++;
                }?> <!-- foreach loop-->
              </div> <!-- showcase -->
       <?php } 
       elseif (!empty($elementvideos)) { ?>
              <div id="showcase" class="showcase">
              <?php $i=0;
                foreach($elementvideos as $elementvideo ) {        
            //      $elementvideo = str_replace( $remove, "", $elementvideo );            
                  echo '<div>';            
                  echo '<iframe src="http://www.youtube.com/embed/'.$elementvideo.'" frameborder="0" width="650" height="400"></iframe>';  ?>
                  <div class="showcase-caption">
                    <?php echo'<h3>'.$elementtitles[$i].'</h3>';?>
                  </div>             
                  <?php echo '</div>'; 
                   $i++;
                }?> <!-- foreach loop-->
       </div> <!-- showcase -->
       <?php } ?>      
       </div> <!--item-video -->
<?php }
    
/*$index=0;
	$aoutput='';    
  foreach(loop('files') as $file):  
	//variables used to check mime types for VideoJS compatibility, etc.
    $mime = $file['mime_type'];    
    if (array_search($mime,$audio) !== false){ //echo display_file($file, array('width' => 200, 'height' => 20));//,'icons' => array('audio/mpeg'=>img('sound-icon.jpg'))));		      
  		//print_r($theme_name);
      //$this->fileMetadata($file, 'Dublin Core', 'Title');      
      $sound_title = metadata('file',array('Dublin Core', 'Title'));
      $uri = metadata('file','uri');      
			$aoutput .= '<li class="audio-test">';    
			$htmlscript = '<div id="mediaspace_'.$index.'">This text will be replaced</div>
			<script type="text/javascript">
			jwplayer("mediaspace_'.$index.'").setup({
			"flashplayer": "/exhibits/themes/'.$theme_name.'/javascripts/JwPlayer/jplayer/player.swf",
      "skin": "/exhibits/themes/'.$theme_name.'/javascripts/mlibrary/mlibrary.zip",
    "dock": "false",
    "backcolor": "FFFFFF",
    "frontcolor": "FFFFFF",
    "lightcolor": "FFFFFF",
    "screencolor": "FFFFFF",
			"file": "'.$uri.'",
			"controlbar": "bottom",
			"width": "232",
			"height": "23"			
			});
			</script>';
			$aoutput.= $htmlscript;
      $aoutput .=$sound_title;
			$aoutput.= '</li>';
		}    
	 $index++;
  endforeach;
	if(strlen($aoutput) > 0){
		echo '<div id="item-audio" class="test"><ul>';
		echo $aoutput;
		echo '</ul></div>';
	}*/?>
		
    <div id="sidebar">	
	    <?php $elementInfos = array(      
                          array('Dublin Core', 'Creator'),
                          array('Dublin Core', 'Date'),
                          array('Dublin Core', 'Identifier'),
        );
   foreach($elementInfos as $elementInfo) {
               $elementSetName = $elementInfo[0];
               $elementName = $elementInfo[1];            
               $elementTexts = metadata('item',array($elementSetName, $elementName), array('no_escape'=>true,'all'=>true));                   
               if (!empty($elementTexts)) { 
		           		echo '<div id="dublin-core-'.strtolower($elementName).'"class="element">';      
             	    if ($elementName=='Identifier'){
					            echo '<h2> View Source </h2>';
					        }
                  foreach($elementTexts as $elementText) {
             	      if ($elementName=='Identifier')
                      	echo "<div class='element-text'><a href=".$elementText.">".$elementText. "</a></div>";
              	    else
                        echo '<h2>' .$elementText . '</h2>';
                 }
                 echo '</div>';
            }  
                
        }
        
   if (metadata('item', 'Collection Name')):
	    $Collection = get_collection_for_item();
	    $title = metadata($Collection, array('Dublin Core', 'Title'));?>
      <div id="collection" class="element">
            <h2>Collection</h2>
            <div class="element-text"><p><?php echo $title; ?></p></div>
      </div>
    <?php endif; ?>
    
    <?php if (metadata('item', 'has tags')): ?>
	   <div id="item-tags" class="element">
		<h2>Tags</h2>
		<div class="element-text"><?php echo tag_string('item'); ?></div> 
	  </div>
	  <?php endif;?>
    <?php //Need to be done*******mlibrary_display_related_exhibits(); ?>
    <?php mlibrary_display_back_button_item_page('item'); ?>
    </div> <!-- end sidebar-->        
    <div id="item-metadata"> 	
<!--  The following function prints all the the metadata associated with an item: Dublin Core, extra element sets, etc. See http://omeka.org/codex or the examples on items/browse for information on how to print only select metadata fields. -->
      	<?php echo all_element_texts('item');?>
    </div> <!--item-metadata-->    
    <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
    <ul class="item-pagination navigation">
	       <li id="previous-item" class="previous">
					<?php echo link_to_previous_item_show('Previous Item in Items archive'); 
			    		//	$id = $item->id;					    			
					    	//Need to be done *******mlibrary_display_items_in_exhibit_page($id);?>
				 </li>
			   <li id="next-item" class="next">
						<?php echo link_to_next_item_show('Next Item in Items archive'); ?>
			   </li>
	   </ul>	  
</div> <!--// end primary-->

<script type="text/javascript">	
jQuery(function(){
jQuery(".element-set").mdcollapse(1,'div');
jQuery("#item-images").data('curimg','img1');
    
	jQuery(".square_thumbnail").click(function(e){   
		var target = jQuery(this);
		var classList = target.attr('class').split(/\s+/);
		var imgClass = classList[1]; //hardcoded, but simplest way to get to specific class, without regex and loop
		
		target.siblings(".file-metadata").hide();
		jQuery("#fsize_images div").hide();
		target.siblings(".file-metadata."+imgClass).show();
		
		var fullsize = target.siblings('#fsize_images').find("."+imgClass);
		if(fullsize.length > 0 && fullsize.hasClass('fullsize')){
			fullsize.show();
		}else {
			// question to Wesley why we 
			jQuery("#fsize_images").html(imagesJSON[imgClass]+jQuery("#fsize_images").html());
			jQuery("a.fancyitem").fancybox(jQuery(document).data('fboxSettings'));
		}
        // look for imgClass and show it.			
    });
  });
    </script>   
<?php echo foot(); ?>