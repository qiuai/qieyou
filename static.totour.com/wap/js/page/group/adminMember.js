define('page/group/adminMember', function(){
	var elem = {
			navTabs: $('#nav_tabs li'),
			swiperWrapper: $('.swiper-wrapper'),
			verifiedNum: $('#verified_num'),
			waitingNum: $('#waiting_num')
		},
		data = {filterData: {type: 'verified', group: REQUIRE.GROUP_ID}, filter: {}, activeIndex: 0, classMap: ['verified','waiting'], isNewTab: 1},
		tabsSwiper, popup = QY.util.popup;

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
		}
	});
	elem.swiperSlides = elem.swiperWrapper.children('.swiper-slide');
	elem.navTabs.on('touchstart mousedown', function(){
		tabsSwiper.swipeTo($(this).index());
	});
	elem.swiperWrapper.on('click', '[data-action]', function(e){
		var self = $(this), mid = parseInt(self.attr('data-mid')), act = self.attr('data-action'), formData = {act: act, group: REQUIRE.GROUP_ID, member: mid};
		e.preventDefault();
		e.stopPropagation();
		switch(act){
			case 'opr':
				elem.ctrl && elem.ctrl.hide();
				elem.ctrl = self.next().toggle();
                var w = elem.ctrl.show().width();
                elem.ctrl.css({width: 0, overflow: 'hidden'}).animate({width: w}, 200);
				break;
			case 'delmember':
				if( confirm('是否删除此成员？') ){
					actionReq(formData, self);
				}
				break;
			case 'admin':
				var action = self.attr('data-act');
				if( confirm('是否' + (action=='setadmin'?'设为':'取消') + '管理员？') ){
					formData.act = action;
					actionReq(formData, self);
				}
				break;
			case 'allow':
				actionReq(formData, self);
				break;
			case 'ignore':
				actionReq(formData, self);
		}
	});
	$(window).scroll(function(){
		var body = document.body;
		if( body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
	});
	$(document).on('click', function(){
		elem.ctrl && elem.ctrl.hide();
	});

	function actionReq(formData, self){
		data.actionSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('group/modifyMember'),
			data: formData,
			beforeSend: function(){
				data.actionSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					switch(formData.act){
						case 'delmember':
							popup.success('删除成功');
							removeBox(self.closest('ul'));
							break;
						case 'allow':
							popup.success('接受成功');
							if( data.filter.verified.firstFetch ){
								var num = parseInt(elem.verifiedNum.attr('data-num'));
								num && elem.verifiedNum.html(++num);
								data.filter.verified.page = 1;
								data.filter.verified.firstFetch = false;
							}
							removeBox(self.closest('ul'));
							break;
						case 'ignore':
							popup.success('忽略成功');
							removeBox(self.closest('ul'));
							break;
						case 'setadmin':
							popup.success('成功设为管理员');
							break;
						case 'unsetadmin':
							popup.success('成功取消管理员');
					}
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.actionSend = false;
			}
		});
	}

	function removeBox(item){
		item.css({height: item.height(),overflow: 'hidden'}).animate({height: 0, opacity: 0, padding: 0}, 400, 'ease', function(){
			var el = elem[data.filterData.type=='verified'?'verifiedNum':'waitingNum'], num = parseInt(el.attr('data-num'));
            if( item.siblings().size() == 0 ){
                item.after('<div class="rs-empty">暂无数据</div>');
            }
			item.remove();
			if( num ){
				el.html(--num);
			}
			setAutoHeight();
		});
	}

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
			url: QY.util.url('group/getMember'),
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
		options.container[data.isNewTab ? 'html' : 'append'](QY.util.template('template_item', {list: options.list, tab: options.tab}));
	}

	getData();
});