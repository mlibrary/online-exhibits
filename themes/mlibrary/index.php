<?php echo head(array('bodyid'=>'home'));
?>

<div id="primary">
  <h1>Online Exhibits</h1>
  <section id="greeting" >
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
  <?php
    if (get_theme_option('Display Featured Exhibit')
         && function_exists('exhibit_builder_display_random_featured_exhibit')) {
      $feature_exhibits = mlibrary_exhibit_builder_display_random_featured_exhibit();

      $feature_exhibit = array_pop($feature_exhibits);


     /* if (class_exists('ExhibitBuilderImagePlugin')) {
        $exhibit_image = ImagBelongToExhibitRelationShip::findImageBelongToExhibit($feature_exhibit->id);
      }
      else {
        $exhibit_image = '';
      }*/

      $exhibitImage = record_image($feature_exhibit, 'original', array('alt' => $feature_exhibit->title));

      if (!empty($exhibitImage)) {
           $Exhibit_image = $exhibitImage;

      } else {
           $Exhibit_image = '';

      }

     /* if (!empty($exhibit_image)) {
        $image_size = getimagesize(
           str_replace(
           'https://',
           'http://',
           WEB_FILES . $exhibit_image['image_name']
          )
        );
      }
      else {
      $image_size = '';
      }*/

      $file = $feature_exhibit->getFile();

        if (!$file) {

          //  return false;
              $image_size = '';
        }
        elseif ($file->hasThumbnail()) {

            $file_name = $file['filename'];
          //  print_r(WEB_FILES.'/fullsize/'.$file_name);
            $image_size = getimagesize(
               str_replace(
               'https://',
               'http://',
                WEB_FILES.'/fullsize/'.$file_name
                )
          );
        }
        else {

            $image_size = '';
        }


//$ret = html_entity_decode($Exhibit_image, ENT_COMPAT, 'UTF-8');

/*      if (!empty($Exhibit_image)) {
        $image_size = getimagesize(
           str_replace(
           'https://',
           'http://',
           $Exhibit_image
          )
        );
      }
      else {
        $image_size = '';
      }*/


      // If we successfully got the image size...
     if ($image_size) {
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
      }

      echo '<section id="featured-exhibit-wrap"> <h2>Featured Exhibit</h2>' .
              '<a class="figure-wrap ' . $wrap_class . '" href="' . exhibit_builder_exhibit_uri($feature_exhibit) . '">' .
               '<figure' . $figure_style . '>' .
                 $Exhibit_image .
                 '<figcaption><span>' . $feature_exhibit->title . '</span></figcaption>' .
               '</figure>' .
             '</a>' .
           '</section>';
    }
  ?>
</div>

<section id="recent-exhibits" class="pretty-list">
  <h2 class="item-list-heading"> Recent Exhibits </h2>
  <?php
    if (function_exists('exhibit_builder_recent_exhibits')) {
        $exhibits_records = exhibit_builder_recent_exhibits(4);
        set_loop_records('exhibits', $exhibits_records);
        if (has_loop_records('exhibits')):
         foreach (loop('exhibits') as $exhibits):
 ?>
        <article class="cf">
          <div class="item-body">
            <h3 class="item-heading"><?php echo link_to_exhibit(); ?></h3>
            <div class="img-wrap">
              <?php
                $exhibitImage = record_image($exhibits, 'square_thumbnail', array('alt' => $exhibits->title));
                if (!empty($exhibitImage)) {
                  echo $exhibitImage;
                } else {
                  echo('<img src="'.img("mlibrary_galleryDefault.jpg").'" alt="Mlibrary default image"/>');
                }
              ?>
            </div>

            <?php
              if($exhibitDescription = metadata('exhibit', 'description', array('snippet'=>300,'no_escape' => true))) {
                 echo '<p class="item-description">' . $exhibitDescription . '</p>';
              }
            ?>
          </div>

          <?php
            $tags = str_replace(';', '', tag_string($exhibits,'exhibits/browse'));
            if (!empty($tags)) { echo '<div class="tags"> <h4 class="tags-heading">Tags</h4> ' . $tags . '</div>'; }
          ?>
        </article>
      <?php endforeach; ?>
  <?php endif;
   }
  ?>


  <div class="button-wrap">
    <div class="button">
      <a href="<?php echo url('exhibits'); ?>">Browse All Exhibits</a>
    </div>
  </div>

</section>

<?php echo foot(); ?>
