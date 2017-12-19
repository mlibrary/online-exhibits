<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
set_theme_option('display_header','0');
$title = __('Browse Exhibits');
       echo head(
            array(
               'title' =>$title,
               'bodyid'=>'exhibit',
               'bodyclass' => 'exhibits browse'
            )
       );


?>
  <div class="col-xs-9">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
        <li class="breadcrumb-item active">Browse all exhibits</li>
      </ol>
  </div>


  <?php
    $html = mlibrary_display_popular_tags();
    echo $html;
  ?>

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
              <?php

              if ($exhibitImage = record_image($exhibit, 'square_thumbnail', array('alt' => $exhibit->title))) {
                    $Exhibit_image = $exhibitImage;//array('image_name'=>$exhibitImage);
               } else {
                      $Exhibit_image = '';
                    }

                echo '<div class="img-wrap">';

                  if (!empty($Exhibit_image)) {
                      echo $Exhibit_image;//$Exhibit_image['image_name'];
                  } else {
                      echo('<img class="image-card" src="'.img("defaulthbg.jpg").'" alt="Mlibrary default image"/>');                  
                  }

                echo '</div>';?>
 
                <h2 class="item-heading"><?php echo link_to_exhibit(); ?></h2>
                <?php if($exhibitDescription = metadata('exhibit', 'description', array('snippet'=>300,'no_escape' => true))) {
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
