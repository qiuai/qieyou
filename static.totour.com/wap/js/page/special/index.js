define('page/special', function(){
	var elem = {chooseTabs: $('#choose_tabs'), chooseContainer: $('#choose_container'), chooseCon: $('#choose_con'), contentList: $('#content_list'), loading: $('.loading'), salesTime: $('#sales_time'), chooseCover: $('#choose_cover')},
		data = {filterData: {today: 1, cid: 0, ccid: 0, dest: 0, local: 0, sort: 0, page: 1}, keyword: QY.util.getParam('keyword'), cache: {}, loading: false, loadPageType: 0, loadPageTry: 1, loadPageEvent: 0},
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
		var self = $(this), target = self.attr('data-target');
		e.preventDefault();
		if( !target ) return;
		elem.chooseCover.show();
		self.toggleClass('topnow').siblings('.topnow').removeClass('topnow');
		elem.chooseCon.children('[data-name="' + target + '"]').toggle().siblings().hide();
	});

	elem.chooseCon.on('click', '[node-type="cate"] [data-id]', function(e){
		var self = $(this), id = self.attr('data-id'), type = self.closest('[data-type]').attr('data-type'), name = self.attr('data-name');
		e.preventDefault();
		if( !id ) return;
		data.cache.cateName = name;
		self.addClass('now').siblings('.now').removeClass('now');
		self.closest('[node-type="cate"]').siblings('[node-type="list"]').children('[data-pid="' + id + '"]').show().siblings().hide();
		elem.chooseTabs.children('[data-target="' + type + '"]').removeClass('topnow').find('em').html(name);
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
			self.closest('.choose-con').hide();
			data.loadPageType = 1;
			data.filterData.page = 1;
			getData();
		}
	});

	elem.chooseCon.on('click', '[node-type="list"] [data-id]', function(e){
		var self = $(this), id = self.attr('data-id'), parent = self.closest('[data-name]'), type = parent.attr('data-name'), name;
		e.preventDefault();
		if( !id ) return;
		parent.hide();
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
		elem.chooseCover.hide();
		data.loadPageType = 1;
		data.filterData.page = 1;
		getData();
	});

	$('#choose_container').click(function(e){
		e.stopPropagation();
	});

	$('.choose-close').click(function(){
		elem.chooseCon.children('[data-name]').hide();
		elem.chooseTabs.children('.topnow').removeClass('topnow');
		elem.chooseCover.hide();
	});

	elem.chooseCover.click(function(){
		elem.chooseCon.children('[data-name]').hide();
		elem.chooseTabs.children('.topnow').removeClass('topnow');
		elem.chooseCover.hide();
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

	require('page/index:menu')();
	require('page/index:selectCity')();
	data.loadPageType = 1;
	getData();
});