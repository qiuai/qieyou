define('page/home/order', function(){
	var elem = {navTabs: $('#nav_tabs li'), swiperWrapper: $('.swiper-wrapper')},
		data = {filterData: {state: REQUIRE.ACTION}, filter: {}, activeIndex: 0, classMap: ['O','A','U','R'], isNewTab: 1},
		i = 0, len = data.classMap.length, tabsSwiper, popup = QY.util.popup;
		// ['O','A','P','S','R','C','N','U']

	for(i = 0; i < len; i++){
		data.filter[data.classMap[i]] = {page: 1, allowPage: 1, loadPageTry: 1};
		elem['content' + data.classMap[i]] = $('#content_' + data.classMap[i]);
	}

	tabsSwiper = $('.swiper-container').swiper({
		cssWidthAndHeight: 'height',
		onSlideChangeStart: function(){
			var tab;
			data.activeIndex = tabsSwiper.activeIndex;
			tab = data.filterData.state = data.classMap[data.activeIndex];
			elem.navTabs.eq(data.activeIndex).addClass('active').siblings('.active').removeClass('active');
			data.filter[tab].firstFetch ? setAutoHeight() : (data.isNewTab = 1, getData({tab: tab}));
		}
	});
	elem.swiperSlides = elem.swiperWrapper.children('.swiper-slide');
	elem.navTabs.on('touchstart mousedown', function(){
		tabsSwiper.swipeTo($(this).index());
	});
	elem.swiperWrapper.on('click', '[node-type="cancel"]', function(e){
		var self = $(this), oid = self.attr('data-oid'), comment;
		e.preventDefault();
		comment = prompt('请输入取消原因');
		if( comment === null ) return;
		data.cancleSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('order/cancel'),
			data: {order:oid,comment: comment},
			beforeSend: function(){
				data.cancleSend = true;
			},
			success: function(response){
				var item;
				if( response.code == 1 ){
					popup.success('订单取消成功');
					if( getUri() == 'O' ){
						self.closest('.o-list').find('.zhuang .right').html('已取消');
						self.closest('.btn').remove();
					} else {
						item = self.closest('.o-list');
	                    item.css({height: item.height(),overflow: 'hidden'}).animate({height: 0, opacity: 0}, 400, 'ease', function(){
                            if( item.siblings().size() == 0 ){
                                item.after('<div class="rs-empty">暂无数据</div>');
                            }
	                        item.remove();
	                        setAutoHeight();
	                        if( data.filterData.state == 'U' ){
	                        	data.filter.R.page = 1;
	                        	data.filter.R.firstFetch = 0;
	                        }
	                    });
					}
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.cancleSend = false;
			}
		});
	});
	$(window).scroll(function(){
		var body = document.body;
		if( body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
	});
	tabsSwiper.swipeTo($.inArray(getUri(), data.classMap), 0);

	function setAutoHeight(){
		var c = elem.swiperSlides.eq(data.activeIndex);
		elem.swiperWrapper.css('height', c.height());
	}

	function getUri(){
		var uri = window.location.href.toString().match(/#(\w+)$/);
		return uri ? uri[1] : 'O';
	}

	function getData(options){
		var container, filter;
		options = options || {};
		options.tab = options.tab || data.filterData.state;
		filter = data.filter[options.tab];
		if( !filter.allowPage ) return;
		container = elem['content' + options.tab];
		data.isIndexSend || QY.util.request({
			data: $.extend(data.filterData, filter),
			type: 'GET',
			url: QY.domain.base + 'home/orderlist',
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
					data.filter[options.tab].firstFetch = 1;
				} else {
					QY.util.popup.error(response.msg);
				}
			},
			complete: function(){
				data.isNewTab = 0;
				data.isIndexSend = false;
				container.next().hide();
                setAutoHeight();
			}
		});
	}

	function renderList(options){
		options.container[data.isNewTab ? 'html' : 'append'](QY.util.template('template_content_' + options.tab, {list: options.list}));
	}

	getData();
});