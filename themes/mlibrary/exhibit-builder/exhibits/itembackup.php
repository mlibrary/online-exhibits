<?php head(array('title'=> html_escape("Item #{$item->id}"),'bodyid'=>'items','bodyclass'=>'show item')); ?>

<h1><?php echo item('Dublin Core', 'Title'); ?></h1>

<!-- start FancyBox initialization and configuration -->
<?php //echo js('fancybox/fancybox-init-config');?>
<?php $lightbox_setting = mlibrary_light_box(); ?>		

<!-- MDCollapse Load -->
<?php echo js('mdcollapse');?>	
 <?php echo js('JwPlayer/jwplayer');?>
 <?php echo js('jquery.loupe');?>
 
 

 <?php 
$remove[] = "'";
$remove[] = '"';
$remove[] = " ";
?>

 <script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{
	jQuery("#showcase").awShowcase(
	{
		width:					550,
		height:					500,
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
	
<!-- if user has installed Docs Viewer plugin and turned off auto embed, the viewer will be embedded in the main content area (unless this is turned off in Deco theme config)-->
	<?php //mlibrary_docs_viewer_placement();	?>


<!-- display files -->	

  <?php
  $item_type = $item->getItemType()->name;
  $audio = array('application/ogg','audio/aac','audio/aiff','audio/midi','audio/mp3','audio/mp4','audio/mpeg','audio/mpeg3','audio/mpegaudio','audio/mpg','audio/ogg','audio/x-mp3','audio/x-mp4','audio/x-mpeg','audio/x-mpeg3','audio/x-midi','audio/x-mpegaudio','audio/x-mpg','audio/x-ogg','application/octet-stream');
if ($item_type!='Video')
{	
	echo '<div id="open-slideshow"><a href="#">open slideshow</a></div>';
  	echo'<div id="item-images">';

	//start the loop of item files
  $fullsizeimage=false;
  $audio_file=false;
  
while(loop_files_for_item()):$file = get_current_file();
	//variables used to check mime types for VideoJS compatibility, etc.
	$mime = item_file('MIME Type');
    $image_index++;
   if (in_array($mime,$audio))
     $audio_file=true;
   if($file->hasThumbnail()){
	  if ($fullsizeimage==false){	  
        $file_metadata = '<div class="file-metadata img'.$image_index.'">'.item_file('Dublin Core', 'Title')."</div>";
      
    //  $html_fullsize_image= display_file($file, array('imageSize'=>'fullsize','linkAttributes'=>array('rel'=>'fancy_group', 'class'=>'fancyitem ' ,'title' => item('Dublin Core', 'Title'))),array('class' => 'fullsize img'.$image_index, 'id' => 'item-image')); 
      
      $filename = basename($file->archive_filename,'.jpg');
      
    if(file_exists('archive/zoom_tiles/'.$filename.'_zdata')){    
      $html_fullsize_image= '<OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="750" HEIGHT="450" ID="theMovie">
<PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata">
<PARAM NAME="MENU" VALUE="FALSE">
<PARAM NAME="SRC" VALUE="'.uri('').'themes/mlibrary/javascripts/ZoomifyViewer.swf">
<EMBED FlashVars="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata" SRC="'.uri('').'themes/mlibrary/javascripts/ZoomifyViewer.swf" MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="550" HEIGHT="450" NAME="theMovie"></EMBED>
</OBJECT>';      
      $fullsizeimage=true;
      }
      else{
      $html_fullsize_image= display_file($file, array('imageSize'=>'fullsize','linkAttributes'=>array('rel'=>'fancy_group', 'class'=>'fancyitem ' ,'title' => item('Dublin Core', 'Title'))),array('class' => 'fullsize img'.$image_index, 'id' => 'item-image'));
      $fullsizeimage=true;
      }
      
      $html_thumnailsize_image= display_file($file, array('imageSize'=>'square_thumbnail','linkToFile'=>false),array('class' => 'square_thumbnail img'.$image_index ));      
    }
	
	 elseif ($fullsizeimage==true){ 
	    $file_metadata .= '<div class="file-metadata img'.$image_index.'" style="display:none">'.item_file('Dublin Core', 'Title')."</div>";     
        $html_fullsize_image .=display_file($file, array('imageSize'=>'fullsize','linkAttributes'=>array('rel'=>'fancy_group', 'class'=>'fancyitem' ,'title' => item('Dublin Core', 'Title'))),array('class' => 'fullsize img'.$image_index, 'style'=>'display:none', 'id' => 'item-image')); 
    //echo display_file($file, array('imageSize'=>'square_thumbnail', 'linkToFile'=>true,'linkAttributes'=>array('rel'=>'fancy_group', 'class'=>'fancyitem','title' =>    item('Dublin Core', 'Title'))),array('class' => 'square_thumbnail')); 
        $html_thumnailsize_image .=display_file($file, array('imageSize'=>'square_thumbnail', 'linkToFile'=>false),array('class' => 'square_thumbnail img'.$image_index )); 
    }
    
    }
	//this is testing for rich media files and deciding what to do with them.	
		//videoJS
				//wma video
		//elseif (array_search($mime,$wma_video) !== false) echo display_file($file, array('scale'=>'tofit', 'width' => 600, 'height' => 338));
		//wmv video
		//elseif (array_search($mime,$wmv_video) !== false) echo display_file($file, array('scale'=>'tofit', 'width' => 600, 'height' => 338));

	endwhile;
			
  echo $html_fullsize_image;
  echo $file_metadata;
  echo $html_thumnailsize_image;
  
  
  if (($fullsizeimage!=true) and (($audio_file==true) || ($item_type=='Sound')))
     echo '<img src="'.img('audio_default02.gif').'" alt="Oops" />';
 
	?>
 
</div>

<?php }
 elseif ($item_type=='Video')
  {  
    	echo'<div id="item-video" style="float:left">';
    $elementvideos = item('Item Type Metadata', 'Video_embeded_code', array('no_escape'=>true,'all'=>true));    
    $elementtitles = item('Item Type Metadata', 'video_title', array('no_escape'=>true,'all'=>true));?>
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
           }
    ?>
    </div>
    
    </div>
 
   <?php // echo($item->getTypeMetadata());
    //($item->getElements());
       
   }

	$index=0;
	$aoutput='';  
 	//$audio = array('application/ogg','audio/aac','audio/aiff','audio/midi','audio/mp3','audio/mp4','audio/mpeg','audio/mpeg3','audio/mpegaudio','audio/mpg','audio/ogg','audio/x-mp3','audio/x-mp4','audio/x-mpeg','audio/x-mpeg3','audio/x-midi','audio/x-mpegaudio','audio/x-mpg','audio/x-ogg','application/octet-stream');
  
    while(loop_files_for_item()):$file = get_current_file();
	//variables used to check mime types for VideoJS compatibility, etc.
    $mime = item_file('MIME Type');
   ?>  
<!-- download links -->
	<!--div id="itemfiles" class="element"-->
	    <!--h3>File(s)</h3-->
		<!--div class="element-text"-->
		    <?php 
		    //while(loop_files_for_item()):$file = get_current_file();
			//echo '<div style="clear:both;padding:2px;"><a href="'. file_download_uri($file). '" class="download-file">'. $file->original_filename. '</a>&nbsp; ('.item_file('MIME Type').')</div> ';
			//endwhile;
			?>
		<!--/div-->
	<!--/div-->
<!-- end download links -->
	<?php 

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
echo '<div id="dublin-core-'.strtolower($elementName).'"
class="element">';            
                foreach($elementTexts as $elementText) {
                    echo '<h3>' .$elementText . '</h3>';
                }
                echo '</div>';
            }
       
        }
    ?>
       
       
