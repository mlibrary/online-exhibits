<?php
// Use this file to define customized helper functions, filters or 'hacks' defined
// specifically for use in your Omeka theme. Note that helper functions that are
// designed for portability across themes should be grouped into a plugin whenever
// possible.

function mlibrary_get_tagline($tagline = null)
{    
    if (!$tagline) {
        
        $tagline = get_theme_option('Tagline') ? 
        get_theme_option('Tagline') : 
        'Add a tagline for your site in theme options';
    }
    
    return $tagline; 
    
}

/*function mlibrary_awkward_gallery(){
		$awkward_gallery_setting=get_theme_option('Featured Image Gallery') ? get_theme_option('Featured Image Gallery') : 'yes';
		if ($awkward_gallery_setting == 'yes'){ mlibrary_display_exhibit_gallery();
} else{
	echo '<style>#showcase,.showcase, h2.awkward{display:none; visibility:hidden;}</style>';
}
}*/

function mlibrary_display_related_page_in_exhibits()
{
?>
		
  
    <a href="#" onClick ="history.go(-1); return false;">Back to Exhibit Page</a>
  
 <?php
}

//extends featured exhibit function to include snippet from description and read more link
function mlibrary_exhibit_builder_display_random_featured_exhibit()
{
    if (function_exists('exhibit_builder_random_featured_exhibit')){
    $html = '<div id="featured-exhibit">';
    $featuredExhibit = exhibit_builder_random_featured_exhibit();
    $html .= '<h2>Featured Exhibit</h2>';
    if ($featuredExhibit) {
       $html .= '<h3>' . exhibit_builder_link_to_exhibit($featuredExhibit) . '</h3>';
       $html .= '<p>' . snippet($featuredExhibit->description, 0, 500,exhibit_builder_link_to_exhibit($featuredExhibit, '<br/>...more')) . '</p>';

    } else {
       $html .= '<p>You have no featured exhibits.</p>';
    }
    $html .= '</div>';
    return $html;
} } 



/*function mlibrary_display_exhibit_gallery($exhibits_array=array()){
        $featured_exhibits = exhibit_builder_get_exhibits(array('featured' => true)); 
        $all_exhibits = exhibit_builder_get_exhibits(array('featured' => false)); 
                
        foreach($featured_exhibits as $featured_exhibit){
          $exhibit = exhibit_builder_get_exhibit_by_id($featured_exhibit->id);            
          
          $items = get_items(array('exhibit' => $featured_exhibit->id),1);
 
          $image_found=false;
          $index=0;
          if ($items!=null) //todo: make sure that the item also is an image (non-image will probably break function, or at least slideshow)
          {
            set_items_for_loop($items);            
           while(loop_items()):
        //get exhibit item
            $index = 0;
          
            while ($file = loop_files_for_item()):               
                if ($file->hasThumbnail()):      
//print_r($file);                
                //this makes sure the loop grabs only the first image for the exhibit item
                    if ($index == 0):
                       echo '<div><img src="'.$file->getWebPath('fullsize').'"/>';
                       $featured_exhibit->setThemeOptions('exhibit_image','58e05a3c0f653cfa234a8e9b2254341b.jpg');
                       echo $featured_exhibit->getThemeOptions();
                       $index++;
                    endif;
                endif;
            endwhile;
        endwhile;         
            }
            echo '<div class="showcase-caption">';*/
            //echo /*Exhibit Title and Link*/'<h3><a href="'.$exhibit->slug.'">'.$exhibit->title.'</a></h3>';
           //echo /*Exhibit Description Excerpt*/'<p>'.snippet($exhibit->description, 0, 300,exhibit_builder_link_to_exhibit($exhibit, '<br/>...more')).'';
           //echo '</p></div></div>';               
    //}
 //}
        


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
	
	/* if (!$styleSheet) {
        
        $styleSheet = get_theme_option('Style Sheet') ? 
        get_theme_option('Style Sheet') : 
        'greenstripe';
    } */
	
	/* set stylesheet to omeka-mibrary default */
	$styleSheet = 'omeka-mlibrary';
    
    return $styleSheet; 
    
}

/**
 * This function returns the homepage about text for the theme.  
 *
 **/

/*function mlibrary_get_about($about = null)
{    
    if (!$about) {
        
        $about = get_theme_option('About') ? 
        get_theme_option('About') : 
        'Add some text about your site in theme options. You can use HTML!';
    }
    
    return $about; 
    
}*/
/**
 * This function returns the number of recent items to display on the homepage for the theme.  
 *
 **/
