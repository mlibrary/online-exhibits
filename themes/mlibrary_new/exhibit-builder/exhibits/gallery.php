<?php $galleryPage = get_current_record('exhibit_page'); ?>
<!--Breadcrumb and Share Bar-->
<section class="row">
    <div class="col-xs-12 col-sm-9">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
          <li class="breadcrumb-item"><?php echo link_to_exhibit(metadata('exhibit','title',array('no_escape' => true)));?></li>
          <li class="breadcrumb-item active"><?php echo 'Exhibit Image '. metadata('exhibit_page','title',array('no_escape' => true)); ?></li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-3">
      <ul class="share-this">
        <li>Share this Gallery:</li>
        <li><a href="https://twitter.com/share" class="twitter-share-button" data-text="I just saw '<?php echo metadata('exhibit','title',array('no_escape' => true)); ?>' at the U-M Library Online Exhibits!" ><span class="sr-only">Tweet</span> </a></li>
      </ul>
     </div>
</section>
<!--End breadcrumb and share bar-->

<section>
    <div class="gallery-intro">
      <h1 class="exhibit-gallery-heading">Exhibit Image Gallery</h1>
        <p class="exhibit-gallery-subheading">Browse all images in this exhibit by section and by the order they appear within each section.</p>
        <p class="exhibit-gallery-subheading">The University of Michigan Library has placed copies of these works online for educational and research purposes. For more information about using images from this exhibit please see the <a href="<?php echo url('about/#image-rights'); ?>">Rights Statement</a>.</p>
    </div>

  <?php set_exhibit_pages_for_loop_by_exhibit();

  foreach (loop('exhibit_page') as $exhibitSection) {
    $cardsInSubsection = '';
    $cardsInSectionFlag = false;
    ?>
    <section class="exhibit-gallery-cards">
      <?php
        $cardsInSection = mlibrary_new_get_cards_in_section_gallery(
          $exhibitSection->id,
          $exhibitSection->getAllAttachments(),
          $galleryPage
        );
        if (!empty($cardsInSection)) {
          echo '<h2 class="exhibit-gallery-section-header">'.$exhibitSection->title.'</h2>';
          echo implode($cardsInSection);
          $cardsInSectionFlag = true;
        }
        if (!empty($childPages = $exhibitSection->getChildPages())) {
          foreach ($childPages as $child) {
            $cardsInSubsection = mlibrary_new_get_cards_in_section_gallery(
              $child->id,
              $child->getAllAttachments(),
              $galleryPage
            );
            if ((!$cardsInSectionFlag) and (!empty($cardsInSubsection))) {
              echo '<h2 class="exhibit-gallery-section-header">'.$exhibitSection->title.'</h2>';
              $cardsInSectionFlag = true;
            }
            echo implode($cardsInSubsection);
          }
        }
      ?>
    </section>
  <?php }?>
</section>
