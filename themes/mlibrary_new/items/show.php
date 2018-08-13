<?php
fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item));

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
