define('page/order', function(){
	var elem = {
			filterList: $('#filterList'),
			orderList: $('#orderList'),
			searchInput: $('#searchInput'),
			orderDetail: $('#orderDetail'),
			cover: $('#cover')
		},
		data = {
			filterData: {
				type: 'O'
			},
			filter: {},
			activeIndex: 0,
			typeMap: ['O','A','P','S','R','C','N','U'],
			isNewTab: 1
		},
		popup = QY.util.popup;

	for(var i = 0, len = data.typeMap.length; i < len; i++){
		data.filter[data.typeMap[i]] = {page: 1, allowPage: 1, loadPageTry: 1};
		elem['order_' + data.typeMap[i]] = $('#order_' + data.typeMap[i]);
	}

	elem.filterList.on('tap', 'li', function(){
		var self = $(this), tab;
		tab = data.filterData.type = self.attr('data-type');
		self.addClass('now').siblings('li').removeClass('now');
		elem['order_' + tab].parent().show().siblings().hide();
		data.filter[tab].firstFetch || (data.isNewTab = 1, data.filter[tab].firstFetch = 1, getData({tab: tab}));
	});
	$('#orderBox').on('click', '.list-con', function(){
		var self = $(this), oid = +self.attr('data-oid');
		if( !oid ) return;
		renderOrderDetail(oid);
	});
	elem.cover.on('tap', function(){
		clearTimeout(data.orderDetailOpenTimer);
		elem.cover.removeClass('fadeIn').addClass('fadeOut');
		elem.orderDetail.removeClass('slideLeft').addClass('slideRight');
		data.orderDetailCloseTimer = setTimeout(function(){
			elem.cover.hide().removeClass('fadeOut');
			elem.orderDetail.hide().removeClass('slideRight');
		}, 299);
	});
	elem.cover.on('touchstart', function(e){e.preventDefault()});
	$('#searchBtn').on('tap', searchHandler);
	$('#searchForm').on('submit', searchHandler);
	$(window).scroll(function(){
		var body = document.body;
		if( body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
	});

	function getData(options){
		var container, filter;
		options = options || {};
		options.tab = options.tab || data.filterData.type;
		container = elem['order_' + options.tab];
		filter = data.filter[options.tab];
		if( !filter.allowPage ) return;
		data.isIndexSend || QY.util.request({
			data: $.extend(data.filterData, filter),
			type: 'GET',
			url: QY.util.url('static/B/api.php?mod=order'),
			beforeSend: function(){
				data.isIndexSend = true;
				container.next().show();
			},
			success: function(response){
				if( response.code == 1 ){
					if( !data.isNewTab && response.msg.length < 1 ){
						filter = data.filter[options.tab];
						if( filter.loadPageTry >= 1 ){
							filter.allowPage = false;
						} else {
							++filter.loadPageTry;
						}
						return;
					} else {
						++data.filter[options.tab].page;
					}
					$.extend(options, {
						list: response.msg,
						container: container
					});
					renderList(options);
					data.filter[options.tab].firstFetch = true;
				} else {
					QY.util.popup.error(response.msg);
				}
			},
			complete: function(){
				data.isNewTab = 0;
				data.isIndexSend = false;
				container.next().hide();
			}
		});
	}

	function renderList(options){
		options.container[data.isNewTab ? 'html' : 'append'](QY.util.template('template_item', {list: options.list}));
	}

	function searchHandler(){
		var keyword = $.trim(elem.searchInput.val()),
			tab = data.filterData.type;
		if( keyword == '' ){
			alert('请输入搜索内容');
			return;
		}
		data.isNewTab = 1;
		data.filter[tab].firstFetch = 1;
		data.filter[tab].keyword = keyword;
		elem['order_' + tab].html('');
		getData();
	}

	function renderOrderDetail(oid){
		clearTimeout(data.orderDetailCloseTimer);
		elem.cover.removeClass('fadeIn').show().addClass('fadeIn');
		elem.orderDetail.removeClass('slideRight').addClass('slideLeft').show();
		data.orderDetailOpenTimer = setTimeout(function(){
			elem.cover.removeClass('fadeIn')
			elem.orderDetail.removeClass('slideLeft');
		}, 299);
		data.getOrderDetailSend || QY.util.request({
			type: 'GET',
			url: QY.util.url('static/B/api.php?mod=orderDetail'),
			data: {order: oid},
			beforeSend: function(){
				data.getOrderDetailSend = true;
				elem.orderDetail.html('<div class="loading"></div>');
			},
			success: function(response){
				console.log(response);
				elem.orderDetail.html(QY.util.template('template_detail', {data: {order_num: oid}}));
			},
			complete: function(){
				data.getOrderDetailSend = false;
			}
		});
	}

	getData();
});