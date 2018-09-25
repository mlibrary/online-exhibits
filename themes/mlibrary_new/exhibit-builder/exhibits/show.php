<?php
 /**
  * Copyright (c) 2018, Regents of the University of Michigan.
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

<?php
// If there is a file that matches the slug of this page, display that as the template
// Otherwise, use the template below on show.php
$fname = dirname(__FILE__) . '/' . metadata('exhibit_page', 'slug') . '.php';
if (is_file( $fname )){
    include( $fname );
}
else {
//Close your PHP tags and add your show.php content here.
?>

  <section class="row">
    <div class="col-xs-12 col-sm-3">
      <nav class="exhibit-navigation" data-spy="affix" data-offset-top="250" data-offset-bottom="120">
        <div class="nav-text-inline">
          <h2 class="nav-text-inline-heading">Exhibit Contents</h2>
          <button class="navbar-toggler nav-text-inline-button" type="button" data-toggle="collapse" data-target="#nav-toggle" aria-controls="nav-toggle" aria-expanded="false" aria-label="Toggle navigation">
          <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>  
          </button>
        </div>

        <div class="exhibit-overview collapse navbar-collapse" id="nav-toggle">
          <ul id="exhibit-pages" class="exhibit-nav-list exhibit-pages-show">
            <li><?php echo link_to_exhibit('Introduction'); ?></li>
              <?php
                $exhibit_page = get_current_record('exhibit_page', false);
                set_exhibit_pages_for_loop_by_exhibit();
                foreach (loop('exhibit_page') as $exhibitPage) {
                   echo mlibrary_new_exhibit_builder_page_summary($exhibitPage, $exhibit_page);
                }
              ?>
          </ul>
        </div>
      </nav>
    </div>

  <section class="exhibit-content cf">
    <div class="col-xs-12 col-sm-9 show-wrapper">
      <h1 class="section-title--large">
        <?php
          set_current_record('exhibit_page', $exhibit_page);
          echo metadata('exhibit_page', 'title');
        ?>
      </h1>

    <?php
      print mlibrary_new_render_exhibit_page($exhibit_page);

      $children = $exhibit_page->getChildPages();
      foreach ((array) $children as $child) {
        $slug = html_escape($child['slug']);
        $title = metadata($child, 'title');
        $page_html = mlibrary_new_render_exhibit_page($child);
        release_object($child);
        ?>
        <section>
          <h2 class="sub-section col-xs-12" id="<?php echo $slug; ?>">
            <?php echo $title; ?>
          </h2>
          <?php echo $page_html ?>
        </section>
        <?php
      }
    ?>
  </div> 
  <div class="col-xs-12 col-sm-9 col-sm-offset-3 padding-0">
    <div class="section-nav">
          <?php $navigate_previous_exhibit = mlibrary_new_exhibit_builder_previous_link_to_exhibit($exhibit, $exhibit_page);?>
          <?php if (!empty($navigate_previous_exhibit)){ ?>  
                    <section class="section-nav-panel">
                         <figure class="section-nav-image"><?php echo $navigate_previous_exhibit["image"]; ?></figure>
                         <div class="section-link-prev"><?php echo $navigate_previous_exhibit["url-link"]; ?></div>
                         <div class="section-nav-text">
                            <p class="section-title"><?php echo $navigate_previous_exhibit["title"];;?></p>
                         </div>
                    </section> 
          <?php } ?>
          
          <?php $navigate_next_exhibit = mlibrary_new_exhibit_builder_next_link_to_exhibit($exhibit, $exhibit_page);?>
          <?php if (!empty($navigate_next_exhibit)) { ?>
                  <section class="section-nav-panel">
                      <figure class="section-nav-image"><?php echo $navigate_next_exhibit["image"]; ?></figure>
                      <div class="section-link-next"><?php echo $navigate_next_exhibit["url-link"];?></div>
                      <div class="section-nav-text">
                           <p class="section-title"><?php echo $navigate_next_exhibit["title"]; ?></p>
                      </div>
                  </section> 
          <?php } ?>
    </div>
   </div>
  </section>
  </div>
</div>
<?php }?>
<?php echo foot(); ?>
