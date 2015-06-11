define('page/special/inn', function(){
	var elem = {},
		data = {};

	$('#collect_btn').click(function(e){
		var self = $(this), id = parseInt(self.attr('data-id')), act = self.attr('data-act');
		e.preventDefault();
		if( !id ) return;
		data.collectSend || QY.util.request({
			type: 'GET',
			url: QY.util.url('special/innlike'),
			data: {act: act, sid: id},
			beforeSend: function(){
				data.collectSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					self.html('<img src="' + QY.domain.resource + 'images/collect' + (act=='like'?'-light':'') + '.png"> 收藏');
					self.attr('data-act', act=='like'?'unlike':'like');
					if( act == 'like' ){
						QY.util.popup.success('收藏成功');
					} else {
						QY.util.popup.success('取消收藏成功');
					}
				} else {
					QY.util.popup.error(response.msg);
				}
			},
			complete: function(){
				data.collectSend = false;
			}
		});
	});

	$('#share_btn').on('click', function(e){
		e.preventDefault();
		QY.UI.share(SHARE_DATA);
	});
});