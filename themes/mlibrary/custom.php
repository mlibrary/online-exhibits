<?php
// Use this file to define customized helper functions, filters or 'hacks' defined
// specifically for use in your Omeka theme. Note that helper functions that are
// designed for portability across themes should be grouped into a plugin whenever
// possible.

add_filter('exhibit_builder_generate_xml', 'mlibrary_exhibit_builder_generate_xml');

function mlibrary_exhibit_builder_generate_xml($xml)
{
   return $xml;
}

function mlibrary_get_tagline($tagline = null)
{
  if (!$tagline) {
     $tagline = get_theme_option('Tagline') ?
		get_theme_option('Tagline') :
		'Add a tagline for your site in theme options';
  }
  return $tagline;
}

/**
* There is two item.php pages, one under Exhibit and the second one under item archive.
**/
function mlibrary_display_back_button_item_page($page_type) {
 switch ($page_type)
 {
   case 'exhibit':
         echo '<a href="#" onClick ="history.go(-1); return false;">Back to Exhibit Page</a>';
	 break;
   case 'item':
         echo '<a href="#" onClick ="history.go(-1); return false;">Back to Item Archive Page</a>';
	 break;
 }
}

/**
* Extends featured exhibit function to include snippet from description for exhibits and
* read more link
*
**/
function mlibrary_exhibit_builder_display_random_featured_exhibit()
{
  $exhibits = get_records('Exhibit' , array ('featured'=>true),$num=4);
  shuffle($exhibits);
  return $exhibits;
}


/**
 * This function returns the style sheet for the theme. It will use the argument
 * passed to the function first, then the theme_option for Style Sheet, then
 * a default style sheet.
 *
 * @param $styleSheet The name of the style sheet to use. (null by default)
 *
 **/
function mlibrary_get_stylesheet($styleSheet = null)
{
   /* set stylesheet to omeka-mibrary default */
   $styleSheet = 'omeka-mlibrary';
   return $styleSheet;
}

/**
* Called by mlibrary_display_related_exhibits() to display a link to related exhibits for
* a particular item if the Related Exhibit option is set in the config page of the exhibit.
**/
function mlibrary_link_to_related_exhibits($id) {
  require_once "Exhibit.php";
  $db = get_db();
  $select = "
	    SELECT DISTINCT e.* FROM {$db->prefix}exhibits e
	    INNER JOIN {$db->prefix}exhibit_pages ep on ep.exhibit_id = e.id
	    INNER JOIN {$db->prefix}exhibit_page_entries epe ON epe.page_id = ep.id
	    WHERE epe.item_id = ?";

  $exhibits = $db->getTable("Exhibit")->fetchObjects($select,array($id));
  $i= 0;
  if(!empty($exhibits)) {
     echo '<div class="element"><h2>Related Exhibits</h2>';
     echo '<ul>';
     foreach($exhibits as $exhibit) {
	echo '<li style="padding-bottom: 5px;">'.link_to_exhibit(null, array(), null, $exhibit).'</li>';
     }
     echo '</ul></div>';
  }
}


/**
* This is the function that is actually used on items/show...
**/
function mlibrary_display_related_exhibits($item) {
  $related_exhibits_setting = get_theme_option('Related Exhibits');
  if ($related_exhibits_setting == 'yes') {
     return mlibrary_link_to_related_exhibits(get_current_record('item')->id);
  }
}


//Return a zoomed image object.
function mlibrary_zoom_fullsize_image($image_index=0, $filename = Null, $theme_name = Null) {
  $image_object = '<div class="zoom img' . $image_index . ' swf-zoom">
	<OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
   CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
   WIDTH="750"
   HEIGHT="450"
   ID="theMovie">
   <PARAM NAME="FlashVars" VALUE="zoomifyImagePath=' . url('') . 'files/zoom_tiles/' .
   $filename . '_zdata&zoomifyX=0.0&zoomifyY=0.0&zoomifyZoom=-1&zoomifyToolbar=1&zoomifyNavWindow=0">
   <PARAM NAME="MENU" VALUE="FALSE">
   <PARAM NAME="SRC" VALUE="' . url('') . 'themes/' . $theme_name . '/javascripts/ZoomifyViewer.swf">
   <PARAM NAME=wmode VALUE=opaque>
	 <EMBED  FlashVars="zoomifyImagePath=' .
     url('') . 'files/zoom_tiles/' . $filename .
     '_zdata&zoomifyX=0.0&zoomifyY=0.0&zoomifyZoom=-1&zoomifyToolbar=1&zoomifyNavWindow=0"
     SRC="' . url('') . 'themes/' . $theme_name . '/javascripts/ZoomifyViewer.swf"
	   wmode=opaque MENU="false"
     PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"
     WIDTH="550"
     HEIGHT="450"
	   NAME="theMovie">
   </EMBED>
	</OBJECT>
  </div>';
  return $image_object;
}

