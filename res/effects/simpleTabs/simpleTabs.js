jQuery.noConflict();
jQuery(document).ready(function($){
	var effectBox = {
		options : {}
		,$el : []
		,$tabItems : []
		,maxHeight : []
		,$navigationContainer : []
		,start : function () {
			var self = this;
			$('div.simpleTabs').each(function(index, element){
				var id = element.id.split('_')[1];
				
				self.options  = window['mullticolumnEffectBox_'+id] ? window['mullticolumnEffectBox_'+id] : {};
				self.$el = $(this);
				self.$simpleTabsContainer = self.$el.find('.simpleTabsContainer'),
				self.$tabItems = self.$el.find('li.tabItem');
			});
			
			if(self.$el.length) {
				this.buildNavigation();
				if(self.options['fixHeight']) this.setContainerHeight();
			}
		}
		
		,buildNavigation : function () {
			var	$navigationAppend = this.$el.find('.simpleTabNavigationContainer'),
				self = this;
			
			self.$navigationContainer = $('<ul class="simpleTabNavigation clearfix"></ul>');
			
			this.$tabItems.each(function(index){
				var $el = $(this),
					$navigationItemContent = $el.find('.simpleTabNavigationItemContent'),
					$navigationLabel = $navigationItemContent.text(),
					$a = $('<a href="#">' + $navigationLabel + '</a>'),
					$item = $('<li class="simpleTabNavigationItem simpleTabNavigationItem' + index + '"></li>');
				
				$item.append($a);
				self.$navigationContainer.append($item);
				$navigationItemContent.remove();
				self.maxHeight.push($el.height());
				
				$a.click(function(event){
					event.preventDefault();
					self.hideAll();
					$el.show();
					$item.addClass('tabItemAct');
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
});