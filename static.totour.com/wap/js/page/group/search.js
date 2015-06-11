define('page/group/search', function(){
	var elem = {
			container: $('#container'),
			keywords: $('#keywords'),
			contentGroup: $('#content_group'),
			contentForum: $('#content_forum'),
			keywordsRecom: $('#keywords_recom')
		},
		data = {filter: {keyword: null, page: 1, allowPage: 1, loadPageTry: 1}, isNewTab: 1, cache: {imgs: []}},
		pictureView = require('widget/pictureView');

	elem.keywords.focus(function(){
		data.input = 1;
	});
	elem.keywords.blur(function(){
		data.input = 0;
	});
	$('#form_search').submit(function(e){
		e.preventDefault();
		search();
	});
	$('#search_btn').click(function(){
		search();
	});
	elem.contentForum.on('click', '[data-pictureView]', function(){
		var imgs, self = $(this), index = self.attr('data-pictureView');
		if( !index ) return;
		index = String(index).split(':');
		imgs = data.cache.imgs[index[0]];
		pictureView({list: imgs, swipeTo: index[1]});
	});
	elem.keywordsRecom.on('click', '[data-keyword]', function(e){
		var self = $(this), keyword = self.attr('data-keyword');
		e.preventDefault();
		elem.keywords.val(keyword);
		data.filter.keyword = keyword;
		elem.keywordsRecom.hide();
		elem.container.show();
		search();
	});
	$(window).scroll(function(){
		var body = document.body;
		if( data.firstFetch && !data.input && body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
	});

	function getData(){
		if( !data.filter.allowPage ) return;
		data.isIndexSend || QY.util.request({
			data: data.filter,
			type: 'GET',
			url: QY.util.url('group/searchKeyWord'),
			beforeSend: function(){
				data.isIndexSend = true;
				elem.contentGroup.next().show();
				elem.contentForum.next().show();
			},
			success: function(response){
				if( !data.isNewTab && response.forum.length < 1 ){
					if( data.filter.loadPageTry >= 1 ){
						data.filter.allowPage = false;
					} else {
						++data.filter.loadPageTry;
					}
					return;
				} else {
					++data.filter.page;
				}
				renderList(response);
				data.firstFetch = 1;
			},
			complete: function(){
				elem.contentGroup.next().hide();
				elem.contentForum.next().hide();
				data.isNewTab = 0;
				data.isIndexSend = false;
			}
		});
	}

	function search(){
		data.isNewTab = data.filter.page = data.filter.loadPageTry = 1;
		data.filter.keyword = $.trim(elem.keywords.val());
		getData();
	}

	function renderList(list){
		var p, imgs, ilen, arr = [];
		for(var i = 0, len = list.forum.length; i < len; i++){
			list.forum[i].lat = parseFloat(list.forum[i].lat);
			list.forum[i].lon = parseFloat(list.forum[i].lon);
			if( G.pos.lat && G.pos.lon && list.forum[i].lat && list.forum[i].lon ){
				list.forum[i].dist = QY.util.getGreatCircleDistance(list.forum[i].lat, list.forum[i].lon, G.pos.lat, G.pos.lon);
			}

			if( list.forum[i].type == 'rank' || !list.forum[i].pictures ) continue;
            imgs = String(list.forum[i].pictures).split(',');
			list.forum[i].pictures = [];
			ilen = data.cache.imgs.length;
			for(var j = 0, jlen = imgs.length; j < jlen; j++){
                if(  j > 2 ) break;
                if( imgs[j] == '' ) continue;
				p = QY.domain.attach + imgs[j];
				arr.push({src: p});
				list.forum[i].pictures.push({id: ilen, index: j, src: p});
			}
			data.cache.imgs.push(arr);
			arr = [];
		}
		if( data.isNewTab ){
			elem.keywordsRecom.hide();
			elem.container.show();
		}
		if( list.group ){
			elem.contentGroup[data.isNewTab ? 'html' : 'append'](QY.util.template('template_group', {list: list.group}));
		}
		if( list.forum ){
			elem.contentForum[data.isNewTab ? 'html' : 'append'](QY.util.template('template_forum', {list: list.forum}));
			QY.util.setImageMiddle(elem.contentForum);
		}
	}

});