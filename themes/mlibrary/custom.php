<?php
// Use this file to define customized helper functions, filters or 'hacks' defined
// specifically for use in your Omeka theme. Note that helper functions that are
// designed for portability across themes should be grouped into a plugin whenever
// possible.

add_filter('neatlinetime_display_search_query', 'mlibrary_neatlinetime_display_search_query');

function mlibrary_neatlinetime_display_search_query($neat_line_exhibit)
{
		if (function_exists('exhibit_builder_get_exhibit_by_id')) {
        $exhibit = exhibit_builder_get_exhibit_by_id($neat_line_exhibit);
        }
    $slug = $exhibit->slug;
    $title = $exhibit->title;

    $html = '<div id="timeline-exhibit">';
    $html .='<a href=https://nancymou.www.lib.umich.edu/online-exhibits/exhibits/show/'.$slug.'><----'.$title.'</a>';
    $html .= '</div>';
   return $html;
}

add_filter('exhibit_builder_generate_xml', 'mlibrary_exhibit_builder_generate_xml');

function mlibrary_exhibit_builder_generate_xml($xml)
{
	//$xml = generate_xml();
	
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

/*function mlibrary_awkward_gallery(){
		$awkward_gallery_setting=get_theme_option('Featured Image Gallery') ? get_theme_option('Featured Image Gallery') : 'yes';
		if ($awkward_gallery_setting == 'yes'){ mlibrary_display_exhibit_gallery();
} else{
	echo '<style>#showcase,.showcase, h2.awkward{display:none; visibility:hidden;}</style>';
}
}*/

function mlibrary_display_back_button_item_page($page_type){
switch ($page_type)
{
	case 'exhibit':
    	echo '<a href="#" onClick ="history.go(-1); return false;">Back to Exhibit Page</a>';
    	break;
	case 'item':
    	echo '<a href="#" onClick ="history.go(-1); return false;">Back to Item Archive Page</a>';
    	break;
	case 'gallery':
    	 echo '<a href="#" onClick ="history.go(-1); return false;">Back to Gallery Page</a>';
    	 break;
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
		echo '<div class="element"><h2>Related Exhibits</h2>';
		echo '<ul>';
		foreach($exhibits as $exhibit) {
			echo '<li>'.exhibit_builder_link_to_exhibit($exhibit).'</li>';
		}
		echo '</ul></div>';
	}
}
//this is the function that is actually used on items/show...
function mlibrary_display_related_exhibits(){
		$related_exhibits_setting=get_theme_option('Related Exhibits');
		if ($related_exhibits_setting == 'yes')return mlibrary_link_to_related_exhibits(item('ID'));
}
   
/* function mlibrary_exhibit_image($currentexhibit=null) {
//$exhibit_image_setting=get_theme_option('Exhibit Image');
if($currentexhibit==null):
	 $currentexhibit = get_current_exhibit();
endif;
	 
$item_found=false;
$items = get_items(array('exhibit' => $currentexhibit['id']));
		       if ($items!=null)
        		{
	  	        	set_items_for_loop($items);
              
	  	             while(loop_items()):
                     if ($item_found!=true){
        				//get exhibit item		            	
                  $index = 0;
			           	while (loop_files_for_item()):
        		    		$file = get_current_file();
                   
        		    		if ($file->hasThumbnail()):                
    	                		if ($index == 0):
                        
//        	               			echo '<img src="'.$file->getWebPath('square_thumbnail').'"/>';
//        	               			$exhibit_image_setting[0]['image'] = $file->getWebPath('thumbnail');
  //     	               			  $exhibit_image_setting[0]['title'] = item('Dublin Core','Title');
       	               			 // $exhibit_image_setting = array('image'=>'https://test.www.lib.umich.edu/online-exhibits/archive/'.$file->getStoragePath('fullsize'),'title'=>item('Dublin Core','Title'));
 $exhibit_image_setting = array('image'=>'/'.$file->getStoragePath('fullsize'),'title'=>item('Dublin Core','Title'));
       	               			  
       	               			//         	               			  $exhibit_image_setting = array('image'=>$file->getPath('fullsize'),'title'=>item('Dublin Core','Title'));
       	               			            	           			$index++;
                                                          $item_found=true;
                	    		endif;
                    		endif;
		               endwhile;
                     }
        		    endwhile;
              
        		}
return $exhibit_image_setting;
}*/

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
		$output = "<h1 class='default' style='background:url(".CURRENT_BASE_URL.'/archive/theme_uploads/'.$header_banner.")'>";
	} else {
		$output = "<h1 class='default'>";
	}
	if($header_text == 'yes' || !$header_text){
		$output .= "<span>".html_escape(exhibit('title'))."</span></h1>";
	} else {
		$output .= "</h1>";
	}
	return $output;
}

