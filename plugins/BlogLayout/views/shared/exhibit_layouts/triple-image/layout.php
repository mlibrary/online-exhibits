<?php
$position ='center';
$size = 'fullsize';
$image_size_css = 'fullsize';
$captionPosition ='center';
?>

    <?php foreach ($attachments as $attachment): ?>
         <div class="exhibit-items">       
              <?php echo $this->exhibitAttachment($attachment, array('imageSize' => $size)); ?>
         </div>
    <?php endforeach; ?>
