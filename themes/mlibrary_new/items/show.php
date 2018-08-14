<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
  $item_title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
  if (empty($item_title)) { $item_title = __('[Untitled]'); }

  echo head(
    array(
      'title'     => $item_title,
      'bodyid'    => 'items',
      'bodyclass' => 'show item'
    )
  );
 // if the item is part of the exhibit builder layout
 if (isset($_GET['exhibit']) && isset($_GET['page'])) {
    $pageClean = html_escape($_GET['page']);
    $exhibitClean = html_escape($_GET['exhibit']);
    echo '<div class="button exhibit-item-back-button"><a href="' .
        exhibit_builder_exhibit_uri(
          get_record_by_id('exhibit', $exhibitClean),
          get_record_by_id('exhibit_page', $pageClean)
        ) .
      '">Return to Exhibit</a></div>';
  }
?>
<h1><?php echo $item_title; ?></h1>
<div id="primary">
  <?php
    $index_file = 0;
    $audio_file = false;
    $item_type = (empty($item->getItemType()->name)) ? 'Image' : $item->getItemType()->name;
    $theme_name = (isset($exhibit->theme)) ? $exhibit->theme : 'mlibrary_new';


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

    if ($item_type != 'Video') { ?>
       <div id="item-images">
         <?php
              $filePath = FILES_DIR.'/'.$file->getStoragePath('original');?>
              <div id="fsize_images">
              <div id="image-zoomer-os" style="height: 600px; width: 100%;"></div>
              <script type="text/javascript">
              var $map = document.querySelector("#image-zoomer-os");
             // var $preview = $("#image-zoomer-os");
              var $toolbar;

              var identifier = $map.dataset.identifier;
              var image_base;

              var viewer; var mode;
                  // -- info_url is the URL to the IIIF info end point for the image
              //var info_url = '/online-exhibits-2.5.1/iiif/002b7e2b8a64911b76bb8d35535e39ed.jpg/info.json';
              var info_url = 'https://quod.lib.umich.edu/cgi/i/image/api/tile/sclaudubon:B6719890:29376_0019/info.json';
    viewer = OpenSeadragon({
        id: "image-zoomer-os",
        prefixUrl: "//openseadragon.github.io/openseadragon/images/",
        gestureSettingsMouse: {
          scrollToZoom: false,
          clickToZoom: false,
          dblClickToZoom: true,
          flickEnabled: true,
          pinchRotate: true
        },
        gestureSettingsTouch: {
    pinchRotate: true
        },
        showNavigationControl: true,
zoomInButton: 'action-zoom-in',
        zoomOutButton: 'action-zoom-out',
        rotateLeftButton: 'action-rotate-left',
        rotateRightButton: 'action-rotate-right',
        homeButton: 'action-reset-viewer'
    });

    viewer.addHandler('zoom', function(e) {
      //$(".span-zoom-status").text(Math.floor(e.zoom * 100) + '%');
    })

    viewer.open(info_url);

      function onViewerScroll(event) {
                  // Disable mousewheel zoom on the viewer and let the original mousewheel events bubble
        if (!event.isTouchEvent) {
             event.preventDefaultAction = true;
             return true;
         }
      }
      var rotateViewer = function(delta) {
          var deg = viewer.viewport.getRotation();
          var next_deg = deg + delta;
          if ( next_deg < 0 ) { next_deg = 360 + next_deg; }
          viewer.viewport.setRotation(next_deg);     
      }
              </script>
            </div> <!--fsize_images-->
    <?php echo '</div>'; //item-images
   }

    if (!$fullsizeimage && ($audio_file || ($item_type == 'Sound'))) {
       // if first file is an audio file then display a default image for sound file.
     echo '<img src="' . img('audio_default02.gif') . '" alt="Oops" /></div>'; //item-images
    } elseif ($item_type == 'Video') {
       echo mlibrary_display_video('item');
    }?>

</div> <!--primary-->

<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item));

// The page isn't injected by the controller
$page = get_db()->getTable('ExhibitPage')->find($_GET['page']);

// Navigation
$next = mlibrary_new_item_sequence_next($exhibit->id, $page->id, $item->id);
if ($next) {
  $nextUrl = WEB_ROOT . "/exhibits/show/{$exhibit->slug}" .
  "/item/{$next->id}?exhibit={$exhibit->id}&page={$next->page_id}";
}

$prev = mlibrary_new_item_sequence_prev($exhibit->id, $page->id, $item->id);
if ($prev) {
$prevUrl = WEB_ROOT . "/exhibits/show/{$exhibit->slug}" .
  "/item/{$prev->id}?exhibit={$exhibit->id}&page={$prev->page_id}";
}

// Metadata
$title = metadata('item', array('Dublin Core', 'Title'));
$creator = metadata('item', array('Dublin Core', 'Creator'));
$date = metadata('item', array('Dublin Core', 'Date'));
$source = metadata('item', array('Dublin Core', 'Source'));
$format = metadata('item', array('Dublin Core', 'Format'));
$rights = metadata('item', array('Dublin Core', 'Rights'));
$files = $item->getFiles();
if ($files) {
  $file = $files[0];
} else {
  $file = null;
}
$itemType = (empty($item->getItemType()->name)) ?
  'Image' :
  $item->getItemType()->name;

?>
<h1><?php print $title; ?></h1>
<dl>
  <dt>Item Type</dt>
  <dd><?php print $itemType; ?></dd>
  <dt>Creator</dt>
  <dd><?php print $creator; ?></dd>
  <dt>Date</dt>
  <dd><?php print $date; ?></dd>
  <dt>Source</dt>
  <dd><?php print $source; ?></dd>
  <dt>Format</dt>
  <dd><?php print $format; ?></dd>
  <dt>rights</dt>
  <dd><?php print $rights; ?></dd>
    <dt>file</dt>
  <?php if ($file) { ?>
    <dd><?php print $file->id; ?></dd>
  <?php } else { ?>
    <dd>No file attached to item</dd>
  <?php } ?>
  <dt>prev</dt>
  <?php if ($prev) { ?>
    <dd>
      <a href="<?php print html_escape($prevUrl); ?>">
        <?php print html_escape($prevUrl); ?>
      </a>
    </dd>
    <dd><?php print $prev->id; ?></dd>
    <dd><?php print $prev->block_id; ?></dd>
    <dd><?php print $prev->page_id; ?></dd>
    <dd><?php print $prev->exhibit_id; ?></dd>
  <?php } else { ?>
    <dd>No prev item</dd>
  <?php } ?>
  <?php if ($next) { ?>
    <dt>next</dt>
    <dd>
      <a href="<?php print html_escape($nextUrl); ?>">
        <?php print html_escape($nextUrl); ?>
      </a>
    </dd>
    <dd><?php print $next->id; ?></dd>
    <dd><?php print $next->block_id; ?></dd>
    <dd><?php print $next->page_id; ?></dd>
    <dd><?php print $next->exhibit_id; ?></dd>
  <?php } else { ?>
    <dd>No next item</dd>
  <?php } ?>
</dl>
<?php echo foot();
