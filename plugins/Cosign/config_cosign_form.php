<div class="field">
  <div class="two columns alpha">
    <label for="cosign_logout_url">
      Enter the default URL you want your site to use:
    </label>
  </div>
  <div class="inputs five columns omega">
    <?php
       print get_view()->formText(
           array('name'=>'cosign_logout_url'),
           get_option('cosign_logout_url')
       );
    ?>
  </div>
</div>
