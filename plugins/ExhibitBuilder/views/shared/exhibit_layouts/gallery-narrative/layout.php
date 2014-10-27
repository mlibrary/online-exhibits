<div class="gallery-narrative">
	<div class="primary">
		<?php if ($text = exhibit_builder_page_text(1)):?>
		<div class="exhibit-text">		
			<?php echo $text; ?>
		</div>
		<?php endif;?>
	</div>
	<div class="secondary">
	    <?php echo exhibit_builder_thumbnail_gallery(1, 12, array('class'=>'permalink'));//exhibit_builder_display_exhibit_thumbnail_gallery(1, 12, array('class'=>'permalink'),'thumbnail'); ?>
	</div>
</div>