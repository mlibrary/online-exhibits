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

function mlibrary_new_recent_exhibits_bootstrap($recentExhibits) {
    $exhibits = exhibit_builder_recent_exhibits($recentExhibits);
    $html = '';
    if ($exhibits) {
        foreach ($exhibits as $exhibit) {
            $title =  metadata($exhibit, 'title', array('snippet'=>300,'no_escape' => true));
            $exhibitImage = record_image($exhibit, 'original', array('alt' => $exhibit->title,
                                                                     'class' => 'image-card'));
            if ($exhibitImage == Null) {
                $exhibitImage = '<img class="image-card" src="'.img("defaulthbg.jpg").'" alt="Mlibrary default image"/>';
            }

            $html .= get_view()->partial('exhibit-builder/exhibits/card.php', array('exhibitImage' => $exhibitImage,
                                                                                    'exhibit' => $exhibit,
                                                                                    'title' => $title ));
        }
    } else {
        $html = '<p>' . __('No recent exhibits available.') . '</p>';
    }
    return $html;
}

function mlibrary_new_item_sequence($exhibitId, $from = NULL, $direction = NULL) {
  $db = get_db();
  $itemTable = $db->getTable('Item');
  $select = $itemTable->getSelect()
    ->joinInner(
      ['attachments' => $db->ExhibitBlockAttachment],
      'attachments.item_id = items.id',
      ['block_id']
    )
    ->joinInner(
      ['blocks' => $db->ExhibitPageBlock],
      'attachments.block_id = blocks.id',
      ['page_id']
    )
    ->joinInner(
      ['pages' => $db->ExhibitPage],
      'blocks.page_id = pages.id',
      ['exhibit_id']
    )
    ->where('pages.exhibit_id = ?', $exhibitId);

  if ($direction && $from) {
    if ($direction == 'next') {
      $select->where('(pages.order * 65536 + blocks.order * 256 + attachments.order) > ?', $from);
      $select->order(['pages.order', 'blocks.order',  'attachments.order']);
      $select->limit(1);
    }
    else {
      $select->where('(pages.order * 65536 + blocks.order * 256 + attachments.order) < ?', $from);
      $select->order(['pages.order DESC', 'blocks.order DESC', 'attachments.order DESC']);
      $select->limit(1);
    }
  }
  else {
    $select->order(['pages.order', 'blocks.order', 'attachments.order']);
  }
  return $itemTable->fetchObjects($select);
}

function mlibrary_new_item_sequence_prev($exhibitId, $pageId, $itemId) {
  $order = mlibrary_new_item_sequence_order($exhibitId, $pageId, $itemId);
  $sequence = mlibrary_new_item_sequence($exhibitId, $order, 'prev');
  if ($sequence) {
    return $sequence[0];
  }
  return null;
}

function mlibrary_new_item_sequence_next($exhibitId, $pageId, $itemId) {
  $order = mlibrary_new_item_sequence_order($exhibitId, $pageId, $itemId);
  $sequence = mlibrary_new_item_sequence($exhibitId, $order, 'next');
  if ($sequence) {
    return $sequence[0];
  }
  return null;
}

function mlibrary_new_item_sequence_order($exhibitId, $pageId, $itemId) {
  $db = get_db();
  $sql = <<<EOF
SELECT
  pages.order * 65536 + blocks.order * 256 + attachments.order
FROM
  {$db->ExhibitPage} pages JOIN
  {$db->ExhibitPageBlock} blocks ON blocks.page_id = pages.id JOIN
  {$db->ExhibitBlockAttachment} attachments ON  attachments.block_id = blocks.id
WHERE
  pages.exhibit_id = ? AND
  pages.id         = ? AND
  attachments.item_id = ?
ORDER BY
  pages.order, blocks.order, attachments.order
EOF;
  return $db->query($sql, [$exhibitId, $pageId, $itemId])->fetchColumn();
}

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

// get the type of the item
function mlibrary_new_display_exhibit_type_of_item($rawAttachment)
{
   $itemType = 'Still Image';

   if ($rawAttachment->getItem()->getItemType()!=null) {
       $itemType = $rawAttachment->getItem()->getItemType()->name;
 }

   return $itemType;
}