function mlibrary_display_still_image($item, $image_index=0, $audio, $theme_name) {
 set_loop_records('files', get_current_record('item')->Files);
  // removed the if condition from here
  // Either image or sound
  //start the loop of item files
 $fullsizeimage = false;
 foreach(loop('files') as $file):
   $mime = $file['mime_type'];
   $image_index++;

   if (in_array($mime, $audio)) {
    $audio_file=true;
   }

   $extension = pathinfo($file->filename, PATHINFO_EXTENSION);
   $filename = basename($file->filename, '.' . $extension);
   if ($file->hasThumbnail()) {
    if ($fullsizeimage == false) {
     $file_metadata = '<div class = "file-metadata img'.$image_index.'">'
     .strip_formatting(metadata('file',array('Dublin Core', 'Title')))."</div>";
     $html_thumnailsize_image = file_markup($file, array(
		                            'imageSize'=>'square_thumbnail',
		                            'imgAttributes'=>array(
		                                 'alt'=>strip_formatting(metadata(
		                       	         'item',
		                                  array('Dublin Core', 'Title'))).' '.'image'.' '.$image_index),
    		                            'linkToFile'=>false),
    		                             array(
		                              'class' => 'square_thumbnail img'.$image_index
		                             )
		                );

     if (file_exists('files/zoom_tiles/'.$filename.'_zdata')) {
     $html_fullsize_image = mlibrary_zoom_fullsize_image($image_index, $filename, $theme_name);
     } else {
     $html_fullsize_image = file_markup($file,array(
				        'imageSize' => 'fullsize',
                                        'imgAttributes' => array(
                              	             'alt' => strip_formatting(metadata(
                              	                      'item',
                              	                      array('Dublin Core', 'Title')))
                              	         ),
                          	        'linkAttributes' => array(
       					     'rel' => 'group-fancy-image',
      			            	     'class' => 'fancyitem',
			                     'title' => strip_formatting(metadata(
			                                     'item',
			                  	             array('Dublin Core', 'Title'))))),
			                  		     array(
						             'class' => 'fullsize img' . $image_index,
            						     'id' => 'item-image')
          						     );
     }
     $fullsizeimage = true;
     $json_fullsize['img'.$image_index] = $html_fullsize_image;
     } else {
     $file_metadata .= '<div class="file-metadata img' .$image_index .
                          '" style="display:none">' . strip_formatting(metadata(
                                                                  'file',
                                                                  array('Dublin Core', 'Title'))) .
	                "</div>";
     $html_thumnailsize_image .= file_markup($file,array(
                 		             'imageSize' => 'square_thumbnail',
			                     'imgAttributes ' => array(
							     'alt' => strip_formatting(metadata('item',array('Dublin Core', 'Title'))) .
                                                                ' ' . 'image' . ' ' .$image_index),
                          				     'linkToFile' => false), array(
                                            					    'class' => 'square_thumbnail img' .$image_index )
				           );
     if (file_exists('files/zoom_tiles/'.$filename.'_zdata')) {
      $json_fullsize['img'.$image_index] = mlibrary_zoom_fullsize_image($image_index, $filename, $theme_name);
     } else {
      $html_fullsize_image .= file_markup($file, array(
					  'imageSize' => 'fullsize',
				          'imgAttributes'=> array(
           				          'alt' => strip_formatting(metadata('item',array('Dublin Core', 'Title')))
				                  ),
  					  'linkAttributes' => array(
					          'rel' => 'group-fancy-image',
                				  'class'=> 'fancyitem',
					          'title' => strip_formatting(metadata(
                                                  'item',array('Dublin Core', 'Title')))
                                                  )
                                        ),
                                        array(
                                        'style' => 'display:none',
                                        'class' => 'fullsize img' . $image_index,
                                        'id' => 'item-image'
                                       )
                            );
      $json_fullsize['img'.$image_index] = file_markup($file, array(
                                                       'imageSize' => 'fullsize',															   				    'imgAttributes' => array(
                                                             'alt' => strip_formatting(metadata('item', array('Dublin Core', 'Title')))),												          'linkAttributes' => array(
                                                              'rel' => 'group-fancy-image',
                                                              'class' => 'fancyitem',
                                                              'title' => strip_formatting(metadata('item',array('Dublin Core', 'Title'))))), array(
								                                                 'class' => 'fullsize img' . $image_index,
									                         		 'id' => 'item-image'
											                         )
						     );
      }//zoom
   }// else fullsizeimage is true
  } // file has a thumbnail
  endforeach;
  if (!empty($json_fullsize)) {
   echo '<script type="text/javascript"> var imagesJSON ='.json_encode($json_fullsize).'</script>' .
        '<div id="item-images"> <div id="fsize_images">' .
            $html_fullsize_image .
          '</div>' .
          $file_metadata .
          $html_thumnailsize_image .
        '</div>';
  } // json
 }

