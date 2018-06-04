<?php
 /**
  * Copyright (c) 2017, Regents of the University of Michigan.
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

  <div class="col-xs-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
        <li class="breadcrumb-item active">Browse all exhibits</li>
      </ol>
  </div>

  <div class="col-xs-12">
    <h1>Browse Exhibits</h1>
    <p>Explore and discover inspiring collections of art, literature, culture, and history</p>
    <div class="detail-nav-border"></div>
    
  <section class="tag-cloud row">
  <?php
    $html = mlibrary_new_display_popular_tags();
    echo $html;
  ?>
  </section>

<h2><?php echo $title; ?> <?php echo __('(%s total)', $total_results); ?></h2>

<div class="detail-nav-border"></div>

<div id="primary" class="browse">

  <?php if (count($exhibits) > 0): ?>
    
    <div id="exhibits" class="pretty-list">

      <?php
        foreach (loop('exhibit') as $exhibit):
      ?>
          <?php $theme_options_array = $exhibit->getThemeOptions(); ?>

         <article>
          <div class="col-xs-12 browse-wrap">
           
              <?php

               if ($exhibitImage = record_image($exhibit, 'square_thumbnail', array('alt' => $exhibit->title))) {
                  $Exhibit_image = $exhibitImage;//array('image_name'=>$exhibitImage);
               } else {
                  $Exhibit_image = '';
               }

               echo '<div class="col-xs-12 col-sm-3"> <div class="img-wrap">';

               if (!empty($Exhibit_image)) {
                      echo $Exhibit_image;//$Exhibit_image['image_name'];
               } else {
                      echo('<img src="'.img("defaulthbg.jpg").'" alt="Mlibrary default image"/>');                  
               }

                echo '</div></div>';?>
 
                <div class="col-xs-12 col-sm-9"><h2 class="item-heading"><?php echo link_to_exhibit(); ?></h2>
                <?php if($exhibitDescription = metadata('exhibit', 'description', array('snippet'=>300,'no_escape' => true))) {
                  echo '<p class="item-description">' . $exhibitDescription . '</p>';
                }

                $tags = str_replace(';', '', tag_string($exhibit, 'exhibits/browse'));
                if (!empty($tags)) { echo '<div class="tags"><ul class="tag-list"> <li>Tags:</li> ' . $tags . '</li></ul></div>'; }

                  echo '</div></div>';
              ?>
          </article>

      <?php
        endforeach;
      ?>

      <?php
        echo '<p class="sr-only">Pagination</p>' . pagination_links();
      ?>

  

  <?php else: ?>
    <p>There are no exhibits available yet.</p>
  <?php endif; ?>
  </div>
</div>

<?php echo foot(); ?>