//
function mlibrary_new_get_image_card($rawAttachment = null, $alt = '') 
{
   $default_image = img("defaulthbg.jpg");
   $page_image = "<img class='image-card' alt='".$alt."' src='{$default_image}'/>";
   if (!empty($rawAttachment)) {
       if ((mlibrary_new_display_exhibit_type_of_item($rawAttachment) == 'Video')) {
           $page_image = mlibrary_new_exhibit_builder_video_attachment($rawAttachment->getItem(),$alt);
        } else {
           $page_image = record_image($rawAttachment->getFile(),'original',array('class' => 'image-card','alt'=>$alt));
        }
   } 
   return $page_image;
}


function mlibrary_new_get_page_description($blocks) {
  if (empty($blocks)) {
    return '';
  }
  return get_view()->shortcodes(snippet_by_word_count(metadata($blocks[0], 'text',['no_escape' => true]),20,'..'));
}


function mlibrary_new_display_exhibit_card_info($exhibitPage)
{  
  $rawAttachment = $exhibitPage->getAllAttachments();
  $rawAttachment_reset = reset($rawAttachment);
  
  return [
    'image'       => mlibrary_new_get_image_card($rawAttachment_reset),
    'title'       => get_view()->shortcodes(metadata($exhibitPage, 'title')),
    'description' => mlibrary_new_get_page_description($exhibitPage->getPageBlocks()),
  ];
}

//gallery
function mlibrary_new_get_image_for_gallery($attachment = null)
{
  $alt_text = mlibrary_new_alt_text($attachment->getItem());
  return mlibrary_new_get_image_card($attachment,$alt_text);
}


function mlibrary_new_create_card_for_gallery($attachment, $slug)
{
   $item_link = exhibit_builder_exhibit_item_uri($attachment->getItem());
   set_current_record('exhibit_page',$slug);
   $url_item = $item_link.'?'.http_build_query(mlibrary_new_exhibit_item_query_string_settings());
   return [
     'image' => mlibrary_new_get_image_for_gallery($attachment),
     'url' => $url_item,
   ];
}

function mlibrary_new_render_gallery_section($sectionpage_cards_info){
 return array_map(function ($sectionpage_card_info) {
   return '<div class="exhibit-gallery-theme-item panel panel-default">'.
     '<a href='.$sectionpage_card_info["url"].'>'. '<div class="panel-heading">'.$sectionpage_card_info["image"].'</div>'.'</a>'
     . '</div>';
 }, $sectionpage_cards_info);
}

function mlibrary_new_get_cards_in_section_gallery($attachments = null, $slug = null){
  $sectionpage_card_info = array();
  if (!empty($attachments)) {
     foreach ($attachments as $attachment) {
            $sectionpage_card_info[] = mlibrary_new_create_card_for_gallery($attachment, $slug);
     }
  }
  return mlibrary_new_render_gallery_section($sectionpage_card_info);
}


