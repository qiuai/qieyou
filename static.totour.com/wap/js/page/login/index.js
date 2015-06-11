define('page/login/index', function(){
	var elem = {userName: $('#userName'), password: $('#password'), xuan: $('#xuan'), logbtn: $('#logbtn')}, data = {logbtn: elem.logbtn.val(), isSend: false};
	$('#formLogin').submit(function(){
		var userName = $.trim(elem.userName.val()), password = $.trim(elem.password.val());
		if( userName == '' ){
			elem.userName.focus();
			QY.util.popup.error('请输入用户名');
			return false;
		}
		if( !QY.util.validate.mobile(userName) && (userName.length < 6 && userName.length > 16) ){
			elem.userName.focus();
			QY.util.popup.error('用户名输入错误');
			return false;
		}
		if( password == '' ){
			elem.password.focus();
			QY.util.popup.error('请输入密码');
			return false;
		}

		data.isSend || QY.util.request({
			url: QY.util.url('login/userLogin'),
			data: {username: userName, password: password, remember: elem.xuan.prop('checked') ? 1 : 0},
			beforeSend: function(){
				data.isSend = true;
				elem.logbtn.val('登录中......');
			},
			success: function(data){
				if( data.code == 1 ){
					window.location.href = BACKURL || QY.domain.base;
				} else {
					QY.util.popup.error(data.msg);
				}
			},
			complete: function(){
				data.isSend = false;
				elem.logbtn.val(data.logbtn);
			}
		});

		return false;
	});
});