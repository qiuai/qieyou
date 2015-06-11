define('page/user/message', function(){
	var elem = {navTabs: $('#nav_tabs li'), swiperWrapper: $('.swiper-wrapper')},
		data = {filterData: {type: 'forum'}, filter: {}, activeIndex: 0, classMap: ['forum','system'], isNewTab: 1},
		tabsSwiper, popup = QY.util.popup, uri;

	for(var i = 0, len = data.classMap.length; i < len; i++){
		data.filter[data.classMap[i]] = {lastId: 0, allowPage: 1, loadPageTry: 1};
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
	elem.swiperWrapper.on('click', '[node-action]', function(e){
		var self = $(this), mid = self.attr('data-mid');
		e.preventDefault();
		if( !mid ) return;
		switch(self.attr('node-action'))
		{
			case 'ctrl':
				var ctrl = self.next();
				if( elem.ctrl && elem.ctrl.css('display') != 'none' ){
					elem.ctrl.slideRight(0);
				}
				elem.ctrl = ctrl;
				if( ctrl.css('display') == 'none' ){
					ctrl.slideLeft(1)
				} else {
					ctrl.slideRight(0);
				}
				break;
			case 'del':
				if( !confirm('是否确认删除？') ) return;
				data.Send || QY.util.request({
					type: 'GET',
					url: QY.util.url('user/delMessage'),
					data: {id: mid},
					beforeSend: function(){
						data.Send = true;
					},
					success: function(response){
						var con = self.closest('.scon-list');
						if( response.code == 1 ){
							popup.success('删除成功');
							removeBox(con);
						} else {
							popup.error(response.msg);
						}
					},
					complete: function(){
						data.Send = false;
					}
				});
				break;
			case 'agree':
				ajaxGroup({member: mid, act: 'allow'}, self);
				break;
			case 'ignore':
				ajaxGroup({member: mid, act: 'ignore'}, self);
				break;
		}
	});
	$(window).scroll(function(){
		var body = document.body;
		if( body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
	});

	uri = window.location.href.toString().match(/#(\w+)$/);
	if( uri && uri[1] ){
		uri = uri[1];
		tabsSwiper.swipeTo($.inArray(uri, data.classMap), 0);
	} else {
		uri = data.classMap[0];
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
			url: QY.util.url('user/getMessage'),
			beforeSend: function(){
				data.isIndexSend = true;
				container.next().show();
			},
			success: function(response){
				var len, d;
				if( response.code == 1 ){
					len = response.msg.length;
					if( !data.isNewTab && len < 1 ){
						filter = data.filter[options.tab];
						if( filter.loadPageTry >= 1 ){
							filter.allowPage = false;
						} else {
							++filter.loadPageTry;
						}
						return;
					} else {
						d = response.msg[len - 1];
						data.filter[options.tab].lastId = d ? d.id : 0;
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
		options.container[data.isNewTab ? 'html' : 'append'](QY.util.template('template_' + options.tab, {list: options.list}));
	}

	function ajaxGroup(formData, self){
		formData.group = self.attr('data-gid');
		if( !formData.group ) return;
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
						case 'allow':
							popup.success('接受成功');
							self.closest('em').html(QY.userInfo.name + ' 设为通过');
							break;
						case 'ignore':
							popup.success('忽略成功');
							self.closest('em').html(QY.userInfo.name + ' 设为忽略');
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

	function removeBox(con){
		con.slideUp(0);
		setTimeout(function(){
			if( con.siblings().size() == 0 ){
                con.after('<div class="rs-empty">暂无数据</div>');
            }
			con.remove();
		}, 500);
	}

	getData();
});