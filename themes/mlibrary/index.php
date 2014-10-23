<?php echo head(array('bodyid'=>'home')); ?>
<div id="primary">
  <div id="greeting" >
    <h1>Online Exhibits</h1>
    <h2>Collected, Curated, <span>Celebrated.</span></h2>
    <p>
      explore and discover inspiring collections of <strong>art</strong>, <strong>literature</strong>, <strong>culture</strong>, and <strong>history</strong>! Brought to you by the <a href="http://www.lib.umich.edu">University of Michigan Library</a>.
    </p>

    <ul>
      <li>
        <a href="<?php echo url('exhibits'); ?>">browse</a> all the exhibits to find what inspires you!
      </li>

      <li>
        flip through the <a href="<?php echo url('items'); ?>">archive</a> to see the full listing of items!
      </li>
    </ul>
  </div>

  <h3 id="featured-title"><span>Featured</span> Exhibits</h3>
  <div id="showcase" class="showcase">
    <?php
      if ((get_theme_option('Display Featured Exhibit')) && function_exists('exhibit_builder_display_random_featured_exhibit')) {
        // get feature exhibits, limit to 4 exhibits
        $feature_exhibits =  mlibrary_exhibit_builder_display_random_featured_exhibit();
        foreach (loop('exhibit',$feature_exhibits) as $feature_exhibit) {
          //get_image_attached_to_exhibits is built in the mlibrary plugin to get the image attached to each Exhibit.
          $Exhibit_image = get_image_attached_to_exhibits($feature_exhibit->id);
          if (!empty($Exhibit_image)) {
            echo  '<div class="showcase-slide">' .
                    '<a href="' . exhibit_builder_exhibit_uri($feature_exhibit) . '">' .
                      '<img src="' . WEB_FILES . $Exhibit_image['image_name'] . '" alt="'.$Exhibit_image['image_title'] . '" />' .
                    '</a>' .
                    '<div class="showcase-caption">' .
                      '<h4>' . exhibit_builder_link_to_exhibit($feature_exhibit) . '</h4>' .
                    '</div>' .
                  '<div class="showcase-thumbnail active" style="width:100px;">';

            if (!empty($Exhibit_image)) {
              echo '<img src="' . WEB_FILES . $Exhibit_image['image_name'] . '" alt="' . $Exhibit_image['image_title'] . '" width="44" height="44" />';
            } else {
              echo('<img src="'.img("mlibrary_galleryDefault.jpg").'" alt="Mlibrary default image"/>');
            }

            echo '</div></div>';
          }
        }
      }
    ?>
 </div>
</div>

<h3> Recent Exhibits </h3>
<div id="recent-exhibits">
  <?php
    $first_exhibit='false';
    set_loop_records('exhibits', exhibit_builder_recent_exhibits(4));
    if (has_loop_records('exhibits')):
  ?>
      <ul class="exhibits-list">
        <?php foreach (loop('exhibits') as $exhibits): ?>
          <li class="exhibits <?php if ($first_exhibit=='false') echo 'first';  ?>">
            <?php $first_exhibit='true';?>
            <h3><?php echo link_to_exhibit(); ?></h3>
            <?php
              $Exhibit_image = get_image_attached_to_exhibits($exhibits->id);
              if (!empty($Exhibit_image)) {
                echo '<img src="'.WEB_FILES.$Exhibit_image['image_name'].'" alt="'.$Exhibit_image['image_title'].'" />';
              } else {
                echo('<img src="'.img("mlibrary_galleryDefault.jpg").'" alt="Mlibrary default image"/>');
              }
            ?>

            <?php if($exhibitDescription = metadata('exhibit', 'description', array('snippet'=>300))): ?>
              <p class="exhibits-description"><?php echo $exhibitDescription; ?></p>
            <?php endif; ?>

            <?php echo '<p class="tags">'.tag_string($exhibits,url('exhibits/browse')).'</p>';?>
          </li>
        <?php endforeach; ?>
      </ul>
  <?php endif; ?>

  <div class="button-wrap">
    <div class="button">
      <a href="<?php echo url('exhibits'); ?>">Browse All Exhibits</a>
    </div>
  </div>
</div>

<script type="text/javascript">
  jQuery.noConflict();
  jQuery(document).ready(function() {
    jQuery("#showcase").awShowcase({
      width:                475,
      height:               393,
      auto:                 false,
      interval:             6500,
      continuous:           false,
      loading:              true,
      tooltip_width:        200,
      tooltip_icon_width:   32,
      tooltip_icon_height:  32,
      tooltip_offsetx:      18,
      tooltip_offsety:      0,
      arrows:               true,
      buttons:              false,
      btn_numbers:          true,
      keybord_keys:         true,
      mousetrace:           false,
      pauseonover:          true,
      transition:           'fade', /* vslide/hslide/fade */
      transition_speed:     250,
      show_caption:         'onload', /* onload/onhover/show */
      thumbnails:           true,
      thumbnails_position:  'inside-last', /* outside-last/outside-first/inside-last/inside-first */
      thumbnails_direction: 'horizontal', /* vertical/horizontal */
      thumbnails_slidex:    0 /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */
    });
  });
</script>

<?php echo foot(); ?>
