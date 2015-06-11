define('page/order/view', function(){
	var elem = {},
		data = {},
		popup = QY.util.popup;

	$('#cancel_order').click(function(e){
		var self = $(this), oid = self.attr('data-oid'), comment;
		e.preventDefault();
		comment = prompt('请输入取消原因');
		data.cancleSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('order/cancel'),
			data: {order:oid,comment: comment},
			beforeSend: function(){
				data.cancleSend = true;
			},
			success: function(response){
				var item;
				if( response.code == 1 ){
					popup.success('订单取消成功');
					window.location.reload();
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.cancleSend = false;
			}
		});
	});

	if( ORDER_TYPE == 'A' )
	{
		elem.end_time = $('#end_time'), elem.oprBtn = $('#opr_btn');
		data.endTime = (parseInt(CREATE_TIME) + 7200) * 1000;
		data.timer = setInterval(function(){
			var d = parseInt((data.endTime - new Date()) / 1000), h = Math.floor(d / 3600), m = Math.ceil(d % 3600 / 60), s = d % 60;
			if( h <= 0 && m <= 0 && s <= 0 ){
				clearInterval(data.timer);
				elem.oprBtn && elem.oprBtn.remove();
			}
			elem.end_time.html([h <= 0 ? '00' : h, m <= 0 ? '00' : m, s <= 0 ? '00' : s].join(':'));
		}, 1000);
	}
});