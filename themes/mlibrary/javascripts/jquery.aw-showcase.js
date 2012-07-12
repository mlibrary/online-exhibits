/*
 
 Awkward Showcase - jQuery plugin 
 http://www.jquery.com
 http://www.awkward.se/sandbox/awkward-showcase-a-jquery-plugin
 http://demo.awkward.se/showcase
 Version: 0.2.4 (Beta)

 Copyright (C) 2010 Awkward (http://www.awkward.se)
 Licensed under Attribution-ShareAlike 3.0 Unported
 http://creativecommons.org/licenses/by-sa/3.0/

 Markup example for jQuery("#showcase").awShowcase();
 
 <div id="showcase">
     <div><img src="images/01.jpg" alt="01" /></div>
     <div><img src="images/02.jpg" alt="02" /></div>
     <div><img src="images/03.jpg" alt="03" /></div>
 </div>

*/

(function(jQuery)
{
	jQuery.fn.awShowcase = function(options)
	{
		// Default configuration properties
		var defaults =
		{
			width:					700,
			height:					470,
			auto:					false,
			interval:				3000,
			continuous:				false,
			loading:				true,
			tooltip_width:			200,
			tooltip_icon_width:		32,
			tooltip_icon_height:	32,
			tooltip_offsetx:		18,
			tooltip_offsety:		0,
			arrows:					true,
			buttons:				true,
			btn_numbers:			false,
			keybord_keys:			false,
			mousetrace:				false,
			pauseonover:			true,
			transition:				'vslide', /* vslide/hslide/fade */
			transition_speed:		500,
			show_caption:			'onload', /* onload/onhover/show */
			thumbnails:				false,
			thumbnails_position:	'outside-last', /* outside-last/outside-first/inside-last/inside-first */
			thumbnails_direction:	'vertical', /* vertical/horizontal */
			thumbnails_slidex:		0 /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */
		};
		
		// Declare and set up some important variables
		options = jQuery.extend(defaults, options);
		var current_id = 0;
		var previous_id = 0;
		var break_loop = false;
		var pause_loop = false;
		var myInterval = null;
		var showcase = jQuery(this);
		
		// Create the content and thumbnail array
		var contentArray = [];
		var thumbnailArray = [];
		var content_count = 0;
		showcase.children('div').each(function()
		{
			// Add content to the array and remove from dom
			var object = jQuery(this);
			
			// If thumbnails are activated
			if (options.thumbnails)
			{
				// Get thumbnail and put in array
				var thumb = object.find('.showcase-thumbnail');
				thumbnailArray.push(thumb);
				thumb.remove();
			}
			
			// Add content html in array
			contentArray.push(object.html());
			object.remove();
			content_count++;
		});
		
		// Set up the content wrapper
		var content_wrapper = jQuery(document.createElement('div'))
			.css('width', options.width)
			.css('height', options.height)
			.css('overflow', 'hidden')
			.css('position', 'relative')
			.addClass('showcase-content-wrapper')
			.prependTo(showcase);

		// Returns the specified content (by array id)
		function getContent(id)
		{
			return jQuery(document.createElement('div'))
				.attr('id', 'showcase-content-' + id)
				.css('width', options.width + 'px')
				.css('height', options.height + 'px')
				.css('overflow', 'hidden')
				.css('position', 'absolute')
				.addClass('showcase-content')
				.html(contentArray[id]);
		}
		
		// Function that runs when content is set to change automatically
		function autoChange()
		{
			// Set next content id
			var nextID = parseInt(current_id)+1;
			// If the next id is outside the array and continuous is set to true set the id to 0
			if (nextID == content_count && options.continuous) { nextID = 0; }
			// If continuous is set to false break the auto change
			else if (nextID == content_count && !options.continuous) { break_loop = true; clearInterval(myInterval); }
			// Don't change the content if the auto change is broken
			if (!break_loop) { changeContent(nextID, 'next'); }
		}
		
		// Changes the content (no explanation/comments needed :)
		function changeContent(id, direction)
		{
			// If the next content isn't the current content
			if (current_id != id)
			{
				var obj;
				var obj2;
				
				// If we want to display the next content
				if (id > current_id && direction != 'previous' || direction == 'next')
				{
					// Get current content element
					obj = jQuery(showcase).find('#showcase-content-' + parseInt(current_id));
					if (options.transition == 'vslide')
					{
						displayAnchors(obj, true);
						displayCaption(obj, true);
						jQuery(obj).animate({
							left: -(options.width+100)
							}, options.transition_speed+300, function() {
								obj.remove();
							});
					}
					else if (options.transition == 'hslide')
					{
						displayAnchors(obj, true);
						displayCaption(obj, true);
						jQuery(obj).animate({
							top: -(options.height+100)
							}, options.transition_speed+300, function() {
								obj.remove();
							});
					}
					else
					{
						displayAnchors(obj, true);
						displayCaption(obj, true);
						jQuery(obj).fadeOut(options.transition_speed, function() {
							obj.remove();
						});
					}
					
					// Get next element
					obj2 = getContent(id);
					if (options.transition == 'vslide')
					{
						obj2.css('left', options.width + 'px');
						jQuery(obj2).animate({
							left: '0px'
							}, options.transition_speed, function() {
								displayAnchors(obj2);
								displayCaption(obj2);
							});
					}
					else if (options.transition == 'hslide')
					{
						obj2.css('top', options.height + 'px');
						jQuery(obj2).animate({
							top: '0px'
							}, options.transition_speed, function() {
								displayAnchors(obj2);
								displayCaption(obj2);
							});
					}
					else
					{
						obj2.css('left', '0px');
						obj2.css('display', 'none');
						jQuery(obj2).fadeIn(options.transition_speed, function() {
								displayAnchors(obj2);
								displayCaption(obj2);
								obj.remove();
							});
					}
					content_wrapper.append(obj2);
				}
				// If we want to display the previous content
				else if (id < current_id || direction == 'previous')
				{
					// Get current content element
					obj = jQuery(showcase).find('#showcase-content-' + parseInt(current_id));
					if (options.transition == 'vslide')
					{
						displayAnchors(obj, true);
						displayCaption(obj, true);
						jQuery(obj).animate({
							left: (options.width+100) + 'px'
							}, options.transition_speed+300, function() {
								obj.remove();
						});
					}
					else if (options.transition == 'hslide')
					{
						displayAnchors(obj, true);
						displayCaption(obj, true);
						jQuery(obj).animate({
							top: (options.height+100) + 'px'
							}, options.transition_speed+300, function() {
								obj.remove();
						});
					}
					else
					{
						displayAnchors(obj, true);
						displayCaption(obj, true);
						jQuery(obj).fadeOut(options.transition_speed, function() {
							obj.remove();
						});
					}
					
					// Get next element
					obj2 = getContent(id);
					if (options.transition == 'vslide')
					{
						obj2.css('left', '-' + options.width + 'px');
						content_wrapper.append(obj2);
						jQuery(obj2).animate({
							left: '0px'
							}, options.transition_speed, function() {
								displayAnchors(obj2);
								displayCaption(obj2);
						});
					}
					else if (options.transition == 'hslide')
					{
						obj2.css('top', '-' + options.height + 'px');
						content_wrapper.append(obj2);
						jQuery(obj2).animate({
							top: '0px'
							}, options.transition_speed, function() {
								displayAnchors(obj2);
								displayCaption(obj2);
						});
					}
					else 
					{
						obj2.css('left', '0px');
						obj2.css('display', 'none');
						jQuery(obj2).fadeIn(options.transition_speed, function() {
							displayAnchors(obj2);
							displayCaption(obj2);
							//obj.remove();
						});
					}
					content_wrapper.append(obj2);
				}
				
				// Change previous and current content id
				previous_id = current_id;
				current_id = id;
				
				// Slide thumbnail wrapper
				if (options.thumbnails)
				{
					if (current_id > previous_id && direction != 'previous' || direction == 'next')
					{
						slideThumbnailWrapper('forward', true);
					}
					else if (current_id < previous_id || direction == 'previous')
					{
						slideThumbnailWrapper('backward', true);
					}
				}
				
				// Change click handlers for arrows
				if (options.arrows)
				{
					jQuery(showcase).find('.showcase-arrow-previous')
						.unbind('click')
						.click(function() {
							if (myInterval) { pause_loop = true; clearInterval(myInterval); }
							changeContent((current_id === 0) ? content_count-1 : parseInt(current_id)-1, 'previous');
						});
						jQuery(showcase).find('.showcase-arrow-next')
						.unbind('click')
						.click(function() {
							if (myInterval) { pause_loop = true; clearInterval(myInterval); }
							changeContent((current_id == content_count-1) ? 0 : parseInt(current_id)+1, 'next');
						});
				}
				
				// Change active class for buttons and thumbnails
				var i;
				if (options.buttons)
				{
					i = 0;
					showcase.find('.showcase-button-wrapper span').each(function()
					{
						var object = jQuery(this);
						object.removeClass('active');
						if (i == current_id) { object.addClass('active'); }
						i++;
					});
				}
				if (options.thumbnails)
				{
					i = 0;
					showcase.find('.showcase-thumbnail').each(function()
					{
						var object = jQuery(this);
						object.removeClass('active');
						if (i == current_id) { object.addClass('active'); }
						i++;
					});
				}
				
				// If caption is set to 'show'
				if (options.show_caption == 'show') { jQuery(obj2).find('.showcase-caption').show(); }
			}
		}
		
		// Activating the keybord arrow keys
		if (options.keybord_keys)
		{
			jQuery(document).keydown(function(e)
			{
				// Left arrow
				if (e.keyCode == 37) { changeContent((current_id === 0) ? content_count-1 : parseInt(current_id)-1, 'previous'); }
				// Right arrow
				if (e.keyCode == 39) { changeContent((current_id == content_count-1) ? 0 : parseInt(current_id)+1, 'next'); }
			});
		}
		
		// Slide thumbnail wrapper
		var thumbnailSlidePosition = 0;
		function slideThumbnailWrapper(direction, check, backwardforward)
		{
			var doTheSlide = true;
			var thumbnailHeightOrWidth = getElementHeight(jQuery(thumb_wrapper).find('.showcase-thumbnail'));
			if (options.thumbnails_direction == 'horizontal')
			{
				thumbnailHeightOrWidth = getElementWidth(jQuery(thumb_wrapper).find('.showcase-thumbnail'));
			}
			var multiplySlidePosition = 1;
			
			// Set slide x
			if (options.thumbnails_slidex === 0) { options.thumbnails_slidex = thumbnailsPerPage; }
			
			// Check if we need to do the slide
			if (check)
			{
				var thumbnailSlidePositionCopy = thumbnailSlidePosition;
				var thumbnailsScrolled = 0;
				while (thumbnailSlidePositionCopy < 0)
				{
					if (options.thumbnails_direction == 'horizontal')
					{
						thumbnailSlidePositionCopy += getElementWidth(jQuery(thumbnailArray[0]));
					}
					else
					{
						thumbnailSlidePositionCopy += getElementHeight(jQuery(thumbnailArray[0]));
					}
					thumbnailsScrolled++;
				}
				
				var firstVisible = thumbnailsScrolled;
				var lastVisible = thumbnailsPerPage + thumbnailsScrolled -1;
				
				// Check if the next active thumbnail is outside the visible area
				if (current_id >= firstVisible && current_id <= lastVisible) { doTheSlide = false; }
				
				// If the next active thumbnail is far away..
				var distance;
				if ((current_id - lastVisible) > options.thumbnails_slidex)
				{
					distance = current_id - lastVisible;
					
					while (distance > options.thumbnails_slidex)
					{
						distance -= options.thumbnails_slidex;
						multiplySlidePosition++;
					}
				}
				else if ((firstVisible - current_id) > options.thumbnails_slidex)
				{
					distance =  firstVisible - current_id;
					
					while (distance > options.thumbnails_slidex)
					{
						distance -= options.thumbnails_slidex;
						multiplySlidePosition++;
					}
				}
				else { multiplySlidePosition = 1; }
			}
			
			if (direction == 'forward' && doTheSlide)
			{
				if (options.thumbnails_direction == 'vertical' && options.height < (thumbnailStretch + thumbnailSlidePosition))
				{
					thumbnailSlidePosition -= thumbnailHeightOrWidth * (options.thumbnails_slidex * multiplySlidePosition);
				}
				else if (options.thumbnails_direction == 'horizontal' && options.width < (thumbnailStretch + thumbnailSlidePosition))
				{
					thumbnailSlidePosition -= thumbnailHeightOrWidth * (options.thumbnails_slidex * multiplySlidePosition);
				}
				else if (current_id === 0)
				{
					if (!backwardforward) { thumbnailSlidePosition = 0; }
				}
				if (options.thumbnails_direction == 'horizontal') { thumb_wrapper.animate({ left: thumbnailSlidePosition }, 300); }
				else { thumb_wrapper.animate({ top: thumbnailSlidePosition }, 300); }
			}
			else if (doTheSlide)
			{
				if (thumbnailSlidePosition < 0)
				{
					thumbnailSlidePosition += thumbnailHeightOrWidth * (options.thumbnails_slidex * multiplySlidePosition);
				}
				else if (current_id == content_count-1)
				{
					if (!backwardforward)
					{
						thumbnailSlidePosition -= thumbnailHeightOrWidth * (options.thumbnails_slidex * multiplySlidePosition);
					}
				}
				else { thumbnailSlidePosition = 0; }
				if (options.thumbnails_direction == 'horizontal') { thumb_wrapper.animate({ left: thumbnailSlidePosition }, 300); }
				else { thumb_wrapper.animate({ top: thumbnailSlidePosition }, 300); }
			}
		}
		
		// Displays the caption
		function displayCaption(container, fadeOut)
		{
			var caption = container.find('.showcase-caption');
			if (!fadeOut)
			{
				if (options.show_caption == 'onload') { caption.fadeIn(300); }
				else if (options.show_caption == 'onhover')
				{
					jQuery(container).mouseenter(function()
					{
						caption.fadeIn(300);
					});
					
					jQuery(container).mouseleave(function()
					{
						caption.stop(true, true).fadeOut(100);
					});
				}
			}
			else { caption.stop(true, true).fadeOut(300); }
		}
		
		// Displays the anchors in the current slide
		function displayAnchors(container, fadeOut)
		{
			// Get each anchor tooltip
			container.children('a.showcase-tooltip').each(function()
			{
				if (!fadeOut)
				{
					// Get coordinates
					var coords = jQuery(this).attr('coords');
					coords = coords.split(',');
					// Style and position anchor
					jQuery(this).addClass('showcase-plus-anchor');
					jQuery(this).css('position', 'absolute');
					jQuery(this).css('display', 'none');
					jQuery(this).css('width', options.tooltip_icon_width);
					jQuery(this).css('height', options.tooltip_icon_height);
					jQuery(this).css('left', parseInt(coords[0]) - (parseInt(options.tooltip_icon_width)/2));
					jQuery(this).css('top', parseInt(coords[1]) - (parseInt(options.tooltip_icon_height)/2));
					var content = jQuery(this).html();
					jQuery(this).mouseenter(function()
					{
						animateTooltip(container, coords[0], coords[1], content);
					});
					jQuery(this).mouseleave(function()
					{
						animateTooltip(container, coords[0], coords[1], content);
					});
					jQuery(this).html('');
					jQuery(this).fadeIn(300);
				}
				else
				{
					jQuery(this).stop(true, true).fadeOut(300);
				}
			});
		}
		
		// Controls the animation for the tooltips
		var tooltip = null;
		function animateTooltip(container, x, y, content)
		{
			// if tooltip is null (doesn't exsist)
			if (tooltip === null)
			{
				// Create the tooltip
				tooltip = jQuery(document.createElement('div'))
					.addClass('showcase-tool-tip')
					.css('display', 'none')
					.css('position', 'absolute')
					.css('max-width', options.tooltip_width)
					.html(content);
				container.append(tooltip);
				
				// Position the tooltip (this is where we try not to display the tooltip outside the content wrapper)
				var tooltip_paddingx = parseInt(tooltip.css('padding-right'))*2 + parseInt(tooltip.css('border-right-width'))*2;
				var tooltip_paddingy = parseInt(tooltip.css('padding-bottom'))*2 + parseInt(tooltip.css('border-bottom-width'))*2;
				lastx = parseInt(x) + tooltip.width() + tooltip_paddingx;
				lasty = parseInt(y) + tooltip.height() + tooltip_paddingy;
				
				if (lastx < options.width)
				{
					tooltip.css('left', parseInt(x) + parseInt(options.tooltip_offsetx));
				}
				else
				{
					tooltip.css('left', (parseInt(x) - parseInt(options.tooltip_offsetx)) - (parseInt(tooltip.width()) + parseInt(options.tooltip_offsetx)));
				}
				
				if (lasty < options.height)
				{
					tooltip.css('top', parseInt(y) + parseInt(options.tooltip_offsety));
				}
				else
				{
					tooltip.css('top', (parseInt(y) - parseInt(options.tooltip_offsety)) - (parseInt(tooltip.height()) + parseInt(tooltip_paddingy)));
				}
				
				// Fade in the tooltip
				tooltip.fadeIn(400);
			}
			else
			{
				// Fade out the tooltip
				tooltip.fadeOut(400);
				// Remove it from the DOM
				tooltip.remove();
				// And set the variable to null
				tooltip = null;
			}
		}
		
		// Traces the mouse position (used fpr positioning anchors)
		if (options.mousetrace)
		{
			// Create the div tha displays the coordinates
			var mousetrace = jQuery(document.createElement('div'))
				.css('position', 'absolute')
				.css('top', '0')
				.css('background-color', '#fff')
				.css('color', '#000')
				.css('padding', '3px 5px')
				.css('x-index', '30')
				.html('X: 0 Y: 0');
			showcase.append(mousetrace);
			var offset = showcase.offset();
			// Print the coordinates
			content_wrapper.mousemove(function(e){ mousetrace.html('X: ' + (e.pageX - offset.left)  + ' Y: ' + (e.pageY - offset.top)); });
		}
		
		/* Returns the correct height for the element, including padding and borders. */
		function getElementHeight(el, incHeight, incMargin, incPadding, incBorders)
		{
			incHeight = typeof(incHeight) != 'undefined' ? incHeight : true;
			incMargin = typeof(incMargin) != 'undefined' ? incMargin : true;
			incPadding = typeof(incPadding) != 'undefined' ? incPadding : true;
			incBorders = typeof(incBorders) != 'undefined' ? incBorders : true;
			var elHeight = (incHeight) ? jQuery((el)).height() : 0;
			var elMargin = (incMargin) ? parseFloat(jQuery((el)).css('margin-top')) + parseFloat(jQuery(el).css('margin-bottom')) : 0;
			var elPadding = (incPadding) ? parseFloat(jQuery((el)).css('padding-top')) + parseFloat(jQuery(el).css('padding-bottom')) : 0;
			var elBorder = (incBorders) ? parseFloat(jQuery((el)).css('border-top-width')) + parseFloat(jQuery((el)).css('border-bottom-width')) : 0;
			elHeight += elMargin + elPadding + elBorder;
			return elHeight;
		}
		
		/* Returns the correct width for the element, including width, margin, padding and borders. */
		function getElementWidth(el, incWidth, incMargin, incPadding, incBorders)
		{
			incWidth = typeof(incWidth) != 'undefined' ? incWidth : true;
			incMargin = typeof(incMargin) != 'undefined' ? incMargin : true;
			incPadding = typeof(incPadding) != 'undefined' ? incPadding : true;
			incBorders = typeof(incBorders) != 'undefined' ? incBorders : true;
			var elWidth = (incWidth) ? jQuery((el)).width() : 0;
			var elMargin = (incMargin) ? parseFloat(jQuery((el)).css('margin-left')) + parseFloat(jQuery(el).css('margin-right')) : 0;
			var elPadding = (incPadding) ? parseFloat(jQuery((el)).css('padding-left')) + parseFloat(jQuery((el)).css('padding-right')) : 0;
			var elBorder = (incBorders) ? parseFloat(jQuery((el)).css('border-left-width')) + parseFloat(jQuery((el)).css('border-right-width')) : 0;
			elWidth += elMargin + elPadding + elBorder;
			return elWidth;
		}
		
		
		// SET UP THE PLUGIN
		// Declare the thumbnail wrapper
		var thumb_wrapper;
		var thumbnailStretch = 0;
		var thumbnailsPerPage = 0;
		
		if (options.thumbnails)
		{
			// Create wrapper
			thumb_container = jQuery('<div />');
			thumb_restriction = jQuery('<div />');
			thumb_wrapper = jQuery('<div />');
			
			// Add content to thumbnail wrapper
			for (var i = thumbnailArray.length-1; i >= 0; --i)
			{
				var thumbnail = jQuery(thumbnailArray[i]).css({'overflow' : 'hidden'});
				thumbnail.attr('id', 'showcase-thumbnail-' + i);
				thumbnail.addClass((i === 0) ? 'active' : '');
				thumbnail.click(function(a, b)
				{
					// This function is used to extract the correct i value on click
					return function()
					{
						// Disable auto change on click
						if (myInterval) { pause_loop = true; clearInterval(myInterval); }
						changeContent(a, b);
					};
				}(i, ''));	
				thumb_wrapper.prepend(thumbnail);
			}
			
			// Style an position thumbnail container and content wrapper
			// + insert thumbnail container
			if (options.thumbnails_position == 'outside-first' || options.thumbnails_position == 'outside-last')
			{
				if (options.thumbnails_direction != 'horizontal')
				{
					/* outside & vertical */
					content_wrapper.css('float', 'left');
					thumb_container.css('float', 'left');
					thumb_container.css('height', options.height);
				}
				else
				{
					/* outside & horizontal */
					jQuery(thumb_wrapper).find('.showcase-thumbnail').css('float', 'left');
					//jQuery(thumb_wrapper).append(jQuery('<div />').addClass('clear'));
				}
				
				if (options.thumbnails_position == 'outside-last')
				{
					/* outside-last */
					showcase.append(thumb_container);
					if (options.thumbnails_direction != 'horizontal') { showcase.append(jQuery('<div />').addClass('clear')); }
				}
				else
				{
					/* outside-first */
					showcase.prepend(thumb_container);
					if (options.thumbnails_direction != 'horizontal') { showcase.append(jQuery('<div />').addClass('clear')); }
				}
			}
			else
			{
				thumb_container.css({'position' : 'absolute', 'z-index' : 20});
				if (options.thumbnails_direction == 'horizontal') 
				{
					/* inside & horizontal */
					thumb_container.css({'left' : 0, 'right' : 0});
					jQuery(thumb_wrapper).find('.showcase-thumbnail').css('float', 'left');
					jQuery(thumb_wrapper).append(jQuery('<div />').addClass('clear'));
					
					/* inside first */
					if (options.thumbnails_position == 'inside-first') { thumb_container.css('top', 0); }
					/* inside last */
					else { thumb_container.css('bottom', 0); }
				}
				else 
				{
					/* inside & vertical */
					thumb_container.css({'top' : 0, 'bottom' : 0});
					/* inside first */
					if (options.thumbnails_position == 'inside-first') { thumb_container.css('left', 0); }
					/* inside last */
					else { thumb_container.css('right', 0); }
				}
				content_wrapper.prepend(thumb_container);
			}
			
			// Add class and style to thumbnail container
			thumb_container.addClass('showcase-thumbnail-container');
			thumb_container.css('overflow', 'hidden');
			
			// Add class and style to thumbnail restriction
			thumb_restriction.addClass('showcase-thumbnail-restriction');
			thumb_restriction.css({'overflow' : 'hidden', 'position' : 'relative'});
			if (options.thumbnails_direction == 'horizontal') { thumb_restriction.css({'float' : 'left'}); }
			
			// Add class and style to thumbnail wrapper 
			thumb_wrapper.addClass('showcase-thumbnail-wrapper');
			if (options.thumbnails_direction == 'horizontal') { thumb_wrapper.addClass('showcase-thumbnail-wrapper-horizontal'); }
			else { thumb_wrapper.addClass('showcase-thumbnail-wrapper-vertical'); }
			thumb_wrapper.css('position', 'relative');
			
			// Append wrapper and restriction
			thumb_restriction.append(thumb_wrapper);
			thumb_container.append(thumb_restriction);
			
			// Add backward button
			var buttonBackward = jQuery('<div class="showcase-thumbnail-button-backward" />');
			if (options.thumbnails_direction != 'horizontal')
			{
				buttonBackward.html('<span class="showcase-thumbnail-vertical"><span>Up</span></span>');
			}
			else
			{
				buttonBackward.css({'float' : 'left'});
				buttonBackward.html('<span class="showcase-thumbnail-horizontal"><span>Left</span></span>');
			}
			buttonBackward.click(function() { slideThumbnailWrapper('backward', false, true); });
			thumb_container.prepend(buttonBackward);
			
			// Add forward button
			var buttonForward = jQuery('<div class="showcase-thumbnail-button-forward" />');
			if (options.thumbnails_direction != 'horizontal')
			{
				buttonForward.html('<span class="showcase-thumbnail-vertical"><span>Down</span></span>');
			}
			else
			{
				buttonForward.css({'float' : 'left'});
				buttonForward.html('<span class="showcase-thumbnail-horizontal"><span>Right</span></span>');
			}
			buttonForward.click(function() { slideThumbnailWrapper('forward', false, true); });
			thumb_container.append(buttonForward);
			
			// Set the number of thumbnails per page.
			var thumbnailVisibleStretch = 0;
			if (options.thumbnails_direction != 'horizontal')
			{
				thumbnailVisibleStretch = getElementHeight(thumb_wrapper, false);
				thumbnailVisibleStretch += (getElementHeight(buttonBackward)) + (getElementHeight(buttonForward));
				while (thumbnailVisibleStretch < options.height)
				{
					thumbnailVisibleStretch += getElementHeight(jQuery(thumbnailArray[0]));
					thumbnailsPerPage++;
				}
			}
			else
			{
				thumbnailVisibleStretch = getElementWidth(thumb_wrapper, false);
				thumbnailVisibleStretch += (getElementWidth(buttonBackward)) + (getElementWidth(buttonForward));
				
				while (thumbnailVisibleStretch < options.width)
				{
					thumbnailVisibleStretch += getElementWidth(jQuery(thumbnailArray[0]));
					thumbnailsPerPage++;
				}
			}
			
			// Hide buttons if they're not necessary
			if (thumbnailsPerPage+1 > thumbnailArray.length)
			{
				if (options.thumbnails_direction != 'horizontal')
				{
					thumb_restriction.css('margin-top', getElementHeight(buttonBackward));
				}
				else
				{
					thumb_restriction.css('margin-left', getElementWidth(buttonBackward));
				}
				buttonBackward.hide();
				buttonForward.hide();
			}
			
			// Set thumbnail restriction height or width
			if (options.thumbnails_direction != 'horizontal')
			{
				var buttonsHeight = (getElementHeight(buttonBackward)) + (getElementHeight(buttonForward));
				thumb_restriction.css('height', options.height - buttonsHeight);
			}
			else
			{
				var buttonsWidth = (getElementWidth(buttonBackward)) + (getElementWidth(buttonForward));
				thumb_restriction.css('width', options.width-buttonsWidth);
			}
			
			// Set thumbnail wrapper width
			if (options.thumbnails_direction == 'horizontal')
			{
				jQuery('.showcase-thumbnail').each(function() { thumbnailStretch += getElementWidth(jQuery(this)); });
				thumb_wrapper.css('width', thumbnailStretch);
			}
			else { jQuery('.showcase-thumbnail').each(function() { thumbnailStretch += getElementHeight(jQuery(this)); }); }
		}
		
		// Set showcase width and height
		if (options.thumbnails && options.thumbnails_position.indexOf("outside") != -1 && options.thumbnails_direction != 'horizontal')
		{
			showcase.css('width', options.width + getElementWidth(thumb_wrapper, true, false));
		}
		else { showcase.css('width', options.width); }
		
		// Get the first content in the array and display it
		var first_content = getContent(0);
		content_wrapper.append(first_content);
		displayAnchors(first_content);
		displayCaption(first_content);
		if (options.show_caption == 'show')
		{
			jQuery(first_content).find('.showcase-caption').show(); // If caption is set to 'show'
		}
		
		// Turn on/off auto slide
		if (content_count > 1 && options.auto) { myInterval = window.setInterval(autoChange, options.interval); }
		
		// Pause auto on mouse over
		if (options.auto && options.pauseonover)
		{
			showcase.mouseenter(function() { break_loop = true; clearInterval(myInterval); });
			showcase.mouseleave(function()
			{
				if (!pause_loop)
				{
					break_loop = false; myInterval = window.setInterval(autoChange, options.interval);
				}
			});
		}
		
		// Adding navigation arrows
		if (options.arrows && content_count > 1)
		{
			// Left arrow
			jQuery(document.createElement('div'))
				.addClass('showcase-arrow-previous')
				.prependTo(showcase)
				.click(function() {
					// Disable auto change on click
					if (myInterval) { pause_loop = true; clearInterval(myInterval); }
					changeContent(content_count-1, 'previous');
				});
			// Right arrow
			jQuery(document.createElement('div'))
				.addClass('showcase-arrow-next')
				.prependTo(showcase)
				.click(function() {
					// Disable auto change on click
					if (myInterval) { pause_loop = true; clearInterval(myInterval); }
					changeContent(1, 'next');
				});
		}
		
		// Adding navigation buttons
		if (options.buttons && content_count > 1)
		{
			// Create button wrapper
			jQuery(document.createElement('div'))
				.css('clear', 'both')
				.addClass('showcase-button-wrapper')
				.appendTo(showcase);
			var i = 0;
			// Add button for each content
			while (i < content_count)
			{
				jQuery(document.createElement('span'))
					.attr('id', 'showcase-navigation-button-' + i)
					.addClass((i === 0) ? 'active' : '')
					// Add numbers or unicode
					.html((options.btn_numbers) ? parseInt(i)+1 : '&#9679;')
					.click(function(a, b)
					{
						// This function is used to extract the correct i value on click
						return function()
						{
							// Disable auto change on click
							
							if (myInterval) { pause_loop = true; clearInterval(myInterval); }
							changeContent(a, b);
						};
					}(i, ''))
					.appendTo(jQuery(showcase).find('.showcase-button-wrapper'));
				i++;
			}
		}
		
		// Remove loading class
		showcase.removeClass('showcase-load');
	};

})(jQuery);
