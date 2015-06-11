define('page/special', function(){
	var elem = {chooseTabs: $('#choose_tabs'), chooseContainer: $('#choose_container'), chooseCon: $('#choose_con'), contentList: $('#content_list'), loading: $('.loading'), salesTime: $('#sales_time'), chooseCover: $('#choose_cover')},
		data = {filterData: {today: 1, cid: 0, ccid: 0, dest: 0, local: 0, sort: 0, page: 1}, keyword: QY.util.getParam('keyword'), cache: {}, loading: false, loadPageType: 0, loadPageTry: 1, loadPageEvent: 0, timer: {}},
		events = {
			loadPage: function(){
				if( document.body.scrollTop >= document.body.offsetHeight - window.innerHeight ){
					getData();
				}
			}
		};

	if( data.keyword ){
		data.filterData = {type: 'item', keyword: data.keyword, page: 1};
	}

	$('#nav_tabs').on('click', 'a', function(e){
		var self = $(this), index = self.index();
		e.preventDefault();
		self.addClass('now').siblings('.now').removeClass('now');

		data.loadPageType = 1;
		data.filterData.page = 1;
		if( data.keyword ){
			data.filterData.type = ['item', 'inn'][index];
		} else {
			data.filterData.today = index == 0 ? 1 : 0;
			elem.salesTime[index ? 'hide' : 'show']();
		}
		getData();
	});

	elem.chooseTabs.on('click', '[data-target]', function(e){
		var self = $(this), target = self.attr('data-target'), con = elem.chooseCon.children('[data-name="' + target + '"]');
		e.preventDefault();
		if( !target ) return;
		if( self.hasClass('topnow') ){
			slideToggle(con, 0);
		} else {
			slideToggle(con, 1);
		}
		self.toggleClass('topnow').siblings('.topnow').removeClass('topnow');
		elem.chooseTabs.parent().css('z-index', '999999');
		con.siblings().hide();
	});

	elem.chooseCon.on('click', '[node-type="cate"] [data-id]', function(e){
		var self = $(this), id = self.attr('data-id'), type = self.closest('[data-type]').attr('data-type'), name = self.attr('data-name');
		e.preventDefault();
		if( !id ) return;
		data.cache.cateName = name;
		self.addClass('now').siblings('.now').removeClass('now');
		self.closest('[node-type="cate"]').siblings('[node-type="list"]').children('[data-pid="' + id + '"]').show().siblings().hide();
		elem.chooseTabs.children('[data-target="' + type + '"]').find('em').html(name);
		switch(type){
			case 'all':
				data.filterData.cid = id;
				if( id == 0 ){
					data.filterData.ccid = id;
				}
				break;
			case 'city':
				data.filterData.dest = id;
				if( id == 0 ){
					data.filterData.local = id;
				}
		}
		if( id == 0 ){
			elem.chooseTabs.children('[data-target="' + type + '"]').removeClass('topnow');
			slideToggle(self.closest('.choose-con'), 0);
			data.loadPageType = 1;
			data.filterData.page = 1;
			getData();
		}
	});

	elem.chooseCon.on('click', '[node-type="list"] [data-id]', function(e){
		var self = $(this), id = self.attr('data-id'), parent = self.closest('[data-name]'), type = parent.attr('data-name'), name;
		e.preventDefault();
		if( !id ) return;
		self.addClass('now').siblings('.now').removeClass('now');
		name = id == 0 ? data.cache.cateName : self.attr('data-cname');
		elem.chooseTabs.children('[data-target="' + type + '"]').removeClass('topnow').find('em').html(name);
		switch(type){
			case 'all':
				data.filterData.ccid = id;
				break;
			case 'city':
				data.filterData.local = id;
				break;
			case 'inte':
				data.filterData.sort = id;
				self.hide().siblings().show();
		}
		slideToggle(parent, 0);
		data.loadPageType = 1;
		data.filterData.page = 1;
		getData();
	});

	$('#choose_container').on('click', function(e){
		e.stopPropagation();
	});

	$('.choose-close').on('click', function(){
		elem.chooseTabs.children('.topnow').removeClass('topnow');
		slideToggle(elem.chooseCon.children('[data-name]'), 0);
	});

	elem.chooseCover.on('click', function(){
		elem.chooseTabs.children('.topnow').removeClass('topnow');
		slideToggle(elem.chooseCon.children('[data-name]'), 0);
	});

	bindScrollEvent();

	function getData(){
		data.loading || QY.util.request({
			type: 'GET',
			url: QY.domain.base + 'special/get',
			data: data.filterData,
			beforeSend: function(){
				elem.loading.show();
				data.loading = true;
				if( data.loadPageType ){
					data.loadPageTry = 0;
					bindScrollEvent();
					elem.contentList.html('');
				}
			},
			success: function(response){
				if( Array.isArray(response) ){
					if( !data.loadPageType ){
						if( data.loadPageTry >= 1 ){
							data.loadPageEvent = 0;
							$(window).off('scroll', events.loadPage);
							return;
						}
						if( response.length == 0 ){
							++data.loadPageTry;
							return;
						}
					}
					renderList(response);
					++data.filterData.page;
				} else {
					QY.util.popup.error('出错啦');
				}
			},
			complete: function(response){
				elem.loading.hide();
				data.loading = false;
				data.loadPageType = 0;
			}
		});
	}

	function renderList(list){
		var score;
		for(var i = 0, len = list.length; i < len; i++){
			score = Math.round(list[i].score);
			score = score < 1 ? 5 : score;
			list[i].score = score;

			list[i].pos = QY.util.getGreatCircleDistance(parseFloat(list[i].lat), parseFloat(list[i].lon), POSITION.lat, POSITION.lon);
		}
		elem.contentList[data.loadPageType?'html':'append'](QY.util.template('page_item', {data: {list: list, page: data.filterData.page, type: data.filterData.type}}));
	}

	function bindScrollEvent(){
		if( data.loadPageEvent ) return;
		$(window).on('scroll', events.loadPage);
		data.loadPageEvent = 1;
	}

	function slideToggle(con, type){
		if( type ){
			clearTimeout(data.timer.up);
			con.addClass('slideDown').removeClass('slideUp').show();
			elem.chooseCover.removeClass('fadeOut').addClass('fadeIn').show();
			data.timer.down = setTimeout(function(){
				con.removeClass('slideDown');
				elem.chooseCover.removeClass('fadeIn');
			}, 499);
			elem.chooseTabs.parent().css('z-index', '');
		} else {
			clearTimeout(data.timer.down);
			con.addClass('slideUp').removeClass('slideDown');
			elem.chooseCover.removeClass('fadeIn').addClass('fadeOut');
			data.timer.up = setTimeout(function(){
				con.removeClass('slideUp').hide();
				elem.chooseCover.removeClass('fadeOut').hide();
			}, 499);
		}
	}

	require('page/index:menu')();
	require('page/index:selectCity')();
	data.loadPageType = 1;
	getData();
});