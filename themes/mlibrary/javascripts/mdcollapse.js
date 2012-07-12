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
			/* console.log('mdcollapse called on: '+$(this).attr("class")+' at '+pos+' grouped as '+gpr);
			   console.log($target);
			
			 var totalHeight = 0;
			$target.each(function(){
				console.log($(this).height());
				totalHeight += $(this).height();				
			}); */
			
			$target.fadeTo(0,0).hide().wrapAll('<div class="md-wrap" />');
			$wrap = $target.parent();

			$wrap.prepend('<span>show more information</span><div class="md-toggle button"><a href="#">show</a></div>');
	
			$('.md-toggle a').toggle(function(){
				$target.show('500').fadeTo('500',1);	
				$(this).text('hide').prev().text('hide more information');
			},function(){
				$target.fadeTo('300',0).hide('fast');
				$(this).text('show').prev().text('show more information');
			});
			
		});
	}
})(jQuery)