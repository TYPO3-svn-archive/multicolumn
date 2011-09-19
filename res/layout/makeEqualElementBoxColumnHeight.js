jQuery.noConflict();
(function($) {
        $(document).ready(function(){
		fixColumnHeight.start();
	});
        
        var fixColumnHeight = {
                elements : {}
                ,start : function() {
                        this.catchItems();
                        this.forceElementHeight();
                }
                
                ,catchItems : function () {
                        $('.multicolumnContainerfixColumnHeight').each(function(containerIndex, container){
                                fixColumnHeight.elements[containerIndex] = {};
				fixColumnHeight.elements[containerIndex]['columns'] = [];
				fixColumnHeight.elements[containerIndex]['columnHeights'] = [];
				
                                $(container).find('div.columnItems').each(function(columnIndex, column){
					var $column = $(column);
					fixColumnHeight.elements[containerIndex]['columns'].push($column);
					fixColumnHeight.elements[containerIndex]['columnHeights'].push($column.height());
                                });
                        });                       
                }
                
                ,forceElementHeight : function () {
                        $.each(this.elements, function(containerIndex, container){
				var columnHeight = container['columnHeights'].sort(fixColumnHeight.sortNumber)[0];
				$(container['columns']).each(function(){
					$(this).css('min-height', columnHeight + 'px');	
				});
                        });
                }
                
                ,sortNumber : function (a, b) {
                       return b -a; 
                }
        };
}(jQuery));