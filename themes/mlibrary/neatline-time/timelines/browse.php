<?php
/**
 * The public browse view for Timelines.
 */

$head = array('bodyclass' => 'browse', 'bodyid'=>'timelines',
              'title' => html_escape(__('Browse Timelines')));

head($head);

$exhibit_image_object = new CosignImagexhibitrelationship();
?>
<?php
$first_timeline='false';?>
<h1><?php echo __('Browse Timelines'); ?></h1>
<div id="primary" class="browse">

<div id="times">
    <?php if (has_timelines_for_loop()) : while ( loop_timelines() ) :
          $this_timeline = get_current_timeline();
          $query = neatlinetime_convert_search_filters(timeline('query', $this_timeline));
					if (!empty($query['exhibit'])) {
              $theme_options_array['exhibitimage'] = $exhibit_image_object->get_image_attached_to_exhibits($query['exhibit']);
              $Exhibit_image = WEB_ARCHIVE.$theme_options_array['exhibitimage']['image_name'];
	    		 }
	    		else {
	    		    $Exhibit_image ="https://nancymou.www.lib.umich.edu/online-exhibits/themes/mlibrary/images/mlibrary_galleryDefault.jpg";
	    		}
    ?>
    	   <div class="time <?php if ($first_timeline=='false') echo 'first';  ?>">

	 	        	<?php $first_timeline='true';?>
	    	      <h2>
	    	      <?php echo link_to_timeline(); ?>
	    	      </h2>
	    	      <?php echo '<img src="'.$Exhibit_image.'">';?>
	    	      <p class="timeline-description">
              <?php //echo timeline('Description');
              //$text = timeline('Description');
              //echo $text;
  						   $tags = array("<p>", "</p>");
						     $this_timeline = str_replace($tags, "", $this_timeline['description']);
                 echo $this_timeline;
              ?>
              </p>
         </div>
    <?php endwhile; ?>
    </div>
    <div class="pagination">
      <?php echo pagination_links(); ?>
    </div>
    <?php else: ?>
    <p><?php echo __('You have no timelines.'); ?></p>
    <?php endif; ?>
</div>
<?php foot(); ?>
