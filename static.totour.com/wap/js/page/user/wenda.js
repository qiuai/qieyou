define('page/user/wenda', function(){
	var elem = {
			navTabs: $('#nav_tabs li'),
			swiperWrapper: $('.swiper-wrapper'),
			postNum: $('#post_num'),
			favoriteNum: $('#favorite_num')
		},
		data = {filterData: {type: 'wenda', act: 'post'}, filter: {}, activeIndex: 0, classMap: ['post','collect'], isNewTab: 1, cache: {imgs: [], share: []}},
		tabsSwiper, pictureView = require('widget/pictureView'), popup = QY.util.popup;

	for(var i = 0, len = data.classMap.length; i < len; i++){
		data.filter[data.classMap[i]] = {page: 1, allowPage: 1, loadPageTry: 1};
		elem['content' + data.classMap[i]] = $('#content_' + data.classMap[i]);
	}

	tabsSwiper = $('.swiper-container').swiper({
		cssWidthAndHeight: 'height',
		onSlideChangeStart: function(){
			var tab;
			data.activeIndex = tabsSwiper.activeIndex;
			tab = data.filterData.act = data.classMap[data.activeIndex];
			elem.navTabs.eq(data.activeIndex).addClass('active').siblings('.active').removeClass('active');
			data.filter[tab].firstFetch ? setAutoHeight() : (data.isNewTab = 1, data.filter[tab].firstFetch = 1, getData({tab: tab}));
		}
	});
	elem.swiperWrapper.on('click', '[data-pictureView]', function(){
		var imgs, self = $(this), index = self.attr('data-pictureView');
		if( !index ) return;
		index = String(index).split(':');
		imgs = data.cache.imgs[index[0]];
		pictureView({list: imgs, swipeTo: index[1]});
	});
	elem.swiperSlides = elem.swiperWrapper.children('.swiper-slide');
	elem.navTabs.on('touchstart mousedown', function(){
		tabsSwiper.swipeTo($(this).index());
	});
	elem.swiperWrapper.on('click', '[node-type]', function(e){
		var self = $(this), type = self.attr('node-type'), fid = parseInt(self.attr('data-fid')), formData;
		e.preventDefault();
		if( !fid ) return;
		switch(type){
			case 'del':
				if( confirm('是否要删除？') ){
					formData = {type: 'forum', typeid: fid};
					operateAction(formData, {
						elem: self,
						url: QY.util.url('user/delMyForum')
					});
				}
				break;
			case 'unfav':
				if( confirm('是否要取消收藏？') ){
					formData = {forum: fid, act: type};
					operateAction(formData, {
						elem: self,
						url: QY.util.url('forum/favForum')
					});
				}
		}
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
	});

	function setAutoHeight(){
		var c = elem.swiperSlides.eq(data.activeIndex);
		elem.swiperWrapper.css('height', c.height());
	}

	function getData(options){
		var container, filter;
		options = options || {};
		options.tab = options.tab || data.filterData.act;
		filter = data.filter[options.tab];
		if( !filter.allowPage ) return;
		container = elem['content' + options.tab];
		data.isIndexSend || QY.util.request({
			data: $.extend(data.filterData, filter),
			type: 'GET',
			url: QY.util.url('user/getMyForum'),
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
		if( options.refresh ){
			data.refreshing = 0;
			elem.swiperWrapper.animate({top: 0});
		}
		for(var i = 0, len = options.list.length; i < len; i++){
			imgs = String(options.list[i].pictures).split(',');
			options.list[i].pictures = [];
			ilen = data.cache.imgs.length;
			for(var j = 0, jlen = imgs.length; j < jlen; j++){
                if(  j > 2 ) break;
                if( imgs[j] == '' ) continue;
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
		options.container[data.isNewTab ? 'html' : 'append'](QY.util.template('template_item', {list: options.list, act: data.filterData.act}));
		QY.util.setImageMiddle(options.container);
	}

	function operateAction(formData, options){
		data.oprSend || QY.util.request({
			type: 'POST',
			url: options.url,
			data: formData,
			beforeSend: function(){
				data.oprSend = true;
			},
			success: function(response){
				var item;
				if( response.code == 1 ){
					switch(formData.act){
						case 'del':
							popup.success('删除成功');
							break;
						case 'unfav':
							popup.success('取消收藏成功');
					}
					item = options.elem.closest('.scon-list');
					item.css({height: item.height(),overflow: 'hidden'}).animate({height: 0, opacity: 0, padding: 0}, 400, 'ease', function(){
						var el = elem[data.filterData.act=='post'?'postNum':'favoriteNum'], num = parseInt(el.attr('data-num'));
                        if( item.siblings().size() == 0 ){
                            item.after('<div class="rs-empty">暂无数据</div>');
                        }
						item.remove();
						if( num ){
							el.html(--num);
						}
						setAutoHeight();
					});
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.oprSend = false;
			}
		});
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

	getData();
});