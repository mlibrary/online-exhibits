<?php
    echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyclass' => 'items show'));
?>
<h1>Item page under construction</h1>
 <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
