<?php
echo head(array('title' => __('Add New User'), 'bodyclass' => 'users'));
echo flash();
?>

<section class="seven columns alpha">
  <p class='explanation'>* <?php echo __('required field'); ?></p>
  <form method="post">
    <?php include('form.php'); ?>
    <input type="submit" name="submit" value="Add this User" />
  </form>

</section>

<?php foot();?>