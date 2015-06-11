define('page/group/newgroup', function(){
	var elem = {
			formJoinMethod: $('#join_method input[name="join_method"]'),
			avatar: $('#avatar'),
			avatarPreview: $('#avatar_preview'),
			cropContainer: $('#crop_container')
		},
		data = {},
		popup = QY.util.popup, Crop = require('widget/crop');
	QY.util.extendElement(elem, ['#form_name', '#form_avatar', '#form_desc']);
	data.avatar = new Crop({
		url: QY.util.url('upload/wap?type=grouphead')
	});

	$('#submit_btn').on('click', function(e){
		var self = $(this), formData;
		e.preventDefault();
		formData = {
			groupname: $.trim(elem.formName.val()),
			groupimg: $.trim(elem.formAvatar.val()),
			note: $.trim(elem.formDesc.val()),
			joinmethod: elem.formJoinMethod.filter(':checked').val()
		};

		if( formData.groupname == '' ){
			popup.error('请输入部落名称');
			elem.formName.focus();
			return;
		}
		if( formData.groupname.length > 8 ){
			popup.error('部落名称超过8个字符');
			elem.formName.focus();
			return;
		}
		if( formData.groupimg == '' ){
			popup.error('请上传部落头像');
			return;
		}

		data.isSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('group/groupCreate'),
			data: formData,
			beforeSend: function(){
				data.isSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					popup.success('部落创建成功');
					QY.util.redirect('group/' + response.msg);
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSend = false;
			}
		});
	});

	data.avatar.on('success', function(response){
		if( response.code == 1 ){
			elem.formAvatar.val(response.msg);
			elem.avatarPreview.attr('src', QY.domain.attach + response.msg);
			elem.cropContainer.hide();
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