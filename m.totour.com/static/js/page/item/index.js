define('page/item/index:vote', function(){
	return function(){
		var self = $(this), id = parseInt(self.attr('data-id')), formData = {comment_id: id};
		if( !id ) return;
		formData.act = self.hasClass('is_vote') ? 'unlike' : 'like';
		QY.util.request({
			type: 'GET',
			url: QY.domain.base + 'item/commentlike',
			data: formData,
			success: function(response){
				var num;
				if( response.code == 1 ){
					num = parseInt(self.attr('data-num')) || 0;
					self.html('<img src="' + QY.domain.resource + 'images/praise-light.png">' + (++num));
				} else {
					QY.util.popup.error(response.msg);
				}
			}
		});
	};
});
define('page/item/index', function(){
	var elem = {
			navTabs: $('#nav_tabs li'),
			itemCommentTabs: $('#item_comment_tabs a'),
			swiperMainWrapper: $('#swiper_main_wrapper'),
			swiperCommentWrapper: $('#swiper_comment_wrapper')
		},
		data = {filterData: {type: 'all', item_id: ITEM_ID}, filter: {}, activeIndex: 0, classMap: ['base', 'detail', 'koubei'], typeMap: ['all','good','between','bad','pic'], cache: {imgs: []}, isNewTab: 1},
		swipers = {}, popup = QY.util.popup, pictureView = require('widget/pictureView'), uri = data.classMap[0];

	for(var i = 0, len = data.typeMap.length; i < len; i++){
		data.filter[data.typeMap[i]] = {page: 1, allowPage: 1, loadPageTry: 1};
		elem['comment' + data.typeMap[i]] = $('#comment_' + data.typeMap[i]);
	}

	swipers.main = $('#swiper_main_container').swiper({
		cssWidthAndHeight: 'height',
		onSlideChangeStart: function(){
			elem.navTabs.eq(swipers.main.activeIndex).addClass('active').siblings('.active').removeClass('active');
			if( !data.koubei && swipers.main.activeIndex == 2 ){
				getData();
				elem.swiperCommentWrapper.on('click', '[node-type="vote"]', function(e){
					e.preventDefault();
					require('page/item/index:vote').call(this);
				});
				data.koubei = 1;
			} else {
				setAutoHeight('Main');
			}
			history.replaceState && history.replaceState({}, null, '#' + data.classMap[swipers.main.activeIndex]);
		}
	});
	elem.swiperMainSlides = elem.swiperMainWrapper.children('.swiper-slide');
	elem.navTabs.on('touchstart mousedown', function(){
		swipers.main.swipeTo($(this).index());
	});

	swipers.comment = $('#swiper_comment_container').swiper({
		cssWidthAndHeight: 'height',
		onSlideChangeStart: function(){
			var tab;
			data.activeIndex = swipers.comment.activeIndex;
			tab = data.filterData.type = data.typeMap[data.activeIndex];
			elem.itemCommentTabs.eq(data.activeIndex).addClass('now').siblings('.now').removeClass('now');
			data.filter[tab].firstFetch ? setAutoHeight('Comment') : (data.isNewTab = 1, data.filter[tab].firstFetch = 1, getData({tab: tab}));
		}
	});
	elem.swiperCommentSlides = elem.swiperCommentWrapper.children('.swiper-slide');
	elem.itemCommentTabs.on('touchstart mousedown', function(e){
		var index = $(this).index();
		e.preventDefault();
		data.filterData.type = data.typeMap[index];
		swipers.comment.swipeTo(index);
	});
	elem.swiperCommentWrapper.on('click', '[data-pictureView]', function(){
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
	$('#share_btn').click(function(){
		QY.UI.share(SHARE_DATA);
	});
	// 收藏
	$('#collect').click(function(){
		var self = $(this), id = parseInt(self.attr('data-id')), formData = {};
		if( !id ) return;
		if( self.hasClass('is_fav') ){
			formData.act = 'unlike';
		} else {
			formData.act = 'like';
		}
		self.toggleClass('is_fav');

		formData.item_id = id;
		data.collectSend || QY.util.request({
			type: 'GET',
			url: DOMAIN.like,
			data: formData,
			beforeSend: function(){
				data.collectSend = true;
			},
			success: function(response){
				if( !QY.util.checkLogin(response.code) ) return;
				if( response.code == 1 ){
					self.html('<img src="' + QY.domain.resource + 'images/collect' + (formData.act == 'like' ? '-light.png">取消' : '.png">收藏'));
					QY.util.popup.success(formData.act == 'like' ? '收藏成功' : '取消收藏成功');
				} else {
					QY.util.popup.error(response.msg);
				}
			},
			complete: function(){
				data.collectSend = false;
			}
		});
	});
	$('#item_comment_btn').click(function(){
		swipers.main.swipeTo(2);
	});
	$(window).scroll(function(){
		var body = document.body;
		if( body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
	});

	function setAutoHeight(type){
		type = type || 'Main';
		var index = 'swiper' + type + 'Slides', c = elem[index].eq(swipers[type.toLowerCase()].activeIndex);
		elem['swiper' + type + 'Wrapper'].css('height', c.height());
	}

	function getData(options){
		var container, filter;
		options = options || {};
		options.tab = options.tab || data.filterData.type;
		filter = data.filter[options.tab];
		if( !filter.allowPage ) return;
		container = elem['comment' + options.tab];
		data.isIndexSend || QY.util.request({
			data: $.extend(data.filterData, filter),
			type: 'GET',
			url: QY.util.url('item/commentlist'),
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
                setAutoHeight('Comment');
			}
		});
	}

	function renderList(options){
		var p, imgs, ilen, arr = [];
		for(var i = 0, len = options.list.length; i < len; i++){
            imgs = String(options.list[i].picture).split(',');
			options.list[i].pictures = [];
			ilen = data.cache.imgs.length;
			for(var j = 0, jlen = imgs.length; j < jlen; j++){
                if(  j > 2 ) break;
                if( imgs[j] == '' ) continue;
				p = QY.domain.attach + imgs[j];
				arr.push({content: p});
				options.list[i].pictures.push({id: ilen, index: j, src: p});
			}
			data.cache.imgs.push(arr);
			arr = [];
		}
		options.container[data.isNewTab ? 'html' : 'append'](QY.util.template('template_comment_item', {list: options.list}));
	}

	$('#slider').on('click', 'li', function(e){
		e.preventDefault();
        pictureView({
            data: BASEPREVIEW,
            initIndex: +$(this).index(),
            isLooping: true,
            animateType: 'flip',
            useZoom: true
        });
	}).swiper({
		pagination: '#btnBox'
	});
	$('#detail_images').on('click', '[node-type="pic"]', function(){
        pictureView({
            data: DETAILPREVIEW,
            initIndex: +$(this).index(),
            isLooping: true,
            animateType: 'flip',
            useZoom: true
        });
	});

	uri = window.location.href.match(/#(\w+)$/);
	if( uri && uri[1] ){
		uri = uri[1];
		swipers.main.swipeTo($.inArray(uri, data.classMap), 0);
	} else {
		uri = data.classMap[0];
	}

	setAutoHeight('Main');
});
/*define('page/item/index2', function(){
	var elem = {itemContent: $('#itemContent')},
		data = {commentEvent: false, ajaxUrl: QY.domain.base + 'item/commentlist', picCache: {}},
		events = {
			itemComment: function(){
				$.extend(elem, {
					commentList: $('#item_comment_list'),
					loadMoreBtn: $('#load_more_btn'),
					loadMoreIcon: $('#load_more_icon')
				});
				$('#item_comment_tabs').on('click', 'a', function(e){
					var self = $(this), type = self.attr('data-type');
					e.preventDefault();
					self.addClass('now').siblings('.now').removeClass('now');
					elem.loadMoreBtn.attr({'data-type': type, 'data-page': 2});
					fetchComment({
						type: type,
						page: 1,
						item_id: ITEM_ID
						//没有id怎么查
					});
				});
				elem.commentList.on('click', '[node-type="vote"]', function(e){
					e.preventDefault();
					require('page/item/index:vote').call(this);
				});
				$(document).on('click', '[data-pictureView]', function(e){
					var index = $(this).attr('data-pictureView').split(':'), pics = data.picCache[index[0]];
					e.preventDefault();
					if( !pics ) return;
					pictureView({list: pics, swipeTo: index[1]});
				});
				// require.async('widget/pullList', function(){
				// 	$('#load_more_btn').pullList({
				// 		type: 'GET',
				// 		data: {item_id: ITEM_ID},
				// 		url: data.ajaxUrl,
				// 		success: function(response){
				// 			renderComment(response, true);
				// 		}
				// 	});
				// });
			}
		},
		fetchComment = function(formData){
			QY.util.request({
				type: 'GET',
				url: data.ajaxUrl,
				data: formData,
				beforeSend: function(){
					elem.commentList.html('');
					elem.loadMoreBtn.hide();
					elem.loadMoreIcon.show();
				},
				success: function(response){
					renderComment(response);
				},
				complete: function(xhr){
					var response = JSON.parse(xhr.responseText);
					elem.loadMoreIcon.hide();
					if( response.msg.length < 1 ){
						// elem.commentList.html('<div class="rs-empty">暂无评论</div>');
					} else {
						elem.loadMoreBtn.show();
					}
				}
			});
		},
		renderComment = function(response, append){
			var tpl = [], i = 0, len, list, pics = [], picCache = [], p;
			if( append ){
				response = {code: 1, msg: response};
			}
			list = response.msg || [];
			if( response.code == 1 ){
				len = list.length;
				if( len == 0 ){
					elem.commentList.html('<div class="rs-empty">暂无评论</div>');
					return;
				}
				for(; i < len; i++){
					pics = list[i].picture.split(',');
					tpl.push('<div class="comment">'+
								'<ul>'+
									'<li class="comleft"><span class="outer"><span class="cover"></span><img src="' + QY.domain.attach + list[i].headimg + '"></span></li>'+
									'<li class="comright">'+
										'<ul>'+
											'<li class="comname">' + list[i].user_name + '</li>'+
											'<li class="comtime"><span><img src="' + res + 'images/star' + list[i].points + '.jpg"></span>' + (new Date(parseInt(list[i].create_time)*1000).format('yyyy-mm-dd')) + '</li>'+
											'<li class="comtext">' + list[i].note + '</li>'+
											'<li class="compic">');
					for(var j = 0, jlen = pics.length; j < len; j++){
						p = QY.domain.attach + pics[i];
						picCache.push({src: p});
						tpl.push(			'<a data-pictureView="' + i + ':' + j + '" href="#"><img src="' + p + '" alt=""></a>');
					}
					tpl.push(				'</li>'+
											'<span class="clear"></span>'+
											'<li class="operate">'+
												'<a node-type="vote" data-id="' + list[i].comment_id + '" data-num="' + list[i].likeNum + '" href="javascript:void(0);"><img src="' + res + 'images/praise.png">' + list[i].likeNum + '</a>'+
												'<a node-type="comm" data-id="' + list[i].comment_id + '" href="' + QY.domain.base + 'item/comment/' + list[i].comment_id + '"><img src="' + res + 'images/info.png">' + list[i].replyNum + '</a>'+
											'</li>'+
										'</ul>'+
									'</li>'+
								'</ul>'+
								'<span class="clear"></span>'+
							'</div>');
					picCache.length > 0 && (data.picCache[i] = picCache, picCache = []);
				}
				elem.commentList[append ? 'append' : 'html'](tpl.join(''));
			} else {
				QY.util.popup.error(response.msg);
			}
		}, res = QY.domain.resource, pictureView = require('widget/pictureView');

	$('#nav_tabs').on('click', 'li', function(){
		var self = $(this), target = self.attr('data-target');
		self.addClass('active').siblings('.active').removeClass('active');
		elem.itemContent.children('[data-name="' + target + '"]').show().siblings('[data-name]').hide();
		switch(target){
			case 'koubei':
				data.commentEvent || (events.itemComment(), data.commentEvent = true);
				break;
		}
	});

	// 分享
	$('#share_btn').click(function(){
		QY.UI.share(SHARE_DATA);
	});

	// 收藏
	$('#collect').click(function(){
		var self = $(this), id = parseInt(self.attr('data-id')), formData = {};
		if( !id ) return;
		if( self.hasClass('is_fav') ){
			formData.act = 'unlike';
		} else {
			formData.act = 'like';
		}
		self.toggleClass('is_fav');

		formData.item_id = id;
		data.collectSend || QY.util.request({
			type: 'GET',
			url: DOMAIN.like,
			data: formData,
			beforeSend: function(){
				data.collectSend = true;
			},
			success: function(response){
				if( !QY.util.checkLogin(response.code) ) return;
				if( response.code == 1 ){
					self.html('<img src="' + QY.domain.resource + 'images/collect' + (formData.act == 'like' ? '-light.png">取消' : '.png">收藏'));
					QY.util.popup.success(formData.act == 'like' ? '收藏成功' : '取消收藏成功');
				} else {
					QY.util.popup.error(response.msg);
				}
			},
			complete: function(){
				data.collectSend = false;
			}
		});
	});

	$('#item_comment_btn').click(function(){
		$('#nav_tabs [data-target="koubei"]').trigger('click');
	});

	new Swipe(document.getElementById('slider'),{
		startSlide: 0,
		speed: 600,
		auto: 0,
		callback: function(event, index, elem) {
			var btnBox = document.getElementById('btnBox');
			var lis = btnBox.getElementsByTagName('li');
			for(var i=0; i<lis.length; i++){
				lis[i].className = '';
			}
			lis[index].className = 'curr';
		}
	});
});*/
define('page/item/comment', function(){
	var elem = {dialogComment: $('#dialog_comment'), note: $('#note'), subcommentList: $('#subcomment_list'), commentUserName: $('#comment_user_name')},
		data = {uid: 0, pictureView: null},
		pictureView = require('widget/pictureView');
	$(document).click(function(){
		elem.dialogComment.hide();
	});
	elem.dialogComment.click(function(e){
		e.stopPropagation();
	});
	$('#vote_btn').click(function(e){
		e.preventDefault();
		require('page/item/index:vote').call(this);
	});
	$('#comment_btn').click(function(e){
		e.stopPropagation();
		dialogCommentShow(this);
	});
	$('#dialog_close').click(function(){
		elem.dialogComment.hide();
	});
	$('#dialog_send').click(function(e){
		var content = $.trim(elem.note.val());
		e.preventDefault();
		if( content == '' ){
			QY.util.popup.error('请输入内容');
			elem.note.focus();
			return;
		}

		QY.util.request({
			url: QY.domain.base + 'item/commentReply',
			data: {comment_id: COMMENT_ID, content: content, reply_user: data.uid},
			success: function(response){
				if( response.code == 1 ){
					elem.note.val('');
					elem.dialogComment.hide();
					QY.util.popup.success('评论成功');
					window.location.reload();
				} else if( response.code == 1001 ){
					window.location.href = QY.domain.base + 'login?url=' + window.location.href;
				} else {
					QY.util.popup.error(response.msg);
				}
			}
		});
	});

	elem.subcommentList.on('click', '[node-type="com-btn"]', function(e){
		e.preventDefault();
		e.stopPropagation();
		dialogCommentShow(this);
	});

	$(document).on('click', '[node-type="pictureView"]', function(e){
		e.preventDefault();
        if( data.pictureView ){
            data.pictureView.wrap.style.display = 'block';
        } else {
            data.pictureView = pictureView({
                data: COMMENT_IMG,
                isLooping: true,
                animateType: 'flip',
                useZoom: true
            });
        }
	});

	require.async('widget/pullList', function(){
		$('#load_more_btn').pullList({
			type: 'GET',
			url: QY.domain.base + 'item/commentReplyList',
			data: {comment_id: COMMENT_ID},
			success: function(list){
				var i = 0, len = 0, tpl = [];
				len = list.length;
				if( len == 0 ){
					QY.util.popup.error('暂无评论');
					return;
				}
				for(; i < len; i++){
					tpl.push('<div class="comment2">'+
								'<ul>'+
									'<li class="comleft"><span class="outer"><span class="cover"></span><img src="' + list.headimg + '"/></span></li>'+
									'<li class="comright">'+
										'<ul>'+
											'<li class="comname">'+
												'<dl>'+
													'<dt>' + list[i].create_nick_name + '<font>回复</font>' + list[i].reply_nick_name + '</dt>'+
													'<dd><a data-uid="' + list[i].reply_user_id + '" data-name="' + list[i].reply_nick_name + '" node-type="com-btn" href="#">回复</a></dd>'+
												'</dl>'+
											'</li>'+
											'<li class="comtime">' + (new Date(parseInt(list[i].create_time)*1000).format('yyyy-mm-dd hh:ii')) + '</li>'+
											'<li class="comtext">' + list[i].note + '</li>'+
										'</ul>'+
									'</li>'+
								'</ul>'+
								'<span class="clear"></span>'+
							'</div>');
				}
				elem.subcommentList.append(tpl.join(''));
			}
		});
	});

	function dialogCommentShow(_this){
		var self = $(_this), uid = parseInt(self.attr('data-uid')), name = self.attr('data-name');
		elem.note.attr('placeholder', '回复 ' + name);
		data.uid = uid;
		elem.dialogComment.show();
		elem.note.focus();
	}
});