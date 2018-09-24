<?php
  $size = 'fullsize';
  echo $text;
  foreach ($attachments as $attachment): ?>
     <div class="exhibit-items">       
         <?php echo $this->exhibitAttachment($attachment, array('imageSize' => $size)); ?>
     </div>
  <?php endforeach; ?>
