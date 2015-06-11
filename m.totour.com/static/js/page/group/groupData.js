define('page/group/groupData', function(){
	var elem = {},
		data = {},
		popup = QY.util.popup;

	$('#gate_btn').on('click', function(e){
		var self = $(this), gid = parseInt(self.attr('data-gid')), act = self.attr('data-attention'), joinable = self.attr('data-joinable');
		e.preventDefault();
		if( !gid || act != 'join' ) return;
		data.attentionSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('group/groupJoin'),
			data: {act: act, group: gid},
			beforeSend: function(){
				data.attentionSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					if( joinable == 'verify' ){
						popup.success('加入部落审核中');
						self.html('审核中');
					} else {
	                    popup.success('加入部落成功');
	                    self.html('已加入')
					}
                    self.attr('data-attention', 'quit').addClass('btnDisable');
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