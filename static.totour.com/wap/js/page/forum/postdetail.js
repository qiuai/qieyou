define('page/forum/postdetail', function(){
	var elem = {
			dialogReply: $('#dialogReply'),
			dialogReplyNote: $('#dialogReplyNote'),
			dialogReplySend: $('#dialogReplySend'),
			commentList: $('#comment_list'),
			commentUserName: $('#comment_user_name'),
			pictureList: $('#picture_list')
		},
		data = {request: {post: REQUIRE.POST_ID, page: 1, allowPage: true, loadPageTry: 1}, picture: [], cache: {}},
		popup = QY.util.popup, pictureView = require('widget/pictureView');


	elem.pictureList.on('click', 'li', function(){
		pictureView({list: data.picture, swipeTo: $(this).index()});
	}).children('li').each(function(){
		var self = $(this);
		data.picture.push({src: self.attr('data-pictureView')});
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
				elem.commentUserName.html(self.attr('data-uname'));
				elem.dialogReply.show();
		}
	});
	$('#dialogReplyCancle').click(function(){
		elem.dialogReply.hide();
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
	$(window).scroll(function(){
		var body = document.body;
		if( !data.replyFocus && body.scrollTop >= body.offsetHeight - window.innerHeight ){
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
	QY.util.setImageMiddle(elem.pictureList);
});