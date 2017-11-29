<div class ="exhibit record">
<?php
     $title =  metadata($exhibit, 'title', array('snippet'=>300,'no_escape' => true));
?>
    <?php $exhibitImage = record_image($exhibit, 'original', array('alt' => $exhibit->title,
                                                                   'class' => 'image-card'));
          if (!empty($exhibitImage))
             {
               echo $exhibitImage;
             } else
             {
               echo('<img class="image-card" src="'.img("defaulthbg.jpg").'" alt="Mlibrary default image"/>');
              }
    ?>
  <h3><?php echo link_to($exhibit, 'show', strip_formatting($title)); ?></h3>
</div>
