<?php
/**
 * The public browse view for Timelines.
 */

$head = array('bodyclass' => 'timelines',
              'title' => html_escape(__('Browse Timelines')));
head($head);
?>
<?php 
$first_timeline='false';?>

<div id="primary" class="timelines">
<h1><?php echo __('Browse Timelines'); ?></h1>
<div class="time">
    <?php if (has_timelines_for_loop()) : while ( loop_timelines() ) :?>
    	   <div class="timeline <?php if ($first_timeline=='false') echo 'first';  ?>">
	 	        	<?php $first_timeline='true';?>
	    	      <h2>
	    	      <?php echo link_to_timeline(); ?>
	    	      </h2>
              <?php echo timeline('Description'); ?>
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
