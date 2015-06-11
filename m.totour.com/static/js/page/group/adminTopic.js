define('page/group/adminTopic', function(){
	var elem = {contentTop: $('#content_top'), contentForum: $('#content_forum')},
		data = {filter: {type: 'hot', group: REQUIRE.GROUP_ID, page: 1, allowPage: 1, loadPageTry: 1}, isNewTab: 1, cache: {imgs: [], share: []}},
		pictureView = require('widget/pictureView'), popup = QY.util.popup;

	elem.contentForum.on('click', '[data-pictureView]', function(){
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
	$(document).on('click', '[node-type]', function(e){
		var self = $(this), act;
		e.preventDefault();
		data.oprStop = 1;
		switch(self.attr('node-type')){
			case 'opr':
				elem.ctrl = self.next();
				if( elem.ctrl.data('isShow') == 1 ){
					elem.ctrl.data('isShow', 0);
					elem.ctrl.slideRight(0);
				} else {
					elem.ctrl.data('isShow', 1);
					elem.ctrl.slideLeft(1);
				}
				break;
			case 'float':
				act = self.attr('data-act');
				operateAction({
					formData: {forum: self.attr('data-fid'), act: act}
				});
				self.attr('data-act', act=='set_top'?'unset_top':'set_top').html(act=='set_top'?'取消置顶':'置顶');
				self.parent().hide();
				popup.success(act=='set_top'?'置顶成功':'取消置顶成功');
				window.location.reload();
				break;
			case 'hidden':
				operateAction({
					formData: {forum: self.attr('data-fid'), act: self.attr('data-act')}
				});
				item = self.closest('.scon-list');
				item.css({height: item.height(),overflow: 'hidden'}).animate({height: 0, opacity: 0, padding: 0}, 400, 'ease', function(){
                    if( item.siblings().size() == 0 ){
                        item.after('<div class="rs-empty">暂无数据</div>');
                    }
					item.remove();
					setAutoHeight();
				});
				popup.success('屏蔽成功');
		}
	}).on('click', function(){
		!data.oprStop && elem.ctrl && elem.ctrl.slideRight(0);
		data.oprStop = 0;
	});
	elem.contentForum.on('click', '[data-action]', function(e){
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
			url: QY.util.url('group/groupForum'),
			beforeSend: function(){
				data.isIndexSend = true;
				elem.contentForum.next().show();
			},
			success: function(response){
				var len = response.msg.length;
				if( response.code == 1 ){
					if( !data.isNewTab && len < 1 ){
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
				elem.contentForum.next().hide();
			}
		});
	}

	function renderList(list){
		var p, imgs, ilen, arr = [], topList = [], share = {};
		for(var i = 0, len = list.length; i < len; i++){
			if( parseInt(list[i].is_top) ){
				topList.push(list[i]);
				delete list[i];
				continue;
			}

			imgs = String(list[i].pictures).split(',');
			list[i].pictures = [];
			ilen = data.cache.imgs.length;
			for(var j = 0, jlen = imgs.length; j < jlen; j++){
                if( imgs[j] == '' ) continue;
                if(  j > 2 ) break;
				p = QY.domain.attach + imgs[j];
				arr.push({content: p});
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
		elem.contentTop.append(QY.util.template('template_top', {list: topList}));
		elem.contentForum.append(QY.util.template('template_forum', {list: list}));
		QY.util.setImageMiddles(elem.contentForum);
	}

	function operateAction(options){
		data.oprSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('group/forumManage'),
			data: options.formData,
			beforeSend: function(){
				data.oprSend = true;
			},
			success: function(response){
				console.log(response);
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