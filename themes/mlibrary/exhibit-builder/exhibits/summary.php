<?php echo head(array('title' => html_escape('Summary of ' . metadata('exhibit','title')),'bodyid'=>'exhibit','bodyclass'=>'summary')); ?>

<div id="primary">

 <?php echo mlibrary_header_banner(); ?>

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

  <div id="summary-view">

    <div id="sharethis">
      <span>Share this Exhibit!</span>
      <div class="fb-like" data-send="false" data-layout="button_count" data-show-faces="false" data-font="arial"></div>
      <div class="twitter-share">
        <a  href="https://twitter.com/share"
            class="twitter-share-button"
            data-text="I just saw '<?php echo metadata('exhibit','title',array('no_escape' => true)); ?>' at the MLibary Online Exhibits!" >
          Tweet
        </a>
      </div>
    </div>

    <div class="exhibit_image">
      <?php
        $exhibit_record = get_current_record('exhibit', false);
        $theme_options_array = $exhibit_record->getThemeOptions();
        $theme_options_array['exhibitimage'] = get_image_attached_to_exhibits($exhibit_record['id']);
        $exhibit_image = $theme_options_array['exhibitimage'];

        if ($exhibit_image) {
          echo '<img src="' . WEB_FILES.$exhibit_image['image_name'] . '" alt="' . $exhibit_image['image_title'] . '" />';
        } else {
          echo '<img src="' . img("mlibrary_galleryDefault.jpg") . '" alt="Mlibrary default image"/>';
        }
      ?>
    </div>

    <div id="summary-sidebar">
      <?php echo metadata('exhibit','description',array('no_escape' => true)); ?>
      <h2 class="credits"><span>Curated by</span> <?php echo html_escape(metadata('exhibit','credits')); ?></h2>
    </div>

  </div>

</div>

<?php echo foot(); ?>
