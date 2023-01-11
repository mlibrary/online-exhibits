<div class="item record">
    <?php
    $title = metadata($item, 'rich_title', array('no_escape' => true));
    $subtitle = (get_theme_option('Display Featured Subtitle')) ? '<span class="subtitle">' . __('Featured Item') . '</span>' : '';
    $description = metadata($item, array('Dublin Core', 'Description'), array('snippet' => 150));
    $thumbnailSetting = (option('use_square_thumbnail')) ? 'square_thumbnail' : 'fullsize';
    $itemImage = (record_image($item)) ? record_image($item, $thumbnailSetting, array('class' => 'image')) : '';
    ?>
    <div class="title"><?php echo link_to($this->item, 'show', $itemImage . $subtitle . $title); ?></div>
    <?php if ($description): ?>
        <p class="item-description"><?php echo $description; ?></p>
    <?php endif; ?>
</div>
