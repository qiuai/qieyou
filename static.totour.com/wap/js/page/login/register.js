define('page/login/register', function(){
	var elem = {userName: $('#userName'), identifyCode: $('#identifyCode'), identifyBtn: $('#identifyBtn'), password: $('#password'), check: $('#check1'), regbtn: $('#regbtn')},
		reg = {digit: /^\d+$/, word: /^[a-zA-Z]+$/, special: ''},
		data = {regIsSend: false, idenIsSend: false}, popup = QY.util.popup;
	// 提交表单
	$('#formBd').submit(function(){
		var userName = $.trim(elem.userName.val()), identifyCode = $.trim(elem.identifyCode.val()), password = $.trim(elem.password.val());
		if( !elem.check.prop('checked') ){
			popup.error('请先阅读使用协议');
			return false;
		}
		if( userName == '' ){
			popup.error('请输入11位手机号码');
			elem.userName.focus();
			return false;
		}
		if( !QY.util.validate.mobile(userName) ){
			popup.error('手机号码格式不正确');
			elem.userName.focus();
			return false;
		}
		if( identifyCode == '' ){
			popup.error('请输入验证码');
			elem.identifyCode.focus();
			return false;
		}
		if( password == '' ){
			popup.error('请输入密码');
			elem.password.focus();
			return false;
		}
		if( password.length < 6 || password.length > 16 ){
			popup.error('密码6-16个字符');
			elem.password.focus();
			return false;
		}
		if( reg.digit.test(password) ){
			popup.error('密码不能为纯数字');
			elem.password.focus();
			return false;
		}
		if( reg.word.test(password) ){
			popup.error('密码不能为纯字母');
			elem.password.focus();
			return false;
		}

		data.idenbtn = elem.regbtn.val();
		data.regIsSend || QY.util.request({
			url: QY.util.url('login/userregpost'),
			data: {username: userName, identifycode: identifyCode, password: password},
			beforeSend: function(){
				data.regIsSend = true;
				elem.regbtn.val('注册中......');
			},
			success: function(data){
				if( data.code == 1 ){
					QY.util.redirect('home');
					popup.success('注册成功');
				} else {
					popup.error(data.msg);
				}
			},
			complete: function(){
				data.regIsSend = false;
				elem.regbtn.val(data.idenbtn);
			}
		});
		return false;
	});

	// 获取验证码
	$('#identifyBtn').click(function(){
		var userName = $.trim(elem.userName.val());
		if( userName == '' ){
			QY.util.popup.error('请输入11位手机号码');
			elem.userName.focus();
			return;
		}
		if( !QY.util.validate.mobile(userName) ){
			popup.error('手机号码格式不正确');
			elem.userName.focus();
			return false;
		}

		data.idenbtn = elem.identifyBtn.val();
		data.idenIsSend || QY.util.request({
			url: DOMAIN.identifyCode,
			data: {mobile: userName},
			beforeSend: function(){
				data.idenIsSend = true;
				elem.identifyBtn.val('发送中......');
				elem.identifyBtn.prop('disabled', true);
			},
			success: function(response){
				if( response.code == 1 ){
					popup.success('发送成功');
					data.time = 60;
					data.timer = setInterval(function(){
						if( data.time < 1 ){
							clearInterval(data.timer);
							data.idenIsSend = false;
							elem.identifyBtn.prop('disabled', false).val(data.idenbtn);
							return;
						}
						elem.identifyBtn.val('重新发送' + data.time + '秒');
						--data.time;
					}, 1000);
				} else {
					popup.error(response.msg);
					data.idenIsSend = false;
					elem.identifyBtn.prop('disabled', false).val(data.idenbtn);
				}
			}
		});
	});

});