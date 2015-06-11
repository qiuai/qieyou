define('page/index:menu', function(){
	return function(options){
		var con, options = $.extend({
			selector: '#dialog_foot_menu_btn',
			container: '#dialog_foot_menu'
		}, options), timer = {o: 0, c: 0};
		con = $(options.container);
		$(options.selector).on('click', function(){
			clearTimeout(timer.o);
			con.removeClass('closeDynamic').addClass('openDynamic').show();
			timer.o = setTimeout(function(){
				con.removeClass('openDynamic');
			}, 800);
		});
		con.find('.close').on('click', function(){
			clearTimeout(timer.c);
			con.removeClass('openDynamic').addClass('closeDynamic');
			timer.c = setTimeout(function(){
				con.hide().removeClass('closeDynamic');
			}, 799);
		});
	};
});
define('page/index:selectCity', function(){
	return function(){
		var elem = {
			cityList: $('#city_list')
		},
			data = {};
		$('#select_city').click(function(){
			elem.cityList.toggle();
		});
		elem.cityList.on('click', '[data-cid]', function(e){
			var self = $(this), cid = self.attr('data-cid'), cname = self.attr('data-cname');
			e.preventDefault();
			QY.util.cookie.set('city', encodeURIComponent(cname), null, '/', 86400*30);
			QY.util.cookie.set('cityid', encodeURIComponent(cid), null, '/', 86400*30);
			window.location.reload();
		});
	};
});
define('page/index', function(){
	require('page/index:menu')();
	require('page/index:selectCity')();

	if( !G.pos.lat || !G.pos.lon ){
		navigator.geolocation && navigator.geolocation.getCurrentPosition(function(position){
			var lon = position.coords.longitude, lat = position.coords.latitude;
	        window.G = {pos: {lat: lat, lon: lon}};
	        QY.util.request({
	        	type: 'POST',
	        	url: QY.util.url('user/localGps'),
	        	data: {lat: lat, lon: lon}
	        });
		});
	}

	$('#ringimg').swiper({
		pagination: '#btnBox2'
	});

	$('#jianren_list ul').each(function(){
		var self = $(this), pos = self.attr('data-pos').split(',');
		pos = QY.util.getGreatCircleDistance(parseFloat(pos[0]), parseFloat(pos[1]), G.pos.lat, G.pos.lon);
		if( pos ){
			self.find('[node-type="pos"]').html('<img src="' + QY.domain.resource + 'images/pos.jpg" />' + pos);
		}
	});
});