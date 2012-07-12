<?php
// Use this file to define customized helper functions, filters or 'hacks' defined
// specifically for use in your Omeka theme. Note that helper functions that are
// designed for portability across themes should be grouped into a plugin whenever
// possible.


/**
 * Custom function to retrieve any number of random featured items.
 * via Jeremy Boggs
 * This functionality will likely be incorporated into future versions of Omeka (1.4?)
 * @param int $num The number of random featured items to return
 * @param boolean $withImage Whether to return items with derivative images. True by default.
 */

 
 
function mlibrary_get_random_featured_items($num = '10', $withImage = true)
{
    // Get the database.
    $db = get_db();

    // Get the Item table.
    $table = $db->getTable('Item');

    // Build the select query.
    $select = $table->getSelect();
    $select->from(array(), 'RAND() as rand');
    $select->where('i.featured = 1');
    $select->order('rand DESC');
    $select->limit($num);

    // If we only want items with derivative image files, join the File table.
    if ($withImage) {
        $select->joinLeft(array('f'=>"$db->File"), 'f.item_id = i.id', array());
        $select->where('f.has_derivative_image = 1');
    }

    // Fetch some items with our select.
    $items = $table->fetchObjects($select);

    return $items;
}

// initialize Awkward Gallery on homepage
// AwkwardGallery is jQuery must use HTML that looks like this...
//
// <div id="showcase" class="showcase">
//	<div>
//		<img src="IMAGE.JPG" alt="IMAGE" />
//		<div class="showcase-thumbnail">
//			<img src="IMAGE.JPG" alt="IMAGE" width="140px" />
//			<div class="showcase-thumbnail-caption">THUMB CAPTION</div>
//			<div class="showcase-thumbnail-cover"></div>
//		</div>
//		<div class="showcase-caption">
//			<a href=""><h3>OVERLAY TITLE</h3></a><br/><p>OVERLAY DESCRIPTION</p>
//		</div>
//	</div>
// </div>



function mlibrary_display_awkward_gallery(){
		//this loops the most recent featured items
		$items = mlibrary_get_random_featured_items(10);
		if ($items!=null) 
		{
		set_items_for_loop($items);
		while(loop_items()):
	
			$index = 0; 
			while ($file = loop_files_for_item()):
			    if ($file->hasThumbnail()):
			    //this makes sure the loop grabs only the first image for the item 
			        if ($index == 0): 
			           //item_file('fullsize uri') broke in Omeka version 1.3, so I use getWebPath instead...
		    	       echo '<div><img src="'.$file->getWebPath('fullsize').'" alt="" title=""/>'; 
		    	    endif;
			    endif; 
			endwhile;
			
			echo '<div class="showcase-caption">';
			echo /*Item Title and Link*/'<h3>'.link_to_item().'</h3>';
			echo /*Item Description Excerpt*/'<p>'.item('Dublin Core', 'Description',array('snippet'=>190));
			echo /*Link to Item*/ link_to_item(' ...more ').'</p></div></div>';
			//echo '</div></div>';
			
			endwhile; 
}else 
			{
        	//echo'<div><img src="'.uri('').'/themes/deco/images/emptyslideshow.png" alt="Oops" /><div class="showcase-caption"><h3>UH OH!</h3><br/><p>There are no featured images right now. You should turn off "Display Slideshow" in the theme settings until you have some.</p></div></div>';
    		}}


function mlibrary_awkward_gallery(){
		$awkward_gallery_setting=get_theme_option('Featured Image Gallery') ? get_theme_option('Featured Image Gallery') : 'yes';
		if ($awkward_gallery_setting == 'yes'){ mlibrary_display_exhibit_gallery();
} else{
	echo '<style>#showcase,.showcase, h2.awkward{display:none; visibility:hidden;}</style>';
}
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



function mlibrary_display_exhibit_gallery($exhibits_array=array()){
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
            echo '<div class="showcase-caption">';
            echo /*Exhibit Title and Link*/'<h3><a href="'.$exhibit->slug.'">'.$exhibit->title.'</a></h3>';
            echo /*Exhibit Description Excerpt*/'<p>'.snippet($exhibit->description, 0, 300,exhibit_builder_link_to_exhibit($exhibit, '<br/>...more')).'';
           echo '</p></div></div>';               
    }
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
	
	/* if (!$styleSheet) {
        
        $styleSheet = get_theme_option('Style Sheet') ? 
        get_theme_option('Style Sheet') : 
        'greenstripe';
    } */
	
	/* set stylesheet to omeka-mibrary default */
	$styleSheet = 'omeka-mlibrary';
 // $styleSheet = 'custom';
    
    return $styleSheet; 
    
}
/**
 * This function returns the tagline for the theme.  
 *
 **/

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
 * This function returns the homepage about text for the theme.  
 *
 **/

