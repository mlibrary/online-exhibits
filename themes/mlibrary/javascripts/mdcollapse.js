/**

 Meta Data Collapse - mdcollapse.js
 
 Creates an Accordion Style Hide/Show on a list of elements. Passed Parameters define from which list element 
 to start hiding and what the type type that defines an element. 
 
 **/
 
;(function($){
	$.fn.mdcollapse = function(position,grouping){
		var pos = position-1; //account for 0 index 
		var gpr =  grouping;
		return this.each(function(){
  			$target = $(this).children(gpr).filter(function(index){ return index > pos; });			
			// DEBUG OUTPUTS
			//  console.log('mdcollapse called on: '+$(this).attr("class")+' at '+pos+' grouped as '+gpr);
			 // console.log($target);
			
			// var totalHeight = 0;
			// $target.each(function(){
				// console.log($(this).height());
				// totalHeight += $(this).height();				
			 //}); 
			
	
			$target.wrapAll('<div class="md-wrap" />').toggle();
			$wrap = $target.parent();

			$wrap.prepend('<span>show more information</span><div class="md-toggle button"><a href="#">show</a></div>');
	
			$('.md-toggle a').click(function(){
				$target.toggle();
				$data = $('div.md-toggle a').text();
				if ($data=='hide') {
			  	$(this).text('show').prev().text('show more information');
				}
				else {
					$(this).text('hide').prev().text('hide more information');
				}
				return false;
			});								
		});// return each func
	}
})(jQuery)// main func