function mlibrary_display_video() {
 $elementvideos = metadata('item',array('Item Type Metadata', 'Video_embeded_code'),array(
                                                                                   'no_escape' => true, 
                                                                                   'all' => true
                                                                                   )
                         );

 $elementtitles = metadata('item',array('Item Type Metadata', 'video_title'),array(
                                                                             'no_escape'=>true,
                                                                             'all'=>true
                                                                            )
                         );

  //Kultura video
 $elementvideos_VCM = metadata('item',array('Item Type Metadata', 'video_embeded_code_VCM'),array(
                                                                                            'no_escape' => true, 
                                                                                            'all' => true
                                                                                            )
                              );

  if (!empty($elementvideos_VCM)) {
  $html_video = '<div id="showcase" class="showcase">';
  foreach($elementvideos_VCM as $i => $elementvideo_VCM ) {
  $html_video .='<div>' .$elementvideo_VCM .
                '<div class="showcase-caption">
                  <h3>' . $elementtitles[$i] . '</h3>
                </div>
                </div>';
  }//end of foreach
  $html_video .='</div>';
  } elseif (!empty($elementvideos)) {
    $html_video ='<div id="showcase" class="showcase">';
    foreach($elementvideos as $i => $elementvideo ) {
    $html_video .='<div>
                   <iframe src="http://www.youtube.com/embed/' . $elementvideo . '" frameborder="0" width="650" height="400"></iframe>
                   <div class="showcase-caption">
                   <h3>' . $elementtitles[$i] . '</h3>
                   </div>
                   </div>';
    }// end of foreach
    $html_video .='</div>';
  }// end elseif (!empty($elementvideos))
  return $html_video;
}


function mlibrary_metadata_sideinfo($item){
  $html = '';
  get_current_record('item');

  $elementInfos = array(
    array('Dublin Core', 'Creator'),
    array('Dublin Core', 'Date'),
    array('Dublin Core', 'Identifier'),
  );

  foreach($elementInfos as $elementInfo) {
    $elementSetName = $elementInfo[0];
    $elementName = $elementInfo[1];
    $elementTexts = metadata(
      'item',
      array($elementSetName, $elementName),
      array('no_escape' => true, 'all' => true)
    );

    if (!empty($elementTexts)) {
        $name = ($elementName == 'Identifier') ? 'Source' : $elementName;
        $html .= '<dt>' . $name . '</dt>';

      foreach($elementTexts as $elementText) {
        $data = ($elementName == 'Identifier') ? '<a href="' . $elementText . '">View Source</a>' : $elementText;
        $html .= '<dd>' . $data . '</dd>';
      }
    }
  }

  if (metadata('item', 'Collection Name')) {
    $Collection = get_collection_for_item();
    $title = metadata($Collection, array('Dublin Core', 'Title'));
    $html .= '<dt>Collection</dt> <dd>' .
               $title .
             '</dd>';
  }

  if (metadata('item', 'has tags')) {
    $html .= '<dt>Tags</dt> <dd class="tags">' .
               str_replace(';', '', tag_string('item')) .
             '</dd>';
  }

  return (empty($html)) ? '' : '<dl id="sidebar" class="record-metadata-list">' . $html . '</dl>';
}

/**
* If audio will be used in exhibit, set the Exhibit Audio configuration option.
**/
function mlibrary_exhibit_audio() {
	$exhibit_audio_setting=get_theme_option('Exhibit Audio');
	return $exhibit_audio_setting;
}

