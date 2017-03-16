<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
  echo head(
    array(
      'title' => 'Summary of ' . metadata('exhibit','title'),
      'bodyid'=>'exhibit','bodyclass'=>'summary'
    )
  );

  //$exhibit_image_object = new ImagBelongToExhibitRelationShip();
?>

<div id="primary">

  <?php echo mlibrary_header_banner(); ?>

  <nav class="exhibit-navigation">
    <h2 class="element-invisible">Exhibit Navigation</h2>

    <div class="exhibit-overview active">
      <?php echo link_to_exhibit('Introduction'); ?>
    </div>

    <ul id="exhibit-pages">
      <?php
        set_exhibit_pages_for_loop_by_exhibit();
        foreach (loop('exhibit_page') as $exhibitPage) {
          echo exhibit_builder_page_summary($exhibitPage);
        }
      ?>
    </ul>
  </nav>

  <section id="summary-view" class="exhibit-content cf">

    <div class="sharethis-wrap">
      <div id="sharethis">
        <span>Share this Exhibit!</span>
        <div class="fb-like" data-send="false" data-layout="button_count" data-show-faces="false" data-font="arial"></div>
        <div class="twitter-share">
          <a  href="https://twitter.com/share"
              class="twitter-share-button"
              data-text="I just saw '<?php echo metadata('exhibit','title',array('no_escape' => true)); ?>' at the U-M Library Online Exhibits!" >
            Tweet
          </a>
        </div>
      </div>
    </div>

    <h2 class="element-invisible">Exhibit Summary</h2>

    <div class="exhibit_image">
      <?php
        $exhibit_record = get_current_record('exhibit', false);
        $theme_options_array = $exhibit_record->getThemeOptions();

        if ($exhibitImage = record_image($exhibit, 'fullsize', array('alt' => $exhibit->title))) {
           $Exhibit_image = $exhibitImage;
        } else {
           $Exhibit_image = '';
        }

        if ($Exhibit_image) {
         // echo '<img src="' . WEB_FILES.$exhibit_image['image_name'] . '" alt="' . $exhibit_image['image_title'] . '" />';
            echo $Exhibit_image;
        } else {
          echo '<img src="' . img("mlibrary_galleryDefault.jpg") . '" alt="Mlibrary default image"/>';
        }
      ?>
    </div>

    <div id="summary-sidebar">
      <?php echo metadata('exhibit','description',array('no_escape' => true)); ?>
      <p class="credits">Curated by <?php echo metadata('exhibit','credits'); ?></p>
    </div>

  </section>

</div>

<?php echo foot(); ?>
