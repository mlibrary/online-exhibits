<?php head(array('title'=>'Browse Collections','bodyid'=>'collections','bodyclass' => 'browse')); ?>
<div id="primary">
	<h1>Collections</h1>
    <div class="pagination"><?php echo pagination_links(); ?></div>
		<?php while (loop_collections()): ?>
			<div class="collection">
			    
            	<h2><?php echo link_to_collection(); ?></h2>
	
            	<div class="element">
                <h3>Description</h3>
            	<div class="element-text"><?php echo nls2p(collection('Description', array('snippet'=>250))); ?></div>
	            </div>
	            

	
            	<p class="view-items-link"><?php echo link_to_browse_items('View All Items',array('collection' => collection('id'))); ?> | <?php echo link_to_collection('More Information'); ?></p>
            	
            <?php echo plugin_append_to_collections_browse_each(); ?>
            
            </div><!-- end class="collection" -->
		<?php endwhile; ?>
		
        <?php echo plugin_append_to_collections_browse(); ?>
</div><!-- end primary -->
			
<?php foot(); ?>