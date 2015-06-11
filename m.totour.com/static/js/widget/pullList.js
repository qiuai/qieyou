define('widget/pullList', function(){
	$.fn.pullList = function(options){
		var self = $(this), loadingIcon;
		options = $.extend({}, options, {
			auto: true,
			loadingIcon: '#load_more_icon'
		});
		loadingIcon = $(options.loadingIcon);
		self.click(loadingPage);
		options.auto && loadingPage();

		function loadingPage(){
			var page = parseInt(self.attr('data-page')) || 1, data = {type: self.attr('data-type'), page: page, perpage: parseInt(self.attr('data-num')) || ''};
			self.hide();
			loadingIcon.show();
			QY.util.request({
				dataType: options.dataType ? options.dataType : 'json',
				url: options.url,
				type: options.type ? options.type : 'POST',
				data: $.extend({}, options.data, data),
				success: function(response){
					if( response.code == 1 ){
						typeof options.success === 'function' && options.success(response.msg || response, page);
						self.attr('data-page', page + 1);
					} else {
						QY.util.popup.error(response.msg);
					}
				},
				complete: function(){
					loadingIcon.hide();
					self.show();
				}
			});
		}
	};
});