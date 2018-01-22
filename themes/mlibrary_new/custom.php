<?php
require_once dirname(__FILE__) . '/functions.php';

/**
* Copyright (c) 2016, Regents of the University of Michigan.
* All rights reserved. See LICENSE.txt for details.
*/

// Use this file to define customized helper functions, filters or 'hacks' defined
// specifically for use in your Omeka theme. Note that helper functions that are
// designed for portability across themes should be grouped into a plugin whenever
// possible.

/**
* Extends featured exhibit function to include snippet from description for exhibits and
* read more link
**/

/**
* Create the information in cards in introduction page, image with title and text
*
**/

function mlibrary_new_exhibit_builder_previous_link_to_exhibit($exhibit, $exhibit_page)
{
   $previoustargetPage = $exhibit_page->previous();
   $navigate_previous_exhibit = ""; 
   if ($previoustargetPage) {
      if (!isset($props['class'])) {
            $props['class'] = 'previous-page';
      }
   
   $link =  exhibit_builder_link_to_exhibit($exhibit, 'Previous Section', $props, $previoustargetPage);
   $page_card_info = mlibrary_new_display_exhibit_card_info($previoustargetPage);
   $caption = snippet_by_word_count($page_card_info['description'],10,'..');
   $navigate_previous_exhibit = array(
                                 'title'=>$page_card_info['title'],
                                 'url-link'=>$link,
                                 'caption'=>$caption,
                                 'image'=>$page_card_info['image']);

   }

   return $navigate_previous_exhibit;
}


function mlibrary_new_exhibit_builder_next_link_to_exhibit($exhibit, $exhibit_page)
{
   $nexttargetPage = $exhibit_page->next();
   $navigate_next_exhibit = "";

   if ($nexttargetPage) {
      if (!isset($props['class'])) {
           $props['class'] = 'next-page';
      }

   $link = exhibit_builder_link_to_exhibit($exhibit, 'Next Section', $props, $nexttargetPage);
   $page_card_info = mlibrary_new_display_exhibit_card_info($nexttargetPage);
   $caption = snippet_by_word_count($page_card_info['description'],10,'..');   
   $navigate_next_exhibit = array(
                                 'title'=>$page_card_info['title'], 
                                 'url-link'=>$link, 
                                 'caption'=>$caption,
                                 'image'=>$page_card_info['image']);

   }   
   
   return $navigate_next_exhibit;
}


function mlibrary_new_get_most_used_tags_in_exhibits()
{
  $db = get_db();
  $select = "
             select t.name, count(t.name)  As name_occurrence from {$db->prefix}tags t
             INNER JOIN {$db->prefix}records_tags tg on tg.tag_id = t.id
             INNER JOIN {$db->prefix}exhibits e on tg.record_id = e.id group by t.name order by name_occurrence DESC limit 10";
   
  $tags = get_db()->getTable('Tag')->fetchObjects($select);
  
  return $tags;
}


function mlibrary_new_display_popular_tags() 
{
  $tagsExhibit = mlibrary_new_get_most_used_tags_in_exhibits(); 
  $html = '<div>';
  if ($tagsExhibit) {
       $html .= get_view()->partial('exhibit-builder/exhibits/tags.php', array('tags' => $tagsExhibit));
  } else {
       $html .= '<p>' . __('You have no tags in exhibits.') . '</p>';
  }
  $html .= '</div>';
  
  return $html;
}

function mlibrary_new_get_page_image($rawAttachment = null) 
{
     $default_image = img("defaulthbg.jpg");
     $page_image = "<img class='image-card' alt='' src='{$default_image}'/>";
     
     if (!empty($rawAttachment)) {
         if ((mlibrary_new_display_exhibit_type_of_item($rawAttachment) == 'Video')) {
             $page_image = mlibrary_new_exhibit_builder_video_attachment($rawAttachment[0]->getItem());
          } else {
              $page_image = record_image($rawAttachment[0]->getFile(),'original',array('class' => 'image-card'));
          }
     } 

     return $page_image;
}


function mlibrary_new_display_exhibit_card_info($exhibitPage)
{ 
  $page_image = mlibrary_new_get_page_image($exhibitPage->getAllAttachments());
  $page_title = get_view()->shortcodes(metadata($exhibitPage, 'title'));
 
  if (empty($exhibitPage->getPageBlocks())) {
    $page_description = '';
  } else {
    $block = $exhibitPage->getPageBlocks()[0];
    $page_description = get_view()->shortcodes(snippet_by_word_count(metadata($block, 'text',['no_escape' => true]),20,'..'));
  }
 
  return [
    'image'       => $page_image,
    'title'       => $page_title,
    'description' => $page_description
  ];
}

