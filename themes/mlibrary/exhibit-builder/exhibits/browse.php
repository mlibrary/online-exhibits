<?php $title = __('Browse Exhibits');
//echo head(array('title'=>$title,'bodyid'=>'exhibit','bodyclass'=>'browse')); 
echo head(array('title' =>$title, 'bodyid'=>'exhibit', 'bodyclass' => 'exhibits browse'));?>

<?php $awkward_gallery_setting=get_theme_option('Featured Image Gallery') ? get_theme_option('Featured Image Gallery') : 'yes'; ?>
  <h1><?php echo $title; ?> <?php echo __('(%s total)', $total_results); ?></h1>


<div id="primary" class="browse">

        <?php if (count($exhibits) > 0): ?>

        <nav class="navigation" id="secondary-nav">
            <?php /*echo nav(array(
                  array(
                  'label'=>'Browse',
                  'uri'=>url('exhibits')
                  ),
                  array(
                  'label'=>'Browse by Tag',
                  'uri'=> url('exhibits/tags')
                  )
                  )); Tag will not be used bc low percentage of people use it*/
                  ?>
    </nav>
<?php echo pagination_links(); ?>

   <div id="exhibits">
    <?php
        $exhibitCount = 0;
        $first_exhibit='false';?>

    <?php foreach (loop('exhibit') as $exhibit):
    if($exhibit['title']!='Galleries'){ ?>
        <?php   $theme_options_array = $exhibit->getThemeOptions(); ?>
        <?php //if (mlibrary_exhibit_gallery()!='gallery'){?>
                          <div class="exhibit <?php if ($first_exhibit=='false') echo 'first';  ?>">
                          <?php $first_exhibit='true';?>
                                <h2><?php echo link_to_exhibit(); ?></h2>
                          <?php $theme_options_array['exhibitimage'] = get_image_attached_to_exhibits($exhibit->id);
                                $Exhibit_image = $theme_options_array['exhibitimage'];
                                if (!empty($Exhibit_image))
                                 echo '<img src="'.WEB_FILES.$Exhibit_image['image_name'].'" alt="'.$Exhibit_image['image_title'].'" />';
                                else
                                         echo('<img src="'.img("mlibrary_galleryDefault.jpg").'" alt="Mlibrary default image"/>');
                                               if($exhibitDescription = metadata('exhibit', 'description', array('snippet'=>300))): ?>
                   <p class="exhibits-description"><?php echo $exhibitDescription; ?></p>
            <?php endif; ?>

                                <?php echo '<p class="tags">'.tag_string($exhibit, url('exhibits/browse?tags=')).'</p>';?>
                    </div>
                <?php  }
     endforeach; ?>

    <?php echo pagination_links(); ?>
 </div>
    <?php else: ?>
        <p>There are no exhibits available yet.</p>
        <?php endif; ?>

</div>


<?php //}?>
<?php echo foot(); ?>                              
