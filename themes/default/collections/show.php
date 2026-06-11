<?php
$collectionTitle = metadata('collection', 'display_title');
$totalItems = metadata('collection', 'total_items');
?>

<?php echo head(array('title' => $collectionTitle, 'bodyclass' => 'collections show')); ?>

<h1><?php echo metadata('collection', 'rich_title', array('no_escape' => true)); ?></h1>

<?php echo all_element_texts('collection'); ?>

<div id="collection-items">
    <h2><?php echo __('Collection Items'); ?></h2>
    <?php if ($totalItems > 0) {
        foreach (loop('items') as $item) {
            echo $this->partial('items/single.php', array('item' => $item));
        }
        echo link_to_items_browse(__(plural('View item', 'View all %s items', $totalItems), $totalItems), array('collection' => metadata('collection', 'id')), array('class' => 'view-items-link button'));
    } else {
        echo '<p>' . __("There are currently no items within this collection.") . '</p>';
    }
    ?>
</div><!-- end collection-items -->

<?php fire_plugin_hook('public_collections_show', array('view' => $this, 'collection' => $collection)); ?>

<?php echo foot(); ?>
