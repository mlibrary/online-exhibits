<?php head(array('title'=>collection('Name'))); ?>

<div id="primary" class="show">
    <h1><?php echo collection('Name'); ?></h1>

    <div id="description" class="element">
        <h2>Description</h2>
        <div class="element-text"><?php echo nls2p(collection('Description')); ?></div>
    </div><!-- end description -->
    
                <?php if(collection_has_collectors()): ?>
                      <?php echo '<div id="collectors" class="element">';?>
       				  <?php echo'<h2>Collector(s)</h2>';?> 
       				  <?php echo '<div class="element-text">';?>
          			  <?php echo '<ul><li><p>';?>
              		  <?php echo ''.collection('Collectors', array('delimiter'=>'</li><li>')); ?>
					  <?php echo'</p></li></ul>';?>
        			  <?php echo'</div></div>';?>
                <?php endif; ?>


    <p class="view-items-link"><?php echo link_to_browse_items('View the items in ' . collection('Name'), array('collection' => collection('id'))); ?></p>
    
    <div id="collection-items">
        <?php while (loop_items_in_collection(4)): ?>
    		<?php if (item_has_thumbnail()): ?>
    		<div class="item-img">
    			<?php echo link_to_item(item_square_thumbnail()); ?>						
    		</div>
    		<?php endif; ?>

		
    <?php endwhile; ?>
    </div><!-- end collection-items -->
    
    <?php echo plugin_append_to_collections_show(); ?>
</div><!-- end primary -->

<?php foot(); ?>