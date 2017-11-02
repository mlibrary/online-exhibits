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

    <h2> Introduction </h2>
    <div id="summary-sidebar">
      <?php echo metadata('exhibit','description',array('no_escape' => true)); ?>
      <p class="credits">Curated by <?php echo metadata('exhibit','credits'); ?></p>
    </div>
    <div class = "exhibit-theme">
      <?php  set_exhibit_pages_for_loop_by_exhibit();
             foreach (loop('exhibit_page') as $exhibitPage) {
                          $block = $exhibitPage->getPageBlocks();
                          $rawAttachment = $exhibitPage->getAllAttachments();?>
                          <div id = "exhibit-theme-item">
                           <?php
                              $page_card_info = mlibrary_display_exhibit_card_info($rawAttachment,$block,$exhibitPage);
                              $uri = exhibit_builder_exhibit_uri($exhibit, $exhibitPage);
                              echo '<a href="' . html_escape($uri) .'">';
                              echo '<div>'.$page_card_info["image"].'</div>';
                              echo '<div class="card-info"><h3>'.$page_card_info["title"].'</h3>';
                              echo $page_card_info["description"].'</div>';
                              echo '</a>';
                           ?>
                         </div>
             <?php }?>  
    </div>
  </section>
</div>

<?php echo foot(); ?>
