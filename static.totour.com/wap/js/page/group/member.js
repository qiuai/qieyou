define('page/group/member', function(){
	var elem = {container: $('#container')},
		data = {filter: {type: 'verified', group: QY.util.getParam('group'), page: 1, allowPage: 1, loadPageTry: 1}, isNewTab: 1},
		popup = QY.util.popup;

	$(window).scroll(function(){
		var body = document.body;
		if( body.scrollTop >= body.offsetHeight - window.innerHeight ){
			getData();
		}
	});

	function getData(){
		if( !data.filter.allowPage ) return;
		data.isIndexSend || QY.util.request({
			data: data.filter,
			type: 'GET',
			url: QY.util.url('group/getMember'),
			beforeSend: function(){
				data.isIndexSend = true;
				elem.container.next().show();
			},
			success: function(response){
				if( response.code == 1 ){
					if( !data.isNewTab && response.msg.length < 1 ){
						if( data.filter.loadPageTry >= 1 ){
							data.filter.allowPage = false;
						} else {
							++data.filter.loadPageTry;
						}
						return;
					} else {
						++data.filter.page;
					}
					renderList(response.msg);
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isNewTab = 0;
				data.isIndexSend = false;
				elem.container.next().hide();
			}
		});
	}

	function renderList(list){
		elem.container.append(QY.util.template('template_item', {list: list}));
	}

	getData();
});