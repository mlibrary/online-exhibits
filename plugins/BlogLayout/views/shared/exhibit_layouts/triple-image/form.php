<?php
//$formStem = $block->getFormStem();
//$options['file-position'] = array('Center' => __('Center'));
//$options['file-size'] = array('two-fullsize-images' => __('Two Fullsize Images'));
?>

<div class="selected-items">
    <h4><?php echo __('Items'); ?></h4>
    <?php echo $this->exhibitFormAttachments($block); ?>
</div>

<div class="block-text">
    <h4><?php echo __('Text'); ?></h4>
    <?php echo $this->exhibitFormText($block); ?>
</div>


