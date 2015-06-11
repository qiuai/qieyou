define('page/user/group', function(){
	var elem = {navTabs: $('#nav_tabs li'), swiperWrapper: $('.swiper-wrapper')},
		data = {activeIndex: 0},
        popup = QY.util.popup;

	tabsSwiper = $('.swiper-container').swiper({
		cssWidthAndHeight: 'height',
		onSlideChangeStart: function(){
			data.activeIndex = tabsSwiper.activeIndex;
			elem.navTabs.eq(data.activeIndex).addClass('active').siblings('.active').removeClass('active');
			setAutoHeight();
		}
	});
	elem.swiperSlides = elem.swiperWrapper.children('.swiper-slide');
	elem.navTabs.on('touchstart mousedown', function(){
		tabsSwiper.swipeTo($(this).index());
	});
	$('#content_attention').on('click', '[data-attention]', function(e){
		var self = $(this), gid = parseInt(self.attr('data-gid')), act = self.attr('data-attention');
		e.preventDefault();
		if( !gid || act != 'quit' ) return;
        if( !confirm('是否退出此部落？') ) return;
		data.attentionSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('group/groupJoin'),
			data: {act: act, group: gid},
			beforeSend: function(){
				data.attentionSend = true;
			},
			success: function(response){
				var text, attr, item;
				if( response.code == 1 ){
					popup.success('退出部落成功');
                    item = self.closest('.groupcon');
                    item.css({height: item.height(),overflow: 'hidden'}).animate({height: 0, opacity: 0}, 400, 'ease', function(){
                        if( item.siblings().size() == 0 ){
                            item.after('<div class="rs-empty">暂无数据</div>');
                        }
                        item.remove();
                        setAutoHeight();
                    });
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.attentionSend = false;
			}
		});
	});

	function setAutoHeight(){
		var c = elem.swiperSlides.eq(data.activeIndex);
		elem.swiperWrapper.css('height', c.height());
	}
});