define('page/home/shoucang', function(){
	var elem = {navTabs: $('#nav_tabs li'), swiperWrapper: $('.swiper-wrapper'), itemEditBtn: $('#item_edit_btn')},
		data = {filterData: {type: 'item'}, filter: {}, activeIndex: 0, classMap: ['item','inn'], isNewTab: 1}, tabsSwiper;

	for(var i = 0, len = data.classMap.length; i < len; i++){
		data.filter[data.classMap[i]] = {page: 1, allowPage: 1, loadPageTry: 1};
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
			// data.activeIndex ? elem.itemEditBtn.hide() : elem.itemEditBtn.show();
		}
	});
	elem.swiperSlides = elem.swiperWrapper.children('.swiper-slide');
	elem.navTabs.on('touchstart mousedown', function(){
		tabsSwiper.swipeTo($(this).index());
	});
	elem.itemEditBtn.click(function(){
		var self = $(this), el = elem.contentitem, text = el.hasClass('open-edit') ? '编辑' : '完成';
		el.toggleClass('open-edit');
		elem.contentinn.toggleClass('open-edit');
		self.html(text);
	});
	elem.swiperWrapper.on('click', '[node-type="edit"]', function(e){
		var self, id, url, formData = {act: 'unlike'}, typeText;
		e.preventDefault();
		if( data.filterData.type == 'item' ){
			typeText = '商品';
			url = 'item/itemlike';
			self = $(this).closest('dl');
			id = self.attr('data-id');
			formData.item_id = id;
		} else {
			typeText = '店铺';
			url = 'special/innlike';
			self = $(this).closest('ul');
			id = self.attr('data-id');
			formData.sid = id;
		}
		id = self.attr('data-id');

		if( confirm('是否删除【' + self.attr('data-name') + '】' + typeText + '？') ){
			QY.util.request({
				type: 'GET',
				url: QY.util.url(url),
				data: formData,
				success: function(response){
					if( response.code == 1 ){
						QY.util.popup.success('取消收藏成功');
						self.css({height: self.height(),overflow: 'hidden'}).animate({height: 0, opacity: 0, padding: 0}, 400, 'ease', function(){
							if( self.siblings().size() == 0 ){
                                self.after('<div class="rs-empty">暂无数据</div>');
                            }
							self.remove();
                            setAutoHeight();
						});
					} else {
						QY.util.popup.error(response.msg);
					}
				}
			});
		}
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
			url: QY.util.url('home/shoucang_ajax'),
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
                setAutoHeight();
			}
		});
	}

	function renderList(options){
		if( options.tab == 'inn' ){
			for(var i = 0, len = options.list.length; i < len; i++){
				options.list[i].lat = parseFloat(options.list[i].lat);
				options.list[i].lon = parseFloat(options.list[i].lon);
				if( G.pos.lat && G.pos.lon && options.list[i].lat && options.list[i].lon ){
					options.list[i].dist = QY.util.getGreatCircleDistance(options.list[i].lat, options.list[i].lon, G.pos.lat, G.pos.lon);
				}
			}
		}
		options.container[data.isNewTab ? 'html' : 'append'](QY.util.template('template_' + options.tab, {list: options.list}));
	}

	getData();
});