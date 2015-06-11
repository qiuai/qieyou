define('page/home/editaddress', function(){
	var elem = {submitBtn: $('#submit_btn'),module: $('#class'), classid: $('#classid'), act: $('#act'), name: $('#name'), mobile: $('#mobile'), province: $('#CS_province'), city: $('#CS_city'), district: $('#CS_district'), address: $('#address')},
		data = {isSubmit: false},
		popup = QY.util.popup;
	elem.submitBtn.click(function(e){
		var d;
		e.preventDefault();
		d = {
			module: elem.module.val(),
			classid: elem.classid.val(),
			act: elem.act.val(),
			name: $.trim(elem.name.val()),
			mobile: $.trim(elem.mobile.val()),
			province: elem.province.val(),
			city: elem.city.val(),
			district: elem.district.val(),
			address: elem.address.val()
		};
		if( d.name == '' ){
			popup.error('请输入证实姓名');
			elem.name.focus();
			return;
		}
		if( d.mobile == '' ){
			popup.error('请输入手机号码');
			elem.mobile.focus();
			return;
		}
		if( !/^1\d{10}$/.test(d.mobile) ){
			popup.error('手机号码格式不正确');
			elem.moblie.focus();
			return;
		}
		if( d.city == '' || d.city < 0 ){
			popup.error('请选择省份');
			return;
		}
		if( d.province == '' || d.province < 0 ){
			popup.error('请选择城市');
			return;
		}
		if( d.district == '' || d.district < 0 ){
			popup.error('请选择区县');
			return;
		}
		if( d.address == '' ){
			popup.error('请输入详细地址');
			elem.address.focus();
			return;
		}

		data.submitBtn = elem.submitBtn.html();
		data.isSubmit || QY.util.request({
			url: QY.util.url('home/modifyUserData'),
			data: {'type': d.module, classid: d.classid, act: d.act, real_name: d.name, mobile: d.mobile, local_id: d.district, address: d.address, submit_order: ADDRESS_TYPE == 1 ? 1 : 0},
			beforeSend: function(){
				elem.submitBtn.html('保存中......');
				data.isSubmit = true;
			},
			success: function(response){
				if( response.code == 1 ){
					if( ADDRESS_TYPE == 1 ){
						var returnurl = QY.util.cookie.get('returnurl');
						QY.util.redirect(returnurl, false);
						return;
					}
					popup.success(d.classid ? '添加成功' : '修改成功');
					QY.util.redirect('home/address');
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				elem.submitBtn.html(data.submitBtn);
				data.isSubmit = false;
			}
		});
	});

	require.async('widget/citySelect', function(){
		require('widget/citySelect')(initLoca);
	});
});