/**
* If video will be used in exhibit, set the Exhibit video configuration option.
**/
function mlibrary_exhibit_video() {
	$exhibit_video_setting=get_theme_option('Exhibit Video');
	return $exhibit_video_setting;
}

/**
 * This function returns the Header Image based on selection in Exhibit Theme Configurations.
 *
 **/
function mlibrary_header_banner() {
	$header_banner = get_theme_option('Header Banner');
	$header_text = get_theme_option('Header Text');
	if($header_banner){
			$output = "<h1 class='default' style='background-image: url(" . CURRENT_BASE_URL . '/files/theme_uploads/' . $header_banner . ")'>";
	} else {
			$output = "<h1 class='default'>";
	}
	if($header_text == 'yes' || !$header_text){
			$output .= "<span>".metadata('exhibit', 'title')."</span></h1>";
	} else {
			$output .= "</h1>";
	}
	return $output;
}

/** New exhibits feed to RSS **/
function mlibrary_display_rss($feedUrl, $num = 3) {
  try {
   $feed = Zend_Feed_Reader::import($feedUrl);
  } catch (Zend_Feed_Exception $e) {
    echo '<p>Feed not available.</p>';
  return;
  }
  $posts = 0;
  foreach ($feed as $entry) {
  if (++$posts > $num) break;
  $title = $entry->getTitle();
  $link = $entry->getLink();
  $description = $entry->getDescription();
  echo "<p class='feed-title'><a href=\"$link\">$title</a></p>"
  . "<p class='feed-content'>$description <a href=\"$link\">...more</a></p>";
}
}

/**
* Retrieve a thumnail image for a video item type
**/
function mlibrary_exhibit_builder_video_attachment($item, $thumnail_image) {
	$remove[] = "'";
	$elementids_youtube_video = metadata($item, array('Item Type Metadata', 'Video_embeded_code'), array('no_escape'=>true,'all'=>true));
	$elementvideos_kultura_VCM = metadata($item, array('Item Type Metadata', 'video_embeded_code_VCM'),array('no_escape'=>true, 'all'=>true));
	if (!empty($elementids_youtube_video)) {
		foreach ($elementids_youtube_video as $elementid_youtube_video) {
			$videoid = str_replace($remove, "", $elementid_youtube_video);
			if (!empty($videoid)) {
				$video_thumnail_image = "<img src='http://i4.ytimg.com/vi/".$videoid."/default.jpg' style='width:200px; height:152px'/>";
			}
		}
  }//if
  elseif (!empty($elementvideos_kultura_VCM)) {
  	$data = $elementvideos_kultura_VCM[0];
		preg_match('/\/entry_id\/([a-zA-Z0-9\_]*)?/i', $data, $match);
    $partnerId = 1038472;
    $video_thumnail_image = '<img src="http://cdn.kaltura.com/p/'.$partnerId.'/thumbnail/entry_id/'.$match[1].'/width/400/height/400/type/1/quality/100"/>';
  }//if
  $html = exhibit_builder_link_to_exhibit_item($video_thumnail_image,'',$item);
  return $html;
}

