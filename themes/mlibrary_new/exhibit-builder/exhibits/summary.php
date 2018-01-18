<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
  set_theme_option('display_header','1');
  echo head(
    array(
      'title' => 'Summary of ' . metadata('exhibit','title'),
      'bodyid'=>'exhibit','bodyclass'=>'summary'
    )
  );

?>
<!--Breadcrumb and Share Bar-->
  <div class="col-xs-12 col-sm-8">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
        <li class="breadcrumb-item active"><?php echo metadata('exhibit','title',array('no_escape' => true)); ?></li>
      </ol>
  </div>

  <div class="col-xs-12 col-sm-4">
    <ul class="share-this">
      <li>Share this Exhibit:</li>
      <li><a href="https://twitter.com/share" class="twitter-share-button" data-text="I just saw '<?php echo metadata('exhibit','title',array('no_escape' => true)); ?>' at the U-M Library Online Exhibits!" ><span class="sr-only">Tweet</span> </a></li>
    </ul>
   </div>
<!--End breadcrumb and share bar-->

  <div class="exhibit-introduction">
    <div class="col-xs-12">
      <h1><?php echo metadata('exhibit','title',array('no_escape' => true)); ?></h1>
      <p class="credits">Curated by <?php echo metadata('exhibit','credits'); ?></p>
    </div>
  </div>

  <div id="primary">
    <div class="col-xs-12 col-sm-3">
      <nav class="exhibit-navigation">
        <h3 class="element-invisible">Exhibit Contents</h3>

        <div class="exhibit-overview active">
          <?php echo link_to_exhibit('Introduction'); ?>
        </div>
        <ul id="exhibit-pages" class="exhibit-nav-list">
          <?php
            set_exhibit_pages_for_loop_by_exhibit();
            foreach (loop('exhibit_page') as $exhibitPage) {
              echo exhibit_builder_page_summary($exhibitPage);
            }
          ?>
        </ul>
        <ul>
           <?php echo link_to($exhibit,'Gallery','Gallery'); ?>
        </ul>
      </nav>
    </div>

  <section id="summary-view" class="exhibit-content cf">

    <div class="col-xs-12 col-sm-9">
      <h2> Introduction </h2>
      <?php echo metadata('exhibit','description',array('no_escape' => true)); ?>
      <div class = "exhibit-theme">
        <?php  set_exhibit_pages_for_loop_by_exhibit();
               foreach (loop('exhibit_page') as $exhibitPage) {?>
                              <div id = "exhibit-theme-item" class="panel panel-default">
                               <?php
                                  $page_card_info = mlibrary_new_display_exhibit_card_info($exhibitPage);
                                  $uri = exhibit_builder_exhibit_uri($exhibit, $exhibitPage);?>
                                  <a href= <?php echo html_escape($uri);?> >
                                    <div class="panel-heading"><?php  echo $page_card_info["image"];?></div>
                                    <div class="card-info panel-body"><h3 class="panel-card-title"><?php echo html_escape($page_card_info["title"]);?></h3>
                                    <p class="panel-card-text"><?php echo html_escape($page_card_info["description"]);?></p></div>
                                  </a>                           
                           </div>
               <?php }?>  
      </div>
    </div>
  </section>
</div>

<?php echo foot(); ?>
