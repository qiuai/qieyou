define('page/forum/postdetail', function(){
	var elem = {
			dialogReply: $('#dialogReply'),
			dialogReplyNote: $('#dialogReplyNote'),
			dialogReplySend: $('#dialogReplySend'),
			commentList: $('#comment_list'),
			commentUserName: $('#comment_user_name'),
			pictureList: $('#picture_list')
		},
		data = {request: {post: REQUIRE.POST_ID, page: 1, allowPage: true, loadPageTry: 1}, picture: [], cache: {}, timer: {}},
		popup = QY.util.popup, pictureView = require('widget/pictureView');


	elem.pictureList.on('click', 'li', function(){
        pictureView({
            data: data.picture,
            initIndex: +$(this).index(),
            isLooping: true,
            animateType: 'flip',
            useZoom: true
        });
	}).children('li').each(function(){
		var self = $(this);
		data.picture.push({content: self.attr('data-pictureView')});
		self.removeAttr('data-pictureView');
	});
	$('#action').on('click', '[node-action]', function(e){
		var self = $(this), action = self.attr('node-action');
		e.preventDefault();
		switch(action){
			case 'praise':
				break;
			case 'comment':
				data.cid = self.attr('data-cid');
				clearTimeout(data.timer.c);
				elem.dialogReply.removeClass('fadeOut').addClass('zoomIn').show();
				data.timer.o = setTimeout(function(){
					elem.dialogReply.removeClass('zoomIn');
				}, 599);
				elem.commentUserName.html(self.attr('data-uname'));
		}
	});
	$('#dialogReplyCancle').click(function(){
		clearTimeout(data.timer.c);
		elem.dialogReply.removeClass('zoomIn').addClass('fadeOut');
		data.timer.o = setTimeout(function(){
			elem.dialogReply.removeClass('fadeOut').hide();
		}, 599);
	});
	elem.dialogReplySend.click(function(){
		var formData = {
			replypost: data.cid,
			note: $.trim(elem.dialogReplyNote.val())
		};

		if( formData.note == '' ){
			popup.error('请输入内容');
			elem.dialogReplyNote.focus();
			return;
		}

		data.replySend || QY.util.request({
			url: QY.util.url('forum/forumReReply'),
			data: formData,
			beforeSend: function(){
				data.replySend = true;
				elem.dialogReplySend.html('发送中...');
			},
			success: function(response){
				if( response.code == 1 ){
					popup.success('评论成功');
					window.location.reload();
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.replySend = false;
				elem.dialogReplySend.html('发送');
			}
		});
	});
	elem.commentList.on('click', '[node-action="reply"]', function(e){
		var self = $(this), rid = parseInt(self.attr('data-rid'));
		data.cid = rid;
		e.preventDefault();
		elem.dialogReply.show();
		elem.commentUserName.html(self.attr('data-uname'));
	});
	elem.commentList.on('click', '[node-action="hide"]', function(e){
		var self = $(this);
		e.preventDefault();
		data.Send || QY.util.request({
			type: 'POST',
			url: QY.util.url('group/forumManage'),
			data: {post: self.attr('data-pid'), act: 'del_post'},
			beforeSend: function(){
				data.Send = true;
			},
			success: function(response){
				if( response.code == 1 ){
					var con = self.closest('comment2');
					popup.success('屏蔽成功');
					con.slideUp(0);
					setTimeout(function(){
						con.remove();
					}, 501);
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.Send = false;
			}
		});
	});
	$(document).on('click', '[node-action="opr"]', function(){
		var self = $(this);
		data.Send || QY.util.request({
			type: 'GET',
			url: QY.util.url(''),
			data: formData,
			beforeSend: function(){
				data.Send = true;
			},
			success: function(response){
				console.log(response);
			},
			complete: function(){
				data.Send = false;
			}
		});
	});
	$(window).scroll(function(){
		var body = document.body;
		if( body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
	});

	function getData(options){
		options = options || {};
		if( !data.request.allowPage ) return;
		container = elem.commentList;
		data.isIndexSend || QY.util.request({
			data: data.request,
			type: 'GET',
			url: QY.util.url('forum/getPostReply'),
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
		options.container.append(QY.util.template('template_comment', {list: options.list}));
	}

	window.renderGeo = function(response){
		data.cache.formData.city = response.result.addressComponent.city;
	}

	getData();
	QY.util.setImageMiddles(elem.pictureList);
});