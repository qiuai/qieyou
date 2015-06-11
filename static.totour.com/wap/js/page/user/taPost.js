define('page/user/taPost', function(){
	var elem = {container: $('#container')},
		data = {filter: {type: REQUIRE.ACT, user: REQUIRE.USER_ID, page: 1, allowPage: 1, loadPageTry: 1}, isNewTab: 1, cache: {imgs: [], share: []}},
		pictureView = require('widget/pictureView'), popup = QY.util.popup;

	elem.container.on('click', '[data-pictureView]', function(e){
		var imgs, self = $(this), index = self.attr('data-pictureView');
		e.preventDefault();
		if( !index ) return;
		index = String(index).split(':');
		imgs = data.cache.imgs[index[0]];
		pictureView({list: imgs, swipeTo: index[1]});
	});
	REQUIRE.ACT == 'group' && elem.container.on('click', '[data-attention]', function(e){
		var self = $(this), gid = parseInt(self.attr('data-gid')), act = self.attr('data-attention');
		e.preventDefault();
		if( !gid ) return;
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
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.attentionSend = false;
			}
		});
	});
	elem.container.on('click', '[data-action]', function(e){
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

	function getData(){
		if( !data.filter.allowPage ) return;
		data.isIndexSend || QY.util.request({
			data: data.filter,
			type: 'GET',
			url: QY.util.url('user/getUserForum'),
			beforeSend: function(){
				data.isIndexSend = true;
				elem.container.next().show();
			},
			success: function(response){
				if( response.code == 1 ){
					if( !data.isNewTab && response.msg.length < 1 ){
						if( data.filter.loadPageTry >= 1 ){
							data.filter.allowPage = false;
						} else {
							++data.filter.loadPageTry;
						}
						return;
					} else {
						++data.filter.page;
					}
					renderList(response.msg);
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isNewTab = 0;
				data.isIndexSend = false;
				elem.container.next().hide();
			}
		});
	}

	function renderList(list){
		var p, imgs, ilen, arr = [], share = {};
		for(var i = 0, len = list.length; i < len; i++){
			list[i].lat = parseFloat(list[i].lat);
			list[i].lon = parseFloat(list[i].lon);
			if( G.pos.lat && G.pos.lon && list[i].lat && list[i].lon ){
				list[i].dist = QY.util.getGreatCircleDistance(list[i].lat, list[i].lon, G.pos.lat, G.pos.lon);
			}

			if( list[i].type == 'rank' || !list[i].pictures ) continue;
			imgs = String(list[i].pictures).split(',');
			list[i].pictures = [];
			ilen = data.cache.imgs.length;
			for(var j = 0, jlen = imgs.length; j < jlen; j++){
                if(  j > 2 ) break;
                if( imgs[j] == '' ) continue;
				p = QY.domain.attach + imgs[j];
				arr.push({src: p});
				list[i].pictures.push({id: ilen, index: j, src: p});
                if( j == 0 ) share.pic = p;
			}
            share.title = list[i].forum_name;
            share.url = QY.domain.base + 'forum/' + list[i].forum_id;
            list[i].share = data.cache.share.length;
            data.cache.share.push(share);
			data.cache.imgs.push(arr);
            share = {};
			arr = [];
		}
		elem.container.append(QY.util.template('template_item', {list: list}));
		QY.util.setImageMiddle(elem.container);
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