// get the type of the item
function mlibrary_new_display_exhibit_type_of_item($rawAttachment)
{
   $itemType = 'Still Image';

   if ($rawAttachment[0]->getItem()->getItemType()!=null) {
       $itemType = $rawAttachment[0]->getItem()->getItemType()->name;
 }
   
   return $itemType;
}


function mlibrary_new_exhibit_builder_display_random_featured_exhibit()
{
    $html = '<div id="featured-exhibit" class="featured-exhibit-container">';
    $featuredExhibit = exhibit_builder_random_featured_exhibit();
    if ($featuredExhibit) {
        $html .= get_view()->partial('exhibit-builder/exhibits/single.php', array('exhibit' => $featuredExhibit));
    } else {
        $html .= '<p>' . __('You have no featured exhibits.') . '</p>';
    }
    $html .= '</div>';
    $html = apply_filters('exhibit_builder_display_random_featured_exhibit', $html);
    
    return $html;
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
     foreach($exhibits as $exhibit) {
     	$data[] = link_to_exhibit(null, array(), null, $exhibit);
     }
  }
  return $data;
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

// Used in items/show.php and exhibits/item.php
function mlibrary_display_video() {
$html_video = '';
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
      if (empty($elementtitles[$i])) {
  	       $elementtitles[$i] = strip_formatting(metadata('item',array('Dublin Core', 'Title')));
      }
      $html_video .='<div>' .$elementvideo_VCM .
                '<div class="showcase-caption">
                  <h3>' . $elementtitles[$i] . '</h3>
                </div>
                </div>';
    }
    $html_video .='</div>';
  } elseif (!empty($elementvideos)) {
    $html_video ='<div id="showcase" class="showcase">';
    foreach($elementvideos as $i => $elementvideo ) {
      if (empty($elementtitles[$i])) {
  	      $elementtitles[$i] = strip_formatting(metadata('item',array('Dublin Core', 'Title')));
  	  }
      $html_video .='<div>
                   <iframe src="//www.youtube.com/embed/' . $elementvideo . '" frameborder="0" width="650" height="400"></iframe>
                   <div class="showcase-caption">
                   <h3>' . $elementtitles[$i] . '</h3>
                   </div>
                   </div>';
    }// end of foreach
    $html_video .='</div>';
  }// end elseif (!empty($elementvideos))
  return $html_video;
}

