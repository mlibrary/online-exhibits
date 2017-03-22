<?php echo head(array('title'=> 'Permission Denied','bodyclass'=>'error-page  403')); ?>

<main>
  <h1>Permission Denied</h1>
  <p>Sorry, you don't have permission to view this page. If you believe you should have access to this page <a href="/admin">try logging in</a> first.</p>
  <p>If you continue to have problems or believe that this is an error, contact the website administrator at <a href="mailto:<?php echo option('administrator_email'); ?>"><?php echo option('administrator_email'); ?></a></p>
</main>

<?php echo foot(); ?>
