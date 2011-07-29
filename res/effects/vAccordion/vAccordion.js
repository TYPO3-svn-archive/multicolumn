jQuery.noConflict();

(function($) {
		// function accordion
	$.fn.accordion = function(customOptions) {
		var self = $(this)
		,items = $(this).children()
			// defaults
		,defaultOptions = {
		}
			
			// merge defaults with options in new settings object				
		,options = $.extend({}, defaultOptions, customOptions);
		
		self.find('.effectBoxItemContent').hide();
		if (options.showFirst == true) {
			self.find('li.effectBoxItemsFirst .effectBoxItemContent').show();
			self.find('li.effectBoxItemsFirst');
		}
		
		$(items).each(function() {
			$(this).children('.effectBoxItemTitle').click(function(event) {
				var self = $(this);
				
				event.preventDefault();
				
				if (! self.parent().hasClass('active')) {
					self.parent().toggleClass('active');
					self.parent().siblings().removeClass('active');
						
					self.siblings('.effectBoxItemContent').slideDown();	
					self.parent().siblings().children('.effectBoxItemContent').slideUp();
				}
			});
		});
	};
})(jQuery);

jQuery(document).ready(function($) {
	var container = $('ul.vAccordion');
	
	container.each(function(index, element) {
		var id = element.id.split('_')[1];
		var customOptions = window['mullticolumnEffectBox_'+id] ? window['mullticolumnEffectBox_'+id] : {};
			
			// init accordion functions
		$(this).accordion(customOptions);
	});
});