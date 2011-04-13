jQuery.noConflict();

(function($) {
		// function accordion
	$.fn.accordion = function() {
		var items = $(this).children();
			
		$(this).find('.effectBoxItemContent').hide();
		$(this).find('li.effectBoxItemsFirst .effectBoxItemContent').show();
		$(this).find('li.effectBoxItemsFirst').toggleClass('active');
		
		$(items).each(function() {
			$(this).children('.effectBoxItemTitle').click(function() {
				if (! $(this).parent().hasClass('active')) {
					$(this).parent().toggleClass('active');
					$(this).parent().siblings().removeClass('active');
						
					$(this).siblings('.effectBoxItemContent').slideDown();	
					$(this).parent().siblings().children('.effectBoxItemContent').slideUp();
				}
					
				return false;
			});
		});
	};
})(jQuery);

jQuery(document).ready(function($) {
		// init accordion functions
	$('ul.vAccordion').accordion();
});