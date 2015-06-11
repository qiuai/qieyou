define('page/login/forgetPassword', function(){
	var elem = {mobile: $('#mobile'), verifyBtn: $('#verify_btn'), verifycode: $('#verifycode'), password: $('#password'), submitBtn: $('#submit_btn'), token: $('#token')},
		data = {},
		popup = QY.util.popup;

	elem.verifyBtn.click(function(){
		var formData;
		if( elem.verifyBtn.hasClass('btnDisable') ) return;
		formData = validateMobile();
		if( !formData ) return;

		data.verifySend || QY.util.request({
            type: 'POST',
            url: QY.util.url('login/forgotPassSMS'),
            data: formData,
            beforeSend: function(){
                data.verifySend = true;
                elem.verifyBtn.val('获取中......');
            },
            success: function(response){
                var time = 60;
                if( response.code == 1 ){
                    QY.util.popup.success('发送成功');
                    elem.verifyBtn.addClass('btnDisable');
                    data.verifyTimer = setInterval(function(){
                        --time;
                        if( time < 1 ){
                            clearInterval(data.verifyTimer);
                            elem.verifyBtn.val('获取验证码').removeClass('btnDisable');
                            return;
                        }
                        elem.verifyBtn.val('获取验证码(' + time + '秒)');
                    }, 1000);
                } else {
                    QY.util.popup.error(response.msg);
                }
            },
            complete: function(){
                data.verifySend = false;
                elem.verifyBtn.val('获取验证码');
            }
        });
	});

	elem.submitBtn.click(function(){
		var formData = validateMobile();
		if( !formData ) return;
		$.extend(formData, {
			token: elem.token.val(),
			verifycode: $.trim(elem.verifycode.val()),
			password: $.trim(elem.password.val())
		});

		if( formData.verifycode == '' ){
			popup.error('请输入验证码');
			elem.verifycode.focus();
			return;
		}
		if( formData.password == '' ){
			popup.error('请输入密码');
			elem.password.focus();
			return;
		}
		if( formData.confirm == '' ){
			popup.error('请输入确认密码');
			elem.confirm.focus();
			return;
		}
		data.postSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('login/forgetPwd'),
			data: formData,
			beforeSend: function(){
				data.postSend = true;
				elem.submitBtn.val('提交中......');
			},
			success: function(response){
				if( response.code == 1 ){
					popup.success('提交成功');
					QY.util.redirect('login');
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.postSend = false;
				elem.submitBtn.val('提交');
			}
		});
	});

	function validateMobile(){
		var formData = {
			mobile: $.trim(elem.mobile.val())
		};
		if( formData.mobile == '' ){
			popup.error('请输入验证手机号');
			elem.mobile.focus();
			return;
		} else if( !QY.util.validate.mobile(formData.mobile) ){
			popup.error('手机号码格式错误');
			elem.mobile.focus();
			return;
		}
		return formData;
	}
});