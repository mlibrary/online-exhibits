<?php $galleryPage = get_current_record('exhibit_page'); ?>
<div class="container">
<section>
    <div class="gallery-intro">
      <h1 class="exhibit-gallery-heading">Gallery</h1>
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
