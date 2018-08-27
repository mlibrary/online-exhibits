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

 if (isset($_GET['exhibit'])){
      // dipslay the Back link for exhibit and gallery page
     $exhibit_page = get_record_by_id('exhibit_page',$page_id);
     $return_link = ($exhibit_page['slug']=='gallery') ? 'Back to Image Gallery': 'Back to Exhibit';

     echo '<div class="exhibit-item-back-button"><a href="' .
        html_escape(exhibit_builder_exhibit_uri($exhibit,$exhibit_page)).
      '">'.$return_link.'</a></div>';

     $image_gallery_link = '<div class="exhibit-item-back-button"><a href="'.
                           html_escape(exhibit_builder_exhibit_uri($exhibit).'/gallery').
                           '">View Exhibit Image Gallery</a></div>';

     $gallery_plugin_active = plugin_is_active('ExhibitGalleryPage');

     $exhibit_image_gallery_set = isset($gallery_plugin_active)? $image_gallery_link : '';
   }

?>

<!--Breadcrumb Bar-->
<section class="row">
  <div class="col-xs-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
        <li class="breadcrumb-item"><?php echo metadata('exhibit','title',array('no_escape' => true)); ?></li>
        <li class="breadcrumb-item active"><?php echo $item_title; ?></li>
      </ol>
  </div>
</section>
<!--End breadcrumb bar-->

<div class="col-xs-12">
<h1 class="item-title"><?php echo $item_title; ?></h1>
<div class="view-exhibit--link">
<?php // display the View Exhibit Image Gallery
 if (isset($exhibit_image_gallery_set)) {
     echo $exhibit_image_gallery_set;
 }
?>
</div>
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
             echo mlibrary_new_display_video('item');
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

<div class="row">
  <dl>
  <div class="previous-item--nav  col-xs-12 col-sm-6 col-md-4">
    <dt class="previous-item--icon">Previous item</dt>
      <?php if ($prev) { ?>
        <dd>
          <a href="<?php print html_escape($prevUrl); ?>">
            <?php print html_escape($prevUrl); ?>
          </a>
        </dd>
      <?php } else { ?>
        <dd>No previous item</dd>
      <?php } ?>
      </div>

      <div class="next-item--nav col-xs-12 col-sm-6 col-md-4 col-md-offset-4">
      <?php if ($next) { ?>
        <dt class="next-item--icon">Next item</dt>
        <dd>
          <a href="<?php print html_escape($nextUrl); ?>">
            <?php print html_escape($nextUrl); ?>
          </a>
        </dd>
      <?php } else { ?>
        <dd>No next item</dd>
      <?php } ?>
      </div>
  </dl>
</div>

<div class="col-md-6 col-md-offset-3">
  <h2 class="metadata--heading">Item Data</h2>
  <div class="detail-nav-border"></div>
  <dl class="metadata--list">
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
    <dt>Rights</dt>
    <dd><?php print $rights; ?></dd>
  </dl>
</div>
</div>

<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item));
echo foot();
