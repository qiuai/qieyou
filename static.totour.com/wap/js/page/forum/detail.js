/*define('page/group/detail:wenda', function(){
	var elem = {},
		data = {};
});

define('page/group/detail:jianren', function(){
	var elem = {},
		data = {};
});*/

define('page/forum/detail', function(){
	var elem = {
			upload: $('.picture'),
			wendaAction: $('#wenda_action'),
			uploadPic: $('#upload_pic'),
			uploadAdd: $('#upload_add'),
			dialogMainReply: $('#dialogMainReply'),
			dialogMainReplySend: $('#dialogMainReplySend'),
			dialogMainReplyNote: $('#dialogMainReplyNote'),
			replyHd: $('#reply_hd'),
			dialogReply: $('#dialogReply'),
			dialogReplyNote: $('#dialogReplyNote'),
			dialogReplySend: $('#dialogReplySend'),
			commentList: $('#comment_list'),
			commentUserName: $('#comment_user_name')
		},
		data = {imgs: '', request: {forum: REQUIRE.FORUM_ID, page: 1, allowPage: true, loadPageTry: 1}, picture: [], cache: {imgs: [], formData: {city: ''}}},
		popup = QY.util.popup, pictureView = require('widget/pictureView');

	elem.wendaAction.on('click', '[data-action]', function(e){
		var self = $(this), action = self.attr('data-action'), act = self.attr('data-act'), id = parseInt(self.attr('data-id')), num = parseInt(self.attr('data-num'));
		// e.preventDefault();
		if( !id ) return;
		++num;
		switch(action){
			case 'praise' :
				self.attr('data-num', num);
				actionRequest({
					text: num,
					icon: 'praise-rlight',
					action: action,
					data: {forum: id, act: act},
				});
				break;
			case 'share'  :
				e.stopPropagation();
				window.SHARE && QY.UI.share(SHARE);
				break;
			case 'comment':
				if( !QY.util.isLogin() ){
					QY.util.jumpLogin();
					return;
				}
				elem.dialogMainReply.show();
				navigator.geolocation.getCurrentPosition(function(event){
					G.lat = event.coords.latitude;
					G.lon = event.coords.longitude;
					getGeoData();
				}, function(event){
                    if( !G.lat || !G.lon ){
                        alert('您禁用了共享位置信息');
                        return;
                    }
					getGeoData();
				});

				function getGeoData(){
					if( !G.lat || !G.lon ) return;
					data.isGeoSend || $.ajaxJSONP({
						url: 'http://api.map.baidu.com/geocoder/v2/?ak=zxC49c0iAk2XD1FlRGonHqKZ&callback=renderGeo&location=' + G.lat + ',' + G.lon + '&output=json&pois=0&t=' + Date.now()
					});
				}
				$('body').css({overflow: 'hidden'});
				break;
			case 'give'   :
				actionRequest({
					text: '打赏',
					icon: 'jifen-red',
					action: action,
					url: QY.util.url('forum/givenPoint'),
					data: {type: 'forum', typeid: id},
					success: function(){
						popup.success('成打赏功10积分');
					}
				});
				break;
			case 'collect':
				actionRequest({
					text: '收藏',
					icon:  act=='fav'?'collect-rlight':'collect-red',
					action: action,
					data: {forum: id, act: act},
				});
				self.attr('data-act', act=='fav'?'unfav':'fav');
		}
	});
	$('#dialogMainReplyCancle').click(function(){
		elem.dialogMainReply.hide();
		$('body').css({overflow: ''});
        history.replaceState && history.replaceState({}, '', location.href.replace(/#comment$/, ''))
	});
	$('#dialogReplyCancle').click(function(){
		elem.dialogReply.hide();
		$('body').css({overflow: ''});
	});
	elem.dialogMainReplySend.click(function(){
		var formData = {
			forum: REQUIRE.FORUM_ID,
			note: $.trim(elem.dialogMainReplyNote.val()),
			images: String(data.imgs).replace(/(^\,|\,$)/, ''),
			lat: G.lat,
			lon: G.lon,
			city: data.cache.formData.city
		};

		if( formData.note == '' ){
			popup.error('请输入内容');
			elem.dialogMainReplyNote.focus();
			return;
		}

		data.mainReplySend || QY.util.request({
			url: QY.util.url('forum/forumReply'),
			data: formData,
			beforeSend: function(){
				data.mainReplySend = true;
				elem.dialogMainReplySend.html('发送中...');
			},
			success: function(response){
				if( response.code == 1 ){
					elem.dialogMainReply.hide();
					data.imgs = '';
					elem.uploadAdd.siblings().remove();
					popup.success('评论成功');
					elem.commentList.append(QY.util.template('template_comment', {list: [response.msg]}));
				} else {
					if( QY.util.checkLogin(response.code) ) return;
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.mainReplySend = false;
				elem.dialogMainReplySend.html('发送');
			}
		});
	});
	$('#picture_list').on('click', 'li', function(){
		pictureView({list: data.picture, swipeTo: $(this).index()});
	}).children('li').each(function(){
		var self = $(this);
		data.picture.push({src: self.attr('data-pictureView')});
		self.removeAttr('data-pictureView');
	});
	elem.commentList.on('click', '[node-action]', function(e){
		var self = $(this), cid = parseInt(self.attr('data-cid')), name;
        e.preventDefault();
		if( !cid ) return;
        data.cid = cid;
        switch(self.attr('node-action')){
			case 'praise':
				data.praiseSend || QY.util.request({
					url: QY.util.url('forum/favPost'),
					data: {post: cid, act: 'like'},
					beforeSend: function(){
						data.praiseSend = true;
					},
					success: function(response){
						var num = (parseInt(self.attr('data-num')) || 0) + 1;
						if( response.code == 1 ){
							self.attr('data-num', num).html('<img src="' + QY.domain.resource + 'images/praise-rlight.png">' + num);
						} else {
							popup.error(response.msg);
						}
					},
					complete: function(){
						data.praiseSend = false;
					}
				});
				break;
			case 'given':
				data.givenSend || QY.util.request({
					url: QY.util.url('forum/givenPoint'),
					data: {type: 'post', typeid: cid},
					beforeSend: function(){
						data.givenSend = true;
					},
					success: function(response){
						var num = (parseInt(self.attr('data-num')) || 0) + 10;
						if( response.code == 1 ){
							self.attr('data-num', num).html('<img src="' + QY.domain.resource + 'images/jifen.png">' + num);
						} else {
							popup.error(response.msg);
						}
					},
					complete: function(){
						data.givenSend = false;
					}
				});
				break;
			case 'comment':
				if( !QY.util.isLogin() ){
					QY.util.jumpLogin();
					return;
				}
				data.target = self;
				elem.dialogReply.show();
				elem.commentUserName.html(self.attr('data-uname'));
		}
	});
	elem.dialogReplySend.click(function(e){
		var formData = {
			replypost: data.cid,
			forum: REQUIRE.FORUM_ID,
			note: $.trim(elem.dialogReplyNote.val())
		};
		e.preventDefault();

		if( formData.note == '' ){
			popup.error('请输入评论内容');
			elem.dialogReplyNote.focus();
			return;
		}

		data.replySend || QY.util.request({
			url: QY.util.url('forum/forumReReply'),
			data: formData,
			beforeSend: function(){
				data.replySend = true;
			},
			success: function(response){
				var self = data.target, subcommentList = $('#comment_' + data.cid), num = (parseInt(self.attr('data-num')) || 0) + 1;
				if( response.code == 1 ){
					popup.success('评论成功');
					elem.dialogReplyNote.val('');
					elem.dialogReply.hide();
					self.children('em').attr('data-num', num).html(num);
					if( num > 2 ) return;
					subcommentList.closest('[node-type="subcom"]').show();
					subcommentList.append(QY.util.template('template_subcomment', {r: {create_user: 1013, create_name: 'Sanonz', post_detail: formData.note}, v: {post_id: 1002}}));
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.replySend = false;
			}
		});
	});
	elem.commentList.on('click', '[data-pictureView]', function(){
		var imgs, self = $(this), index = self.attr('data-pictureView');
		if( !index ) return;
		index = String(index).split(':');
		imgs = data.cache.imgs[index[0]];
		pictureView({list: imgs, swipeTo: index[1]});
	});
	$(window).scroll(function(){
		var body = document.body;
		if( !data.replyFocus && body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
	});

	elem.uploadPic.on('click', '[node-type="del"]', function(e){
		var self = $(this), src = self.attr('data-src');
		e.preventDefault();
		data.imgs = data.imgs.replace(src + ',', '');
		self.closest('li').remove();
        elem.uploadAdd.show();
	});
	$('#upload_img').change(function(){
		var maxNum = 9, maxSize = 5 * 1024 * 1024, file = this.files[0];
		if( !file ) return;
        if( !/^image\//.test(file.type) ){
            QY.util.popup.error('只能上传gif,jpg,jpeg,png,bmp格式的图片');
            return;
        }
        if( file.size > maxSize ){
            QY.util.popup.error('图片不能大于5M哦');
            return;
        }
        imgs = data.imgs.replace(/(^\,|\,$)/, '').split(',');
        if( imgs.length >= maxNum ){
        	QY.util.popup.error('图片最多只能上传' + maxNum + '张');
        	return;
        }
        if( imgs.length == maxNum - 1 ){
            elem.uploadAdd.hide();
        }
		var xhr = new XMLHttpRequest(), formData = new FormData(), addLine = elem.uploadAdd.find('.add-line'), progress = elem.uploadAdd.find('.progress'), progressBar = elem.uploadAdd.find('.progress-bar'), percent = elem.uploadAdd.find('.progress-percentage');
		formData.append('imgFile', file);
		xhr.open('POST', QY.util.url('upload?type=topic'));
		xhr.onreadystatechange = function(){
			var tpl, info, response;
			if( xhr.readyState == 4 && xhr.status == 200 ){
				response = JSON.parse(xhr.responseText);
				if( !QY.util.checkLogin(response.code) ) return;
				if( response.code == 1 ){
					info = response.msg;
					tpl = '<li><img src="' + QY.domain.attach + info + '"/><a data-src="' + info + '" node-type="del" href="#" class="close"><img src="' + QY.domain.resource + 'images/close4.png" /></a></li>';
					elem.uploadAdd.before(tpl);
					data.imgs += info + ',';
				} else {
					popup.error(response.msg);
				}
			}
			progress.hide();
			addLine.show();
			progressBar.css('width', '');
			percent.html('上传中...');
		};
		addLine.hide();
		progress.show();
		xhr.upload.onprogress = function(e){
			var percentage = e.loaded / e.total * 100;
			progressBar.css('width', percentage + '%');
            percent.html(percentage.toFixed(0) + '%');
		};
		xhr.send(formData);
	});

	function actionRequest(options){
		data.isActionSend || QY.util.request({
			url: options.url || QY.util.url('forum/favForum'),
			data: options.data,
			beforeSend: function(){
				data.isActionSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					elem.wendaAction.children('[data-action="' + options.action + '"]').html('<span><img src="' + QY.domain.resource + 'images/' + options.icon + '.png"></span>' + options.text);
					typeof options.success === 'function' && options.success(response);
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

	function getData(options){
		options = options || {};
		if( !data.request.allowPage ) return;
		container = elem.commentList;
		data.isIndexSend || QY.util.request({
			data: data.request,
			type: 'GET',
			url: QY.util.url('forum/getForumPost'),
			beforeSend: function(){
				data.isIndexSend = true;
				container.next().show();
			},
			success: function(response){
				if( response.code == 1 ){
					if( response.msg.length < 1 ){
						if( data.request.loadPageTry >= 1 ){
							data.request.allowPage = false;
						} else {
							++data.request.loadPageTry;
						}
						return;
					} else {
						++data.request.page;
					}
					$.extend(options, {
						list: response.msg,
						container: container
					});
					renderList(options);
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isIndexSend = false;
				container.next().hide();
			}
		});
	}

	function renderList(options){
		var p, imgs, ilen, arr = [];
		for(var i = 0, len = options.list.length; i < len; i++){
			imgs = String(options.list[i].pictures).split(',');
			options.list[i].pictures = [];
			ilen = data.cache.imgs.length;
			for(var j = 0, jlen = imgs.length; j < jlen; j++){
                if(  j > 2 ) break;
                if( imgs[j] == '' ) continue;
				p = QY.domain.attach + QY.util.changeImageSize(imgs[j], 's');
				arr.push({src: p});
				options.list[i].pictures.push({id: ilen, index: j, src: p});
			}
			data.cache.imgs.push(arr);
			arr = [];
		}
		options.container.append(QY.util.template('template_comment', {list: options.list}));
		QY.util.setImageMiddle(options.container);
	}

	window.renderGeo = function(response){
		data.cache.formData.city = response.result.addressComponent.city;
	}

	var uri = window.location.href.match(/#comment$/);
	if( uri && uri[0] ){
		elem.wendaAction.children('[data-action="comment"]').trigger('click');
	}

	// require('page/group/detail:' + REQUIRE.DEP);
	getData();
	QY.util.setImageMiddle($('#picture_list'));
});