function mlibrary_pages_in_section() {
	$pages_setting=get_theme_option('Pages In Section');
$output="true";
  //print_r($pages_setting);
	switch($pages_setting){
		case "pages_1":
			$output = "true";
			break;
		case "pages_2":
			$output = "close";
			break;	
		case "pages_3":
			$output = "gallery";
			break;	
	}
	return $output;
}
 
 
function mlibrary_exhibit_gallery() {
	$pages_setting=get_theme_option('View Items In Gallery');
$output="exhibit";	
switch($pages_setting){
		case "pages_1":
			$output = "exhibit";
			break;
		case "pages_2":
			$output = "gallery";
			break;		
	}
	return $output;
}   
    
    
 function mlibrary_light_box(){
      $lightbox_setting=get_theme_option('Light Box');
	if ($lightbox_setting == 'fancy'){ 
    	echo js('fancybox/fancybox-init-config');
    	return $lightbox_setting;
   // echo js('mlibrary_omeka');
   }
   else {
       echo js('fancybox/fancybox-init-config');
   		return 'fancy';
   }
   
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

//add_filter('theme_options', 'mlibrary_exhibit_builder_theme_options');

/*function mlibrary_exhibit_builder_theme_option($exhibit) {
//$themeOptions ='test';
//$exhibit
 return exhibit;
}*/


/*function set_theme_option_with_image(){
$image_object=array();
$currentexhibit = get_current_exhibit();
$data = unserialize($currentexhibit->theme_options);
$image_object = mlibrary_exhibit_image($currentexhibit);

if (!empty($image_object['image'])){
	$data[mlibrary]['exhibitimage']= $image_object;//mlibrary_exhibit_image($currentexhibit);
	$currentexhibit->theme_options = serialize($data);
//print_r(unserialize($currentexhibit->theme_options));
	$currentexhibit->save();
}
//return $currentexhibit->theme_options;
}*/


add_filter('exhibit_builder_exhibit_display_item', 'mlibrary_exhibit_builder_exhibit_display_item');

function mlibrary_exhibit_builder_exhibit_display_item($html, $displayFilesOptions, $linkProperties, $item) {

$remove[] = "'";
$remove[] = '"';
$remove[] = " ";

$exhibitPage = exhibit_builder_get_current_page();
   if ($exhibitPage->layout!='mlibrary-custom-layout'){     
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
   }
   else {  
// This layout (custom layout) expect images of files attached to the item. the loop will go through the files. if no files exist then there is no data displayed.
   		  // 	$filename = basename($file->archive_filename,'.jpg');	

          	while (loop_files_for_item()):

          		$file = get_current_file();	
		          // check to see if it is the first file in first item
                if (($file->hasThumbnail()) and ($displayFilesOptions['imageorder']==1) and ($firstimage!='true')){
					$filename = basename($file->archive_filename,'.jpg');	                   	                 	            	        
					$htmlimage['id'.$file->id]['archive'] = $item->id;   
					if (!preg_match('~^https?://~i',  item('Dublin Core','Identifier')))
				    	 $htmlimage['id'.$file->id]['fulltext']='';
			    	 else
			    	 	  $htmlimage['id'.$file->id]['fulltext'] = item('Dublin Core','Identifier'); 	
			   	$htmlimage['id'.$file->id]['creator'] = $displayFilesOptions['creator'];
        	        $htmlimage['id'.$file->id]['description'] = $displayFilesOptions['description'];
        		    $htmlimage['id'.$file->id]['title'] = $displayFilesOptions['title'];
        		    $htmlimage['id'.$file->id]['date'] = $displayFilesOptions['year'];
        		    $firstimage='true';
        		    
	            	if(file_exists('archive/zoom_tiles/'.$filename.'_zdata')){   											
					$htmlimage['id'.$file->id]['image'] = '<div class="zoom id'.$file->id.' exhibit-item"><OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="100%" HEIGHT="450" ID="theMovie">
					<PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata">
					<PARAM NAME="MENU" VALUE="FALSE">
					<PARAM NAME="SRC" VALUE="'.uri('').'themes/mlibrary/javascripts/ZoomifyViewer.swf">
					<param NAME=wmode VALUE=opaque> 
					<EMBED FlashVars="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata" SRC="'.uri('').'themes/mlibrary/javascripts/ZoomifyViewer.swf" wmode=opaque MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="100%" HEIGHT="450" NAME="theMovie"></EMBED></OBJECT></div>';
					}
        		    else{        		                         
		            $htmlimage['id'.$file->id]['image'] = "\n" . '<div class="fullsize id'.$file->id.' exhibit-item">';   
        			$htmlimage['id'.$file->id]['image'] .= display_file($file, array('imageSize'=>'fullsize','imgAttributes'=>array('alt'=>item('Dublin Core', 'Title')),'linkAttributes'=>array('class'=>'fancyitem','title' => item('Dublin Core', 'Title'))));
	                $htmlimage['id'.$file->id]['image'] .= '</div>' . "\n";		            		                
    	            }            	   
            	}
            // all other files should be hide it
            	else{
            	
	            	if ($file->hasThumbnail()):
	            		$filename = basename($file->archive_filename,'.jpg');	           
		                   	
			        	$htmlimage['id'.$file->id]['archive'] = $item->id;        	 
			        	
			        	 if (!preg_match('~^https?://~i',  item('Dublin Core','Identifier')))
			        	 $htmlimage['id'.$file->id]['fulltext']='';
			        	 else
			         // print_r(item('Dublin Core','Identifier'));
			    	 	//  exit;	
			        	 	 $htmlimage['id'.$file->id]['fulltext'] = item('Dublin Core','Identifier'); 
		        	     
		        	     $htmlimage['id'.$file->id]['title'] = $displayFilesOptions['title'];
		        	     $htmlimage['id'.$file->id]['description'] = $displayFilesOptions['description'];            	     
		     	         $htmlimage['id'.$file->id]['creator'] = $displayFilesOptions['creator'];
			        	 $htmlimage['id'.$file->id]['date'] = $displayFilesOptions['year'];
	            		if(file_exists('archive/zoom_tiles/'.$filename.'_zdata')){ 
							$htmlimage['id'.$file->id]['image'] = '<div class="zoom id'.$file->id.' exhibit-item"><OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="100%" HEIGHT="450" ID="theMovie">
							<PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata">
							<PARAM NAME="MENU" VALUE="FALSE">
							<PARAM NAME="SRC" VALUE="'.uri('').'themes/mlibrary/javascripts/ZoomifyViewer.swf">
							<param NAME=wmode VALUE=opaque> 
							<EMBED FlashVars="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata" SRC="'.uri('').'themes/mlibrary/javascripts/ZoomifyViewer.swf" wmode=opaque MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="100%" HEIGHT="450" NAME="theMovie"></EMBED></OBJECT></div>';
							
							}
						else{
	                		
							$htmlimage['id'.$file->id]['image'] = "\n" . '<div class="fullsize id'.$file->id.' exhibit-item">';   
					 		$htmlimage['id'.$file->id]['image'] .= display_file($file, array('imageSize'=>'fullsize','imgAttributes'=>array('alt'=>item('Dublin Core', 'Title')),'linkAttributes'=>array('class'=>'fancyitem','title' => item('Dublin Core', 'Title'))));
							$htmlimage['id'.$file->id]['image'] .= '</div>' . "\n";
    	    	    	    }		
	               endif;  	
            	}            		                                                 	            	                  
           endwhile;   
           $html=$htmlimage;    
   }
 //  $test= array('210'=>array("id"=>210,"harvest time"=>"April", "files"=>array("222"=>array("id"=>222,"filetitle"=>"nancy"))), '216'=>array("id"=>216));
   // echo '<script type="text/javascript"> var obj ='.json_encode($htmlimage).'</script>';
    //  echo '<script type="text/javascript"> var items ='.json_encode($test).'</script>';
//print_r($html);
  // $html .= $jobject;  
  return $html;
//return $html;
}

add_filter('custom_public_nav_header', 'mlibrary_custom_public_nav_header');

function mlibrary_custom_public_nav_header()
{        
        $navArray = array('Browse Exhibits' => uri('exhibits'));
        return public_nav_main($navArray);
    
}


add_filter('exhibit_builder_nested_nav','mlibrary_exhibit_builder_nested_nav');

function mlibrary_exhibit_builder_nested_nav($html,$exhibit,$showAllPages){
	
	  if (!$exhibit) {
        if (!($exhibit = exhibit_builder_get_current_exhibit())) {
            return;
        }    
    }
	
    $html = '<ul class="exhibit-section-nav">';
    foreach ($exhibit->Sections as $exhibitSection) {
    if (mlibrary_pages_in_section()=='gallery'){
        $html .= '<li class="exhibit-nested-section' . (exhibit_builder_is_current_section($exhibitSection) ? ' current' : '') . '"><a class="exhibit-section-title" href="' . html_escape(exhibit_builder_exhibit_uri($exhibit, $exhibitSection)) . '">' . html_escape($exhibitSection->title) . '  gallery </a>';
        }
        else
                $html .= '<li class="exhibit-nested-section' . (exhibit_builder_is_current_section($exhibitSection) ? ' current' : '') . '"><a class="exhibit-section-title" href="' . html_escape(exhibit_builder_exhibit_uri($exhibit, $exhibitSection)) . '">' . html_escape($exhibitSection->title) . '</a>';
		if (mlibrary_pages_in_section()!='gallery'){
    	    if ($showAllPages || exhibit_builder_is_current_section($exhibitSection)) {
	           $html .= exhibit_builder_page_nav($exhibitSection);	        
    	    }
    	}
        
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;   
}
     
    


//Nancy
add_filter('exhibit_builder_display_exhibit_thumbnail_gallery', 'mlibrary_exhibit_builder_display_exhibit_thumbnail_gallery');

function mlibrary_exhibit_builder_display_exhibit_thumbnail_gallery($html, $start, $end, $props, $thumbnailType) {

$remove[] = "'";
$remove[] = '"';
$remove[] = " ";
 
    $html = '';
    $exhibitPage = exhibit_builder_get_current_page();
    
     if (($exhibitPage->layout!='mlibrary-custom-layout') and ($exhibitPage->layout!='gallery-thumbnails')){
     	// $html .="first";
      	for ($i=(int)$start; $i <= (int)$end; $i++) {      
	        if ((exhibit_builder_use_exhibit_page_item($i)) and (item_has_type('Sound', $item))){                  
    	       $html .= "\n" . '<div class="exhibit-item">';   
        	   $html .= exhibit_builder_link_to_exhibit_item("<img src='".img('sound-icon.jpg')."'/>");
	           $html .= exhibit_builder_exhibit_display_caption($i);
    	       $html .= '</div>' . "\n";
        		}
    	    // create thumbnail for video from youtube
	        elseif ((exhibit_builder_use_exhibit_page_item($i)) and (item_has_type('Video', $item))){
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
    	    elseif ((exhibit_builder_use_exhibit_page_item($i)) and (!item_has_type('Sound', $item))){
    	        $html .= "\n" . '<div class="exhibit-item">';
        	    $thumbnail = item_image($thumbnailType, $props);
            	$html .= exhibit_builder_link_to_exhibit_item($thumbnail);
	            $html .= exhibit_builder_exhibit_display_caption($i);
    	        $html .= '</div>' . "\n";        
	        	}
      	}
      }// if it is not custom-layout or gallerythumbs
      elseif ($exhibitPage->layout=='mlibrary-custom-layout'){
	      $image_index=0;
			// start is 1 and end is 12, this is the way set it up in the new layout or other layout that has thumbnail it can be 
			//changed to something else.
		 $firstthumbnail=false;    
	    for ($i=(int)$start; $i <= (int)$end; $i++) {           
    	//check to see if there is item exist with exhibit_builder_use_exhibit_page_item function
	      if ((exhibit_builder_use_exhibit_page_item($i)) and ((!item_has_type('Sound', $item)) or (!item_has_type('Video', $item)))){        
    	      while (loop_files_for_item()): $file=get_current_file();          
        	  	if ($file->hasThumbnail() and ($firstthumbnail!=true)){
				  $html = "\n" . '<div class="square_thumbnail id'.$file->id.' first exhibit-item"  file_id="id'.$file->id.'">';   
	              $html .= display_file($file, array('imageSize'=>'square_thumbnail','imgAttributes'=>array('alt'=>item('Dublin Core', 'Title')),'linkToFile'=>false));
    	          $html .= '</div>' . "\n";
        	       $image_index++;
            	   $firstthumbnail=true;
	            }          	
    	        else if ($file->hasThumbnail() and ($firstthumbnail==true)){        	
	              $html .= "\n" . '<div class="square_thumbnail id'.$file->id.' exhibit-item"  file_id="id'.$file->id.'">';   
	              $title = $file->title;
	             // if (!empty($title))
	    	       //   $html .= display_file($file, array('imageSize'=>'square_thumbnail', 'linkToFile'=>false));
	    	      //else
	    	        //      $html .= display_file($file, array('imageSize'=>'square_thumbnail', 'linkToFile'=>false, 'imgAttributes'=>array('alt' => 'test')));
	    	   // $html.=$title;
	    	          $html .= display_file($file, array('imageSize'=>'square_thumbnail','imgAttributes'=>array('alt'=>item('Dublin Core', 'Title')),'linkToFile'=>false));
	    	        // if ($fileTitle = item_file('Dublin Core', 'Title', array(), $file)){
	    	       //  $html .= item_image('square_thumbnail','',0,$item);
	    	       
	    	        // }
	    	        // else
	    	        // $html .="nothing";
        	      $html .= '</div>' . "\n";            
	               $image_index++;
    	        }
        	  endwhile;       
		     }      
	    }	
	  } //end of elseif custom-layout
  elseif ($exhibitPage->layout=='gallery-thumbnails'){ 
  	     	for ($i=(int)$start; $i <= (int)$end; $i++) {  
  	     		if (exhibit_builder_use_exhibit_page_item($i)) {
  	 			  	 $item = get_current_item($i);
	        	    //$thumbnail = item_image($thumbnailType, $props);
    	        	//$html .= exhibit_builder_link_to_exhibit_item($thumbnail,array('linkToFile'=>false));
    	        	$html .= "\n" . '<div class="exhibit-item">';
    	        	$html .= display_file($item->Files[0], array('imageSize'=>'square_thumbnail','imgAttributes'=>array('alt'=>item('Dublin Core','Title')),'linkToFile'=>false));
    	        	$html .= '</div>' . "\n";        
    	        	}
	        //    $html .= exhibit_builder_exhibit_display_caption($i);
	            }

	        
    }
   
    return $html;   
}
