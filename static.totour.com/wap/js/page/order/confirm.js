define('page/order/confirm', function(){
	var elem = {mobile: $('#mobile'), submitBtn: $('#submit_btn'), count: $('[node-type="count"]'), sessionCode: $('#session_code')},
		data = {}, popup = QY.util.popup;

	if(IS_LOGIN){
		switch(ORDER_TYPE){
			case 1://不需要运送
				elem.submitBtn.click(function(){
					var formData = validateCellphone();
					if( !formData ) return;
					submitForm({data: formData});
				});
				break;
			case 2://需要运送
				$.extend(elem, {
					address: $('#address')
				});
				elem.submitBtn.click(function(){
					var formData = {
						address_id: elem.address.val()
					};
					submitForm({data: formData});
				});
				break;
			case 3://保险类
				$.extend(elem, {
					identify: $('#identify')
				});
				elem.submitBtn.click(function(){
					var formData = validateCellphone(), identify;
					if( !formData ) return;
					formData.identify_id = elem.identify.val();
					submitForm({data: formData});
				});
				break;
		}
	} else {
		$.extend(elem, {
			verifyBtn: $('#verify_btn'),
			verifyCode: $('#verifycode')
		});
		switch(ORDER_TYPE){
			case 1://不需要运送
				elem.submitBtn.click(function(){
					var formData = validateMobile();
					if( !formData ) return;
					submitForm({data: formData});
				});
				break;
			case 2://需要运送
				require.async('widget/citySelect', function(){
					require('widget/citySelect')();
				});
				$.extend(elem, {
					real_name: $('#realname'),
					location: $('#location'),
					district: $('#CS_district')
				});

				elem.submitBtn.click(function(){
					var formData = validateMobile();
					if( !formData ) return;
					$.extend(formData, {
						real_name: $.trim(elem.real_name.val()),
						local_id: $.trim(elem.district.val()),
						address: $.trim(elem.location.val())
					});
					if( formData.real_name == '' ){
						popup.error('请输入真实姓名');
						elem.real_name.focus();
						return;
					}
					if( formData.local_id == '' ){
						popup.error('请输选择收货地址');
						elem.district.focus();
						return;
					}
					if( formData.location == '' ){
						popup.error('请输入收货地址');
						elem.location.focus();
						return;
					}
					submitForm({data: formData});
				});
				break;
			case 3://保险类
				$.extend(elem, {
					real_name: $('#realname'),
					idcard: $('#idcard')
				});
				elem.submitBtn.click(function(){
					var formData = validateMobile(), identify;
					if( !formData ) return;
					identify = validateIdentify();
					if( !identify ) return;
					$.extend(formData, identify);
					submitForm({data: formData});
				});
				break;
		}

		data.verifyBtn = elem.verifyBtn.val();
		elem.verifyBtn.click(function(){
			var mobile = $.trim(elem.mobile.val());
			if( mobile == '' ){
				popup.error('请输入手机号码');
				elem.mobile.focus();
				return;
			} else if( !QY.util.validate.mobile(mobile) ){
				popup.error('手机号码格式错误');
				elem.mobile.focus();
				return;
			}
			data.sendVerify || QY.util.request({
				type: 'POST',
				url: QY.util.url('login/userRegSMS'),
				data: {mobile: mobile},
				beforeSend: function(){
					data.sendVerify = true;
					elem.verifyBtn.val('获取中......');
				},
				success: function(response){
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
					} else if( response.code == 1007 ){
						QY.util.popup.error('号码已经注册，正在跳转到登录...');
						setTimeout(function(){
							QY.util.jumpLogin();
						}, 3000);
					} else {
						QY.util.popup.error(response.msg);
					}
				},
				complete: function(){
					data.sendVerify = false;
					elem.verifyBtn.val(data.verifyBtn);
				}
			});
		});
	}
	data.submitBtn = elem.submitBtn.val();

	function validateMobile(){
		var formData = validateCellphone();
		if( !formData ) return;
		$.extend(formData, {
			identify: $.trim(elem.verifyCode.val())
		});
		if( formData.identify == '' ){
			popup.error('请输入验证码');
			elem.verifyCode.focus();
			return;
		}

		return formData;
	}

	function validateCellphone(){
		var formData = {
			mobile: $.trim(elem.mobile.val())
		};
		if( formData.mobile == '' ){
			popup.error('请输入手机号码');
			elem.mobile.focus();
			return;
		}
		if( !QY.util.validate.mobile(formData.mobile) ){
			popup.error('手机号码格式错误');
			elem.mobile.focus();
			return;
		}
		return formData;
	}

	function validateIdentify(){
		var formData = {
			real_name: $.trim(elem.real_name.val()),
			idcard: $.trim(elem.idcard.val())
		};

		if( formData.real_name == '' ){
			popup.error('请输入真实姓名');
			elem.real_name.focus();
			return;
		}
		if( formData.idcard == '' ){
			popup.error('请输身份证号');
			elem.idcard.focus();
			return;
		}
		if( !QY.util.validate.idcard(formData.idcard) ){
			popup.error('身份证号码格式错误');
			elem.idcard.focus();
			return;
		}

		return formData;
	}


	function submitForm(options){
		options.data.pid = PID;
		options.data.count = elem.count.val();
		options.data.session_code = elem.sessionCode.val();
		data.isSubmit || QY.util.request($.extend({
			type: 'POST',
			url: QY.domain.base + 'order/submit',
			beforeSend: function(){
				data.isSubmit = true;
				elem.submitBtn.val('提交中......');
			},
			success: function(response){
				if( response.code == 1 ){
					QY.util.redirect('order/payment?order=' + response.msg);
				} else {
					QY.util.popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSubmit = false;
				elem.submitBtn.val(data.submitBtn);
			}
		}, options));
	}


	$(".add").click(function(){
		var t=$(this).parent().find('input[class*=text_box]');
		var id = $(this).attr("ref");
		var num = parseInt(t.val())+1;
		if(!check_num(id,num))
		{
			return false;
		} else {
			$('.min').removeClass('btnDisable');
		}
	});
	$(".min").click(function(){ 
		var t=$(this).parent().find('input[class*=text_box]');
		var id = $(this).attr("ref");
		var num = parseInt(t.val())-1;
		if(!check_num(id,num))
		{
			return false;
		} else {
			$('.add').removeClass('btnDisable');
		}
	});
});