define('page/home/coupon', function(){
	var data = {};

	$(document).on('click', '[node-type="get"]', function(e){
		var self = $(this), qid = parseInt(self.attr('data-qid'));
		data.isSend || QY.util.request({
			type: 'POST',
			url: QY.domain.base + 'home/getCoupons',
			data: {quan: qid},
			beforeSend: function(){
				data.isSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					self.addClass('qgray');
					QY.util.popup.success('已领取');
				} else {
					QY.util.popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSend = false;
			}
		});
	})
});