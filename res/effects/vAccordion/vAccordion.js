jQuery.noConflict();

(function($) {
		// function accordion
	$.fn.accordion = function() {
		var self = $(this);
		var items = $(this).children();
			
		self.find('.effectBoxItemContent').hide();
		self.find('li.effectBoxItemsFirst .effectBoxItemContent').show();
		self.find('li.effectBoxItemsFirst').toggleClass('active');
		
		$(items).each(function() {
			$(this).children('.effectBoxItemTitle').click(function(event) {
				var self = $(this);
				
				if (! self.parent().hasClass('active')) {
					self.parent().toggleClass('active');
					self.parent().siblings().removeClass('active');
						
					self.siblings('.effectBoxItemContent').slideDown();	
					self.parent().siblings().children('.effectBoxItemContent').slideUp();
				}
				
				event.preventDefault();
			});
		});
	};
})(jQuery);

jQuery(document).ready(function($) {
		// init accordion functions
	$('ul.vAccordion').accordion();
});