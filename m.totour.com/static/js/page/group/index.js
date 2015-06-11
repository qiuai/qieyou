define('page/group/index', function(){
	var elem = {
			swiperWrapper: $('.swiper-wrapper'),
			navTabs: $('#nav_tabs li'),
			refreshLoading: $('#refresh_loading'),
			scrollTop: $('#scroll_top')
		},
		data = {filterData: {'type': 'live'}, filter: {}, classMap: ['live', 'jianren', 'tour', 'wenda', 'rank'], touch: {x: 0, y: 0}, isTouch: 0, isNewTab: 1, activeIndex: 0, cache: {imgs: [], share: []}},
		pictureView = require('widget/pictureView'), popup = QY.util.popup, uri = data.classMap[0];

	for(var i = 0, len = data.classMap.length; i < len; i++){
		data.filter[data.classMap[i]] = {page: 1, lastid: 0, loadPageTry: 1, allowPage: 1};
		elem['content_' + data.classMap[i]] = $('#content_' + data.classMap[i]);
	}

	elem.swiperWrapper.on('touchstart touchmove touchend', function(e){
		if( data.isTouch ) return;
		var body = document.body, touches = e.touches[0] || {}, touch, offset, top = 0, t;
		touch = {x: touches.pageX || 0, y: touches.pageY || 0};
		switch(e.type){
			case 'touchstart':
				data.touch.x = touch.x;
				data.touch.y = touch.y;
				offset = {x: 0, y: 0};
				elem.refreshLoading.css('position', 'absolute');
				break;
			case 'touchmove':
				offset = {x: data.touch.x - touch.x, y: data.touch.y - touch.y};
				if( offset.y < 0 && body.scrollTop <= 0 ){
					top = Math.abs(offset.y);
					e.preventDefault();
					elem.swiperWrapper.css({
						position: 'relative',
						top: top
					});
					if( !data.refreshing && top >= 50 ){
						data.refreshing = 1;
						getData({refresh: true});
					}
				}
				break;
			case 'touchend':
				var top = parseInt(elem.swiperWrapper.css('top')) || 0;
				if( !top ) return;
				data.refreshing = 0;
				elem.swiperWrapper.css({top: top - 50}).animate({top: 0});
				elem.refreshLoading.css('position', 'static');
		}
	});

	var tabsSwiper = $('.swiper-container').swiper({
		cssWidthAndHeight: 'height',
		onTouchMove: function(){
			data.isTouch = 1;
		},
		onTouchEnd: function(){
			data.isTouch = 0;
		},
		onSlideChangeStart: function(){
			var tab;
			data.activeIndex = tabsSwiper.activeIndex;
			tab = data.filterData.type = data.classMap[tabsSwiper.activeIndex];
			elem.navTabs.eq(tabsSwiper.activeIndex).addClass('active').siblings('.active').removeClass('active');
			data.filter[tab].firstFetch ? setAutoHeight() : (data.isNewTab = 1, getData({tab: tab}));
			history.replaceState && history.replaceState({}, null, '#' + data.classMap[tabsSwiper.activeIndex]);
		}
	});
	elem.swiperSlides = elem.swiperWrapper.children('.swiper-slide');
	elem.navTabs.on('touchstart mousedown', function(e){
		e.preventDefault();
		tabsSwiper.swipeTo($(this).index());
	});
	elem.swiperWrapper.on('click', '[data-pictureView]', function(){
		var imgs, self = $(this), index = self.attr('data-pictureView');
		if( !index ) return;
		index = String(index).split(':');
		imgs = data.cache.imgs[index[0]];
		pictureView({
			data: imgs,
			initIndex: +index[1],
			isLooping: true,
	        animateType: 'flip',
	        useZoom: true
		});
	});
	elem.content_rank.on('click', '[data-attention]', function(e){
		var self = $(this), gid = parseInt(self.attr('data-gid')), act = self.attr('data-attention'), joinable = self.attr('data-joinable');
		e.preventDefault();
		if( !gid || act != 'join' ) return;
		data.attentionSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('group/groupJoin'),
			data: {act: act, group: gid},
			beforeSend: function(){
				data.attentionSend = true;
			},
			success: function(response){
				if( !QY.util.checkLogin(response.code) ) return;
				if( response.code == 1 ){
					if( joinable == 'verify' ){
						popup.success('加入部落审核中');
	                    self.html('审核中');
					} else {
	                    popup.success('加入部落成功');
	                    self.html('已加入');
					}
                    self.attr('data-attention', 'quit');
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.attentionSend = false;
			}
		});
	});
	elem.swiperWrapper.on('click', '[data-action]', function(e){
		var self = $(this), fid = parseInt(self.attr('data-fid')), action = self.attr('data-action'), num = parseInt(self.attr('data-num')), share;
		e.preventDefault();
		switch(action){
			case 'like':
				+self.data('liked') || actionRequest({
					num: num,
					elem: self,
					icon: 'praise-rlight',
					data: {act: action, forum: fid}
				});
				self.data('liked', 1);
				break;
			case 'share':
				e.stopPropagation();
				share = data.cache.share[self.attr('data-share')];
				share && QY.UI.share(share);
				break;
			case 'favorite':
				actionRequest({
					num: num,
					elem: self,
					icon: action=='favorite'?'collect-rlight':'collect-red',
					data: {act: 'fav', forum: fid}
				});
		}
	});
	$(window).scroll(function(){
		var body = document.body;
		if( body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
		if( body.scrollTop > 200 ){
			elem.scrollTop.show();
		} else {
			elem.scrollTop.hide();
		}
	});
	$(window).on('orientationchange', function(){
		QY.util.setImageMiddles(elem['content_' + data.filterData.type]);
	});
	elem.scrollTop.on('click', function(){
		QY.UI.scrollTop();
	});
	uri = window.location.href.toString().match(/#(\w+)$/);
	if( uri && uri[1] ){
		uri = uri[1];
		tabsSwiper.swipeTo($.inArray(uri, data.classMap), 0);
	} else {
		uri = data.classMap[0];
	}

	function setAutoHeight(index){
		var c = elem.swiperSlides.eq(data.activeIndex);
		elem.swiperWrapper.css('height', c.height());
	}

	function getData(options){
		var container, filter;
		options = options || {};
		options.tab = options.tab || data.filterData.type;
		filter = data.filter[options.tab];
		if( !filter.allowPage ) return;
		if( options.refresh ){
			filter.lastid = 0;
			filter.page = 0;
			data.isNewTab = 1;
		}
		container = elem['content_' + options.tab];
		data.isIndexSend || QY.util.request({
			data: $.extend(data.filterData, filter),
			type: 'GET',
			url: QY.util.url('group/get'),
			beforeSend: function(){
				data.isIndexSend = true;
				if( options.refresh ){
					elem.refreshLoading.show();
				} else {
					container.next().show();
				}
			},
			success: function(response){
				var len = response.length;
				if( !data.isNewTab && len < 1 ){
					filter = data.filter[options.tab];
					if( filter.loadPageTry >= 1 ){
						filter.allowPage = false;
					} else {
						++filter.loadPageTry;
					}
					return;
				} else {
					++data.filter[options.tab].page;
					data.filter[options.tab].lastid = len > 0 ? response[len - 1].forum_id : 0;
				}
				$.extend(options, {
					list: response,
					container: container
				});
				renderList(options);
				data.filter[options.tab].firstFetch = true;
			},
			complete: function(){
				data.isNewTab = 0;
				data.isIndexSend = false;
				if( options.refresh ){
					elem.refreshLoading.hide();
				} else {
					container.next().hide();
					setAutoHeight();
				}
			}
		});
	}

	function renderList(options){
		var p, imgs, ilen, arr = [], share = {};
		if( options.refresh ){
			data.refreshing = 0;
			elem.swiperWrapper.animate({top: 0});
		}
		for(var i = 0, len = options.list.length; i < len; i++){
			if( !options.list[i] ){
				delete options.list[i];
				continue;
			}
			options.list[i].lat = parseFloat(options.list[i].lat);
			options.list[i].lon = parseFloat(options.list[i].lon);
			if( G.pos.lat && G.pos.lon && options.list[i].lat && options.list[i].lon ){
				options.list[i].dist = QY.util.getGreatCircleDistance(options.list[i].lat, options.list[i].lon, G.pos.lat, G.pos.lon);
			}

			if( options.list[i].type == 'rank' || !options.list[i].pictures ) continue;
            imgs = String(options.list[i].pictures).split(',');
			options.list[i].pictures = [];
			ilen = data.cache.imgs.length;
			for(var j = 0, jlen = imgs.length; j < jlen; j++){
                if(  j > 2 ) break;
                if( imgs[j] == '' ) continue;
				p = QY.domain.attach + imgs[j];
				arr.push({content: p});
				options.list[i].pictures.push({id: ilen, index: j, src: p});
				if( j == 0 ) share.pic = p;
			}
			share.title = options.list[i].forum_name;
			share.url = QY.domain.base + 'forum/' + options.list[i].forum_id;
			options.list[i].share = data.cache.share.length;
			data.cache.share.push(share);
			data.cache.imgs.push(arr);
			share = {};
			arr = [];
		}
		options.container[data.isNewTab ? 'html' : 'append'](QY.util.template('template_' + options.tab, {list: options.list}));
		QY.util.setImageMiddles(options.container);
	}

	function actionRequest(options){
		data.isActionSend || QY.util.request({
			url: QY.util.url('forum/favForum'),
			data: options.data,
			beforeSend: function(){
				data.isActionSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					options.elem.html('<img src="' + QY.domain.resource + 'images/' + options.icon + '.png">' + (++options.num)).attr('data-num', options.num);
				} else if( response.code == 1001 ){
					QY.util.jumpLogin();
				} else {
					QY.util.popup.error(response.msg);
				}
			},
			complete: function(){
				data.isActionSend = false;
			}
		});
	}

	navigator.geolocation.getCurrentPosition(function(event){
		G.pos.lat = event.coords.latitude;
		G.pos.lon = event.coords.longitude;
		getData();
	}, function(){
		getData();
	});

	require('page/index');
});