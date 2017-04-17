<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */

$title = __('Browse Collections');
       echo head(
            array(
               'title' =>$title,
               'bodyid'=>'collection',
               'bodyclass' => 'collection browse'
            )
       );

?>

<?php //$awkward_gallery_setting = get_theme_option('Featured Image Gallery') ? get_theme_option('Featured Image Gallery') : 'yes'; ?>
  <h1><?php echo $title; ?> <?php echo __('(%s total)', $total_results); ?></h1>


<div id="primary" class="browse">

  <?php if (count($collections) > 0): ?>

    <div id="collectionss" class="pretty-list">

      <?php
        foreach (loop('collection') as $collection):
      ?>
          <?php //$theme_options_array = $collection->getThemeOptions(); ?>
          <article class="cf">
            <div class="collection-body">
              <h2 class="collection-heading"><?php echo link_to_collection(); ?></h2>
              <?php

              if ($collectionImage = record_image($collection, 'fullsize', array('alt' => $collection->title))) {
                    $Collection_image = $collectionImage;//array('image_name'=>$exhibitImage);
               } else {
                      $Collection_image = '';
                    }

                echo '<div class="img-wrap">';

                  if (!empty($Collection_image)) {
                      echo $Collection_image;//$Exhibit_image['image_name'];
                  } else {
                    echo '<img src="' . img("mlibrary_galleryDefault.jpg") . '" alt="Default gallery image"/>';
                  }

                echo '</div>';

                if($collectionDescription = metadata($collection, array('Dublin Core','Description'))) {
                  echo '<p class="collection-Description">' . $collectionDescription . '</p>';
                }

                echo '</div>';

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
    <p>There are no collections available yet.</p>
  <?php endif; ?>

</div>

<?php echo foot(); ?>
