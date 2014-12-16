<?php
  $item_title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
  if (empty($item_title)) { $item_title = __('[Untitled]'); }

  echo head(
    array(
      'title'     => $item_title,
      'bodyid'    => 'items',
      'bodyclass' => 'show item'
    )
  );

  if (isset($_GET['page'])) {
    echo '<div class="button exhibit-item-back-button">' .
        '<a href="' . url('items') . '?page=' . $_GET['page'] . '">Return to Item Archive</a>
      </div>';
  }
?>

<?php echo '<h1>' . $item_title . '</h1>'; ?>

<div id="primary">
  <?php
    $html_thumnailsize_image = "";
    $fullsizeimage = "";
    $image_index = 0;
    $audio_file = false;
    $item_type = (empty($item->getItemType()->name)) ? 'Image' : $item->getItemType()->name ;
    $theme_name = (isset($exhibit->theme)) ? $exhibit->theme : 'mlibrary';

    $audio = array(
      'application/ogg',
      'audio/aac',
      'audio/aiff',
      'audio/midi',
      'audio/mp3',
      'audio/mp4',
      'audio/mpeg',
      'audio/mpeg3',
      'audio/mpegaudio',
      'audio/mpg',
      'audio/ogg',
      'audio/x-mp3',
      'audio/x-mp4',
      'audio/x-mpeg',
      'audio/x-mpeg3',
      'audio/x-midi',
      'audio/x-mpegaudio',
      'audio/x-mpg',
      'audio/x-ogg',
      'application/octet-stream'
    );

    if ($item_type != 'Video') {
      mlibrary_display_still_image('item', $image_index, $audio, $theme_name);
    }

    if (!$fullsizeimage && ($audio_file || ($item_type == 'Sound'))) {
      // if first file is an audio file then display a default image for sound file.
      echo '<img src="' . img('audio_default02.gif') . '" alt="Oops" /></div>'; //item-images
    } elseif ($item_type == 'Video') {
      echo mlibrary_display_video('item');
    }

    echo mlibrary_metadata_sideinfo('item');

    // The following function prints all the the metadata associated with an item: Dublin Core, extra element sets, etc.
    // See http://omeka.org/codex or the examples on items/browse for information on how to print only select metadata fields.
    $rendered_item_metatdata = all_element_texts('item');
    if (!empty($rendered_item_metatdata)) {
      echo '<div id="item-metadata">' . $rendered_item_metatdata . '</div>';
    }

    fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item));

    echo mlibrary_add_vars_to_href(
      '<ul class="item-pagination navigation">
        <li id="previous-item" class="button">' .
          link_to_previous_item_show('Previous Item') .
        '</li>
        <li id="next-item" class="next button">' .
          link_to_next_item_show('Next Item') .
        '</li>
      </ul>',
      [ 'page' => (isset($_GET['page'])) ? $_GET['page'] : '1' ]
    );

  ?>
</div>

<script type="text/javascript">
  jQuery(function(){
    jQuery("#item-images").data('curimg','img1');

    jQuery(".square_thumbnail").click(function(e){
      var target = jQuery(this);
      var classList = target.attr('class').split(/\s+/);
      var imgClass = classList[1]; //hardcoded, but simplest way to get to specific class, without regex and loop

      target.siblings(".file-metadata").hide();
      jQuery("#fsize_images div").hide();
      target.siblings(".file-metadata." + imgClass).show();

      var fullsize = target.siblings('#fsize_images').find("." + imgClass);

      if (fullsize.length > 0 && fullsize.hasClass('fullsize')) {
        fullsize.show();
      } else {
        jQuery("#fsize_images").html(imagesJSON[imgClass] + jQuery("#fsize_images").html());
        jQuery("a.fancyitem").fancybox(jQuery(document).data('fboxSettings'));
      }
    });
  });
</script>

<?php echo foot(); ?>
