<div class="item record">
    <?php
    $title = metadata($item, 'rich_title', array('no_escape' => true));
    $subtitle = (get_theme_option('Display Featured Subtitle')) ? '<span class="subtitle">' . __('Featured Item') . '</span>' : '';
    $description = metadata($item, array('Dublin Core', 'Description'), array('snippet' => 150));
    $thumbnailSetting = (option('use_square_thumbnail')) ? 'square_thumbnail' : 'fullsize';
    if (record_image($item)) {
        $itemImageFile = $item->getFile(0);
        $itemImageTitle = metadata($itemImageFile, 'rich_title', array('no_escape' => true));
        $itemImage = record_image($item, $thumbnailSetting, array('class' => 'image', 'alt' => '', 'title' => $itemImageTitle));
    } else {
        $itemImage = '';
    }
    ?>
    <div class="title"><?php echo link_to($this->item, 'show', $itemImage . $subtitle . $title); ?></div>
    <?php if ($description): ?>
        <p class="item-description"><?php echo $description; ?></p>
    <?php endif; ?>
</div>
