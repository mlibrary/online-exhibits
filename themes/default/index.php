<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>

<?php if ($homepageText = get_theme_option('Homepage Text')): ?>
<div id="intro">
    <?php echo $homepageText; ?>
</div>
<?php endif; ?>

<div id="featured-records">
    <?php if ((get_theme_option('Display Featured Exhibit')) && function_exists('exhibit_builder_display_random_featured_exhibit')): ?>
    <!-- Featured Exhibit(s) -->
    <?php echo thanksroy_display_random_featured_records('exhibit', 2); ?>
    <?php endif; ?>
    <?php if (get_theme_option('Display Featured Collection')): ?>
    <!-- Featured Collection(s) -->
    <?php echo thanksroy_display_random_featured_records('collection', 2); ?>
    <?php endif; ?>	
    <?php if (get_theme_option('Display Featured Item') == 1): ?>
    <!-- Featured Item(s) -->
    <?php echo random_featured_items(2); ?>
    <?php endif; ?>
</div>

<?php
$recentItems = get_theme_option('Homepage Recent Items');
if ($recentItems === null || $recentItems === ''):
    $recentItems = 3;
else:
    $recentItems = (int) $recentItems;
endif;
if ($recentItems):
?>
<div id="recent-items">
    <div class="recent-title"><?php echo __('Recently Added Items'); ?></div>
    <?php echo recent_items($recentItems); ?>
    <p class="view-items-link"><a href="<?php echo html_escape(url('items')); ?>"><?php echo __('View All Items'); ?></a></p>
</div><!--end recent-items -->
<?php endif; ?>

<?php fire_plugin_hook('public_home', array('view' => $this)); ?>

<?php echo foot(); ?>
