

<div class="gallery-custom">	  

<?php echo '<script> var exhibitPath = "'.exhibit_builder_exhibit_uri(get_current_exhibit(), get_current_exhibit_section()).'"</script>'; ?>	
	<div class="primary">	
	<?php $start=1; $end=12; 
	$itemsobj = array();
	$json_fullsize='';
	?>
<?php echo '<div class="FileCaption">';?>
</div>
<?php echo '<div class="fullsizeitemimage">';?>	
	<?php for ($i=(int)$start; $i <= (int)$end; $i++) {   ?>
    <?php  if ($item = exhibit_builder_use_exhibit_page_item($i)):
   	
   $json_fullsize =exhibit_builder_exhibit_display_item(array('imageSize'=>'fullsize', 'imageorder'=>$i, 'title' => item('Dublin Core', 'Title'), 'creator'=> item('Dublin Core','Creator'), 'year'=> item('Dublin Core', 'Date'), 'description'=> item('Dublin Core', 'Description')), array('class'=>'permalink'));?>





      
		<?php $itemsobj[$item->id] = $json_fullsize;
		 endif;?>

				 
    <?php }?>
    </div>
    
    <ul class="multi" id="tab">
		<li class="button" id="viewinarchive">View Item</li>
		<li class="button" id="readfulltext">View Source</li>
	</ul>
	
<?php echo '<div class="Metadata">';?>
<ul>
<li id="creator"></li>
<li id="date"></li>
</ul>
<p></p>
</div>
	

    
    
   <?php echo '<script type="text/javascript"> var obj ='.json_encode($itemsobj).'</script>';	?>	
	</div>
		
		
	<div class="secondary gallery">
		  <div class="custom_layout">
    		    <?php echo exhibit_builder_display_exhibit_thumbnail_gallery(1, 12, array('class'=>'permalink')); ?>      
        		<?php if($text = exhibit_builder_page_text(1)):?>
     					 <div class="exhibit-text">
    	    				<?php echo $text; 
    	    	
    	    				?>
		      			 </div>
      	  </div>
    			<?php endif; ?>       
	</div>
  
</div>







<script>

jQuery(document).ready(function(){
//alert('first');
if (jQuery(".square_thumbnail.first div").length >0){
	var ftarget = jQuery(".square_thumbnail.first");
		var fimgClass = ftarget.attr('file_id');
		var ffileobj;
		jQuery.each(obj,function(findex,fobject){
			jQuery.each(fobject,function(ffileindex,ffileobj){
			if(ffileindex==fimgClass){
				jQuery(".fullsizeitemimage").html(ffileobj.image);
				jQuery(".FileCaption").html(ffileobj.title);
				
				
				if(ffileobj.archive){					
					 jQuery(".multi #viewinarchive").html(function() { 
					 var emph = '<a href='+ exhibitPath+'/item/' + ffileobj.archive + '>';
					 return emph + 'View Item </a>';
					 }).show();
				}
				else {
					jQuery(".multi #viewinarchive").hide();
				}
			
			
				if(ffileobj.fulltext){
//					console.log(ffileobj.fulltext);
					jQuery(".multi #readfulltext").html(function() {
					 var emph = "<a href='" + ffileobj.fulltext + "' target='_blank'>";
					 return emph + "View Source</a>";
					 }).show();
				 }
				 else {
					jQuery(".multi #readfulltext").hide();
				}
				
				jQuery(".Metadata p").html(ffileobj.description);
				if(ffileobj.creator) { jQuery(".Metadata #creator").html(ffileobj.creator).show(); } else { jQuery(".Metadata #creator").hide(); }
				jQuery(".Metadata #date").html(ffileobj.date);
				}
			});
			});
}
	jQuery("a.fancyitem").fancybox(jQuery(document).data('fboxSettings'));		
});


jQuery(".square_thumbnail").click(function(){    
		var target = jQuery(this);
		var imgClass = target.attr('file_id');
		var fileobj;
		jQuery.each(obj,function(index,object){
			jQuery.each(object,function(fileindex,fileobj){
			if(fileindex==imgClass){
				jQuery(".fullsizeitemimage").html(fileobj.image);
				jQuery(".FileCaption").html(fileobj.title);
				
				if(fileobj.archive){
					
					 jQuery(".multi #viewinarchive").html(function() { 

					 var emph = '<a href='+exhibitPath+'/item/' + fileobj.archive + '>';
					 return emph + 'View Item </a>';
					 }).show();
				}
				else {
					jQuery(".multi #viewinarchive").hide();
				}
				
				
			
				if(fileobj.fulltext){
				//	console.log(fileobj.fulltext);
					jQuery(".multi #readfulltext").html(function() {
				
					 var emph = "<a href='" + fileobj.fulltext + "' target='_blank'>";
					 return emph + "View Source</a>";
					 }).show();
				 }
				 else {
					jQuery(".multi #readfulltext").hide();
				}
												
				jQuery(".Metadata p").html(fileobj.description);
				if(fileobj.creator) { jQuery(".Metadata #creator").html(fileobj.creator).show(); } else { jQuery(".Metadata #creator").hide(); }
				jQuery(".Metadata #date").html(fileobj.date);

			}	
				
			});
			
		});
			jQuery("a.fancyitem").fancybox(jQuery(document).data('fboxSettings'));		
 });
 
 
</script>

<?php //$lightbox_setting = mlibrary_light_box();?>
</div>