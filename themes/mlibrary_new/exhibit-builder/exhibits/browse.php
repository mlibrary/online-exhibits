<?php
 /**
  * Copyright (c) 2017, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */

add_filter('theme_options', function ($options, $args) {
  return serialize(['display_header' => '0'] + unserialize($options));
});

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

<h2 class="row"><?php
      $filterSummary = '';
      $browseLink = '';

      if (isset($_GET['tags'])) {
         $filterSummary =  __('with') . __(' "<span class="tag-title">%s</span>"', strip_tags($_GET['tags'])). __(' tag');
         $browseLink = '<div class="col-md-3 col-xs-12 browse-link--container"><a href="'.html_escape(WEB_ROOT).'/exhibits/browse" class="browse-all--link">Browse all exhibits</a></div>'; 
      }
 
      $Browse_title = '<div class="col-md-9 col-xs-12 browse--header">' . __('Browse all ') . __('%s', $total_results). " exhibits " . __('%s', $filterSummary) . '</div>';
      echo $Browse_title; 
      echo $browseLink;?>
</h2>

<div class="detail-nav-border"></div>

<div id="primary" class="browse">

  <?php if (count($exhibits) > 0): ?>
    
    <div id="exhibits" class="pretty-list">

      <?php
        foreach (loop('exhibit') as $exhibit):
      ?>
          <?php $theme_options_array = $exhibit->getThemeOptions(); ?>

         <article>
          <div class="col-xs-12 browse-wrap panel panel-default results-card">
           <div class="col-xs-12 col-sm-3"> <div class="img-wrap">
              <?php
               if ($exhibitImage = record_image($exhibit, 'square_thumbnail', array('alt' =>''))) {
                  echo $exhibitImage;
               } else {
                    echo '<img src="'.img("defaulthbg.jpg").'" alt="" />';
               }?>
            </div></div>
                <div class="col-xs-12 col-sm-9"><h3 class="item-heading"><?php echo link_to_exhibit(); ?></h3>
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