/*function mlibrary_get_recent_number($recentItems = null)
{    
    if (!$recentItems) {
        
        $recentItems = get_theme_option('Recent Items') ? 
        get_theme_option('Recent Items') : 
        '5';
    }
    
    return $recentItems; 
    
}*/
/**
 * This function returns the theme credits settings, displayed in the footer for the theme.  
 *
 **/

/*function mlibrary_display_theme_credit(){
		$theme_credit=get_theme_option('Theme Credit');
		$credit_text=' | <a href="http://jeffersonsnewspaper.org/2010/deco-an-omeka-theme/" title="Deco theme">Deco theme</a> by <a href="http://twitter.com/ebellempire/" title="@ebellempire">E. Bell</a>';
		if ($theme_credit == 'yes')return $credit_text;
}*/
/**
 * This function returns the related exhibit settings for the theme.  
 *
 **/

//defining the function used to show related exhibits in items/show.php (via omeka.org)
//this could be improved to take into account items that are used multiple times in the same exhibit, which right now causes a redundant link
// Nancy add group by to prevent the redundant.

function mlibrary_display_items_in_exhibit_page($item)
{
	//require_once "Exhibit.php"; 
	//$listofitems = new array();

	$db = get_db();

	$table = $db->getTable('Items_Section_Pages');

    // Build the select query.
    $select = $table->getSelect();
	/*$select = "	
	SELECT isp.page_id FROM {$db->prefix}items_section_pages isp 
	WHERE isp.item_id = ?";*/
	
	//$page = $db->getTable("Items_Section_Pages")->fetchObjects($select,array($item));
	

return $select;
}

function mlibrary_link_to_related_exhibits($item) {
	require_once "Exhibit.php"; 
	$db = get_db();

	$select = "
	SELECT e.* FROM {$db->prefix}exhibits e
	INNER JOIN {$db->prefix}sections s ON s.exhibit_id = e.id
	INNER JOIN {$db->prefix}section_pages sp on sp.section_id = s.id
	INNER JOIN {$db->prefix}items_section_pages isp ON isp.page_id = sp.id
	WHERE isp.item_id = ? group by e.id";

	$exhibits = $db->getTable("Exhibit")->fetchObjects($select,array($item));
  
  

	if(!empty($exhibits)) {
		echo '<h3>Related Exhibits</h3>';
		echo '<ul>';
		foreach($exhibits as $exhibit) {
			echo '<li>'.exhibit_builder_link_to_exhibit($exhibit).'</li>';
		}
		echo '</ul>';
	}
}
//this is the function that is actually used on items/show...
function mlibrary_display_related_exhibits(){
		$related_exhibits_setting=get_theme_option('Related Exhibits');
		if ($related_exhibits_setting == 'yes')return mlibrary_link_to_related_exhibits(item('ID'));
}


    
 function mlibrary_exhibit_image() {
$exhibit_image_setting=get_theme_option('Exhibit Image');
return $exhibit_image_setting;
}

 function mlibrary_exhibit_audio() {
$exhibit_audio_setting=get_theme_option('Exhibit Audio');
return $exhibit_audio_setting;
}


 function mlibrary_exhibit_video() {
$exhibit_video_setting=get_theme_option('Exhibit Video');

return $exhibit_video_setting;
}

/**
 * This function returns the Header Image based on selection in Exhibit Theme Configurations 
 *
 **/

function mlibrary_header_banner() {
	$header_banner=get_theme_option('Header Banner');
	$header_text = get_theme_option('Header Text');
	if($header_banner){
		$output = "<h1 style='background:url(".CURRENT_BASE_URL.'/archive/theme_uploads/'.$header_banner.")'>";
	} else {
		$output = "<h1 class='default'>";
	}
	if($header_text == 'yes' || !$header_text){
		$output .= "<p>".html_escape(exhibit('title'))."</p></h1>";
	} else {
		$output .= "</h1>";
	}
	return $output;
}

