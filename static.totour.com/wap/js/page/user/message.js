define('page/user/message', function(){
	var elem = {navTabs: $('#nav_tabs li'), swiperWrapper: $('.swiper-wrapper')},
		data = {filterData: {type: 'forum'}, filter: {}, activeIndex: 0, classMap: ['forum','system']},
		tabsSwiper, popup = QY.util.popup;

	for(var i = 0, len = data.classMap.length; i < len; i++){
		data.filter[data.classMap[i]] = {page: 1, allowPage: 1, loadPageTry: 1, lastId: 0};
		elem['content' + data.classMap[i]] = $('#content_' + data.classMap[i]);
	}

	tabsSwiper = $('.swiper-container').swiper({
		cssWidthAndHeight: 'height',
		onSlideChangeStart: function(){
			var tab;
			data.activeIndex = tabsSwiper.activeIndex;
			tab = data.filterData.type = data.classMap[data.activeIndex];
			elem.navTabs.eq(data.activeIndex).addClass('active').siblings('.active').removeClass('active');
			data.filter[tab].firstFetch ? setAutoHeight() : (data.isNewTab = 1, data.filter[tab].firstFetch = 1, getData({tab: tab}));
		}
	});
	elem.swiperSlides = elem.swiperWrapper.children('.swiper-slide');
	elem.navTabs.on('touchstart mousedown', function(){
		tabsSwiper.swipeTo($(this).index());
	});
	$(window).scroll(function(){
		var body = document.body;
		if( body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
	});

	function setAutoHeight(){
		var c = elem.swiperSlides.eq(data.activeIndex);
		elem.swiperWrapper.css('height', c.height());
	}

	function getData(options){
		var container, filter;
		options = options || {};
		options.tab = options.tab || data.filterData.type;
		filter = data.filter[options.tab];
		if( !filter.allowPage ) return;
		container = elem['content' + options.tab];
		data.isIndexSend || QY.util.request({
			data: $.extend(data.filterData, filter),
			type: 'GET',
			url: QY.util.url('user/getMessage'),
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
					data.filter[options.tab].lastId = response.msg[response.msg.length-1].id;
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
		options.container[data.isNewTab ? 'html' : 'append'](QY.util.template('template_' + options.tab, {list: options.list}));
	}

	getData();
});