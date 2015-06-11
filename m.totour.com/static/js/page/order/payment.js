define('page/order/payment', function(){
	var elem = {selectQuan: $('#select_quan'), quanSelected: $('#quan_selected'), itemTotal: $('#item_total'), accountPrice: $('#account_price'), balance: $('#balance_w'), quanValue: $('#quan_value'), paytype: $('[name="paytype"]'), submitBtn: $('#submit_btn')},
		data = {};

	$('#select_quan_btn').click(function(e){
		e.preventDefault();
		elem.selectQuan.show();
	});
	elem.selectQuan.on('click', '[type="radio"]', function(){
		var self = $(this), id = parseInt(self.attr('data-id')), amount = parseFloat(self.attr('data-amount')), p;
		if( !id && id !== 0 ) return;
		REQUIRE.DATA.quan = amount;
		elem.selectQuan.hide();
		elem.quanSelected.html(id === 0 ? '抵用券：<span>未使用</span>' : '抵用券：您已经使用<span>' + amount.toFixed(2) + '</span>元优惠券');
		setPrice();
		elem.quanValue.val(id);
	});
	elem.accountPrice.change(function(){
		var self = $(this), val = parseFloat(parseFloat(self.val()).toFixed(2)) || 0, t;
		if( val <= 0 ){
			self.val(0);
			return;
		}
		t = REQUIRE.DATA.price - REQUIRE.DATA.quan;
		if( val > t ){
			QY.util.popup.error('土豪，钱付多啦');
			self.val(t);
			return;
		}
		if( val > REQUIRE.DATA.balance ){
			QY.util.popup.error('您的余额没那么多哦');
			self.val(REQUIRE.DATA.balance);
			return;
		}
		REQUIRE.DATA.acp = val;
		setPrice();
		self.val(val);
		t = (REQUIRE.DATA.balance - val).toFixed(2).split('.');
		elem.balance.html(t[0] + '<i>.' + t[1] + '</i>');
	});

	function setPrice(){
		p = (REQUIRE.DATA.price - REQUIRE.DATA.quan - REQUIRE.DATA.acp).toFixed(2);
		elem.itemTotal.html(p.split('.')[0] + '<i>.' + p.split('.')[1] + '</i>');
	}

	elem.submitBtn.click(function(){
		data.isSend || QY.util.request({
			type: 'GET',
			url: 'order/pay',
			data: {
				order: REQUIRE.DATA.order,
				quan_id: elem.quanValue.val(),
				balance: REQUIRE.DATA.acp,
				pay_type: elem.paytype.filter(':checked').val()
			},
			beforeSend: function(){
				data.isSend = true;
				elem.submitBtn.val('提交中......');
			},
			success: function(response){
				if( response.code == 1 ){
					//
				} else if( response.code == 1001 ){
					QY.util.jumpLogin();
				} else {
					QY.util.popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSend = false;
				elem.submitBtn.val('确认支付');
			}
		});
	});
});