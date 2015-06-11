define('page/forum/post:jianren', function(){
	var elem = {
			formTitle: $('#form_title'),
			formGroup: $('#form_group'),
			formCircuit: $('#form_circuit'),
			formDate: $('#form_date'),
			formNum: $('#form_num'),
			formTag: $('#form_tag'),
			formNote: $('#form_note'),
			uploadPic: $('#upload_pic')
		},
		data = {},
		popup = QY.util.popup;

	elem.formNum.on('input', function(){
		var num = parseInt(this.value) || 1;
		this.value = num;
	});
	$('#submit_btn').click(function(e){
		var formData, tags;
		e.preventDefault();
		formData = {
			type: 'jianren',
			group: $.trim(elem.formGroup.val()),
			title: $.trim(elem.formTitle.val()),
			line: $.trim(elem.formCircuit.val()),
			start_time: $.trim(elem.formDate.val()),
			day: $.trim(elem.formNum.val()),
			tags: $.trim(elem.formTag.val()),
			note: $.trim(elem.formNote.val()),
			city: _DATA.city
		};
		if( formData.title == '' ){
			popup.error('请输入标题');
			elem.formTitle.focus();
			return;
		}
		if( formData.title.length > 50 ){
			popup.error('标题大于50个字符');
			elem.formTitle.focus();
			return;
		}
		if( formData.line == '' ){
			popup.error('请输入线路');
			elem.formCircuit.focus();
			return;
		}
		if( !formData.day ){
			popup.error('请输入正确的天数');
			elem.formNum.focus();
			return;
		}
		tags = formData.tags.split(/\s+/);
		if( tags.length > 3 ){
			popup.error('标签不能超过3个');
			elem.formTag.focus();
			return;
		}
		for(var i = 0, len = tags.length; i < len; i++){
			if( tags[i].length > 6 ){
				popup.error('单个标签不能超过6个字');
				elem.formTag.focus();
				return;
			}
		}
		formData.tags = tags.join();
		if( formData.note == '' ){
			popup.error('请输入内容');
			elem.formNote.focus();
			return;
		}
		if( !_DATA.imgs || _DATA.imgs == '' ){
			popup.error('至少上传一张图片哦');
			return;
		}
		formData.images = _DATA.imgs.replace(/(^\,|\,$)/, '');
		if( _DATA.pos ){
			$.extend(formData, _DATA.pos || {lat: 0, lon: 0});
		}

		data.isSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('forum/postForum'),
			data: formData,
			beforeSend: function(){
				data.isSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					popup.success('发布成功');
					QY.util.redirect('group');
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSend = false;
			}
		});
	});
});

define('page/forum/post:youji', function(){
	var elem = {
			formGroup: $('#form_group'),
			formTitle: $('#form_title'),
			formTag: $('#form_tag'),
			formNote: $('#form_note'),
			uploadPic: $('#upload_pic')},
		data = {},
		popup = QY.util.popup;

	$('#submit_btn').click(function(e){
		var formData;
		e.preventDefault();
		formData = {
			type: 'tour',
			group: $.trim(elem.formGroup.val()),
			title: $.trim(elem.formTitle.val()),
			tags: $.trim(elem.formTag.val()),
			note: $.trim(elem.formNote.val()),
			city: _DATA.city
		};
		if( formData.title == '' ){
			popup.error('请输入标题');
			elem.formTitle.focus();
			return;
		}
		if( formData.title.length > 50 ){
			popup.error('标题大于50个字符');
			elem.formTitle.focus();
			return;
		}
		tags = formData.tags.split(/\s+/);
		if( tags.length > 3 ){
			popup.error('标签不能超过3个');
			elem.formTag.focus();
			return;
		}
		for(var i = 0, len = tags.length; i < len; i++){
			if( tags[i].length > 6 ){
				popup.error('单个标签不能超过6个字');
				elem.formTag.focus();
				return;
			}
		}
		formData.tags = tags.join();
		if( formData.note == '' ){
			popup.error('请输入内容');
			elem.formNote.focus();
			return;
		}
		if( !_DATA.imgs || _DATA.imgs == '' ){
			popup.error('至少上传一张图片哦');
			return;
		}
		formData.images = _DATA.imgs;
		if( _DATA.pos ){
			$.extend(formData, _DATA.pos || {lat: 0, lon: 0});
		}

		data.isSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('forum/postForum'),
			data: formData,
			beforeSend: function(){
				data.isSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					popup.success('发布成功');
					QY.util.redirect('group');
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSend = false;
			}
		});
	});
});