function mlibrary_get_about($about = null)
{    
    if (!$about) {
        
        $about = get_theme_option('About') ? 
        get_theme_option('About') : 
        'Add some text about your site in theme options. You can use HTML!';
    }
    
    return $about; 
    
}
/**
 * This function returns the number of recent items to display on the homepage for the theme.  
 *
 **/
function mlibrary_get_recent_number($recentItems = null)
{    
    if (!$recentItems) {
        
        $recentItems = get_theme_option('Recent Items') ? 
        get_theme_option('Recent Items') : 
        '5';
    }
    
    return $recentItems; 
    
}
/**
 * This function returns the theme credits settings, displayed in the footer for the theme.  
 *
 **/

function mlibrary_display_theme_credit(){
		$theme_credit=get_theme_option('Theme Credit');
		$credit_text=' | <a href="http://jeffersonsnewspaper.org/2010/deco-an-omeka-theme/" title="Deco theme">Deco theme</a> by <a href="http://twitter.com/ebellempire/" title="@ebellempire">E. Bell</a>';
		if ($theme_credit == 'yes')return $credit_text;
}
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

/**
 * This function returns the FancyBox (lightbox) settings for the theme
 * if the user has not turned them off in theme settings
 *
 **/
 // comment by nancy bc i added the deco_lightbox to gave an option for zoomfi or fancy or none
/*function deco_fancy_box(){
		$fancybox_setting=get_theme_option('Fancy Box');
		if ($fancybox_setting == 'yes')
{    echo js('fancybox/fancybox-init-config');}
 // echo js('fancybox/fancybox-init-config');
   // echo js('fancybox/fancybox-init-config')
		}*/

    
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
	$header_setting=get_theme_option('Header Banner');
	switch($header_setting){
		case "header_1":
			$output = "<h1 class='header01'>".html_escape(exhibit('title'))."</h1>";
			break;
		case "header_2":
			$output = "<h1 class='header02'>".html_escape(exhibit('title'))."</h1>";
			break;
		case "header_3":
			$output = "<h1 class='header03'>".html_escape(exhibit('title'))."</h1>";
			break;
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
   // echo js('mlibrary_omeka');
   }
    
    elseif ($lightbox_setting == 'zoomfi')
    echo "zoomfi code";
    
    }

/**
 * This function returns the random featured collection settings for the theme.  
 *
 **/
//this is the function that toggles the Collection Thumbs
function mlibrary_collection_thumbs_number(){
		$collection_thumbs_setting=get_theme_option('Collection Thumbs');
		if ($collection_thumbs_setting == 'yes'){    
			echo '<div id="index-collection-img">';
    	    while (loop_items_in_collection(4)):
			echo link_to_item((item_square_thumbnail()), array('item' => item('id')));
			endwhile;
			echo'</div>';
		} 
}
		
function mlibrary_random_featured_collection(){
			$collection = random_featured_collection();
			set_current_collection($collection);
			if ($collection) {
			echo '<h2>'."Featured Collection".'</h2>';
			echo '<h3>'.link_to_collection().'</h3>';
			echo '<p>'.collection('Description').'</p>';
			mlibrary_collection_thumbs_number();
			} else 
			{
        	echo'<p><em>There are no featured collections right now. You should turn off "Display Featured Collections" in the theme settings until you have some.</em></p>';
    		}
}
//this is the function that is actually used on homepage...
function mlibrary_display_random_featured_collection(){
		$random_featured_collection_setting=get_theme_option('Random Featured Collection') ? get_theme_option('Random Featured Collection') : 'yes';
		if ($random_featured_collection_setting == 'yes')return mlibrary_random_featured_collection();
}
function mlibrary_custom_docs_viewer_placement(){
	if (class_exists('DocsViewerPlugin')&&(!get_option('docsviewer_embed_public'))):
	$docsViewer = new DocsViewerPlugin;
    $docsViewer->embed();
	endif;
}
function mlibrary_docs_viewer_placement(){
	$docs_viewer_placement=get_theme_option('Docs Viewer Placement');
	if ($docs_viewer_placement=='yes') return mlibrary_custom_docs_viewer_placement();
}
// this function uses Zend_Feed to fetch and display an RSS or Atom feed
// example usage, to display one post from omeka.org --> echo deco_display_rss('http://omeka.org/feed/',1) 
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