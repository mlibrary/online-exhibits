<?php
  echo head(
    array(
      'title' => html_escape(
                   metadata('exhibit', 'title') . ' : ' .
                   metadata('exhibit_page', 'title')
                 ),
      'bodyid'=>'exhibit',
      'bodyclass' => 'show'
    )
  );
?>

<div id="primary">

  <?php echo mlibrary_header_banner(); ?>

  <div id="nav-container">
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
  </div>

  <h2>
    <?php
      set_current_record('exhibit_page', $exhibit_page);
      echo metadata('exhibit_page', 'title');
    ?>
  </h2>

  <?php echo exhibit_builder_render_exhibit_page($exhibit_page); ?>

  <div id="exhibit-page-navigation">
    <?php
      echo exhibit_builder_link_to_previous_page("Previous Page");
      echo exhibit_builder_link_to_next_page("Next Page");
    ?>
  </div>

</div>
<?php echo foot(); ?>
