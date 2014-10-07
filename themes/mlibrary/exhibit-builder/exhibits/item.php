<?php $item_title = item('Dublin Core', 'Title');
$item_title_in_browse = strip_formatting($item_title);?>
<?php head(array('title'=> html_escape("{$item_title_in_browse}"),'bodyid'=>'items','bodyclass'=>'show item')); ?>

<h1><?php echo $item_title; ?></h1>

<div id="sharethis">
<span>Share this Item!</span>
<div class="g-plusone" data-size="medium"></div>
<div class="fb-like" data-send="false" data-layout="button_count" data-show-faces="false" data-font="arial"></div>
<div class="twitter-share"><a href="https://twitter.com/share" class="twitter-share-button" data-text="I just saw '<?php echo item('Dublin Core', 'Title'); ?>' at the MLibary Online Exhibits! <?php echo item('permalink') ?>">Tweet</a></div>
</div>

<!-- start FancyBox initialization and configuration -->
<?php //echo js('fancybox/fancybox-init-config');?>
<?php $lightbox_setting = mlibrary_light_box(); ?>		

<!-- MDCollapse Load -->
<?php echo js('mdcollapse');?>	
<?php echo js('JwPlayer/jwplayer');?>

 <?php 
//$remove[] = "'";
//$remove[] = '"';
//$remove[] = " ";
?>
<?// Use Show case for video, to have more than one video at a time.?>
 <script type="text/javascript">
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
</script>
   	
<div id="primary">
	
<!-- display files -->	

  <?php
  $item_type = $item->getItemType()->name;
  
    if (!isset($exhibit->theme))
		$theme_name= 'mlibrary';
  else
		$theme_name= $exhibit->theme;
		
  $audio = array('application/ogg','audio/aac','audio/aiff','audio/midi','audio/mp3','audio/mp4','audio/mpeg','audio/mpeg3','audio/mpegaudio','audio/mpg','audio/ogg','audio/x-mp3','audio/x-mp4','audio/x-mpeg','audio/x-mpeg3','audio/x-midi','audio/x-mpegaudio','audio/x-mpg','audio/x-ogg','application/octet-stream');
  //Three types of doc, image, sound or video
