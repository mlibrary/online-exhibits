<?php
 /**
  * Copyright (c) 2018, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
  set_theme_option('display_header','1');
  echo head(
    array(
      'title' => 'Summary of ' . metadata('exhibit','title'),
      'bodyid'=>'exhibit','bodyclass'=>'summary'
    )
  );

?>

<!--Breadcrumb and Share Bar-->
<section class="row">
 <div class="col-xs-12">
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
 </div>
</section>
<!--End breadcrumb and share bar-->

  <div class="exhibit-introduction">
    <div class="col-xs-12">
      <h1><?php echo metadata('exhibit','title',array('no_escape' => true)); ?></h1>
      <p class="credits">Curated by <?php echo metadata('exhibit','credits'); ?></p>
    </div>
  </div>

  <div id="primary">
    <section>
      <div class="col-xs-12 col-sm-3">
        <nav class="exhibit-navigation" data-spy="affix" data-offset-top="650" data-offset-bottom="100">
          <div class="nav-text-inline">
          <h2 class="nav-text-inline-heading">Exhibit Contents</h2>
           <button class="navbar-toggler nav-text-inline-button" type="button" data-toggle="collapse" data-target="#summary-nav-toggle" aria-controls="nav-toggle" aria-expanded="false" aria-label="Toggle navigation">
            <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>  
            </button>
          </div>
        <div class="exhibit-overview collapse navbar-collapse" id="summary-nav-toggle">
        <ul id="exhibit-pages" class="exhibit-nav-list exhibit-pages-summary">
           <li class="active"><?php echo link_to_exhibit('Introduction'); ?></li>
          <?php
            set_exhibit_pages_for_loop_by_exhibit();
            foreach (loop('exhibit_page') as $exhibitPage) {
              echo exhibit_builder_page_summary($exhibitPage);
            }
          ?>
        </ul>
        </nav>
      </div>
    </section>

  <section id="summary-view" class="exhibit-content cf">

    <div class="col-xs-12 col-sm-9">
      <h2 class="section-title--large"> Introduction </h2>
      <?php echo metadata('exhibit','description',array('no_escape' => true)); ?>
      <div class = "exhibit-theme">
        <?php  set_exhibit_pages_for_loop_by_exhibit();
               foreach (loop('exhibit_page') as $exhibitPage) {?>
                               <?php
                                  if (metadata('exhibit_page','title')!= 'Gallery') {
                                      $page_card_info = mlibrary_new_display_exhibit_card_info($exhibitPage);
                                      if(!empty($page_card_info)) {
                                        $uri = exhibit_builder_exhibit_uri($exhibit, $exhibitPage);?>
                                         <div class="exhibit-theme-item panel panel-default">
                                             <a href= <?php echo html_escape($uri);?> >
                                               <div class="panel-heading"><?php  echo $page_card_info["image"];?></div>
                                               <div class="card-info panel-body"><h3 class="panel-card-title"><?php echo html_escape($page_card_info["title"]);?></h3>
                                               <p class="panel-card-text"><?php echo html_escape($page_card_info["description"]);?></p></div>
                                             </a>                           
                                         </div>
                                    <?php }}}?>  
      </div>
    </div>
  </section>
</div>

<?php echo foot(); ?>
