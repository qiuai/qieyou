define('page/home/edituser', function(){
	var elem = {
			dialogEdit: $('#dialog_edit'),
			dialogEditTitle: $('#dialog_edit_title'),
			editContent: $('#edit_content'),
			nickname: $('#nickname'),
			signature: $('#signature'),
			birthday: $('#birthday'),
			sex: $('input[name="sex"]'),
			avatar: $('#avatar'),
			avatarPreview: $('#avatar_preview'),
			cropContainer: $('#crop_container')
		},
		data = {
			currentTab: null
		},
		popup = QY.util.popup, Crop = require('widget/crop');
	data.avatar = new Crop({
		url: QY.util.url('upload/wap?type=userhead')
	});

	$(document).on('click', '[node-edit]', function(e){
		var self = $(this), target = self.attr('node-edit');
		e.preventDefault();
		elem.dialogEditTitle.html(self.attr('node-edit-name'));
		elem.editContent.children('[data-name="' + target + '"]').show().siblings().hide();
		elem.dialogEdit.show();
		data.currentTab = target;
	});

	elem.dialogEdit.find('.save').click(function(){
		var self = $(this), formData = {type: data.currentTab};
		switch(data.currentTab){
			case 'nickname':
				formData.nickname = $.trim(elem.nickname.val());
				if( formData.nickname == '' ){
					popup.error('请输入昵称在提交');
					elem.nickname.focus();
					return;
				}
				if( formData.nickname.length > 8 ){
					popup.error('昵称大于8个字符');
					elem.nickname.focus();
					return;
				}
				break;
			case 'signature':
				formData.signature = $.trim(elem.signature.val());
				if( formData.signature == '' ){
					popup.error('请输入内容在提交');
					elem.signature.focus();
					return;
				}
				break;
			case 'birthday':
				$.extend(formData, {
					birthday: elem.birthday.val()
				});
				break;
			case 'sex':
				formData.sex = elem.sex.filter(':checked').val();
		}

		data.isSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('/home/editUserinfo'),
			data: formData,
			beforeSend: function(){
				data.isSend = true;
				self.html('保存中...');
			},
			success: function(response){
				var con, text, year, day;
				if( response.code == 1 ){
					elem.dialogEdit.hide();
					switch(data.currentTab){
						case 'nickname':
							text = formData.nickname;
							break;
						case 'signature':
							text = formData.signature
							break;
						case 'birthday':
							year = new Date().getFullYear();
							day = (year % 400 == 0)||(year % 4 == 0)&&(year % 100 != 0) ? 366 : 365;
							text = Math.floor((Date.now() - new Date(formData.birthday.replace(/\-/g, '/')).getTime()) / (24*3600*day*1000));
							break;
						case 'sex':
							text = {M: '男', F: '女'}[formData.sex];
					}
					$('#edit_content_' + data.currentTab).html(text);
					popup.success('保存成功');
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSend = false;
				self.html('保存');
			}
		});
	});
	elem.dialogEdit.find('.cancel').click(function(){
		elem.dialogEdit.hide();
	});

	data.avatar.on('success', function(response){
		if( response.code == 1 ){
			data.avatarSend || QY.util.request({
				type: 'POST',
				url: QY.util.url('/home/editUserinfo'),
				data: {type: 'headimg', headimg: response.msg},
				beforeSend: function(){
					data.avatarSend = true;
				},
				success: function(response2){
					if( response2.code == 1 ){
						elem.avatarPreview.attr('src', QY.domain.attach + response.msg);
						elem.cropContainer.hide();
					} else {
						popup.error(response2.msg);
					}
				},
				complete: function(){
					data.avatarSend = false;
				}
			});
		} else {
			popup.error(response.msg);
		}
	});
	elem.avatar.on('change', function(){
		var maxSize = 5 * 1024 * 1024, file = this.files[0], imgs;
		if( !file ) return;
        if( file.size > maxSize ){
            popup.error('图片不能大于5M哦');
            return;
        }

        data.avatar.render(file);
	});
});