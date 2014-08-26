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


function mlibrary_display_back_button_item_page($page_type){
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



//extends featured exhibit function to include snippet from description and read more link
function mlibrary_exhibit_builder_display_random_featured_exhibit()
{
    /*if (function_exists('exhibit_builder_random_featured_exhibit')){
    $html = '<div id="featured-exhibit">';
    $featuredExhibit = exhibit_builder_random_featured_exhibit(3);
   // print_r($featuredExhibit);
    $html .= '<h2>Featured Exhibit</h2>';
    if ($featuredExhibit) {
       $html .= '<h3>' . exhibit_builder_link_to_exhibit($featuredExhibit) . '</h3>';
       $html .= '<p>' . snippet($featuredExhibit->description, 0, 300,exhibit_builder_link_to_exhibit($featuredExhibit, '<br/>...more')) . '</p>';

    } else {
       $html .= '<p>You have no featured exhibits.</p>';
    }
    $html .= '</div>';
    return $html;
}*/

$exhibits=get_records('Exhibit' , array ('featured'=>true),$num=4);
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

/*function mlibrary_display_items_in_exhibit_page($item)
{
	//require_once "Exhibit.php"; 
	//$listofitems = new array();

	$db = get_db();

	$table = $db->getTable('Items_Section_Pages');

    // Build the select query.
    $select = $table->getSelect();

return $select;
}*/


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
				if ($exhibit['title']!='Galleries')
						echo '<li style="padding-bottom: 5px;">'.link_to_exhibit(null, array(), null, $exhibit).'</li>';//exhibit_builder_link_to_exhibit($exhibit).'</li>';
				}
				echo '</ul></div>';
			}
}


//this is the function that is actually used on items/show...
function mlibrary_display_related_exhibits(){
		$related_exhibits_setting = get_theme_option('Related Exhibits');
		if ($related_exhibits_setting == 'yes') {		
		   return mlibrary_link_to_related_exhibits(get_current_record('item')->id);
		}
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
			$output = "<h1 class='default' style='background:url(".CURRENT_BASE_URL.'/files/theme_uploads/'.$header_banner.")'>";
	} else {
			$output = "<h1 class='default'>";
	}
	
	if($header_text == 'yes' || !$header_text){
			$output .= "<span>".html_escape(metadata('exhibit', 'title'))."</span></h1>";
	} else {
			$output .= "</h1>";
	}
	return $output;
}