function mlibrary_new_exhibit_builder_display_random_featured_exhibit(){
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

// Used in items/show.php and exhibits/item.php
function mlibrary_new_display_video() {
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
// Used in items/show.php
function mlibrary_new_metadata($item){
  $html = '';
  $item = get_current_record('item');

  $elementInfos = array(
    array('Dublin Core', 'Creator','Creator'),
    array('Dublin Core', 'Date','Date'),
    array('Dublin Core', 'Source','Item Source'),
    array('Dublin Core', 'Description','Description'),
    array('Dublin Core', 'Identifier','Source'),    
    array('Dublin Core', 'Publisher','Publisher'),
    array('Dublin Core', 'Contributor','Contributor'),
    array('Dublin Core', 'Rights','Right'),  
    array('Dublin Core', 'Relation','Relation'), 
    array('Dublin Core', 'Format','Format'),  
    array('Dublin Core', 'Language','Language'),
    array('Dublin Core', 'Type','Type'),
  );

  foreach($elementInfos as $elementInfo) {
    $elementSetName = $elementInfo[0];
    $elementName = $elementInfo[1];
    $labelName = $elementInfo[2];
    $elementTexts = metadata(
      'item',
      array($elementSetName, $elementName),
      array('no_escape' => true, 'all' => true)
    );

    if (!empty($elementTexts)) {
        $html .= '<dt>' . $labelName . '</dt>';

      foreach($elementTexts as $elementText) {
	$data = ($elementName == 'Identifier' && (filter_var($elementText, FILTER_VALIDATE_URL))) ? '<a href="' . $elementText . '">View Source</a>' : $elementText;
        $html .= '<dd>' . $data . '</dd>';
      }
    }
   } 
  return $html;
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
function mlibrary_new_display_rss($feedUrl, $num = 3) {
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

function mlibrary_new_alt_text($item) {
     return (strip_formatting(metadata($item,array('Dublin Core', 'Title'))));
}


/**
 * Retrieve a thumnail image for a video item type
 *  It is not used in this installation, but it can be used in the future.
 **/
function mlibrary_new_exhibit_builder_video_attachment($item,$alt) {
$remove[] = "'";
	$elementids_youtube_video = metadata($item, array('Item Type Metadata', 'Video_embeded_code'), array('no_escape'=>true,'all'=>true));
	$elementvideos_kultura_VCM = metadata($item, array('Item Type Metadata', 'video_embeded_code_VCM'),array('no_escape'=>true, 'all'=>true));
        if (!empty($elementids_youtube_video)) {
		foreach ($elementids_youtube_video as $elementid_youtube_video) {
			$videoid = str_replace($remove, "", $elementid_youtube_video);
			if (!empty($videoid)) {
				$video_gallery_image = "<img class='image-card' src='//i.ytimg.com/vi/".$videoid."/maxresdefault.jpg' alt=$alt>";
			}
		}
        }//if
        elseif (!empty($elementvideos_kultura_VCM)) {
  	        $data = $elementvideos_kultura_VCM[0];
		preg_match('/\/entry_id\/([a-zA-Z0-9\_]*)?/i', $data, $match);
                $partnerId = 1038472;
                $video_gallery_image = '<img class="image-card" src="//cdn.kaltura.com/p/'.$partnerId.'/thumbnail/entry_id/'.$match[1].'/width/400/height/400/type/1/quality/100/"/>';
        }//if
  return $video_gallery_image;
}

/**
 * This function will attach item of type video to Exhibit builder out of the box layouts,
 * this filter is used at Exhibit builder
 **/
add_filter('exhibit_attachment_markup', 'mlibrary_new_exhibit_builder_attachment');
function mlibrary_new_exhibit_builder_attachment($html, $compact) {
 $remove[] = "'";
  $elementids = "";
  $elementvideos_VCM = "";
  $gallery_image = false;
  $exhibitPage = get_current_record('exhibit_page', false);
                 if ($exhibitPage->layout != 'mlibrary-custom-layout') {
                        $item = $compact['attachment']->getItem();
                        if (($item !== null) and (!empty($item->getItemType()))) {
                            $item_type = $item->getItemType();
                              if (($item_type['name'] =='Video')) {
                                $alt_text = mlibrary_new_alt_text($item); 
                                 $video_gallery_image = mlibrary_new_exhibit_builder_video_attachment($item, $alt_text);
                                 $html = exhibit_builder_link_to_exhibit_item($video_gallery_image,'',$item); 
                                    if (!empty($compact['attachment']['caption'])) {
                                        $html .= $compact['attachment']['caption'];
                                    }
                              }
                        }
                        // Add a query string to then end of the href so we know which exhibit you came from
                        $html = mlibrary_new_add_vars_to_href(
                                  $html,
                                  mlibrary_new_exhibit_item_query_string_settings()
                                );
                 }
  return $html;
}

/**
 * A helped function that takes a string, finds the "href" attribute in it,
 * and appends variables to the end
 */
function mlibrary_new_add_vars_to_href($html, $variables) {
  return preg_replace(
    '/href=["\']([^"\']*)/',
    'href="$1?' . http_build_query($variables),
    $html
  );
}


/**
 * Function to return common settings for exhibit item link query strings
 * called by mlibrary_exhibit_builder_attachment
 */
function mlibrary_new_exhibit_item_query_string_settings() {
  return [ 'exhibit' => get_current_record('exhibit_page')->exhibit_id,
           'page'    => get_current_record('exhibit_page')->id ];
}

/**
* function to build anchor link for subsections instead of url
*
*/
function mlibrary_new_exhibit_builder_child_page_summary ($exhibitPage = null, $current_page=null) {
  return '<li>'
           . '<a href="' .'#'.$exhibitPage['slug']. '">'
           . metadata($exhibitPage, 'title') .'</a>';
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
       $html .= mlibrary_new_exhibit_builder_child_page_summary($child,$current_page);
        release_object($child);
    }
    $html .= '</ul>';
  }
  $html .= '</li>';
  return $html;
 }