// Display the Item source from the Identifier field. If it is valid url it will be displayed as a link other wise it will be displayed not as url.
// Used in items/show.php & exhibits/item.php
function mlibrary_metadata_sideinfo($item){
  $html = '';
  $item = get_current_record('item');

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
        $name = ($elementName == 'Identifier') ? 'Item Source' : $elementName;
        $html .= '<dt>' . $name . '</dt>';

      foreach($elementTexts as $elementText) {
        //$array_items = array("5947","5945","5941","5929","5927","5925","5923","5921","5913");
        //if ((in_array($item->id, $array_items)) && ($elementName == 'Identifier')) {
            //  $data = $elementText;
          // }
        //else {
	$data = ($elementName == 'Identifier' && (filter_var($elementText, FILTER_VALIDATE_URL))) ? '<a href="' . $elementText . '">View Item Source</a>' : $elementText;
        //}
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
 * This function is not used anywhere
 **/
function mlibrary_exhibit_audio() {
	$exhibit_audio_setting=get_theme_option('Exhibit Audio');
	return $exhibit_audio_setting;
}

/**
 * If video will be used in exhibit, set the Exhibit video configuration option.
 * this function is not used anywhere
 **/
function mlibrary_exhibit_video() {
	$exhibit_video_setting=get_theme_option('Exhibit Video');
	return $exhibit_video_setting;
}

/**
 * This function returns the Header Image based on selection in Exhibit Theme Configurations.
 * Used at exhibits/show.php and summary.php
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
  .        "<p class='feed-content'>$description <a href=\"$link\">...more</a></p>";
  }
}

/**
 * Retrieve a thumnail image for a video item type
 *  It is not used in this installation, but it can be used in the future.
 **/
function mlibrary_new_exhibit_builder_video_attachment($item) {
$remove[] = "'";
	$elementids_youtube_video = metadata($item, array('Item Type Metadata', 'Video_embeded_code'), array('no_escape'=>true,'all'=>true));
	$elementvideos_kultura_VCM = metadata($item, array('Item Type Metadata', 'video_embeded_code_VCM'),array('no_escape'=>true, 'all'=>true));
	if (!empty($elementids_youtube_video)) {
		foreach ($elementids_youtube_video as $elementid_youtube_video) {
			$videoid = str_replace($remove, "", $elementid_youtube_video);
			if (!empty($videoid)) {
				$video_thumnail_image = "<img src='//i4.ytimg.com/vi/".$videoid."/default.jpg' style='width:200px; height:152px'/>";
			}
		}
        }//if
        elseif (!empty($elementvideos_kultura_VCM)) {
  	        $data = $elementvideos_kultura_VCM[0];
		preg_match('/\/entry_id\/([a-zA-Z0-9\_]*)?/i', $data, $match);
                $partnerId = 1038472;
                $video_thumnail_image = '<img src="//cdn.kaltura.com/p/'.$partnerId.'/thumbnail/entry_id/'.$match[1].'/width/400/height/400/type/1/quality/100"/>';
        }//if
        $html = exhibit_builder_link_to_exhibit_item($video_thumnail_image,'',$item);
  return $html;
}

/**
 * This function will attach item of type video to Exhibit builder out of the box layouts,
 * this filter is used at Exhibit builder
 **/
add_filter('exhibit_attachment_markup', 'mlibrary_exhibit_builder_attachment');
function mlibrary_exhibit_builder_attachment($html, $compact) {
  $remove[] = "'";
  $elementids = "";
  $elementvideos_VCM = "";
  $thumnail_image = false;
  $exhibitPage = get_current_record('exhibit_page', false);
                 if ($exhibitPage->layout != 'mlibrary-custom-layout') {
                        $item = $compact['attachment']->getItem();
                        if (($item !== null) and (!empty($item->getItemType()))) {
                            $item_type = $item->getItemType();
                              if (($item_type['name'] =='Video')) {
                                 $html = mlibrary_new_exhibit_builder_video_attachment($item, $thumnail_image);
                                    if (!empty($compact['attachment']['caption'])) {
                                        $html .= $compact['attachment']['caption'];
                                    }
                              }
                        }
                        // Add a query string to then end of the href so we know which exhibit you came from
                        $html = mlibrary_add_vars_to_href(
                                  $html,
                                  mlibrary_exhibit_item_query_string_settings()
                                );
                 }
  
  return $html;
}

/**
 * A helped function that takes a string, finds the "href" attribute in it,
 * and appends variables to the end
 */
function mlibrary_add_vars_to_href($html, $variables) {
  return preg_replace(
    '/href=["\']([^"\']*)/',
    'href="$1?' . http_build_query($variables),
    $html
  );
}

// called by items/browse.php
function mlibrary_link_to_item_with_return($text, $attributes = []) {
  return mlibrary_add_vars_to_href(
    link_to_item($text, $attributes),
    [ 'page' => (isset($_GET['page'])) ? $_GET['page'] : '1' ]
  );
}

/**
 * Function to return common settings for exhibit item link query strings
 * called by mlibrary_exhibit_builder_attachment
 */
function mlibrary_exhibit_item_query_string_settings() {
  return [ 'exhibit' => get_current_record('exhibit_page')->exhibit_id,
           'page'    => get_current_record('exhibit_page')->id ];
}

/**
 * This function creates the Vertical Navigation on the left hand side of any Exhibit page.
 * This function is necessary to keep consistence with Navigation look on Omeka 1.5
 * called by exhibits/show.php
 **/
function mlibrary_new_exhibit_builder_page_summary($exhibitPage = null, $current_page=null) {
if (!$exhibitPage) {
       $exhibitPage = get_current_record('exhibit_page');
  }
  $parents = $current_page->getAncestors();
  if (($current_page->id == $exhibitPage->id)) {
      $html = '<li class="current">'
         . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
	 . metadata($exhibitPage, 'title') .'</a>';
   } elseif ((!empty($parents))
             && ($exhibitPage->id == $parents[0]->id)
          ) {
              $html = '<li class="current">'
                     . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
                     . metadata($exhibitPage, 'title') .'</a>';
            } else {
                $html  = '<li>'
                  	  . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
                      . metadata($exhibitPage, 'title') .'</a>';
            }
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
