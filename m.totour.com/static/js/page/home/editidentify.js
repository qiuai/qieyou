define('page/home/editidentify', function(){
	var elem = {submitBtn: $('#submit_btn'), 'class': $('#class'), act: $('#act'), classid: $('#classid'), name: $('#real_name'), idcard: $('#idcard')},
		data = {},
		popup = QY.util.popup;

	elem.submitBtn.click(function(){
		var d = {
			'class': elem.class.val(),
			act: elem.act.val(),
			classid: elem.classid.val(),
			name: $.trim(elem.name.val()),
			idcard: $.trim(elem.idcard.val())
		};
		if( d.name == '' ){
			QY.util.popup.error('请输入姓名');
			elem.name.focus();
			return;
		}
		if( d.idcard == '' ){
			QY.util.popup.error('请输入身份证号码');
			elem.idcard.focus();
			return;
		}
		if( !QY.util.validate.idcard(d.idcard) ){
			QY.util.popup.error('身份证号码格式错误');
			elem.idcard.focus();
			return;
		}

		data.submitBtn = elem.submitBtn.html();
		data.isSubmit || QY.util.request({
			url: QY.domain.base + 'home/modifyUserData',
			data: {type: d.class, classid: d.classid, act: d.act, real_name: d.name, idcard: d.idcard, submit_order: IDENTIFY_TYPE == 1 ? 1 : 0},
			beforeSend: function(){
				data.isSubmit = true;
				elem.submitBtn.html('保存中......');
			},
			success: function(response){
				if( response.code == 1 ){
					if( IDENTIFY_TYPE == 1 ){
						var returnurl = QY.util.cookie.get('returnurl');
						QY.util.redirect(returnurl, false);
						return;
					}
					popup.success(d.classid ? '添加成功' : '修改成功');
					QY.util.redirect('home/identify');
				} else if( response.code == 1001 ){
					QY.util.jumpLogin();
				} else {
					QY.util.popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSubmit = false;
				elem.submitBtn.html(data.submitBtn);
			}
		});
	});
});