<?php
/**
 * The public show view for Timelines.
 */
display_js();
display_css();

queue_timeline_assets();
$head = array('bodyclass' => 'timelines primary',
              'title' => timeline('title')
              );
head($head);
?>
<h1><?php echo 'Time Line: '.timeline('title'); ?></h1>

<div id="primary">

    <!-- Construct the timeline. -->
    <?php echo $this->partial('timelines/_timeline.php'); ?>

    <?php echo timeline('description'); 
    $query_array = timeline('query');
   
         // echo neatlinetime_display_search_query($query_array['exhibit']);
         echo mlibrary_neatlinetime_display_search_query($query_array['exhibit']);
    ?>

</div>
<?php foot(); ?>