/**
*
**/
function mlibrary_exhibit_builder_custom_layout($file, $item, $displayFilesOptions, $htmlimage) {
	$extension = pathinfo($file->filename, PATHINFO_EXTENSION);
	$filename = basename($file->filename,'.'.$extension);
	$htmlimage['id'.$file->id]['archive'] = $item->id;
	if (!preg_match('~^https?://~i',metadata($item,array('Dublin Core','Identifier')))) {
           $htmlimage['id'.$file->id]['fulltext']='';
	}
	else {
           $htmlimage['id'.$file->id]['fulltext'] = metadata($item,array('Dublin Core','Identifier'));
	}
	$htmlimage['id'.$file->id]['creator'] = $displayFilesOptions['creator'];
	$htmlimage['id'.$file->id]['description'] = $displayFilesOptions['description'];
	$htmlimage['id'.$file->id]['title'] = $displayFilesOptions['title'];
	$htmlimage['id'.$file->id]['date'] = $displayFilesOptions['year'];
	$firstimage='true';
	if(file_exists('files/zoom_tiles/'.$filename.'_zdata')) {
          $htmlimage['id'.$file->id]['image'] = '<div class="zoom id'.$file->id.' exhibit-item"><OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="100%" HEIGHT="450" ID="theMovie">
		<PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.url('').'files/zoom_tiles/'.$filename.'_zdata">
    <PARAM NAME="MENU" VALUE="FALSE">
	  <PARAM NAME="SRC" VALUE="'.url('').'themes/mlibrary/javascripts/ZoomifyViewer.swf">
  	<param NAME=wmode VALUE=opaque>
		<EMBED FlashVars="zoomifyImagePath='.url('').'files/zoom_tiles/'.$filename.'_zdata" SRC="'.url('').'themes/mlibrary/javascripts/ZoomifyViewer.swf" wmode=opaque MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="100%" HEIGHT="450" NAME="theMovie"></EMBED></OBJECT></div>';
  }
  else {
       $htmlimage['id'.$file->id]['image'] = "\n" . '<div class="fullsize id'.$file->id.' exhibit-item">';
       $htmlimage['id'.$file->id]['image'] .= file_markup($file, array('imageSize'=>'fullsize','imgAttributes'=>array('alt'=>strip_formatting(metadata($item,array('Dublin Core', 'Title')))),'linkAttributes'=>array('class'=>'fancyitem','title' => strip_formatting(metadata($item,array('Dublin Core', 'Title'))))));
       $htmlimage['id'.$file->id]['image'] .= '</div>' . "\n";
    }
    return $htmlimage;
}

/**
* This function will attach item of type video to Exhibit builder out of the box layouts,
* as well as create the big image on the right hand side of the Mlibrary custom layout.
**/
add_filter('exhibit_builder_attachment_markup', 'mlibrary_exhibit_builder_attachment_markup');
function mlibrary_exhibit_builder_attachment_markup($html, $compact) {
$remove[] = "'";
$elementids = "";
$elementvideos_VCM = "";
$thumnail_image = false;
$exhibitPage = get_current_record('exhibit_page', false);
$imageSize = $compact['fileOptions']['imageSize'];
// All Exhibit builder layout out of the box. Only customization for those layouts is adding video item type
if ($exhibitPage->layout!='mlibrary-custom-layout') {
  $item = $compact['attachment']['item'];
  if(!empty($compact['attachment']['item']->getItemType()->name)) {
    $item_type = $compact['attachment']['item']->getItemType()->name;
    if ($item_type == 'Sound') {
    // $html = exhibit_builder_link_to_exhibit_item("<img src='".img('sound-icon.jpg')."'/>");
    }//if
    elseif (($item_type =='Video')) {
    $html = mlibrary_exhibit_builder_video_attachment($item, $thumnail_image);
    }//elseif video
  }
}
else {   // Mlibrary custom layout
  $firstimage = 'false';
  // This layout (custom layout)
  $files = $compact['attachment']['item']->Files;
  $item = $compact['attachment']['item'];
  $displayFilesOptions = $compact['fileOptions'];
  $htmlimage = "";
  set_loop_records('files', $files);
  if(!empty($files)) {
    foreach(loop('files') as $file):
			// render the first image to be the big image.
     if ((($file->hasThumbnail()) && ($displayFilesOptions['imageorder']==1)) && ($firstimage!='true'))
       $htmlimage = mlibrary_exhibit_builder_custom_layout($file, $item, $displayFilesOptions, $htmlimage);
		    // all other files should be hide it
     elseif ($file->hasThumbnail())
  		  //render the rest of the images to be the first image every time a user click on a thumbnail image.
      $htmlimage = mlibrary_exhibit_builder_custom_layout($file, $item, $displayFilesOptions, $htmlimage);
    endforeach;  // loop through files in an item
  }
  else $htmlimage = "";
  $html = $htmlimage;
}
return $html;
}


/**
* This function creates the Vertical Navigation on the left hand side of any Exhibit page.
* This function is necessary to keep consistence with Navigation look on Omeka 1.5
**/
function mlibrary_exhibit_builder_page_summary($exhibitPage = null, $current_page=null) {
 if (!$exhibitPage) {
    $exhibitPage = get_current_record('exhibit_page');
  }
 $parents = $current_page->getAncestors();
 if(($current_page->id == $exhibitPage->id))
 $html = '<li class="current">'
         . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
	 . metadata($exhibitPage, 'title') .'</a>';
 elseif (!empty($parents) && ($exhibitPage->id == $parents[0]->id))
 $html = '<li class="current">'
         . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
         . metadata($exhibitPage, 'title') .'</a>';
 else
 $html  = '<li>'
	  . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
          . metadata($exhibitPage, 'title') .'</a>';
          //Add Children to navigation.
 $children = $exhibitPage->getChildPages();
 if ($children) {
    $html .= '<ul>';
    foreach ($children as $child) {
    $html .= mlibrary_exhibit_builder_page_summary($child,$current_page);
    release_object($child);
    }
    $html .= '</ul>';
  }
  $html .= '</li>';
  return $html;
 }
