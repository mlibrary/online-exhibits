<?php echo head(array('title'=> 'Page Not Found','bodyclass'=>'error-page 404')); ?>

<main>
  <h1>Page Not Found</h1>
  <p>The page you are looking for could not be found. Check the URL in your address bar to make sure it is correct.</p>
  <p>If you continue to have problems or believe that this is an error, contact the website administrator at <a href="mailto:<?php echo option('administrator_email'); ?>"><?php echo option('administrator_email'); ?></a></p>
</main>

<?php echo foot(); ?>
