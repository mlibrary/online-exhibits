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
  // if the item is part of items archive
  elseif (isset($_GET['page'])) {
        $pageClean = html_escape($_GET['page']);
        echo '<div class="button exhibit-item-back-button">' .
        '<a href="' . url('items') . '?page=' . $pageClean . '">Return to Item Archive</a>
      </div>';
  }
?>
<h1><?php echo $item_title; ?></h1>
<div id="primary">
  <?php
    $index_file = 0;
    $html_thumnailsize_image = "";
    $html_fullsize_image ='';
    $fullsizeimage = "";
    $image_index = 0;
    $audio_file = false;
    $item_type = (empty($item->getItemType()->name)) ? 'Image' : $item->getItemType()->name;
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

    if ($item_type != 'Video') { ?>
       <div id="item-images">
         <?php
            $openLayersZoomobject = new openLayersZoomPlugin();
            $tile_sources = [];
            foreach ($item->Files as $file) {
              $image_index++;
              $tile_url = "";
              $filePath = FILES_DIR.'/'.$file->getStoragePath('original');
              // Select Zoom option for the file
              if ($openLayersZoomobject->isZoomed($file) and file_exists($filePath)) {
                $index_file = $index_file + 1;
                $vid = 'os' . $index_file;
                list($width, $height, $type, $attr) = getimagesize($filePath);

                list($root, $ext) = $openLayersZoomobject->_getRootAndExtension($file->filename);
                $tile_url = $openLayersZoomobject->getTileUrl($file);
                $tile_size = 256;
                $tier_size_in_tiles = [];
                $tile_count_up_to_tier = [0];
                $resolution = 1;
                $resolutions = [];
                while ($width > $tile_size || $height > $tile_size) {
                  $resolutions[] = $resolution;
                  $width_in_tiles = ceil($width / $tile_size);
                  $height_in_tiles = ceil($height / $tile_size);
                  $tier_size_in_tiles[] = [$width_in_tiles, $height_in_tiles];
                  $tile_size *= 2;
                  $resolution *= 2;
                }
                $tier_size_in_tiles[] = [1, 1];
                $tier_count = count($tier_size_in_tiles);
                for ($i = count($tier_size_in_tiles) - 1 ; $i < 0; --$i) {
                  $width_in_tiles = $tier_size_in_tiles[$i][0];
                  $height_in_tiles = $tier_size_in_tiles[$i][1];
                  $tile_count_up_to_tier[] =
                    $tile_count_up_to_tier[count($tile_count_up_to_tier) - 1] +
                    $width_in_tiles * $height_in_tiles;
                }

                $tile_sources[] = [
                  'tileUrl' => $tile_url,
                  'origUrl' => file_display_url($file, 'original'),
                  'imageWidth' => $width,
                  'imageHeight' => $height,
                  'tierSizeInTiles' => array_reverse($tier_size_in_tiles),
                  'resolutions' => array_reverse($resolutions),
                  'tileCountUpToTier' => $tile_count_up_to_tier,
                ];

              } //endif
              else {  //zoom option not selected

                $html_fullsize_image .= file_markup(
                  $file,
                   array(
                    'imageSize' => 'fullsize',
                    'imgAttributes'=> array(
                    'alt' => strip_formatting(metadata('item',array('Dublin Core', 'Title')))
                  ),
                  'linkAttributes' => array(
                    'class'=> 'fancyitem',
                    'title' => strip_formatting(metadata(
                      'item',array('Dublin Core', 'Title')))
                    )
                  ),
                  array('class' =>'fullsize', 'style' =>($image_index == 1 ? ' ' : 'display:none'), 'id'=>'page'.$image_index)
                );
                 }//end of it is not zoomed file
           } //end loop for files in item

          if ($index_file > 0) { ?>
            <div id="fsize_images">
            <!-- <div id="<?php echo $vid ?>" style="height: 600px; width: 100%;"></div>-->
              <div id="image-zoomer-os" style="height: 600px; width: 100%;"></div>
              <?php
                $os = [
                  'id' => 'image-zoomer-os',
                  'prefixUrl' => WEB_ROOT.'/themes/mlibrary/items/openseadragon/images/',
                  'showNavigator' => false,
                  'animationTime' => 0.5,
                  'showRotationControl' => false,
                  'sequenceMode' => true,
                  'autoHideControls' => false,
                  'constrainDuringPan' => true,
                  'tileSources' => [],
                ];
              ?>
              <script type="text/javascript">
                var tile_sources = <?php echo json_encode($tile_sources); ?>;
                var os_data = <?php echo json_encode($os); ?>;
                for (i = 0; i < tile_sources.length; ++i) {
                  os_data.tileSources[i] = {
                    height: tile_sources[i].imageHeight,
                    width: tile_sources[i].imageWidth,
                    tileSize: 256,
                    minLevel: 8,
                    getTileUrl: (function(tile_source) {
                      return function(level, x, y) {
                        console.log([x, y , z]);
                        var z = level - 8;
                        if (z < 0) return null;
                        var tileIndex = (y * tile_source.tierSizeInTiles[z][0]) + x + tile_source.tileCountUpToTier[z];
                        console.log(tileIndex);
                        var tileGroup = (tileIndex / 256) | 0;
                        console.log(tileGroup);
                        return tile_source.tileUrl + '/TileGroup' + tileGroup + '/' + [z, x, y].join('-') + '.jpg';
                    };})(tile_sources[i])
                  }
                }
                os = OpenSeadragon(os_data);
                os.addViewerInputHook({
                  hooks: [
                    {tracker: 'viewer', handler: 'scrollHandler', hookHandler: onViewerScroll}
                  ]
                });

                function onViewerScroll(event) {
                  // Disable mousewheel zoom on the viewer and let the original mousewheel events bubble
                  if (!event.isTouchEvent) {
                    event.preventDefaultAction = true;
                    return true;
                  }
                }

                function showPage(i)
                {
                  os.goToPage(i-1);
                  jQuery('.square_thumbnail').removeClass('current');
                  jQuery('#page' + i).addClass('current');
                }
              </script>
            </div> <!--fsize_images-->
            <aside class="openseadragon-help">
              <p>Click on or tab onto the image to zoom in and out, as well as move it around.</p>
              <dl>
                <dt>Zoom in</dt>
                <dd>click on the image <strong>or</strong> hold the shift key and use the up key</dd>
                <dt>Zoom out</dt>
                <dd>hold the shift key and click on the image <strong>or</strong> hold the shift key and use the down key</dd>
                <dt>Move the image around</dt>
               <dd>click and hold on the image and drag it <strong>or</strong> use the arrows</dd>
              </dl>
           </aside>
          <?php }//index_file>0
          else {
                  echo '<div class="item-images"><div id="fsize_images">'.$html_fullsize_image.
                       '</div></div>';?>
                  <script type="text/javascript">
                    jQuery(document).ready(function() {
                    jQuery(".square_thumbnail").click(function(e){
                         jQuery('.square_thumbnail').removeClass('current');
                         jQuery(this).addClass('current');
                         jQuery(".fullsize").hide();
                         jQuery("#"+jQuery(this).data("image")).show();
                    });
                   });
                  </script>

             <?php }
    $index = 0;
    echo '<div class="image_zoom_thumbnail_container">';
    foreach ($item->Files as $file) {
      $index++;
      if ($openLayersZoomobject->isZoomed($file)) {
        echo '<a href="javascript:showPage(' . $index . ');">';
        echo file_markup(
          $file,
          ['imageSize' => 'square_thumbnail', 'linkToFile' => false],
          ['class' => 'square_thumbnail' . ($index == 1 ? ' current' : ''),'id' => 'page' . $index]
        );
        echo '</a>';
      }
      else {
       /**
        * For when we figure out what to do with mixed images.
        */
        echo '<a href="javascript:;">';
        echo file_markup(
          $file,
          ['imageSize' => 'square_thumbnail', 'linkToFile' => false],
          ['class' => 'square_thumbnail' . ($index == 1 ? ' current' : ''),'data-image'=>'page'.$index]
         );
        echo '</a>';
      }
    }
    echo '</div>';?>
<?php    echo '</div>'; //item-images
   }

    if (!$fullsizeimage && ($audio_file || ($item_type == 'Sound'))) {
       // if first file is an audio file then display a default image for sound file.
       echo '<img src="' . img('audio_default02.gif') . '" alt="Oops" /></div>'; //item-images
    } elseif ($item_type == 'Video') {
       echo mlibrary_display_video('item');
    }?>

   <?php echo mlibrary_metadata_sideinfo('item');
    // The following function prints all the the metadata associated with an item: Dublin Core, extra element sets, etc.
    // See http://omeka.org/codex or the examples on items/browse for information on how to print only select metadata fields.
    $rendered_item_metatdata = all_element_texts('item');

    if (!empty($rendered_item_metatdata)) {
      echo '<div id="item-metadata">' . $rendered_item_metatdata . '</div>';
    }

  if (isset($_GET['page']) && !isset($_GET['exhibit'])) {
    echo mlibrary_add_vars_to_href(
      '<ul class="item-pagination navigation">
        <li id="previous-item" class="button">' .
          link_to_previous_item_show('Previous Item') .
        '</li>
        <li id="next-item" class="next button">' .
          link_to_next_item_show('Next Item') .
        '</li>
      </ul>',
      [ 'page' => (isset($_GET['page'])) ? html_escape($_GET['page']) : '1' ]

    );
  }
  ?>
</div> <!--primary-->

<?php echo foot(); ?>
