<?php
$position ='center';
$size = 'fullsize';
$image_size_css = 'fullsize';
$captionPosition ='left';
?>

<?php echo $text; ?>

<div class="exhibit-items">
    <?php foreach ($attachments as $attachment): ?>
        <?php echo $this->exhibitAttachment($attachment, array('imageSize' => $size)); ?>
    <?php endforeach; ?>
</div>
