define('page/group/settings', function(){
	var elem = {
			dialogEdit: $('#dialog_edit'),
			editContent: $('#edit_content'),
			dialogEditTitle: $('#dialog_edit_title'),
			name: $('#name'),
			desc: $('#desc'),
			avatar: $('#avatar'),
			avatarPreview: $('#avatar_preview'),
			cropContainer: $('#crop_container')
		},
		data = {currentTab: null, group: QY.util.getParam('group')},
		popup = QY.util.popup, Crop = require('widget/crop');
	data.avatar = new Crop({
		url: QY.util.url('upload/wap?type=grouphead')
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
		var self = $(this), formData = {group: REQUIRE.GROUP_ID};
		switch(data.currentTab){
			case 'name':
				formData.groupname = $.trim(elem.name.val());
				if( formData.groupname == '' ){
					popup.error('请输入内容在提交');
					elem.name.focus();
					return;
				}
				break;
			case 'desc':
				formData.note = $.trim(elem.desc.val());
				if( formData.note == '' ){
					popup.error('请输入内容在提交');
					elem.note.focus();
					return;
				}
		}

		data.isSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('group/groupSet'),
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
						case 'name':
							text = formData.groupname
							break;
						case 'desc':
							text = formData.note;
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

	$('#join_method').on('click', 'input[name="join_method"]', function(){
		var self = this;
		clearTimeout(data.joinMethodReqeust);;
		data.joinMethodReqeust = setTimeout(function(){
			data.joinMethodSend || QY.util.request({
				type: 'POST',
				url: QY.util.url('group/groupSet'),
				data: {group: REQUIRE.GROUP_ID, joinmethod: self.value},
				beforeSend: function(){
					data.joinMethodSend = true;
				},
				success: function(response){
					if( response.code == 1 ){
						QY.util.popup.success('设置成功');
					} else {
						QY.util.popup.error(response.msg);
					}
				},
				complete: function(){
					data.joinMethodSend = false;
				}
			});
		}, 500);
	});

	data.avatar.on('success', function(response){
		if( response.code == 1 ){
			data.avatarSend || QY.util.request({
				type: 'POST',
				url: QY.util.url('group/groupSet'),
				data: {type: 'grouphead', groupimg: response.msg, group: data.group},
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