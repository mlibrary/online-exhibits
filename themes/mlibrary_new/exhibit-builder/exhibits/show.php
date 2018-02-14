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
<section class="row">
  <div class="col-xs-12 col-sm-9">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
        <li class="breadcrumb-item active"><?php echo metadata('exhibit','title',array('no_escape' => true)); ?></li>
      </ol>
  </div>

  <div class="col-xs-12 col-sm-3">
    <ul class="share-this">
      <li>Share this Exhibit:</li>
      <li><a href="https://twitter.com/share" class="twitter-share-button" data-text="I just saw '<?php echo metadata('exhibit','title',array('no_escape' => true)); ?>' at the U-M Library Online Exhibits!" ><span class="sr-only">Tweet</span> </a></li>
    </ul>
   </div>
</section>
<!--End breadcrumb and share bar-->

<div id="primary">
  <div class="col-xs-12">
    <section>
      <div class="col-sm-12 col-md-3">
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
    </section>

 <section class="exhibit-content cf">
    <div class="col-sm-12 col-md-9 show-wrapper">
      <h2>
        <?php
          set_current_record('exhibit_page', $exhibit_page);
          echo metadata('exhibit_page', 'title');
        ?>
      </h2>

      <div>
        <?php exhibit_builder_render_exhibit_page($exhibit_page);?>
      </div>
      <?php $children = $exhibit_page->getChildPages();
               if ($children) {
                 foreach ($children as $child) {?>
                   <h3> <?php echo metadata($child, 'title');?></h3>
                    <div> <?php echo exhibit_builder_render_exhibit_page($child);?> </div>
                    <?php release_object($child);
                 }
              }
         ?>
  </div> 
  <div class="col-sm-12 col-md-9 col-md-offset-3 padding-0">
    <div class="section-nav">
          <?php $navigate_previous_exhibit = mlibrary_new_exhibit_builder_previous_link_to_exhibit($exhibit, $exhibit_page);?>
          <?php if (!empty($navigate_previous_exhibit)){ ?>  
                    <section class="section-nav-panel">
                         <figure class="section-nav-image"><?php echo $navigate_previous_exhibit["image"]; ?></figure>
                         <div class="section-link-prev"><?php echo $navigate_previous_exhibit["url-link"]; ?></div>
                         <div class="section-nav-text">
                              <p class="section-title"><?php echo $navigate_previous_exhibit["title"];;?></p>
                              <p class="section-caption"><?php echo $navigate_previous_exhibit["caption"];?></p>
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
                           <p class="section-caption"><?php echo $navigate_next_exhibit["caption"];?></p>
                      </div>
                  </section> 
          <?php } ?>
    </div>
   </div>
  </section>
</div>

</div>
<?php echo foot(); ?>
