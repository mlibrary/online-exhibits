<div class="exhibit record">
<?php if ($exhibitImage = record_image($exhibit,'original',array('class' => 'feature-banner-image'))):
         echo exhibit_builder_link_to_exhibit($exhibit, $exhibitImage); 
      endif; ?>
<h3><?php echo exhibit_builder_link_to_exhibit($exhibit); ?></h3>    
<p><?php echo snippet_by_word_count(metadata($exhibit, 'description', array('no_escape' => true))); ?></p>
</div>
