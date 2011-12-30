(function($) {
	var implementRoundAbout = {
		start : function () {
			var	$el = $(this),
				$effectBoxContainer = $el.parents('.roundabout:first'),
				$children = $el.children(),
				id = $effectBoxContainer.attr('id').split('_')[1],
				options = window['mullticolumnEffectBox_' + id],
				$nav = $el.nextAll('.effectBoxNavigation'),
				$pointNav = $nav.find('.pointNav');
			
			if($nav.length) {
				var	$next = $nav.find('.prev'),
					$prev = $nav.find('.next');
					
				options['btnNext'] = $next;
				options['btnPrev'] = $prev;
					//btnNext: null,
			}

				// set random child
			if(options['startingChild'] === 'random') {
				var	from = 0,
					to = $children.length - 1;

				options['startingChild'] = Math.floor(Math.random() * (to - from + 1) + from);
			}
			
				// build point nav
			if($pointNav.length) {
				var $pointNavUl = $('<ul></ul>');
				
				$children.each(function(index){
					var 	$elLi 	= $(this),
						title 	= $elLi.attr('title'),
						act	= (options['startingChild'] == index) ? ' act' : '',
						$li	= $('<li class="item' + index + act + '" title="' + title + '"><span>' + title + '</span></li>');
					
					$li.click(function(){
						if($li.hasClass('act')) return;
						$pointNavUl.children().removeClass('act');
						
						$li.toggleClass('act');
						$el.roundabout('animateToChild', (index));	
					});						
					$pointNavUl.append($li);
				});
								
				$pointNav.append($pointNavUl);

				var navWidth = $pointNav.innerWidth();
				$pointNav.css({
					'margin-left' : '50%'	
				});

				$pointNavUl.css({
					'margin-left' : - (navWidth / 2) + 'px'
				});
			}

			$el.roundabout(options);
			$effectBoxContainer.hide();
			$effectBoxContainer.removeClass('effectBoxLoading');
			$effectBoxContainer.fadeIn();

		}
	};
	
	$.fn.implementRoundAbout = function(method) {
		if (implementRoundAbout[method]) {
			return implementRoundAbout[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else {
		      $.error( 'Method ' +  method + ' does not exist on jQuery.implementRoundAbout' );
		      return false;
		}
	};
	
	$(function(){
		$('.roundabout ul.effectBoxList').implementRoundAbout('start');
	});
})(jQuery);