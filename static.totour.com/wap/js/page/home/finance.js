define('page/home/finance', function(){
	var elem = {financeList: $('#finance_list'), loadMoreIcon: $('#load_more_icon')},
		data = {lastId: '', cache: {}};


	$('#load_more_btn').click(function(){
		var self = $(this);
		QY.util.request({
			type: 'GET',
			url: QY.domain.base + 'home/tranflow',
			data: {lastid: data.lastId},
			beforeSend: function(){
				self.hide();
				elem.loadMoreIcon.show();
			},
			success: function(response){
				var title, list, tpl = [], d, week = ['日', '一', '二', '三', '四', '五', '六'], t = [], con, flag, now = 0;
				if( response.code != 1 ){
					QY.util.popup.error('暂无数据');
					return;
				}
				title = response.msg.title, list = response.msg.list;
				if( list.length < 20 ){
					$('#load_more_btn').remove();
				}
				for(var i = 0, len = title.length; i < len; i++){
					data.lastId = title[i].lastid;
					con = $('#finance_item_' + title[i].month_start);
					d = new Date(parseInt(title[i].month_start)*1000);
					data.cache[title[i].lastid] = title[i].month_start;
					flag = !con || con.length == 0;
					if( flag ){
						tpl.push('<div class="month">');
						tpl.push('<div class="month-tit">'+
						            '<div class="left">'+
						                '<dl>'+
						                    '<dt><span>' + d.format('mm') + '</span>月</dt>'+
						                    '<dd>' + d.format('mm.dd') + '－' + (new Date(parseInt(title[i].month_end)*1000).format('mm.dd')) + '</dd>'+
						                '</dl>'+
						            '</div>'+
						            '<div class="right">'+
						                '<dl>'+
						                    '<dt>' + title[i].cashin + '</dt>'+
						                    '<dd>收入</dd>'+
						                '</dl>'+
						            '</div>'+
						            '<div class="right">'+
						                '<dl>'+
						                    '<dt>' + title[i].cashout + '</dt>'+
						                    '<dd>支出</dd>'+
						                '</dl>'+
						            '</div>'+
						        '</div>');
						tpl.push('<div id="finance_item_' + title[i].month_start + '" class="month-list">');
					}
					for(var o = now, olen = list.length; o < olen; o++){
						if( parseInt(list[o].record_id) >= parseInt(title[i].lastid) ){
							d = new Date(parseInt(list[o].create_time)*1000);
							t.push('<dl>'+
						                '<dt><font>' + d.format('dd') + '</font>周' + (week[d.getDay()]) + '<span class="icon"><img src="' + QY.domain.resource + 'images/' + (list[o].record_type=='sell'?'shou':'zhi') + '.png"></span></dt>'+
						                '<dd class="right">'+
						                    '<ul>'+
						                        '<li class="left"><font>' + list[o].comments + '</font><span>' + (list[o].record_type=='cashout'?'':'订单编号:'+list[o].order_num) + '</span></li>'+
						                        '<li class="right green">' + list[o].amount + '</li>'+
						                    '</ul>'+
						                '</dd>'+
						            '</dl>');
						} else {
							now=o;
							break;
						}
					}
					if( flag ){
						tpl.push(t.join('') + '</div>');
						tpl.push('</div>');
					} else {
						con.append(t.join(''));
					}
					t.length = 0;
					con = null;
				}
				elem.financeList.append(tpl.join(''));
			},
			complete: function(){
				elem.loadMoreIcon.hide();
				self.show();
			}
		});
	}).trigger('click');
});