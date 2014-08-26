<?php $pageTitle = __('Browse Items');
echo head(array('title'=>$pageTitle,'bodyid'=>'items','bodyclass' => 'items browse')); ?>

	<div id="primary">

	<h1><?php echo $pageTitle;?> <?php echo __('(%s total)', $total_results); ?></h1>

		<ul class="items-nav navigation" id="secondary-nav">
		
			<?php /*echo nav(array(
			array(
			'label'=>'Browse All',
			'uri'=>url('items')
			),
			array(
			 'label'=>'Browse by Tag',
			  'uri'=> url('items/tags')
			  ),
		));*/ ?>
		</ul>
		
		<!--<div id="pagination-top" class="pagination">-->
		<?php //echo pagination_links(); ?><!--</div>-->
<?php echo pagination_links(); ?>
		<?php
		  $partnerId = '1038472';
		  $remove="";?>
		  <div style="margin-top: 24px;">
		 <?php foreach (loop('items') as $item): ?>
			<div class="item hentry">    
				<div class="item-meta">
				<?php if (metadata('item', 'has thumbnail')):  ?>
    				     <div class="item-img">
    				       <?php echo link_to_item(item_image('square_thumbnail',array('alt' => strip_formatting(metadata('item', array('Dublin Core', 'Title')))))); //echo link_to_item(item_square_thumbnail(array('alt'=>metadata('item', array('Dublin Core', 'Title'))))); ?>						
    				     </div>
    		<?php elseif ($elementvideos_VCM = metadata('item',array('Item Type Metadata','video_embeded_code_VCM'),array('no_escape'=>true, 'all'=>true))):
        			    $data = $elementvideos_VCM[0];
          	      preg_match('/\/entry_id\/([a-zA-Z0-9\_]*)?/i', $data, $match);          	     
            	    $entry_id = $match[1];            	  
          			  echo link_to_item('<img src="http://cdn.kaltura.com/p/'.$partnerId.'/thumbnail/entry_id/'.$match[1].'/width/200/height/200/type/1/quality/100" / style="width:200px; height:200px">');     
              elseif ($elementvideos = metadata('item',array('Item Type Metadata', 'Video_embeded_code'),array('no_escape'=>true, 'all'=>true))):
                  $videoid = str_replace($remove, "", $elementvideos);   
          			  $image = "<img src='http://i4.ytimg.com/vi/".$videoid[0]."/default.jpg' style='width:200px; height:200px'/>";             
          			  echo link_to_item($image);
			      	endif; ?>
				<h2>
				  <?php echo link_to_item(strip_formatting(metadata('item', array('Dublin Core', 'Title'))), array('class'=>'permalink')); ?>
				</h2>                		
				<?php if ($creator = metadata('item', array('Dublin Core', 'Creator'))):  ?>
    				<div class="item-creator"><p>
      				<?php echo $creator ?></p>
    				</div>
				<?php endif; ?>                
        <?php if ($date = metadata('item', array('Dublin Core', 'Date'))):  ?>
    				<div class="item-date"><p>
    				<?php echo $date ?></p>
    				</div>
				<?php endif; ?>                
        <?php if ($text = metadata('item',array('Item Type Metadata', 'Text'), array('snippet'=>250))): ?>
    				<div class="item-description">
    				<p><?php echo $text; ?></p>
    				</div>
				 <?php //elseif ($description = item('Dublin Core', 'Description', array('snippet'=>250))): ?>
    				<!--<div class="item-description">
    				<?php echo $description; ?>
    				</div>-->     
				<?php endif; ?>
                                
				   <?php if (metadata('item', 'has tags')): ?>
    <div class="tags"><p><strong><?php echo __('Tags'); ?>:</strong>
        <?php echo tag_string('items'); ?></p>
    </div>
    <?php endif; ?>
								
		  <?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' =>$item)); ?>
				</div><!-- end class="item-meta" -->
			</div><!-- end class="item hentry" -->
		<?php endforeach; ?>
		</div>
	
		<div id="pagination-bottom" class="pagination"><?php echo pagination_links(); ?></div>
	
		<?php //echo plugin_append_to_items_browse(); ?>
		<?php fire_plugin_hook('public_items_browse', array('items'=>$items, 'view' => $this)); ?>
			
	</div><!-- end primary -->
	
<?php echo foot(); ?>