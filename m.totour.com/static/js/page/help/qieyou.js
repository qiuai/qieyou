define('page/help/qieyou', function(){
	var elem = {},
		data = {};

	$('#share_btn').on('click', function(e){
		e.preventDefault();
		QY.UI.share({
			title: '且游旅行',
			url: QY.domain.base,
			pic: QY.domain.attach + 'images/code.png'
		});
	});
});