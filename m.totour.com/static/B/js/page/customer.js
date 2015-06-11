define('page/customer', function(){
	var elem = {
			cover: $('#cover'),
			customerComments: $('#customerComments'),
			commentsNote: $('#commentsNote'),
			commentsBtn: $('#commentsBtn'),
			customerList: $('#customerList'),
			searchInput: $('#searchInput'),
		},
		data = {
			filter: {
				type: REQUIRE.ACT,
				user: REQUIRE.USER_ID,
				page: 1,
				allowPage: 1,
				loadPageTry: 1
			},
			isNewTab: 1,
			headerHeight: $('header').height()
		};

	elem.commentsBtn.on('click', function(){
		var note = $.trim(elem.commentsNote.val());
		if( note == '' ){
			alert('请输入备注');
			return;
		}
		data.Send || QY.util.request({
			type: 'POST',
			url: QY.util.url(''),
			data: {cid: data.cid, note: note},
			beforeSend: function(){
				data.commentsSend = true;
				elem.commentsBtn.val('保存中......');
			},
			success: function(response){
				console.log(response);
			},
			complete: function(){
				data.commentsSend = false;
				elem.commentsBtn.val('保存');
			}
		});
	});
	elem.cover.on('tap', function(){
		clearTimeout(data.commentsOpenTimer);
		elem.cover.removeClass('fadeIn').addClass('fadeOut');
		elem.customerComments.removeClass('zoomIn').addClass('zoomOut');
		data.commentsCloseTimer = setTimeout(function(){
			elem.cover.hide().removeClass('fadeOut');
			elem.customerComments.hide().removeClass('zoomOut');
		}, 299);
	});
	elem.cover.on('touchstart', function(e){e.preventDefault()});
	$('#searchBtn').on('tap', searchHandler);
	$('#searchForm').on('submit', searchHandler);
	elem.customerList.on('click', 'ul', function(){
		var self = $(this);
		data.cid = self.attr('data-cid');
		if( !data.cid ) return;
		clearTimeout(data.commentsCloseTimer);
		elem.cover.removeClass('fadeIn').show().addClass('fadeIn');
		elem.customerComments.removeClass('zoomOut').show().addClass('zoomIn');
		data.commentsOpenTimer = setTimeout(function(){
			elem.cover.removeClass('fadeIn')
			elem.customerComments.removeClass('zoomIn');
		}, 299);
	});
	$('#filterList').on('click', 'a', function(e){
		var self = $(this), key = self.attr('data-key'), con = $('[data-value="' + key + '"]'), top;
		e.preventDefault();
		if( con.size() < 1 ) return;
		top = con.offset().top - data.headerHeight;
		if( con.hasClass('cur') ){
			top += self.children('.tit').height();
		}
		window.scrollTo(0, top);
	});
	$(window).on('scroll', function(){
		elem.customerList.children('[data-value]').each(function(k){
			var self = $(this), scrollTop = document.body.scrollTop;
			if( scrollTop == 0 && k == 0 ){
				self.removeClass('cur').siblings('.cur').removeClass('cur');
			} else if( scrollTop > self.offset().top - data.headerHeight - 1 ){
				self.addClass('cur').siblings('.cur').removeClass('cur');
			}
		});
	});

	function getData(){
		if( !data.filter.allowPage ) return;
		data.isIndexSend || QY.util.request({
			data: data.filter,
			type: 'GET',
			url: 'http://b.totour.net/order/customer',
			beforeSend: function(){
				data.isIndexSend = true;
				elem.customerList.next().show();
			},
			success: function(response){
				if( response.code == 1 ){
					if( !data.isNewTab && response.msg.length < 1 ){
						if( data.filter.loadPageTry >= 1 ){
							data.filter.allowPage = false;
						} else {
							++data.filter.loadPageTry;
						}
						return;
					} else {
						++data.filter.page;
					}
					renderList(response.msg);
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isNewTab = 0;
				data.isIndexSend = false;
				elem.customerList.next().hide();
			}
		});
	}

	function renderList(list){
		elem.customerList.append(QY.util.template('template_item', {list: list}));
	}

	function searchHandler(){
		var keyword = $.trim(elem.searchInput.val());
		if( keyword == '' ){
			alert('请输入搜索内容');
			return;
		}
		data.isNewTab = 1;
		data.filter.keyword = keyword;
		elem.customerList.html('');
		getData();
	}

	// getData();

});