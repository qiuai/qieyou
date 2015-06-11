define('page/user/card', function(){
	var elem = {},
		data = {},
		popup = QY.util.popup;

	$('#group_list').on('click', '[data-attention]', function(e){
		var self = $(this), gid = parseInt(self.attr('data-gid')), act = self.attr('data-attention');
		e.preventDefault();
		if( !gid || act !== 'join' ) return;
		data.attentionSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('group/groupJoin'),
			data: {act: act, group: gid},
			beforeSend: function(){
				data.attentionSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
                    popup.success('加入部落成功');
					self.attr('data-attention', 'quit').html('已加入');
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.attentionSend = false;
			}
		});
	});
});