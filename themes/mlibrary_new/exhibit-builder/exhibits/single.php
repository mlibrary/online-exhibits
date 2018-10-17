<section class="exhibit record panel panel-default index-featured-exhibit">
	<div class="panel-heading">
		<?php if ($exhibitImage = record_image($exhibit,'original',array('alt' => '', 'class' => 'feature-banner-image'))):
	         echo exhibit_builder_link_to_exhibit($exhibit, $exhibitImage); 
	      endif; ?>
	</div>
	<div class="card-info panel-body">
		<h3 class="panel-card-title"><?php echo exhibit_builder_link_to_exhibit($exhibit); ?></h3>    
		<p class="panel-card-text"><?php echo snippet_by_word_count(metadata($exhibit, 'description', array('no_escape' => true))); ?></p>
	</div>
</section>