if ($item_type!='Video')
{	
	// Either image or sound 
	 
	//start the loop of item files
  $fullsizeimage=false;
  $audio_file=false;
  
while(loop_files_for_item()):$file = get_current_file();
	//variables used to check mime types for VideoJS compatibility, etc.
   $mime = item_file('MIME Type');
   $image_index++;
   //Check to see if there is audio in one of the files included in any item
   if (in_array($mime,$audio))
    $audio_file=true;
   
   // Check to see it is an image file
   if($file->hasThumbnail()){ 
   // check to see if it is a first image file, first image file will be displayed in full size, anything after this will be thumbnail
   		if ($fullsizeimage==false){	  
			$file_metadata = '<div class="file-metadata img'.$image_index.'">'.item_file('Dublin Core', 'Title')."</div>";
			$filename = basename($file->archive_filename,'.jpg');	
			// To check if zoomed version available, if it is available then use it.
			if(file_exists('archive/zoom_tiles/'.$filename.'_zdata')){  
				$html_fullsize_image= '<div class="zoom img'.$image_index.' swf-zoom"><OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="750" HEIGHT="450" ID="theMovie">
					<PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata">
					<PARAM NAME="MENU" VALUE="FALSE">
					<PARAM NAME="SRC" VALUE="'.uri('').'themes/'.$theme_name.'/javascripts/ZoomifyViewer.swf">
					<param NAME=wmode VALUE=opaque> 
					<EMBED FlashVars="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata" SRC="'.uri('').'themes/'.$theme_name.
					'/javascripts/ZoomifyViewer.swf" wmode=opaque MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="550" HEIGHT="450" NAME="theMovie"></EMBED></OBJECT></div>';
			}else{
			
				$html_fullsize_image= display_file($file, array('imageSize'=>'fullsize','imgAttributes'=>array('alt'=>item('Dublin Core', 'Title')),'linkAttributes'=>array('rel'=>'group-fancy-image','class'=>'fancyitem ' ,'title' => item('Dublin Core', 'Title'))),array('class' => 'fullsize img'.$image_index, 'id' => 'item-image'));	        
			}
			// added to jason object
			$json_fullsize['img'.$image_index] = $html_fullsize_image;
      $fullsizeimage=true;
      $html_thumnailsize_image= display_file($file, array('imageSize'=>'square_thumbnail','imgAttributes'=>array('alt'=>item('Dublin Core', 'Title').' '.'image'.' '.$image_index),'linkToFile'=>false),array('class' => 'square_thumbnail img'.$image_index ));
			      
			// if first image displayed in full size then display all images after that in thumbnail version      
    	} elseif ($fullsizeimage==true){ 
	    	$file_metadata .= '<div class="file-metadata img'.$image_index.'" style="display:none">'.item_file('Dublin Core', 'Title')."</div>";   
			$filename = basename($file->archive_filename,'.jpg');  
  			if(file_exists('archive/zoom_tiles/'.$filename.'_zdata')){   
        		$json_fullsize['img'.$image_index] = '<div class="zoom img'.$image_index.' swf-zoom"><OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="750" HEIGHT="450" ID="theMovie">
					<PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata">
					<PARAM NAME="MENU" VALUE="FALSE">
					<PARAM NAME="SRC" VALUE="'.uri('').'themes/'.$theme_name.'/javascripts/ZoomifyViewer.swf">
					<param NAME=wmode VALUE=opaque> 
					<EMBED FlashVars="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata" SRC="'.uri('').'themes/'.$theme_name.
					'/javascripts/ZoomifyViewer.swf" wmode=opaque MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="550" HEIGHT="450" NAME="theMovie"></EMBED></OBJECT></div>';
			}else{
				$html_fullsize_image .= display_file($file, array('imageSize'=>'fullsize','imgAttributes'=>array('alt'=>item('Dublin Core', 'Title')),'linkAttributes'=>array('rel'=>'group-fancy-image','class'=>'fancyitem ' ,'title' => item('Dublin Core', 'Title'))),array('style' => 'display:none', 'class' => 'fullsize img'.$image_index, 'id' => 'item-image'));	        
	   			$json_fullsize['img'.$image_index] = display_file($file, array('imageSize'=>'fullsize','imgAttributes'=>array('alt'=>item('Dublin Core', 'Title')),'linkAttributes'=>array('rel'=>'group-fancy-image','class'=>'fancyitem ' ,'title' => item('Dublin Core', 'Title'))),array('class' => 'fullsize img'.$image_index, 'id' => 'item-image'));	        
			}
        	$html_thumnailsize_image .=display_file($file, array('imageSize'=>'square_thumbnail','imgAttributes'=>array('alt'=>item('Dublin Core', 'Title').' '.'image'.' '.$image_index),'linkToFile'=>false),array('class' => 'square_thumbnail img'.$image_index )); 
   		} // END - if $fullsizeimge
    } // END hasThumbnail
endwhile;

echo '<script type="text/javascript"> var imagesJSON ='.json_encode($json_fullsize).'</script>';	
echo'<div id="item-images">';	
echo '<div id="fsize_images">'.$html_fullsize_image.'</div>';
echo $file_metadata;
echo $html_thumnailsize_image;
  
  
if (($fullsizeimage!=true) and (($audio_file==true) || ($item_type=='Sound')))
// if first file is an audio file then display a default image for sound file.
 echo '<img src="'.img('audio_default02.gif').'" alt="Oops" />';

?>
 
</div>

<?php }
// videos is displayed by using embeded tag from youtube
 elseif ($item_type=='Video') {  
    echo'<div id="item-video">';
    $elementvideos = item('Item Type Metadata', 'Video_embeded_code', array('no_escape'=>true,'all'=>true));    
    $elementtitles = item('Item Type Metadata', 'video_title', array('no_escape'=>true,'all'=>true));
    $elementvideos_VCM = item('Item Type Metadata', 'video_embeded_code_VCM', array('no_escape'=>true, 'all'=>true));
    if (!empty($elementvideos_VCM)) { ?>
        <div id="showcase" class="showcase">
        <?php 
          $i=0;
            foreach($elementvideos_VCM as $elementvideo_VCM ) {          
                   $elementvideo_VCM = str_replace( $remove, "", $elementvideo_VCM );            
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
            $elementvideo = str_replace( $remove, "", $elementvideo );            
            echo '<div>';            
            echo '<iframe src="http://www.youtube.com/embed/'.$elementvideo.'" frameborder="0" width="500" height="400"></iframe>';?>                               
            <div class="showcase-caption">
                <?php echo'<h3>'.$elementtitles[$i].'</h3>';?>
            </div>             
            <?php echo '</div>'; 
              $i++;
           }?>//loop
      </div> <!-- showcase -->
    <?php } ?>     
    </div> <!--item-video -->
    
   <?php }
	$index=0;
	$aoutput='';  
  
    while(loop_files_for_item()):$file = get_current_file();
	//variables used to check mime types for VideoJS compatibility, etc.
    $mime = item_file('MIME Type');
   
    if (array_search($mime,$audio) !== false) //echo display_file($file, array('width' => 200, 'height' => 20));//,'icons' => array('audio/mpeg'=>img('sound-icon.jpg'))));
		{      
      $sound_title = $this->fileMetadata($file, 'Dublin Core', 'Title');
			$aoutput .= '<li class="audio-test">';    
			$htmlscript = '<div id="mediaspace_'.$index.'">This text will be replaced</div>
			<script type="text/javascript">
			jwplayer("mediaspace_'.$index.'").setup({
			"flashplayer": "/exhibits/themes/'.$exhibit->theme.'/javascripts/JwPlayer/jplayer/player.swf",
      "skin": "/exhibits/themes/'.$exhibit->theme.'/javascripts/mlibrary/mlibrary.zip",
    "dock": "false",
    "backcolor": "FFFFFF",
    "frontcolor": "FFFFFF",
    "lightcolor": "FFFFFF",
    "screencolor": "FFFFFF",
			"file": "'.file_download_uri($file).'",
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
	endwhile;
	if(strlen($aoutput) > 0){
		echo '<div id="item-audio" class="test"><ul>';
		echo $aoutput;
		echo '</ul></div>';

  }
	?>
<?php // display metadata on the side?>
	<div id="sidebar">	

	  	 <?php
        $elementInfos = array(      
            array('Dublin Core', 'Creator'),
             array('Dublin Core', 'Date'),
             array('Dublin Core', 'Identifier'),
        );
foreach($elementInfos as $elementInfo) {
            $elementSetName = $elementInfo[0];
            $elementName = $elementInfo[1];
            
			
            $elementTexts = item($elementSetName, $elementName, array('no_escape'=>true,'all'=>true));
            
            if (!empty($elementTexts)) { 
				echo '<div id="dublin-core-'.strtolower($elementName).'"class="element">';      
             	if ($elementName=='Identifier'){
					 echo '<h2> View Source </h2>';
					 }
                foreach($elementTexts as $elementText) {  
            		 $array_items = array("5947","5945","5941","5929","5927","5925","5923","5921","5913");		  					 
             	   if (($elementName=='Identifier') and (in_array($item->id, $array_items)))                	    
               	     echo '<div class="element-text">'.$elementText.'</div>';                                   	 
                 else if (($elementName=='Identifier') and (stristr($elementText, 'http') || stristr($elementText, 'https') || stristr($elementText, 'www')))
                    	 echo "<div class='element-text'><a href=".$elementText.">".$elementText. "</a></div>";
                  else
                     echo '<div>' .$elementText . '</div>';  
                }
             	  
                echo '</div>';
            }
       
        }
    ?>
       
       
<!-- If the item belongs to a collection, the following creates a link to that collection. -->
	<?php if (item_belongs_to_collection()): 
	 $Collection = get_collection_for_item(); ?>
        <div id="collection" class="element">
            <h2>Collection</h2>
            <div class="element-text"><p><?php echo $Collection->name; ?></p></div>
        </div>
    <?php endif; ?>

<!-- The following prints a list of all tags associated with the item -->
	<?php if (item_has_tags()): ?>
	<div id="item-tags" class="element">
		<h2>Tags</h2>
		<div class="element-text"><?php echo item_tags_as_string(); ?></div> 
	</div>
	<?php endif;?>
	
<!-- The following prints a citation for this item. -->
	<!--<div id="item-citation" class="element">
    	<h3>Citation</h3>
    	<div class="element-text"><?php //echo item_citation(); ?></div>
	</div>-->
 
<!-- list any related exhibits - see theme custom php and configure in theme settings-->
    <?php mlibrary_display_related_exhibits(); ?>
    <?php $exhibit_type = mlibrary_exhibit_gallery();    
    switch ($exhibit_type) {
    case "gallery":
    mlibrary_display_back_button_item_page('gallery'); 
    break;
    case "exhibit":
    mlibrary_display_back_button_item_page('exhibit'); 
    break;
    }?>
    
</div>
<!-- end display files -->
 	<div id="item-metadata"> 	
<!--  The following function prints all the the metadata associated with an item: Dublin Core, extra element sets, etc. See http://omeka.org/codex or the examples on items/browse for information on how to print only select metadata fields. -->

    	<?php echo show_item_metadata(array('show_element_sets'=>array('Dublin Core'))); ?>

	</div><!-- end item-metadata -->
    <?php echo plugin_append_to_items_show(); ?>
	<ul class="item-pagination navigation">
	<li id="previous-item" class="previous">
		<?php //echo link_to_previous_item('Previous Item in Items archive'); 
		$id = item('id') ;		
		mlibrary_display_items_in_exhibit_page($id);
		?>
	</li>
	<li id="next-item" class="next">
		<?php //echo link_to_next_item('Next Item in Items archive'); ?>
	</li>
	</ul>
	<!-- all other plugins-->

	
</div><!-- end primary -->

	<script type="text/javascript">	

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
//			jQuery("#fsize_images").html(imagesJSON[imgClass]+jQuery("#fsize_images"));
			// question to Wesley why we 
			jQuery("#fsize_images").html(imagesJSON[imgClass]+jQuery("#fsize_images").html());
			jQuery("a.fancyitem").fancybox(jQuery(document).data('fboxSettings'));
		}
        // look for imgClass and show it.
		
	
    });
 
    </script>

<?php foot(); ?>