function mlibrary_pages_in_section() {
	$pages_setting=get_theme_option('Pages In Section');
	switch($pages_setting){
		case "pages_1":
			$output = "true";
			break;
		case "pages_2":
			$output = "close";
			break;	
	}
	return $output;
}
    
    
 function mlibrary_light_box(){
      $lightbox_setting=get_theme_option('Light Box');
		if ($lightbox_setting == 'fancy')
    { echo js('fancybox/fancybox-init-config');
    return lightbox_setting;
   // echo js('mlibrary_omeka');
   }
   else
   return lightbox_setting;
   
    
    }

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

//Nancy

add_filter('theme_options', 'mlibrary_exhibit_builder_theme_options');

function mlibrary_exhibit_builder_theme_options($themeOptions, $themeName) {
//$themeOptions ='test';
 return $themeOptions;
}

add_filter('exhibit_builder_exhibit_display_item', 'mlibrary_exhibit_builder_exhibit_display_item');

function mlibrary_exhibit_builder_exhibit_display_item($html, $displayFilesOptions, $linkProperties, $item) {
$remove[] = "'";
$remove[] = '"';
$remove[] = " ";
  if ((item_has_type('Sound', $item)) and ($displayFilesOptions['imageSize']=='thumbnail')){      
       $html = exhibit_builder_link_to_exhibit_item("<img src='".img('sound-icon.jpg')."'/>");   
   }
   elseif ((item_has_type('Video', $item)) and ($displayFilesOptions['imageSize']=='thumbnail')) {          
        $elementids = item('Item Type Metadata', 'Video_embeded_code', array('no_escape'=>true,'all'=>true));         
        foreach ($elementids as $elementid) { 
            $videoid = str_replace($remove, "", $elementid);               
            if ((!empty($videoid)) and ($thumnail_image!=true)){                
                $image = "<img src='http://i4.ytimg.com/vi/".$videoid."/default.jpg' style='width:200px; height:128px'/>";             
                $thumnail_image=true;
            }
        }
       $html = exhibit_builder_link_to_exhibit_item($image);   
   }
   
  

   return $html;
}

add_filter('custom_public_nav_header', 'mlibrary_custom_public_nav_header');

function mlibrary_custom_public_nav_header()
{        
        $navArray = array('Browse Exhibits' => uri('exhibits'));
        return public_nav_main($navArray);
    
}




//Nancy
add_filter('exhibit_builder_display_exhibit_thumbnail_gallery', 'mlibrary_exhibit_builder_display_exhibit_thumbnail_gallery');

function mlibrary_exhibit_builder_display_exhibit_thumbnail_gallery($html, $start, $end, $props, $thumbnailType) {

$remove[] = "'";
$remove[] = '"';
$remove[] = " ";
 
    $html = '';
    for ($i=(int)$start; $i <= (int)$end; $i++) { 
        if ((exhibit_builder_use_exhibit_page_item($i)) and (item_has_type('Sound', $item)))
        {                  
           $html .= "\n" . '<div class="exhibit-item">';   
           $html .= exhibit_builder_link_to_exhibit_item("<img src='".img('sound-icon.jpg')."'/>");
           $html .= exhibit_builder_exhibit_display_caption($i);
           $html .= '</div>' . "\n";
        }
        // create thumbnail for video from youtube
        elseif ((exhibit_builder_use_exhibit_page_item($i)) and (item_has_type('Video', $item)))
        {
           $thumnail_image=false;
           $html .= "\n" . '<div class="exhibit-item">';              
           $elementids = item('Item Type Metadata', 'Video_embeded_code', array('no_escape'=>true,'all'=>true));
          
            foreach ($elementids as $elementid) {           
              $videoid = str_replace($remove, "", $elementid);                
              if ((!empty($videoid)) and ($thumnail_image!=true)){                
                $image = "<img src='http://i4.ytimg.com/vi/".$videoid."/default.jpg' style='width:200px; height:128px'/>";             
                $thumnail_image=true;
              }
            }           
           $html .= exhibit_builder_link_to_exhibit_item($image);
           $html .= exhibit_builder_exhibit_display_caption($i);
           $html .= '</div>' . "\n";
        }
        elseif ((exhibit_builder_use_exhibit_page_item($i)) and (!item_has_type('Sound', $item)))
        {
            $html .= "\n" . '<div class="exhibit-item">';
            $thumbnail = item_image($thumbnailType, $props);
            $html .= exhibit_builder_link_to_exhibit_item($thumbnail);
            $html .= exhibit_builder_exhibit_display_caption($i);
            $html .= '</div>' . "\n";
        
        }
    }
    return $html;
   
}