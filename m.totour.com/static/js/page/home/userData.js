define('page/home/userData', function(){
	var elem = {},
		data = {};
	$('#address_list').on('click', '[node-type="radio"]', function(){
		var self = $(this), id = parseInt(self.attr('data-id'));
		if( !id || self.attr('flag') == 1 ) return;
		data.modifySend || QY.util.request({
			url: QY.domain.base + 'home/modifyUserData',
			beforeSend: function(){
				data.modifySend = true;
			},
			data: {'type': REQUIRE.ACT, act: 'setdefault', classid: id},
			success: function(response){
				var msg;
				if( response.code == 1 ){
					msg = '已设为默认';
					$('#address_list [node-type="radio"]').attr('flag', 0);
					self.attr('flag', 1);
					if( IDENTIFY_TYPE == 1 ){
						var returnurl = QY.util.cookie.get('returnurl');
						QY.util.redirect(returnurl, false);
						return;
					}
					QY.util.popup.success(msg);
				} else {
					msg = response.msg;
					self.prop('checked', false);
					QY.util.popup.error(msg);
				}
			},
			complete: function(){
				data.modifySend = false;
			}
		});
	}).on('click', '[node-type="del"]', function(e){
		var self = $(this), id = parseInt(self.attr('data-id'));
		e.preventDefault();
		if( confirm('是否删除？') ){
			QY.util.request({
				url: QY.domain.base + 'home/modifyUserData',
				data: {'type': REQUIRE.ACT, act: 'del', classid: id},
				success: function(response){
					var item;
					if( response.code == 1 ){
						QY.util.popup.success('删除成功');
						item = self.closest('.confirm-address');
						item.css({height: item.height(),overflow: 'hidden'}).animate({height: 0, opacity: 0, padding: 0}, 400, 'ease', function(){
                            if( item.siblings().size() == 0 ){
                                item.after('<div class="rs-empty">暂无数据</div>');
                            }
							item.remove();
						});
					} else if( response.code == 1001 ){
						QY.util.jumpLogin();
					} else {
						QY.util.popup.error(response.msg);
					}
				}
			});
		}
	});
});