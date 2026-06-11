<?php
$title = __('Browse Exhibits');
$thumbnailSetting = (option('use_square_thumbnail')) ? 'square_thumbnail' : 'fullsize';
echo head(array('title' => $title, 'bodyclass' => 'exhibits browse'));
?>
<h1><?php echo $title; ?> <?php echo __('(%s total)', $total_results); ?></h1>
<?php if (count($exhibits) > 0): ?>

<nav class="navigation secondary-nav" aria-label="Exhibits navigation">
    <?php echo nav(array(
        array(
            'label' => __('Browse All'),
            'uri' => url('exhibits')
        ),
        array(
            'label' => __('Browse by Tag'),
            'uri' => url('exhibits/tags')
        )
    )); ?>
</nav>

<?php echo pagination_links(); ?>

<?php $exhibitCount = 0; ?>
<?php foreach (loop('exhibit') as $exhibit): ?>
    <?php $exhibitCount++; ?>
    <div class="exhibit record <?php if ($exhibitCount%2==1) echo ' even'; else echo ' odd'; ?>">
        <?php
            $linkContent = metadata($exhibit, 'title', array('no_escape' => true));
            if (record_image('exhibit')) {
                $exhibitImageFile = $exhibit->getFile();
                $exhibitImageTitle = metadata($exhibitImageFile, 'rich_title', array('no_escape' => true));
                $linkContent .= (record_image('exhibit')) ? record_image('exhibit', $thumbnailSetting, array('alt' => '', 'title' => $exhibitImageTitle)) : '';
            }
        ?>
        <div class="title"><?php echo link_to_exhibit($linkContent, array('class' => 'permalink')); ?></div>

        <?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>
        <p class="description"><?php echo $exhibitDescription; ?></p>
        <?php endif; ?>
        <?php if ($exhibitTags = tag_string('exhibit', 'exhibits')): ?>
        <p class="tags"><?php echo $exhibitTags; ?></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<?php echo pagination_links(); ?>

<?php else: ?>
<p><?php echo __('There are no exhibits available yet.'); ?></p>
<?php endif; ?>

<?php echo foot(); ?>
