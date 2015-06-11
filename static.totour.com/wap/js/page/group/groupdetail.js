define('page/group/groupDetail', function(){
	var elem = {
			navTabs: $('#nav_tabs li'),
			swiperWrapper: $('.swiper-wrapper'),
			scrollTop: $('#scroll_top')
		},
		data = {filterData: {type: 'hot', group: REQUIRE.GROUP_ID}, filter: {}, activeIndex: 0, classMap: ['hot','near','live'], isNewTab: 1, cache: {imgs: [], share: []}},
		tabsSwiper, pictureView = require('widget/pictureView'), popup = QY.util.popup, uri = data.classMap[0];

	for(var i = 0, len = data.classMap.length; i < len; i++){
		data.filter[data.classMap[i]] = {page: 1, lastid: 0, allowPage: 1, loadPageTry: 1};
		elem['content_' + data.classMap[i]] = $('#content_' + data.classMap[i]);
	}

	tabsSwiper = $('.swiper-container').swiper({
		cssWidthAndHeight: 'height',
		onSlideChangeStart: function(){
			var tab;
			data.activeIndex = tabsSwiper.activeIndex;
			tab = data.filterData.type = data.classMap[data.activeIndex];
			elem.navTabs.eq(data.activeIndex).addClass('active').siblings('.active').removeClass('active');
			data.filter[tab].firstFetch ? setAutoHeight() : (data.isNewTab = 1, getData({tab: tab}));
			history.replaceState && history.replaceState({}, null, '#' + data.classMap[tabsSwiper.activeIndex]);
		}
	});
	elem.swiperSlides = elem.swiperWrapper.children('.swiper-slide');
	elem.navTabs.on('touchstart mousedown', function(){
		tabsSwiper.swipeTo($(this).index());
	});
	$('[data-attention]').on('click', function(e){
		var self = $(this), gid = parseInt(self.attr('data-gid')), act = self.attr('data-attention');
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
				if( response.code == 1 ){
                    popup.success('加入部落成功');
                    self.attr('data-attention', 'quit').html('已加入');
                    return;
				} else {
                    popup.error(response.msg);
                }
			},
			complete: function(){
				data.attentionSend = false;
			}
		});
	});
	elem.swiperWrapper.on('click', '[data-pictureView]', function(){
		var imgs, self = $(this), index = self.attr('data-pictureView');
		if( !index ) return;
		index = String(index).split(':');
		imgs = data.cache.imgs[index[0]];
		pictureView({list: imgs, swipeTo: index[1]});
	});
	elem.swiperWrapper.on('click', '[data-action]', function(e){
		var self = $(this), fid = parseInt(self.attr('data-fid')), action = self.attr('data-action'), num = parseInt(self.attr('data-num'));
		e.preventDefault();
		switch(action){
			case 'like':
				actionRequest({
					num: num,
					elem: self,
					icon: 'praise-rlight',
					data: {act: action, forum: fid}
				});
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
					icon: 'collect-rlight',
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
	elem.scrollTop.on('click', function(){
		QY.UI.scrollTop();
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
		container = elem['content_' + options.tab];
		data.isIndexSend || QY.util.request({
			data: $.extend(data.filterData, filter),
			type: 'GET',
			url: QY.domain.base + 'group/groupForum',
			beforeSend: function(){
				data.isIndexSend = true;
				container.next().show();
			},
			success: function(response){
				var len = response.msg.length;
				if( response.code == 1 ){
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
						data.filter[options.tab].lastid = len > 0 ? response.msg[len - 1].forum_id : 0;
					}
					$.extend(options, {
						list: response.msg,
						container: container
					});
					renderList(options);
					data.filter[options.tab].firstFetch = 1
				} else {
					popup.error(response.msg);
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
		var p, imgs, ilen, arr = [], share = {};
		for(var i = 0, len = options.list.length; i < len; i++){
			options.list[i].lat = parseFloat(options.list[i].lat);
			options.list[i].lon = parseFloat(options.list[i].lon);
			if( G.pos.lat && G.pos.lon && options.list[i].lat && options.list[i].lon ){
				options.list[i].dist = QY.util.getGreatCircleDistance(options.list[i].lat, options.list[i].lon, G.pos.lat, G.pos.lon);
			}

            imgs = String(options.list[i].pictures).split(',');
			options.list[i].pictures = [];
			ilen = data.cache.imgs.length;
			for(var j = 0, jlen = imgs.length; j < jlen; j++){
                if( imgs[j] == '' ) continue;
                if(  j > 2 ) break;
				p = QY.domain.attach + imgs[j];
				arr.push({src: p});
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
		options.container[data.isNewTab ? 'html' : 'append'](QY.util.template('template_forum', {list: options.list}));
		QY.util.setImageMiddle(options.container);
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

	uri = window.location.href.match(/#(\w+)$/);
	if( uri && uri[1] ){
		uri = uri[1];
		tabsSwiper.swipeTo($.inArray(uri, data.classMap), 0);
	} else {
		uri = data.classMap[0];
	}

	require('page/index');
	getData();
});