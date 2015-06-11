define('page/home/quan', function(){
	var elem = {navTabs: $('#nav_tabs li'), swiperWrapper: $('.swiper-wrapper')},
		data = {},
		tabsSwiper, popup = QY.util.popup;

	tabsSwiper = $('.swiper-container').swiper({
		cssWidthAndHeight: 'height',
		onSlideChangeStart: function(){
			elem.navTabs.eq(tabsSwiper.activeIndex).addClass('active').siblings('.active').removeClass('active');
		}
	});
	elem.swiperSlides = elem.swiperWrapper.children('.swiper-slide');
	elem.navTabs.on('touchstart mousedown', function(){
		tabsSwiper.swipeTo($(this).index());
	});

});