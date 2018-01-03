<?php
 /**
  * Copyright (c) 2017, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */

  echo head(
    array(
      'title' => metadata('exhibit', 'title') . ' | ' .
                 metadata('exhibit_page', 'title'),
      'bodyid'=>'exhibit',
      'bodyclass' => 'show'
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

<div id="primary">
  <div class="col-xs-12 col-sm-3">
    <nav class="exhibit-navigation">
      <h3 class="element-invisible">Exhibit Contents </h3>

      <div class="exhibit-overview">
        <?php echo link_to_exhibit('Introduction'); ?>
      </div>

      <ul id="exhibit-pages" class="exhibit-nav-list">
        <?php
          $exhibit_page = get_current_record('exhibit_page', false);
          set_exhibit_pages_for_loop_by_exhibit();
          foreach (loop('exhibit_page') as $exhibitPage) {
             echo exhibit_builder_page_summary($exhibitPage, $exhibit_page);
          }
        ?>
      </ul>
    </nav>
  </div>

 <section class="exhibit-content cf">
    <div class="col-xs-12 col-sm-9 show-wrapper">
      <h2>
        <?php
          set_current_record('exhibit_page', $exhibit_page);
          echo metadata('exhibit_page', 'title');
        ?>
      </h2>

      <div>
        <?php echo exhibit_builder_render_exhibit_page($exhibit_page); ?>
      </div>
  </div>
  
  <div class="col-xs-12 col-sm-9 col-sm-offset-3 padding-0">
    <div class="section-nav">
          <div class="section-nav-panel">
            <div class="section-nav-image"></div>
            <div class="section-link-prev">
              <?php
                echo exhibit_builder_link_to_previous_page("Previous Section");
              ?>
            </div>
            <div class="section-nav-text">
              <p class="section-title">Section Title</p>
              <p class="section-caption"></p>
            </div>
          </div>

          <div class="section-nav-panel">
            <div class="section-nav-image"></div>
              <div class="section-link-next">
              <?php
                echo exhibit_builder_link_to_next_page("Next Section");
              ?>
            </div>
            <div class="section-nav-text">
              <p class="section-title">Section Title</p>
              <p class="section-caption"></p>
            </div>
          </div>
    </div>
   </div>
  </section>

</div>
<?php echo foot(); ?>
