jQuery.noConflict();
jQuery(document).data('fboxSettings',{
	type:'image', 
	autoScale:'true',
	overlayOpacity:0.75,
	overlayColor:'#000',
	titlePosition: 'over',
  nextEffect : 'fade',
  prevEffect : 'none',
	speedIn:500, 
	speedOut:300,
	});
	
jQuery(document).ready(function() {
	jQuery("a.fancyitem").fancybox(jQuery(document).data('fboxSettings'));
	});
	
	