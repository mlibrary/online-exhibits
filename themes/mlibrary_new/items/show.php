<?php
fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item));

// The page isn't injected by the controller
$page = get_db()->getTable('ExhibitPage')->find($_GET['page']);

// Navigation
$next = mlibrary_new_item_sequence_next($exhibit->id, $page->id, $item->id);
$prev = mlibrary_new_item_sequence_prev($exhibit->id, $page->id, $item->id);

// Metadata
$title = metadata('item', array('Dublin Core', 'Title'));
$creator = metadata('item', array('Dublin Core', 'Creator'));
$date = metadata('item', array('Dublin Core', 'Date'));
$source = metadata('item', array('Dublin Core', 'Source'));
$format = metadata('item', array('Dublin Core', 'Format'));
$rights = metadata('item', array('Dublin Core', 'Rights'));
$file  = $item->getFiles()[0];
$itemType = (empty($item->getItemType()->name)) ?
  'Image' :
  $item->getItemType()->name;

echo head(
    [
        'title' => $title,
        'bodyid' => 'items',
        'bodyclass' => 'items show'
    ]
);
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
  <dd><?php print $file->id; ?></dd>
  <dt>next</dt>
  <dd><?php print $next->id; ?></dd>
  <dd><?php print $next->block_id; ?></dd>
  <dd><?php print $next->page_id; ?></dd>
  <dd><?php print $next->exhibit_id; ?></dd>
  <dt>prev</dt>
  <dd><?php print $prev->id; ?></dd>
  <dd><?php print $prev->block_id; ?></dd>
  <dd><?php print $prev->page_id; ?></dd>
  <dd><?php print $prev->exhibit_id; ?></dd>
</dl>
<?php echo foot();