define('page/forum/post:wenda', function(){
	var elem = {
			formGroup: $('#form_group'),
			formTitle: $('#form_title'),
			formTag: $('#form_tag'),
			formNote: $('#form_note'),
			uploadPic: $('#upload_pic')
		},
		data = {},
		popup = QY.util.popup;

	$('#submit_btn').click(function(e){
		var formData;
		e.preventDefault();
		formData = {
			type: 'wenda',
			group: $.trim(elem.formGroup.val()),
			title: $.trim(elem.formTitle.val()),
			tags: $.trim(elem.formTag.val()),
			note: $.trim(elem.formNote.val()),
			city: _DATA.city
		};
		if( formData.title == '' ){
			popup.error('请输入标题');
			elem.formTitle.focus();
			return;
		}
		if( formData.title.length > 50 ){
			popup.error('标题大于50个字符');
			elem.formTitle.focus();
			return;
		}
		tags = formData.tags.split(/\s+/);
		if( tags.length > 3 ){
			popup.error('标签不能超过3个');
			elem.formTag.focus();
			return;
		}
		if( formData.note == '' ){
			popup.error('请输入内容');
			elem.formNote.focus();
			return;
		}
		for(var i = 0, len = tags.length; i < len; i++){
			if( tags[i].length > 6 ){
				popup.error('单个标签不能超过6个字');
				elem.formTag.focus();
				return;
			}
		}
		formData.tags = tags.join();
		/*if( _DATA.imgs == '' ){
			popup.error('至少上传一张图片哦');
			return;
		}*/
		formData.images = _DATA.imgs.replace(/(^\,|\,$)/, '');
		if( _DATA.pos ){
			$.extend(formData, _DATA.pos || {lat: 0, lon: 0});
		}

		data.isSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('forum/postForum'),
			data: formData,
			beforeSend: function(){
				data.isSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					popup.success('发布成功');
					QY.util.redirect('group');
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSend = false;
			}
		});
	});
});

define('page/forum/post:selectGroup', function(){
	var elem = {
			dialogGroup: $('#dialog_group'),
			dialogGroupNum: $('#dialog_group_num'),
			dialogRecommend: $('#dialog_recommend'),
			dialogTip: $('#dialog_tip'),
			formGroup: $('#form_group'),
			groupName: $('#group_name'),
			groupImg: $('#group_img'),
		},
		data = {
			recommend: []
		},
		popup = QY.util.popup;
	$('#select_group').click(function(e){
		e.preventDefault();
		if( data.firstFetchGroup ){
			if( data.hasGroup ){
				elem.dialogGroup.show();
			} else {
				elem.dialogTip.show().prev().show();
			}
		} else {
			elem.dialogGroup.hide();
			data.fetchSend || QY.util.request({
				type: 'GET',
				url: QY.util.url('user/getMyForum'),
				data: {type: 'group'},
				beforeSend: function(){
					data.fetchSend = true;
				},
				success: function(response){
					var list = [];
					for(var i = 0, len = response.msg.length; i < len; i++){
						if( !response.msg[i].join_time && response.msg[i].waiting != 1 && response.msg[i].join_method != 'noable' ){
							list.push(response.msg[i]);
						}
					}
					data.hasGroup = !!list.length;
					if( data.hasGroup ){
						elem.dialogGroup.show();
						elem.dialogGroupNum.html(list.length);
						$('#content_group').html(QY.util.template('template_group', {list: list})).next().hide();
						data.firstFetchGroup = true;
					} else {
						elem.dialogTip.show().prev().show();
					}
				},
				complete: function(){
					data.fetchSend = false;
				}
			});
		}
	});
	$('#dialog_group_close').click(function(){
		elem.dialogGroup.hide();
	});
	$('#dialog_recommend_close').click(function(){
		elem.dialogRecommend.hide();
	});
	elem.dialogTip.on('click', '[node-type]', function(e){
		var self = $(this);
		e.preventDefault();
		switch(self.attr('node-type')){
			case 'confirm':
				elem.dialogTip.hide().prev().hide();
				break;
			case 'recommend':
				elem.dialogTip.hide().prev().hide();
				elem.dialogRecommend.show();
				data.fetchSend || QY.util.request({
					type: 'GET',
					url: QY.util.url('group/get'),
					data: {type: 'rank'},
					beforeSend: function(){
						data.fetchSend = true;
					},
					success: function(response){
						$('#content_recommend').html(QY.util.template('template_recommend', {list: response})).next().hide();
						data.recommend = response;
					},
					complete: function(){
						data.fetchSend = false;
					}
				});
		}
	});
	$('#dialog_recommend_ok').click(function(){
		elem.dialogRecommend.hide();
		elem.dialogGroup.show();
		$('#select_group').trigger('click');
	});
	$('#content_group').on('click', 'input[name="group"]', function(){
		var self = $(this), gid = self.val(), gname = self.attr('data-gname'), gimg = self.attr('data-gimg');
		elem.dialogGroup.hide();
		elem.formGroup.val(gid);
		elem.groupName.html(gname);
		elem.groupImg.attr('src', QY.domain.attach + gimg);
	});
	$('#content_recommend').on('click', '[data-attention]', function(e){
		var self = $(this), gid = parseInt(self.attr('data-gid')), act = self.attr('data-attention'), method = self.attr('data-joinable');
		e.preventDefault();
		if( !gid || act != 'join' ) return;
		data.attentionSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('group/groupJoin'),
			data: {act: act, group: gid},
			beforeSend: function(){
				data.attentionSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					if( method == 'verify' ){
	                    popup.success('加入部落审核中');
	                    self.html('审核中')
					} else {
						popup.success('加入部落成功');
						self.html('已加入');
					}
                    self.attr('data-attention', 'quit');
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.attentionSend = false;
			}
		});
	});
	$('#attention_all').click(function(){
		var ids = [];
		for(var i = 0, len = data.recommend.length; i < len; i++){
			ids.push(data.recommend[i].group_id);
		}
		actionGroup({
			data: {ids: ids.join()},
			success: function(response){
				if( response.code == 1 ){
					$('#content_recommend').html(QY.util.template('template_recommend', {list: data.recommend}));
				} else {
					popup.error(response.msg);
				}
			}
		});
	});

	function actionGroup(options){
		data.attentionSend || QY.util.request($.extend({
			type: 'GET',
			url: QY.util.url('forum/action_group'),
			beforeSend: function(){
				data.attentionSend = true;
			},
			complete: function(){
				data.attentionSend = false;
			}
		}, options));
	}
});

