jQuery.noConflict();
jQuery(document).ready(function($){
	var effectBox = {
		start : function () {
			$('.sudoSlider').each(function(index, element){
				var id = element.id.split('_')[1],
					$el = $(element),
					controlClassesArray = $el.attr('class').split(' '),
					controlClasses = '',
					options = window['mullticolumnEffectBox_'+id] ? window['mullticolumnEffectBox_'+id] : {},
					$thumbs = $el.find('img.sudoSliderThumb');

				if(controlClassesArray.length) {
					$.each(controlClassesArray, function(index){
						var newClassName = this + 'Navigation';
						controlClasses += (index > 0) ? ' ' + newClassName : newClassName;
					});
				}
				
				if(options['convertHeadingToNavigation']) {
					var titles = effectBox.getTitles(element);
					if(titles.length) options['numericText'] = effectBox.getTitles(element);
				}

				if(options['randomOrder']) {
					effectBox.randomOrder(element);
				}
				
				$el.sudoSlider(options);
				var $control = $el.prev();
				if(controlClasses) $control.addClass(controlClasses);

				if($thumbs.length) {
					var $controlLis = $control.find('li a');
					$thumbs.each(function(index){
						var $thumb = $(this);
						if($thumb) {
							$($controlLis[index]).append($thumb);
							$thumb.show();
						}
					});
				}
				
				if(options['prevNext']) {
					var $prev = $control.find('.prevBtn'),
						$next = $control.find('.nextBtn'),
						$sudoControls = $control.find('ol.controls');
					
					if(!$sudoControls.length) {
						$sudoControls = $('<ol class="controls"></ol>');
						$control.append($sudoControls);
					}
					
					$sudoControls.addClass('clearfix');
					$sudoControls.prepend($prev);
					$sudoControls.append($next);
	
					$next.wrap('<li class="nextItem navigationItem"></li>');
					$prev.wrap('<li class="prevItem navigationItem"></li>');
				}
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
		,randomOrder : function(element) {
			var items = [];
			$(element).find('.effectBoxItem').each(function(index,item) {
				items.push(item);
			});
			
			items.sort(function() {
				return Math.round(Math.random());
			});
			
			$(element).children().html(items);
		}
	};
	
	effectBox.start();
});