/**
*
*
**/
add_filter('exhibit_builder_thumbnail_gallery', 'mlibrary_exhibit_builder_thumbnail_gallery');
function mlibrary_exhibit_builder_thumbnail_gallery($html,$compact) {
$remove[] = '';
$exhibitPage = get_current_record('exhibit_page', false);
$start = $compact['start'];
$end =   $compact['end'];
$props = $compact['props'];
$thumnail_image = false;
$html = '';
if ($exhibitPage->layout!= 'mlibrary-custom-layout') {
   for($i=(int)$start; $i <= (int)$end; $i++) {
    $attachment = exhibit_builder_page_attachment($i);
    if (!empty($attachment)) {
     if (!empty($attachment['item']->getItemType()->name))
	$item_type = $attachment['item']->getItemType()->name;
     else
        $item_type = 'Still Image';

    if (($item_type == 'Video')) {
	$image = '';
      	$html .= "\n" . '<div class="exhibit-item">';
      	$html .= mlibrary_exhibit_builder_video_attachment($attachment['item'], $thumnail_image);
      	$html .= exhibit_builder_attachment_caption($attachment);
        $html .= '</div>' . "\n";
    }//type video
    else { //still image
	$html .= "\n" . '<div class="exhibit-item">';
	if ($attachment['file']) {
         $thumbnail = file_image('square_thumbnail', array('class'=>'permalink'), $attachment['file']);
         $html .= exhibit_builder_link_to_exhibit_item($thumbnail, array(), $attachment['item']);
        }
        $html .= exhibit_builder_attachment_caption($attachment);
        $html .= '</div>' . "\n";
      }
    }
  } //forloop
} //($exhibitPage->layout!='mlibrary-custom-layout')
elseif ($exhibitPage->layout == 'mlibrary-custom-layout') {
	//This layout dose not support thumbnail image of youtube video or kultura video
  $image_index=0;
  // start is 1 and end is 12, this is the way set it up in the new layout or other layout that has thumbnail it can be
	//changed to something else.
  $firstthumbnail  =false;
  for ($i=(int)$start; $i <= (int)$end; $i++) {
  	//check to see if there is item exist with exhibit_builder_use_exhibit_page_item function
    $attachment = exhibit_builder_page_attachment($i);
    if (!empty($attachment)) {
    $item_type = $attachment['item']->getItemType()->name;
    $item = $attachment['item'];
    set_loop_records('files', $attachment['item']->Files);
    if (($attachment) && (($item_type!= 'Sound') || ($item_type!= 'video'))) {
    foreach(loop('files') as $file):
     if ($file->hasThumbnail() && ($firstthumbnail!= true)) {
     $html = "\n" . '<div class="square_thumbnail id'.$file->id.' first exhibit-item"  file_id="id'.$file->id.'">';
     $html .= file_markup($file, array(
                                 'imageSize'=>'square_thumbnail',
                                 'imgAttributes'=>array(
                                                  'alt'=>strip_formatting(metadata($item, array('Dublin Core', 'Title')))),
                                                  'linkToFile'=>false));
     $html .= '</div>' . "\n";
     $image_index++;
     $firstthumbnail = true;
     }
     elseif ($file->hasThumbnail() && ($firstthumbnail == true)) {
     $html .= "\n" . '<div class="square_thumbnail id'.$file->id.' exhibit-item"  file_id="id'.$file->id.'">';
     $title = $file->title;
     $html .= file_markup($file, array(
                                 'imageSize'=>'square_thumbnail',
                                 'imgAttributes'=>array(
                                                  'alt'=>strip_formatting(metadata($item, array('Dublin Core', 'Title')))),
                                 'linkToFile'=>false
                                 )
                        );
     $html .= '</div>' . "\n";
     $image_index++;
     }
   endforeach;
   }
  }
 }
}
return $html;
}

