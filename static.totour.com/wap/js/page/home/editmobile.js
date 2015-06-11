define('page/home/editmobile', function(){
	var elem = {
			mobile: $('#mobile'),
			verifyBtn: $('#verify_btn'),
			verifycode: $('#verifycode')
		},
		data = {step: QY.util.getParam('step') || 'verify'},
		popup = QY.util.popup;

	elem.verifyBtn.on('click', function(){
		var formData;
		if( elem.verifyBtn.hasClass('btnDisable') ) return;
		formData = {
			type: 'auth',
			mobile: $.trim(data.step == 'verify' ? elem.mobile.attr('data-mobile') : elem.mobile.val())
		};

		if( formData.mobile == '' ){
			popup.error('请输入手机号码');
			elem.mobile.focus();
			return;
		}
		if( !QY.util.validate.mobile(formData.mobile) ){
			popup.error('手机号码格式不正确');
			elem.mobile.focus();
			return;
		}

		data.verifySend || QY.util.request({
            type: 'POST',
            url: QY.util.url('home/mobileAuth'),
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

	$('#submit_btn').click(function(){
		var formData = {
			mobile: $.trim(elem.mobile.val()),
			verifycode: $.trim(elem.verifycode.val())
		};
		if( formData.mobile == '' ){
			popup.error('请输入手机号码');
			elem.mobile.focus();
			return;
		}
		if( formData.verifycode == '' ){
			popup.error('请输入验证码');
			elem.verifycode.focus();
			return;
		}
		data.submitSend || QY.util.request({
			type: 'GET',
			url: QY.util.url(''),
			data: formData,
			beforeSend: function(){
				data.submitSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					if( data.step == 'verify' ){
						popup.success('验证成功');
						QY.util.redirect('home/editmobile?step=set');
					} else {
						popup.success('修改成功');
						QY.util.redirect('home');
					}
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.submitSend = false;
			}
		});
	});
});