define('page/forum/post', function(){
	var elem = {
			selectPos: $('#select_pos'), 
			uploadPic: $('#upload_pic'),
			uploadAdd: $('#upload_add'),
			dialogPosition: $('#dialog_position'),
			positionList: $('#position_list'),
			selectGroup: $('#select_group')
		},
		data = {},
		popup = QY.util.popup;
	window._DATA = {imgs: ''};
	require('page/forum/post:' + REQUIRE.TYPE);
	require('page/forum/post:selectGroup');

	elem.selectPos.click(function(e){
		e.preventDefault();
		navigator.geolocation.getCurrentPosition(function(event){
			G.lat = event.coords.latitude;
			G.lon = event.coords.longitude;
			_DATA.pos = {lat: G.lat, lon: G.lon};
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
				url: 'http://api.map.baidu.com/geocoder/v2/?ak=zxC49c0iAk2XD1FlRGonHqKZ&callback=renderGeo&location=' + G.lat + ',' + G.lon + '&output=json&pois=1&t=' + Date.now(),
				beforeSend: function(){
					data.isGeoSend = true;
					elem.selectPos.find('em').html('位置获取中......');
				},
				success: function(response){
					// alert(response);
				},
				complete: function(){
					data.isGeoSend = false;
					elem.selectPos.find('em').html('显示你的位置');
				}
			});
		}
	});

	$('#pos_close').click(function(){
		$('body').css({overflow: ''});
		elem.dialogPosition.hide();
	});
	$('.base-top a.left').on('click', function(e){
		e.preventDefault();
		if( confirm('是否离开此页面？') ){
			window.history.back(-1);
		}
	});
	elem.positionList.on('click', 'input[data-pos]', function(){
		var self = $(this), pos = String(self.attr('data-pos')).split(':'), name = self.attr('data-name');
		_DATA.pos = {lat: pos[1], lon: pos[0]};
		$('body').css({overflow: ''});
		elem.dialogPosition.hide();
		elem.selectPos.find('em').html(name);
	});

	elem.uploadPic.on('click', '[node-type="del"]', function(e){
		var self = $(this), src = self.attr('data-src');
		e.preventDefault();
		_DATA.imgs = _DATA.imgs.replace(src + ',', '');
		self.closest('li').remove();
		elem.uploadAdd.show();
	});
	$('#upload_img').change(function(){
		var maxNum = REQUIRE.TYPE == 'youji' ? 15 : 9, maxSize = 5 * 1024 * 1024, file = this.files[0], imgs;
		if( !file ) return;
        if( !/^image\//.test(file.type) ){
            QY.util.popup.error('只能上传gif,jpg,jpeg,png,bmp格式的图片');
            return;
        }
        if( file.size > maxSize ){
            popup.error('图片不能大于5M哦');
            return;
        }
        imgs = _DATA.imgs.replace(/(^\,|\,$)/, '').split(',');
        if( imgs.length >= maxNum ){
        	popup.error('图片最多只能上传' + maxNum + '张');
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
                    tpl = '<li><a href="javascript:;"><img src="' + QY.domain.attach + info + '" onload="QY.util.setImageMiddle(this)" /></a><a data-src="' + info + '" node-type="del" href="#" class="close"><img src="' + QY.domain.resource + 'images/close4.png" /></a></li>';
					elem.uploadAdd.before(tpl);
					_DATA.imgs += info + ',';
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
			var percentage = (e.loaded / e.total * 100);
			progressBar.css('width', percentage + '%');
			percent.html(percentage.toFixed(0) + '%');
		};
		xhr.send(formData);
	});

	window.renderGeo = function(list){
		var rs = list.result.pois;
		_DATA.city = list.result.addressComponent.city;
		elem.positionList.html(QY.util.template('template_position', {list: rs, pos: {lat: G.lat, lon: G.lon}}));
		elem.dialogPosition.show();
		$('body').css({overflow: 'hidden'});
	}
});