function mlibrary_light_box(){
  $lightbox_setting=get_theme_option('Light Box');	
	if ($lightbox_setting == 'fancy'){ 
    	echo queue_js_file('fancybox/fancybox-init-config');
    	return $lightbox_setting;
   // echo js('mlibrary_omeka');
   }
   else {
      echo queue_js_file('fancybox/fancybox-init-config');
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


// Omeka 2
add_filter('exhibit_builder_attachment_markup', 'mlibrary_exhibit_builder_attachment_markup');

function mlibrary_exhibit_builder_attachment_markup($html,$compact) {

$remove[] = "'";
$remove[] = '"';
$remove[] = " ";
$elementids = "";
$elementvideos_VCM = "";
$thumnail_image = false;

   $exhibitPage = get_current_record('exhibit_page', false);
   $imageSize = $compact['fileOptions']['imageSize'];
 
   if ($exhibitPage->layout!='mlibrary-custom-layout') {   
	    $item = $compact['attachment']['item'];
      if(!empty($compact['attachment']['item']->getItemType()->name)) 
      {
			    	  $item_type = $compact['attachment']['item']->getItemType()->name;			    	   			    	  
			    	  if ($item_type == 'Sound')
			    	  {
			    	  	 // $html = exhibit_builder_link_to_exhibit_item("<img src='".img('sound-icon.jpg')."'/>");   
			    	  }//if
		      	  elseif (($item_type =='Video') and ($imageSize=='thumbnail')) 
		      	  {
		      	
		      			   $elementids = metadata($item, array('Item Type Metadata', 'Video_embeded_code'), array('no_escape'=>true,'all'=>true));
         	         $elementvideos_VCM = metadata($item, array('Item Type Metadata', 'video_embeded_code_VCM'),array('no_escape'=>true, 'all'=>true)); 
	                 if (!empty($elementids))
	                {
            	        foreach ($elementids as $elementid) 
            	        { 
                 	        $videoid = str_replace($remove, "", $elementid);               
            	            if ((!empty($videoid)) and ($thumnail_image!=true))
            	            {                
                           	$image = "<img src='http://i4.ytimg.com/vi/".$videoid."/default.jpg' style='width:200px; height:128px'/>";             
   	                        $thumnail_image=true;
              	          }
                  	  }
                   }//if
                   elseif (!empty($elementvideos_VCM))
     			         {
            	  		 		$data = $elementvideos_VCM[0];            	  		 						    					
				    						preg_match('/\/entry_id\/([a-zA-Z0-9\_]*)?/i', $data, $match);   
    		          	  	$partnerId = 1038472;         	 
        		        	  $image = '<img src="http://cdn.kaltura.com/p/'.$partnerId.'/thumbnail/entry_id/'.$match[1].'/width/200/height/200/type/1/quality/100" style="width:200px; height:128px"/>'; 
            		      	$thumnail_image=true;        		
              		 }//if		  	
              		 $html = exhibit_builder_link_to_exhibit_item($image,'',$item);                     	
    			    }//elseif video        			       
    	} //$compact['attachment']['item']->getItemType()->name
   }//$exhibitPage->layout!='mlibrary-custom-layout'
   else {   
			   $firstimage='false';
				// This layout (custom layout) expect images of files attached to the item. the loop will go through the files. if no files exist then there is no data displayed.
   		  // 	$filename = basename($file->archive_filename,'.jpg');	
				 $files = $compact['attachment']['item']->Files;
				 $item = $compact['attachment']['item'];
				 $displayFilesOptions = $compact['fileOptions'];
				 set_loop_records('files', $files);//get_current_record($item)->Files);
				 if(!empty($files)) {
						foreach(loop('files') as $file):             	
		          // check to see if it is the first file in first item
            		if (($file->hasThumbnail()) and ($displayFilesOptions['imageorder']==1) and ($firstimage!='true')){                
				           	$extension = pathinfo($file->filename, PATHINFO_EXTENSION);
              		  $filename = basename($file->filename,'.'.$extension);	                  	                 	            	        
					          $htmlimage['id'.$file->id]['archive'] = $item->id;   
            				if (!preg_match('~^https?://~i',metadata($item,array('Dublin Core','Identifier')))){
         		  		    	 $htmlimage['id'.$file->id]['fulltext']='';         		  		    	 
         		  		  }
			              else{
        			      	 	 $htmlimage['id'.$file->id]['fulltext'] = metadata($item,array('Dublin Core','Identifier')); 	
        			      }
          			   	$htmlimage['id'.$file->id]['creator'] = $displayFilesOptions['creator'];
        	          $htmlimage['id'.$file->id]['description'] = $displayFilesOptions['description'];
        	          $htmlimage['id'.$file->id]['title'] = $displayFilesOptions['title'];
					        	$htmlimage['id'.$file->id]['date'] = $displayFilesOptions['year'];
        						$firstimage='true';        						        		    
     	              if(file_exists('files/zoom_tiles/'.$filename.'_zdata')){      											     	             
        							$htmlimage['id'.$file->id]['image'] = '<div class="zoom id'.$file->id.' exhibit-item"><OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="100%" HEIGHT="450" ID="theMovie">
				        			<PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.url('').'files/zoom_tiles/'.$filename.'_zdata">
         							<PARAM NAME="MENU" VALUE="FALSE">
        							<PARAM NAME="SRC" VALUE="'.url('').'themes/mlibrary/javascripts/ZoomifyViewer.swf">
        							<param NAME=wmode VALUE=opaque> 
				        			<EMBED FlashVars="zoomifyImagePath='.url('').'files/zoom_tiles/'.$filename.'_zdata" SRC="'.url('').'themes/mlibrary/javascripts/ZoomifyViewer.swf" wmode=opaque MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="100%" HEIGHT="450" NAME="theMovie"></EMBED></OBJECT></div>';
          					}
        				    else{            				        		                         
		                    $htmlimage['id'.$file->id]['image'] = "\n" . '<div class="fullsize id'.$file->id.' exhibit-item">';   
                  			$htmlimage['id'.$file->id]['image'] .= file_markup($file, array('imageSize'=>'fullsize','imgAttributes'=>array('alt'=>strip_formatting(metadata($item,array('Dublin Core', 'Title')))),'linkAttributes'=>array('class'=>'fancyitem','title' => strip_formatting(metadata($item,array('Dublin Core', 'Title'))))));
	                      $htmlimage['id'.$file->id]['image'] .= '</div>' . "\n";		  
	                 }            	   
            		}
		            // all other files should be hide it
            		else{            	
    	            	 if ($file->hasThumbnail()):
	                  			$extension = pathinfo($file->filename, PATHINFO_EXTENSION);
					          		  $filename = basename($file->filename,'.'.$extension);	                  	                 	            	        
				  		          	$htmlimage['id'.$file->id]['archive'] = $item->id;        	 				  		          		        	
        			        	 if (!preg_match('~^https?://~i', metadata($item,array('Dublin Core','Identifier'))))
			                    	 $htmlimage['id'.$file->id]['fulltext']='';
        			        	 else
			                  	 	 $htmlimage['id'.$file->id]['fulltext'] = metadata($item,array('Dublin Core','Identifier'));		        	     
		        	           $htmlimage['id'.$file->id]['title'] = $displayFilesOptions['title'];
      		        	     $htmlimage['id'.$file->id]['description'] = $displayFilesOptions['description'];            	     
		          	         $htmlimage['id'.$file->id]['creator'] = $displayFilesOptions['creator'];
			                	 $htmlimage['id'.$file->id]['date'] = $displayFilesOptions['year'];
       	            		if(file_exists('archive/zoom_tiles/'.$filename.'_zdata')){ 
              							$htmlimage['id'.$file->id]['image'] = '<div class="zoom id'.$file->id.' exhibit-item"><OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="100%" HEIGHT="450" ID="theMovie">
														<PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.url('').'files/zoom_tiles/'.$filename.'_zdata">
														<PARAM NAME="MENU" VALUE="FALSE">
														<PARAM NAME="SRC" VALUE="'.url('').'themes/mlibrary/javascripts/ZoomifyViewer.swf">
														<param NAME=wmode VALUE=opaque> 
														<EMBED FlashVars="zoomifyImagePath='.url('').'files/zoom_tiles/'.$filename.'_zdata" SRC="'.url('').'themes/mlibrary/javascripts/ZoomifyViewer.swf" wmode=opaque MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="100%" HEIGHT="450" NAME="theMovie"></EMBED></OBJECT></div>';							
          							}
					            	else{	                		
             	  						 $htmlimage['id'.$file->id]['image'] = "\n" . '<div class="fullsize id'.$file->id.' exhibit-item">';   
					 		               $htmlimage['id'.$file->id]['image'] .= file_markup($file, array('imageSize'=>'fullsize','imgAttributes'=>array('alt'=>strip_formatting(metadata($item,array('Dublin Core', 'Title')))),'linkAttributes'=>array('class'=>'fancyitem','title' => strip_formatting(metadata($item,array('Dublin Core', 'Title'))))));
              		  				 $htmlimage['id'.$file->id]['image'] .= '</div>' . "\n";
    	    	    	      }		
	                 endif;  	
            		}            		                                                 	            	                  
	           endforeach;  
	         }else{
	         $htmlimage = "";
	         }
           $html=$htmlimage;    
      }
  return $html;
}

add_filter('custom_public_nav_header', 'mlibrary_custom_public_nav_header');
function mlibrary_custom_public_nav_header()
{        
        $navArray = array('Browse Exhibits' => uri('exhibits'));
        return public_nav_main($navArray);    
}



add_filter('exhibit_builder_nested_nav','mlibrary_exhibit_builder_nested_nav');
function mlibrary_exhibit_builder_nested_nav($html,$exhibit,$showAllPages)
{	
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
	  		if (mlibrary_pages_in_section()!='gallery') {
    	    if ($showAllPages || exhibit_builder_is_current_section($exhibitSection)) {
	           $html .= exhibit_builder_page_nav($exhibitSection);	        
    	    }
	    	}        
  	    $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;   
}
    
function mlibrary_exhibit_builder_page_summary($exhibitPage = null, $current_page=null) {
   if (!$exhibitPage) {
        $exhibitPage = get_current_record('exhibit_page');
    }
		$parents = $current_page->getAncestors(); 

		if(($current_page->id == $exhibitPage->id))	
		    $html = '<li class="current">'
        	  . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
	          . metadata($exhibitPage, 'title') .'</a>';
    	elseif (!empty($parents) and ($exhibitPage->id == $parents[0]->id))
        	$html = '<li class="current">'
	          . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
    	      . metadata($exhibitPage, 'title') .'</a>';
    	 else
    	 	$html = '<li>'
	          . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
    	      . metadata($exhibitPage, 'title') .'</a>';
    	      
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


//add_filter('exhibit_builder_page_nav','mlibrary_exhibit_builder_page_nav');   
/*function mlibrary_exhibit_builder_page_nav($html, $exhibitPage)
{
  if (!$exhibitPage) {
        if (!($exhibitPage = get_current_record('exhibit_page', false))) {
            return;
        }
    }
     $exhibit = $exhibitPage->getExhibit();
    $html = '<ul class="exhibit-page-nav navigation" id="secondary-nav">' . "\n";
    $pagesTrail = $exhibitPage->getAncestors();      
    $pagesTrail[] = $exhibitPage;    
    $html .= '<li>';
    $html .= '<a class="exhibit-title" href="'. html_escape(exhibit_builder_exhibit_uri($exhibit)) . '">';
    $html .= html_escape($exhibit->title) .'</a></li>' . "\n";
    foreach ($pagesTrail as $page) {   
        $linkText = $page->title;
        $pageExhibit = $page->getExhibit();
        $pageParent = $page->getParent();
       // print_r($pageParent);
        $pageSiblings = ($pageParent ? exhibit_builder_child_pages($pageParent) : $pageExhibit->getTopPages()); 
        $html .= "<li>\n<ul>\n";
        foreach ($pageSiblings as $pageSibling) {
            $html .= '<li' . ($pageSibling->id == $page->id ? ' class="current"' : '') . '>';
            $html .= '<a class="exhibit-page-title" href="' . html_escape(exhibit_builder_exhibit_uri($exhibit, $pageSibling)) . '">';
            $html .= html_escape($pageSibling->title) . "</a></li>\n";
        }
        $html .= "</ul>\n</li>\n";
    }
    $html .= '</ul>' . "\n";
   
    return $html;
}*/

add_filter('exhibit_builder_thumbnail_gallery', 'mlibrary_exhibit_builder_thumbnail_gallery');
function mlibrary_exhibit_builder_thumbnail_gallery($html,$compact) {
 $remove[] = "'";
 $remove[] = '"';
 $remove[] = " ";
 $exhibitPage = get_current_record('exhibit_page', false);  
 $start = $compact['start'];
 $end = $compact['end'];
 $props = $compact['props']; 
 $thumnail_image = false;
   
  if ($exhibitPage->layout!='mlibrary-custom-layout')
  {     
      for($i=(int)$start; $i <= (int)$end; $i++) 
      {     	
      		$attachment = exhibit_builder_page_attachment($i);		      
      	//	print_r($attachment['item']->id.'<br>');      	
          if (!empty($attachment)){	                                  
             if (!empty($attachment['item']->getItemType()->name))
		             $item_type = $attachment['item']->getItemType()->name;
		         else
		             $item_type = "Still Image";
            	
						 $item = $attachment['item']; 		   
    					//	 set_loop_records('files', $attachment['item']->Files); 
             if (($item) and ($item_type=='Video')){                         	         
                 $html .= "\n" . '<div class="exhibit-item-video">';  
  	             $elementids = metadata($item, array('Item Type Metadata', 'Video_embeded_code'),array('no_escape'=>true,'all'=>true));    				
      	         $elementvideos_VCM = metadata($item, array('Item Type Metadata', 'video_embeded_code_VCM'),array('no_escape'=>true, 'all'=>true));   	                								 
            	   if (!empty($elementids))
            	   {            	   
          	        foreach ($elementids as $elementid) 
          	        {           
              	    		$videoid = str_replace($remove, "", $elementid);                
			                  if ((!empty($videoid)) and ($thumnail_image!=true))
			                  {                
    			                 $image = "<img src='http://i4.ytimg.com/vi/".$videoid."/default.jpg' style='width:200px; height:128px'/>";             
        		      	       $thumnail_image=true;
            			    	}//if
  	            	  }//for each
	                }// if elements
	                elseif ($elementvideos_VCM = metadata($item, array('Item Type Metadata', 'video_embeded_code_VCM'), array('no_escape'=>true, 'all'=>true))) 
	                {
          		      	$data = $elementvideos_VCM[0];
          		      	preg_match('/\/entry_id\/([a-zA-Z0-9\_]*)?/i', $data, $match);          	     
	            	      $partnerId = 1038472;         	 
  	                  $image = '<img src="http://cdn.kaltura.com/p/'.$partnerId.'/thumbnail/entry_id/'.$match[1].'/width/200/height/200/type/1/quality/100" style="width:200px; height:128px"/>';                                   
    	      	  		  $thumnail_image=true;        		
                	}//elementvideos_VCM     
      	       		$html .= exhibit_builder_link_to_exhibit_item($image,'',$item);
    	    	  // $html .= exhibit_builder_exhibit_display_caption($i);
		    	       $html .= '</div>' . "\n";
	    	      }//type video
	    	      
    	      /* elseif (($attachment) and ($item_type=='Sound'))
    	       {
      	        $html .= "\n" . '<div class="exhibit-item-sound">';
          	    $thumbnail = item_image($thumbnailType, $props);
              	$html .= exhibit_builder_link_to_exhibit_item($thumbnail);
	              $html .= exhibit_builder_exhibit_display_caption($i);
    	         $html .= '</div>' . "\n";        
	         	} //$attachment) and ($item_type=='Sound'*/
	         	//  		print_r($item_type); 
      }//if (!empty($attachment))
   }
  } //($exhibitPage->layout!='mlibrary-custom-layout')
  elseif ($exhibitPage->layout=='mlibrary-custom-layout'){
	      $image_index=0;
			// start is 1 and end is 12, this is the way set it up in the new layout or other layout that has thumbnail it can be 
			//changed to something else.
		    $firstthumbnail=false;    
	      for ($i=(int)$start; $i <= (int)$end; $i++) 
	      {           
    	//check to see if there is item exist with exhibit_builder_use_exhibit_page_item function  
           	$attachment = exhibit_builder_page_attachment($i);  
           	if (!empty($attachment))
           	{	
             	$item_type = $attachment['item']->getItemType()->name;
//             	 exhibit_builder_use_attachment($attachment);  
			    $item = $attachment['item'];    
			  	set_loop_records('files', $attachment['item']->Files); 
            	    if (($attachment) and ($item_type!='Sound') or ($item_type!='video'))
            	    {                	     
              	       foreach(loop('files') as $file):      
        	             	if ($file->hasThumbnail() and ($firstthumbnail!=true)){
          				          $html = "\n" . '<div class="square_thumbnail id'.$file->id.' first exhibit-item"  file_id="id'.$file->id.'">';   
	                          //$html .= display_file($file, array('imageSize'=>'square_thumbnail','imgAttributes'=>array('alt'=>item('Dublin Core', 'Title')),'linkToFile'=>false));
	                           $html .= file_markup($file, array('imageSize'=>'square_thumbnail','imgAttributes'=>array('alt'=>strip_formatting(metadata($item, array('Dublin Core', 'Title')))),'linkToFile'=>false));
    	                      $html .= '</div>' . "\n";
                   	        $image_index++;
                      	    $firstthumbnail=true;
          	            }          	
    	                  else if ($file->hasThumbnail() and ($firstthumbnail==true)){        	
          	                $html .= "\n" . '<div class="square_thumbnail id'.$file->id.' exhibit-item"  file_id="id'.$file->id.'">';   
	                          $title = $file->title;
	                          $html .= file_markup($file, array('imageSize'=>'square_thumbnail','imgAttributes'=>array('alt'=>strip_formatting(metadata($item, array('Dublin Core', 'Title')))),'linkToFile'=>false));
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

