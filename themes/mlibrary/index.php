<?php echo head(array('bodyid'=>'home')); ?>
<div id="primary">
  <section id="greeting" >
    <h1>Online Exhibits</h1>
    <h2>Collected, Curated, <span>Celebrated.</span></h2>
    <p>
      Explore and discover inspiring collections of <strong>art</strong>, <strong>literature</strong>, <strong>culture</strong>, and <strong>history</strong>!
    </p>

    <p>
      <a href="<?php echo url('exhibits'); ?>">Browse</a> all the exhibits to find what inspires you!
    </p>

    <p>
      Flip through the <a href="<?php echo url('items'); ?>">archive</a> to see the full listing of items!
    </p>

    <p>
      Brought to you by the <a href="http://www.lib.umich.edu">University of Michigan Library</a>.
    </p>
  </section>
  <section id="featured-exhibit-wrap">
    <h2>Featured Exhibits</h2>
      <?php
        if ((get_theme_option('Display Featured Exhibit')) && function_exists('exhibit_builder_display_random_featured_exhibit')) {
          $feature_exhibits = mlibrary_exhibit_builder_display_random_featured_exhibit();
          $feature_exhibit = array_pop($feature_exhibits);
          $exhibit_image = get_image_attached_to_exhibits($feature_exhibit->id);
          $image_size = getimagesize(WEB_FILES . $exhibit_image['image_name']);

          // If we successfully got the image size...
          if ($image_size) {
            $img_src = WEB_FILES . $exhibit_image['image_name'];

            // Adjust the styling based on the aspect ratio of the image...
            if (($image_size[0] / $image_size[1]) > 1.6) {
              $wrap_class = 'landscape';
              $figure_style = ' style="height: ' . ($image_size[1] / $image_size[0]) * 500 . 'px;" ';
            } else {
              $wrap_class = 'portrait';
              $figure_style = '';
            }

          // ...if we didn't get an image...
          } else {
            $wrap_class = 'no-image';
            $figure_style = '';
            $img_src = '';
          }

          echo  '<a class="figure-wrap ' . $wrap_class . '" href="' . exhibit_builder_exhibit_uri($feature_exhibit) . '">' .
                  '<figure' . $figure_style . '>' .
                    '<img src="' . $img_src . '" alt="" />' .
                    '<figcaption><span>' . $feature_exhibit->title . '</span></figcaption>' .
                  '</figure>' .
                '</a';
        }
      ?>
  </section>
</div>
<section id="recent-exhibits">
  <h2> Recent Exhibits </h2>
  <?php
    set_loop_records('exhibits', exhibit_builder_recent_exhibits(4));
    if (has_loop_records('exhibits')):
      foreach (loop('exhibits') as $exhibits):
  ?>
        <article class="cf">
          <div class="exhibit-body">
          <h3><?php echo link_to_exhibit(); ?></h3>

          <div class="img-wrap">
              <?php
                $Exhibit_image = get_image_attached_to_exhibits($exhibits->id);
                if (!empty($Exhibit_image)) {
                  echo '<img src="'.WEB_FILES.$Exhibit_image['image_name'].'" alt="'.$Exhibit_image['image_title'].'" />';
                } else {
                  echo('<img src="'.img("mlibrary_galleryDefault.jpg").'" alt="Mlibrary default image"/>');
                }
              ?>
            </div>

            <?php
              if($exhibitDescription = metadata('exhibit', 'description', array('snippet'=>300))) {
                echo '<p class="exhibit-description">' . $exhibitDescription . '</p>';
              }
            ?>
          </div>

          <?php
            $tags = str_replace(';', '', tag_string($exhibits,url('exhibits/browse')));
            if (!empty($tags)) { echo '<div class="tags"> <h4>Tags</h4> ' . $tags . '</div>'; }
          ?>
        </article>
      <?php endforeach; ?>
  <?php endif; ?>

  <div class="button-wrap">
    <div class="button">
      <a href="<?php echo url('exhibits'); ?>">Browse All Exhibits</a>
    </div>
  </div>
</section>
<?php echo foot(); ?>
