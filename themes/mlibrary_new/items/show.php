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
    
    ));

  if(!empty($_GET['page'])){
       $page_id = (int)$_GET['page']; 
  }

 $exhibit_page = get_record_by_id('exhibit_page',$page_id);

 echo '<div class="exhibit-item-back-button"><a href="' .
        html_escape(exhibit_builder_exhibit_uri($exhibit,$exhibit_page)).
      '">Return to Exhibit</a></div>';
?>
<h1><?php echo $item_title; ?></h1>
<div id="primary">
  <?php
    $file = null;
    $iiifImage_path = null;
    $item_type = (empty($item->getItemType()->name)) ? 'Image' : $item->getItemType()->name;
    
    if ($item_type != 'Video') { ?>
       <div id="item-images">
         <?php
               $files = $item->getFiles();
               if ($files) {
                   $file = $files[0];
                   $iiifImage_path = WEB_ROOT . "/iiif/{$file->id}/info.json"; 
               }?>                
              <div id="fsize_images">
                 <button id="action-zoom-in">Zoom In</button>
                 <span id="span-zoom-status"></span>
                 <button id="action-zoom-out">Zoom Out</button>
                 <button id="action-reset-viewer">Reset View</button>
                 <button id="action-rotate-left">Rotate left</button>
                 <button id="action-rotate-right">Rotate Right</button>
                 <div id = "image-zoomer-os"  data-identifier = "<?php print html_escape($iiifImage_path);?>" 
                      style = "height: 600px; width: 100%;">
                 </div> 
              </div> <!--fsize_images-->
        </div> <!--item-images-->
    <?php } elseif ($item_type == 'Video') {
             echo mlibrary_display_video('item');
    }?>
</div> <!--primary-->

<?php
// Navigation

if (isset($exhibit_page)){
  $next = mlibrary_new_item_sequence_next($exhibit->id, $exhibit_page->id, $item->id);
  if ($next) {
  $nextUrl = WEB_ROOT . "/exhibits/show/{$exhibit->slug}" .
  "/item/{$next->id}?exhibit={$exhibit->id}&page={$next->page_id}";
  }

  $prev = mlibrary_new_item_sequence_prev($exhibit->id, $exhibit_page->id, $item->id);
if ($prev) {
$prevUrl = WEB_ROOT . "/exhibits/show/{$exhibit->slug}" .
  "/item/{$prev->id}?exhibit={$exhibit->id}&page={$prev->page_id}";
}
}else {
$prev ='';
$next ='';
}
// Metadata
$creator = metadata('item', array('Dublin Core', 'Creator'));
$date = metadata('item', array('Dublin Core', 'Date'));
$source = metadata('item', array('Dublin Core', 'Source'));
$description = metadata('item', array('Dublin Core', 'Description'));
$publisher = metadata('item', array('Dublin Core', 'Publisher'));
$contributor = metadata('item', array('Dublin Core', 'Contributor'));
$language = metadata('item', array('Dublin Core', 'Language'));
$type = metadata('item', array('Dublin Core', 'Type'));
$format = metadata('item', array('Dublin Core', 'Format'));
$rights = metadata('item', array('Dublin Core', 'Rights'));

?>
<h1><?php print $item_title; ?></h1>
<dl>
  <dt>Creator</dt>
  <dd><?php print $creator; ?></dd>
  <dt>Date</dt>
  <dd><?php print $date; ?></dd>
  <dt>Source</dt>
  <dd><?php print $source; ?></dd>
  <dt>Description</dt>
  <dd><?php print $description; ?></dd>
  <dt>Publisher</dt>
  <dd><?php print $publisher; ?></dd>
  <dt>Contributor</dt>
  <dd><?php print $contributor; ?></dd>
  <dt>Language</dt>
  <dd><?php print $language; ?></dd>
  <dt>Type</dt>
  <dd><?php print $type; ?></dd>
  <dt>Format</dt>
  <dd><?php print $format; ?></dd>
  <dt>rights</dt>
  <dd><?php print $rights; ?></dd>
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

<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item));
echo foot();
