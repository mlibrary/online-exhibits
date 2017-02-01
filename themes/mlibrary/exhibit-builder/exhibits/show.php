<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
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

<div id="primary">

  <?php echo mlibrary_header_banner(); ?>

  <nav class="exhibit-navigation">
    <h2 class="element-invisible">Exhibit Navigation</h2>

    <div class="exhibit-overview">
      <?php echo link_to_exhibit('Introduction'); ?>
    </div>

    <ul id="exhibit-pages">
      <?php
        $exhibit_page = get_current_record('exhibit_page', false);
        set_exhibit_pages_for_loop_by_exhibit();
        foreach (loop('exhibit_page') as $exhibitPage) {
           echo mlibrary_exhibit_builder_page_summary($exhibitPage, $exhibit_page);
        }
      ?>
    </ul>
  </nav>

 <section class="exhibit-content cf">
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

    <h2>
      <?php
        set_current_record('exhibit_page', $exhibit_page);
        echo metadata('exhibit_page', 'title');
      ?>
    </h2>

    <div class="exhibit-body-wrap">
      <?php echo exhibit_builder_render_exhibit_page($exhibit_page); ?>

      <div id="exhibit-page-navigation">
        <?php
          echo exhibit_builder_link_to_previous_page("Previous Page");
          echo exhibit_builder_link_to_next_page("Next Page");
        ?>
      </div>
    </div>
  </section>

</div>
<?php echo foot(); ?>
