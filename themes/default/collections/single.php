<div class="collection record">
    <?php
    $title = metadata($collection, 'rich_title', array('no_escape' => true));
    $subtitle = (get_theme_option('Display Featured Subtitle')) ? '<span class="subtitle">' . __('Featured Collection') . '</span>' : '';
    $description = metadata($collection, array('Dublin Core', 'Description'), array('snippet' => 150));
    $thumbnailSetting = (option('use_square_thumbnail')) ? 'square_thumbnail' : 'fullsize';
    $collectionImage = (record_image($collection)) ? record_image($collection, $thumbnailSetting, array('class' => 'image')) : '';
    ?>
    <div class="title"><?php echo link_to($this->collection, 'show', $collectionImage . $subtitle . $title); ?></div>
    <?php if ($description): ?>
        <p class="collection-description"><?php echo $description; ?></p>
    <?php endif; ?>
</div>
