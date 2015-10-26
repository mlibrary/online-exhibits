<?php
echo head(array('title' => __('Add New User'), 'bodyclass' => 'users'));
echo flash();
?>
<section class="seven columns alpha">
    <p class='explanation'>* <?php echo __('required field'); ?></p>
    <?php echo $this->form; ?>
</section>
<?php echo foot();?>