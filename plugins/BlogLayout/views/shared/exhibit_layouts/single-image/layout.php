<?php
$position ='center';
$size = 'fullsize';
$image_size_css = 'fullsize';
$captionPosition ='center';
?>

<?php echo $text; ?>

<div class="exhibit-items <?php echo $position; ?> <?php echo $image_size_css; ?> captions-<?php echo $captionPosition; ?>">
    <?php foreach ($attachments as $attachment): ?>
        <?php echo $this->exhibitAttachment($attachment, array('imageSize' => $size)); ?>
    <?php endforeach; ?>
</div>
