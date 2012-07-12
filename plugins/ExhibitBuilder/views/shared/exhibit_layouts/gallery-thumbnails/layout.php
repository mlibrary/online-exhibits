
<?php 
$start=1;
$end=12;

?>
<div class="gallery-thumbnails">
	<?php //echo exhibit_builder_display_exhibit_thumbnail_gallery(1, 16, array('class'=>'permalink')); ?>	
	<div class="primary">
		<div class="exhibit-text">
		<?php echo exhibit_builder_page_text(1); ?>
		</div>
	</div>
	
	<div class="secondary">
		<div id="showcase" class="showcase">
    <!-- Each child div in #showcase represents a slide -->    
		<?php for ($i=(int)$start; $i <= (int)$end; $i++) {   ?>
		    <?php  if ($item = exhibit_builder_use_exhibit_page_item($i)){?>
		    <div class="showcase-slide">
		    <?php 
		    $filename = basename($item->Files[0]->archive_filename,'.jpg');
		    
		    //	$filename = basename($file->archive_filename,'.jpg');	
		    	if(file_exists('archive/zoom_tiles/'.$filename.'_zdata')){  
					$html_fullsize_image= '<div class="zoom swf-zoom"><OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="450" HEIGHT="350" ID="theMovie">
					<PARAM NAME="FlashVars" VALUE="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata">
					<PARAM NAME="MENU" VALUE="FALSE">
					<PARAM NAME="BGCOLOR" VALUE="333333">
					<PARAM NAME="SRC" VALUE="'.uri('').'themes/'.get_current_exhibit()->theme.'/javascripts/ZoomifyViewer.swf">
					<param NAME=wmode VALUE=opaque> 
					<EMBED FlashVars="zoomifyImagePath='.uri('').'archive/zoom_tiles/'.$filename.'_zdata" SRC="'.uri('').'themes/'.get_current_exhibit()->theme.
					'/javascripts/ZoomifyViewer.swf" wmode="opaque" bgcolor="333333" MENU="false" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  WIDTH="450" HEIGHT="350" NAME="theMovie"></EMBED></OBJECT></div>';

					echo $html_fullsize_image;
			
				} else
					 echo exhibit_builder_exhibit_display_item(array('imageSize'=>'fullsize','linkToFile'=>false,'imgAttributes'=>array('style'=>'width:400px;height:300px;')), array('class'=>'permalink')); 	            	
 
					$display_creator = "";
					$display_date = "";
					$layout_default = "";
					
					if (item('Dublin Core', 'Creator')==''){
						$display_creator="display:none";
						if(item('Dublin Core', 'Date')=='')
						$layout_default = "margin-bottom: 38px";
						else
						$layout_default = "margin-bottom: 5px";
					}
					
					if (item('Dublin Core', 'Date')==''){
						$display_date="display:none";
						if(item('Dublin Core', 'Creator')=='')
						$layout_default = "margin-bottom: 38px";
						else
						$layout_default = "margin-bottom: 5px";
					}
					?>
				<div class="showcase-caption">
					
					<?php echo ('<div class="title" style="font-size:18px;'.$layout_default.'">'.exhibit_builder_link_to_exhibit_item().'</div>'.'<div class="creator" style="font-size:13px;'.$display_creator.'">'.'Creator: '.item('Dublin Core', 'Creator').'</div>'.'<div class="citation" style="font-size:11px;'.$display_date.'">'.'Date: '.item('Dublin Core', 'Date').'</div>');?>
				</div>
        	    <div class="showcase-thumbnail active">
				 	<?php echo exhibit_builder_exhibit_display_item(array('imageSize'=>'thumbnail','linkToFile'=>false,'imgAttributes'=>array('style'=>'width:116px;height:100px;')), array('class'=>'permalink')); ?>
    			</div>
    			    			
    		</div>

    		<?php } ?>                             	
		<?php }?>
	</div>
	</div>
</div>


<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{
	jQuery("#showcase").awShowcase(
	{
		width:					530,
		height:					500,
		auto:					false,
		interval:				6500,
		continuous:				true,
		loading:				true,
		tooltip_width:			200,
		tooltip_icon_width:		32,
		tooltip_icon_height:	32,
		tooltip_offsetx:		18,
		tooltip_offsety:		0,
		arrows:					true, 
		buttons:				false,
		btn_numbers:			true,
		keybord_keys:			true,
		mousetrace:				false,
		pauseonover:			true,

   	 	transition:				'fade', /* vslide/hslide/fade */
		transition_speed:		250,
		show_caption:			'onload', /* onload/onhover/show */
		thumbnails:				true,
		thumbnails_position:	'outside-last', /* outside-last/outside-first/inside-last/inside-first */
		thumbnails_direction:	'vertical', /* vertical/horizontal */
		thumbnails_slidex:		0 /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */
				
	});
	
	function fixThumbs(){ // situation on load where all thumbs have active class
		var $thumbs = jQuery("#showcase .showcase-thumbnail");
		if ($thumbs.not(".active").length == 0){ 
			$thumbs.each(function(idx){
				if(idx != 0){
					jQuery(this).removeClass('active');
				}
			});
		}
	}
	
	fixThumbs();
});

</script>
  
  
