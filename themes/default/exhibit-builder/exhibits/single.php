<div class="exhibit record">
    <?php 
    $title = metadata($exhibit, 'title', array('no_escape' => true));
    $subtitle = (get_theme_option('Display Featured Subtitle')) ? '<span class="subtitle">' . __('Featured Exhibit') . '</span>' : '';
    $thumbnailSetting = (option('use_square_thumbnail')) ? 'square_thumbnail' : 'fullsize';
    $exhibitImage = (record_image($exhibit)) ? record_image($exhibit, $thumbnailSetting, array('class' => 'image')) : '';
    ?>
    <div class="title"><?php echo link_to($this->exhibit, 'show', $exhibitImage . $subtitle . $title); ?></div>
    <p><?php echo snippet_by_word_count(metadata($exhibit, 'description', array('no_escape' => true))); ?></p>
</div>
