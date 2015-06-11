define('page/home/password', function(){
	var elem = {submitBtn: $('#submit_btn'), password: $('#password'), newPassword: $('#new_password'), confirm: $('#confirm')},
	 	data = {},
	 	popup = QY.util.popup;

	 elem.submitBtn.click(function(e){
	 	e.preventDefault();
	 	var formData = {
	 		password: $.trim(elem.password.val()),
	 		newPassword: $.trim(elem.newPassword.val()),
	 		confirm: $.trim(elem.confirm.val())
	 	};

	 	if( formData.password == '' ){
	 		popup.error('请输入当前密码');
	 		elem.password.focus();
	 		return;
	 	}
	 	if( formData.newPassword == '' ){
	 		popup.error('请输入新密码');
	 		elem.newPassword.focus();
	 		return;
	 	}
	 	if( formData.confirm == '' ){
	 		popup.error('请输入确认密码');
	 		elem.confirm.focus();
	 		return;
	 	}
	 	if( formData.newPassword != formData.confirm ){
	 		popup.error('密码两次输入不一致');
	 		return;
	 	}

	 	data.isSend || QY.util.request({
	 		url: QY.util.url('login/changePwd'),
	 		data: formData,
	 		beforeSend: function(){
	 			data.isSend = true;
	 			elem.submitBtn.html('保存中......');
	 		},
	 		success: function(response){
	 			if( response.code == 1 ){
	 				popup.success('修改成功');
	 				QY.util.redirect('home/edituser');
	 			} else {
	 				popup.error(response.msg);
	 			}
	 		},
	 		complete: function(){
	 			data.isSend = false;
	 			elem.submitBtn.html('确认提交');
	 		}
	 	});
	 });
});