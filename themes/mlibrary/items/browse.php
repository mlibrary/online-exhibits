<?php head(array('title'=>'Browse Items','bodyid'=>'items','bodyclass' => 'browse')); ?>

	<div id="primary">

		<h1>Browse Items (<?php echo total_results(); ?> total)</h1>

		<ul class="items-nav navigation" id="secondary-nav">
			<?php echo nav(array('Browse All' => uri('items'), 'Browse by Tag' => uri('items/tags'))); ?>
		</ul>
		
		<div id="pagination-top" class="pagination"><?php echo pagination_links(); ?></div>

		<?php
		//$items = get_items(array(),20);
		  $partnerId = '1038472';
		//set_items_for_loop($items);
		 while (loop_items()): ?>
			<div class="item hentry">    
				<div class="item-meta">
				<?php if (item_has_thumbnail()): ?>
    				     <div class="item-img">
    				       <?php echo link_to_item(item_square_thumbnail(array('alt'=>item('Dublin Core', 'Title')))); ?>						
    				     </div>
    		<?php elseif ($elementvideos_VCM = item('Item Type Metadata', 'video_embeded_code_VCM', array('no_escape'=>true, 'all'=>true))):
        			    $data = $elementvideos_VCM[0];
          	      preg_match('/\/entry_id\/([a-zA-Z0-9\_]*)?/i', $data, $match);          	     
            	    $entry_id = $match[1];            	  
          			  echo link_to_item('<img src="http://cdn.kaltura.com/p/'.$partnerId.'/thumbnail/entry_id/'.$match[1].'/width/200/height/200/type/1/quality/100" / style="width:200px; height:200px">');     
              elseif ($elementvideos = item('Item Type Metadata', 'Video_embeded_code', array('no_escape'=>true, 'all'=>true))):
                  $videoid = str_replace($remove, "", $elementvideos);   
          			  $image = "<img src='http://i4.ytimg.com/vi/".$videoid[0]."/default.jpg' style='width:200px; height:200px'/>";             
          			  echo link_to_item($image);
			      	endif; ?>
				<h2>
				  <?php echo link_to_item(item('Dublin Core', 'Title'), array('class'=>'permalink')); ?>
				</h2>                		
				<?php if ($creator = item('Dublin Core', 'Creator')):  ?>
    				<div class="item-creator"><p>
      				<?php echo $creator ?></p>
    				</div>
				<?php endif; ?>                
        <?php if ($date = item('Dublin Core', 'Date')):  ?>
    				<div class="item-date"><p>
    				<?php echo $date ?></p>
    				</div>
				<?php endif; ?>                
        <?php if ($text = item('Item Type Metadata', 'Text', array('snippet'=>250))): ?>
    				<div class="item-description">
    				<p><?php echo $text; ?></p>
    				</div>
				 <?php //elseif ($description = item('Dublin Core', 'Description', array('snippet'=>250))): ?>
    				<!--<div class="item-description">
    				<?php echo $description; ?>
    				</div>-->     
				<?php endif; ?>
                                
				<?php if (item_has_tags()): ?>
    				<div class="tags"><p><strong>Tags:</strong>
    				<?php echo item_tags_as_string(); ?></p>
    				</div>
				<?php endif; ?>
								
				<?php echo plugin_append_to_items_browse_each(); ?>
				</div><!-- end class="item-meta" -->
			</div><!-- end class="item hentry" -->
		<?php endwhile; ?>
	
		<div id="pagination-bottom" class="pagination"><?php// echo pagination_links(); ?></div>
	
		<?php echo plugin_append_to_items_browse(); ?>
			
	</div><!-- end primary -->
	
<?php foot(); ?>