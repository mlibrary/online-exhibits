<?php
/**
 * Copyright (c) 2016, Regents of the University of Michigan.
 * All rights reserved. See LICENSE.txt for details.
 */
$item_title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
if (empty($item_title)) {
   $item_title = __('[Untitled]');
}

echo head(
  array(
    'title'     => $item_title,
    'bodyid'    => 'items',
    'bodyclass' => 'show item'
  )
);

if (isset($_GET['page'])) {
  $page_id = (int) $_GET['page'];
} else {
  $page_id = 0;
}

if (isset($_GET['return'])) {
  $return_id = (int) $_GET['return'];
} else {
  $return_id = $page_id;
}

$page = get_record_by_id('exhibit_page', $page_id);
$return = get_record_by_id('exhibit_page', $return_id);


 if (isset($_GET['exhibit'])){
     $exhibit_image_gallery_set = '';
      // dipslay the Back link for exhibit and gallery page
     $return_link = ($return['slug']=='gallery') ? 'Return to Exhibit Image Gallery': 'Return to Previous Page';?>
     <!--Breadcrumb Bar-->
     <section class="row"> 
      <div class="col-xs-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
          <li class="breadcrumb-item">
          <a href="<?php echo html_escape(exhibit_builder_exhibit_uri($exhibit)); ?>">
            <?php echo  snippet_by_word_count(metadata('exhibit','title',array('no_escape' => true)),5,'..');?>
          </a>
          </li>
         <li class="breadcrumb-item">
           <a href="<?php echo html_escape(exhibit_builder_exhibit_uri($exhibit, $return)); ?>">
             <?php echo html_escape($return['title']); ?>
           </a></li>
          <li class="breadcrumb-item active">Item page</li>
        </ol>
      </div>
     </section>
     <!--End breadcrumb bar--> 

     <?php echo '<div class="exhibit-item-back-button"><a href="' .
        html_escape(exhibit_builder_exhibit_uri($exhibit,$return)).
      '">'.$return_link.'</a></div>';
     
     // display link to gallery only if this page is visited from Exhibit page and the gallery plugin is installed
     if ((plugin_is_active('ExhibitGalleryPage')=='1') and ($return['slug']!='gallery')){
     echo '<div class="exhibit-item-back-button"><a href="'.
                           html_escape(exhibit_builder_exhibit_uri($exhibit).'/gallery').
                           '">View Exhibit Image Gallery</a></div>';
     }}
  ?> 

<div class="col-xs-12">
<h1 class="item-title"><?php echo $item_title; ?></h1>
<div class="view-exhibit--link">
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

if (isset($page)) {
  $next = mlibrary_new_item_sequence_next($exhibit->id, $page_id, $item->id);
  if ($next) {
    $nextUrl = WEB_ROOT . "/exhibits/show/{$exhibit->slug}" .
      "/item/{$next->id}?exhibit={$exhibit->id}&page={$next->page_id}&return={$return_id}";
    $nextTitle = metadata($next, ['Dublin Core', 'Title']);
  }

  $prev = mlibrary_new_item_sequence_prev($exhibit->id, $page_id, $item->id);
  if ($prev) {
    $prevUrl = WEB_ROOT . "/exhibits/show/{$exhibit->slug}" .
      "/item/{$prev->id}?exhibit={$exhibit->id}&page={$prev->page_id}&return={$return_id}";
    $prevTitle = metadata($prev, ['Dublin Core', 'Title']);
  }
} else {
  $prev = false;
  $next = false;
}

// Metadata
$item_metadata = mlibrary_new_metadata('item');

if (isset($_GET['exhibit'])) { ?>
<div class="row">
  <div class="previous-item--nav  col-xs-12 col-sm-6 col-md-4">
    <dl>
      <?php if ($prev) { ?>
        <dt class="previous-item--icon">Previous item</dt>
        <dd>
          <a href="<?php print html_escape($prevUrl); ?>">
            <?php print $prevTitle; ?>
          </a>
        </dd>
      <?php } else { ?>
        <dd>No previous item</dd>
      <?php } ?>
      </dl>
      </div>

      <div class="next-item--nav col-xs-12 col-sm-6 col-md-4 col-md-offset-4">
      <dl>
      <?php if ($next) { ?>
        <dt class="next-item--icon">Next item</dt>
        <dd>
          <a href="<?php print html_escape($nextUrl); ?>">
            <?php print $nextTitle; ?>
          </a>
        </dd>
      <?php } else { ?>
        <dd>No next item</dd>
      <?php } ?>
      </dl>
      </div>
</div>
<?php } 
if (!empty($item_metadata)) {?>
<div class="col-md-6 col-md-offset-3"> 
      <h2 class="metadata--heading">Item Data</h2>
      <div class="detail-nav-border"></div>
      <dl class="metadata--list"><?php echo $item_metadata;?></dl>
</div>
<?php }?>
</div>

<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item));
echo foot();
