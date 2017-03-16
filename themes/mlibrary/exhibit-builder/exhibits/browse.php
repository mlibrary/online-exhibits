<?php 
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */

$title = __('Browse Exhibits');
       echo head(
            array(
               'title' =>$title,
               'bodyid'=>'exhibit',
               'bodyclass' => 'exhibits browse'
            )
       );

?>

<?php $awkward_gallery_setting = get_theme_option('Featured Image Gallery') ? get_theme_option('Featured Image Gallery') : 'yes'; ?>
  <h1><?php echo $title; ?> <?php echo __('(%s total)', $total_results); ?></h1>


<div id="primary" class="browse">

  <?php if (count($exhibits) > 0): ?>

    <div id="exhibits" class="pretty-list">

      <?php
        foreach (loop('exhibit') as $exhibit):
      ?>
          <?php $theme_options_array = $exhibit->getThemeOptions(); ?>
          <article class="cf">
            <div class="item-body">
              <h2 class="item-heading"><?php echo link_to_exhibit(); ?></h2>
              <?php

              if ($exhibitImage = record_image($exhibit, 'fullsize', array('alt' => $exhibit->title))) {
                    $Exhibit_image = $exhibitImage;//array('image_name'=>$exhibitImage);
               } else {
                      $Exhibit_image = '';
                    }

                echo '<div class="img-wrap">';

                  if (!empty($Exhibit_image)) {
                      echo $Exhibit_image;//$Exhibit_image['image_name'];
                  } else {
                    echo '<img src="' . img("mlibrary_galleryDefault.jpg") . '" alt="Default gallery image"/>';
                  }

                echo '</div>';

                if($exhibitDescription = metadata('exhibit', 'description', array('snippet'=>300,'no_escape' => true))) {
                  echo '<p class="item-description">' . $exhibitDescription . '</p>';
                }

                echo '</div>';

                $tags = str_replace(';', '', tag_string($exhibit, 'exhibits/browse'));
                if (!empty($tags)) { echo '<div class="tags"> <h4 class="tags-heading">Tags</h4> ' . $tags . '</div>'; }
              ?>
          </article>

      <?php
        endforeach;
      ?>

      <?php
        echo '<h2 class="element-invisible">Pagination</h2>' . pagination_links();
      ?>

    </div>

  <?php else: ?>
    <p>There are no exhibits available yet.</p>
  <?php endif; ?>

</div>

<?php echo foot(); ?>
