jQuery.noConflict();
jQuery(document).ready(function($){	
	var effectBox = {
		options : {}
		,$el : []
		,id : 0
		,$tabItems : []
		,maxHeight : []
		,$navigationContainer : []
		,start : function () {
			var self = this;
			$('div.simpleTabs').each(function(index, element){
				self.id = element.id.split('_')[1];
				
				self.options  = window['mullticolumnEffectBox_' + self.id] ? window['mullticolumnEffectBox_' + self.id] : {};
				self.$el = $(this);
				self.$simpleTabsContainer = self.$el.find('.simpleTabsContainer'),
				self.$tabItems = self.$el.find('li.tabItem');

				if(self.$el.length) {
					self.buildNavigation(index);
					if(self.options['fixHeight']) self.setContainerHeight();
				}
			});
		}
		
		,buildNavigation : function (tabIndex) {
			var	$navigationAppend = this.$el.find('.simpleTabNavigationContainer'),
				self = this;

			tabIndex = tabIndex + 1;
			self.$navigationContainer = $('<ul class="simpleTabNavigation clearfix"></ul>');
			
			this.$tabItems.each(function(index){
				var $el = $(this),
					$container = $el.parent('ul'),
					$navigationItemContent = $el.find('.simpleTabNavigationItemContent'),
					navigationLabel = $navigationItemContent.text(),
					navigationItemId = 'tab-' + tabIndex + '-' + (index + 1),
					
					$a = $('<a id="' + navigationItemId + '" href="#' + navigationItemId + '">' + navigationLabel + '</a>'),
					$item = $('<li class="simpleTabNavigationItem simpleTabNavigationItem' + index + '"></li>');
				
				$item.append($a);
				self.$navigationContainer.append($item);
				$navigationItemContent.remove();
				self.maxHeight.push($el.height());
				
				var show = function () {
					self.hideAll();
					$el.show();
					$item.addClass('tabItemAct');					
				};
				
				$a.click(function(event){
					event.preventDefault();
					show();
				});

					// add target links
				$container.find('a[href="#' + navigationItemId + '"]').each(function(){
					$(this).click(function(){
						show()
					});
				});
				
				if(index) {
					$el.hide();
				} else {
					$item.addClass('tabItemAct');
				}
			});

			$navigationAppend.append(self.$navigationContainer);
		}
		
		,setContainerHeight : function () {
			var self = this;
				height = self.maxHeight.sort(self.sortNumber)[0];
				
			self.$simpleTabsContainer.css('height', height + 'px');
		}
		
		,hideAll : function () {
			var self = this;
			
			this.$tabItems.each(function(index){
				var $el = $(this);
				$el.hide();
				
				self.$navigationContainer.find('li.simpleTabNavigationItem' + index).removeClass('tabItemAct');
			});
		}
                ,sortNumber : function (a, b) {
                       return b -a; 
                }
	};
	
	effectBox.start();

		// hash bang
	if(window.location.hash) {
		var $href = $(window.location.hash);
		if($href) {
			$href.trigger('click');
		}
	}
});