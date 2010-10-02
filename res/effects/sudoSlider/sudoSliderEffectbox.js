jQuery.noConflict();
jQuery(document).ready(function($){
	var effectBox = {
		start : function () {
			$('.sudoSlider').each(function(index, element){
				var id = element.id.split('_')[1];
				var options = window['mullticolumnEffectBox_'+id] ? window['mullticolumnEffectBox_'+id] : {};
				if(options['convertHeadingToNavigation']) {
					var titles = effectBox.getTitles(element);
					if(titles.length) options['numericText'] = effectBox.getTitles(element);
				}
				$(element).sudoSlider(options);
			});
		}
		,getTitles : function (element) {
			var titles = [];
			$(element).find('.effectBoxItem').find(':header:first').each(function(index,item){
				var heading = $(item);
				heading.hide();
				titles.push(heading.text());
			});
			
			return titles;
		}
	};
	
	effectBox.start();
});