<!-- If the item belongs to a collection, the following creates a link to that collection. -->
	<?php if (item_belongs_to_collection()): ?>
        <div id="collection" class="element">
            <h3>Collection</h3>
            <div class="element-text"><p><?php echo link_to_collection_for_item(); ?></p></div>
        </div>
    <?php endif; ?>

<!-- The following prints a list of all tags associated with the item -->
	<?php if (item_has_tags()): ?>
	<div id="item-tags" class="element">
		<h3>Tags</h3>
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
    <?php mlibrary_display_related_page_in_exhibits(); ?>
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
	jQuery("#open-slideshow").data('curimg','img1');
    
	jQuery(".square_thumbnail").click(function(){    
		var target = jQuery(this);
		var classList = target.attr('class').split(/\s+/);
		var imgClass = classList[classList.length-2]; //hardcoded, but simplest way to get to specific class, without regex and loop
		//alert(imgClass);
		target.siblings(".fullsize").hide();
        target.siblings(".file-metadata").hide();
        // look for imgClass and show it.
		target.siblings("."+imgClass).show();
		//This code is the same as the above line so that is why I commented Nancy
        //  target.siblings(".file-metadata").hasClass(imgClass).show();;
		jQuery('#open-slideshow').data('curimg',imgClass);
    });
    jQuery(".fullsize a").loupe({
	  width: 140, // width of magnifier
	  height: 85, // height of magnifier
	  loupe: 'loupe' // css class for magnifier
	});
    
	jQuery("#open-slideshow").click(function(e){
		e.preventDefault();
		var curimg = jQuery(this).data('curimg');
		jQuery(".fullsize."+curimg+" a").trigger('click');
   
		return false;
	});
	 
	
    </script>

<?php foot(); ?>