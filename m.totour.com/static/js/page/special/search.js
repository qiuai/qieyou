define('page/special/search', function(){
	var elem = {
			keywords: $('#keywords'),
			formSearch: $('#form_search')
		},
		data = {cookieTime: 86400*30};

	elem.keywords.val(QY.util.cookie.get('keyword'));
	$('#search_btn').on('click', function(){
		elem.formSearch.submit();
	});
	$('#keywords_recom').on('tap', 'a', function(){
		var self = $(this), keyword = self.attr('data-keyword');
		QY.util.cookie.set('keyword', encodeURIComponent(keyword), null, '/', data.cookieTime);
	});
	elem.formSearch.submit(function(){
		var keyword = $.trim(elem.keywords.val());
		QY.util.cookie.set('keyword', encodeURIComponent(keyword), null, '/', data.cookieTime);
		QY.util.redirect('special?keyword=' + keyword);
		return false;
	});
});