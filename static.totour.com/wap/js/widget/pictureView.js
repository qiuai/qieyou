define('widget/pictureView', function(){
	var elem = {},
		data = {};

	function pictureView(options){
		options = $.extend({
			imageType: 'm'
		}, options);
		var tpl = ['<div class="swiper-wrapper">'], list = options.list, initial = data.swiper, t;
		checkContainer();
		for(var i = 0, len = list.length; i < len; i++){
			t = '<img class="pic-item" src="' + (options.imageType ? QY.util.changeImageSize(list[i].src, options.imageType) : list[i].src) + '" />';
			initial ? initial.appendSlide(t, 'swiper-slide', 'div')
			: tpl.push('<div class="swiper-slide">' + t + '</div>');
		}
		if( !initial ){
			tpl.push('</div>');
			elem.container.html(tpl.join(''));
			bindEvents();
			initial = data.swiper;
			setAuto();
		}
		if( options.swipeTo && options.swipeTo != 0 )
			initial.swipeTo(options.swipeTo, 1000);

		return {elem: elem.container, swiper: data.swiper};
	}

	function checkContainer(){
		if( elem.container ){
			data.swiper.removeAllSlides();
			elem.container.show();
			return;
		}
		elem.container = $('<div class="pictureView"></div>').appendTo('body');
	}

	function bindEvents(){
		data.swiper = elem.container.swiper();
		elem.container.click(function(){
			data.swiper.swipeTo(0);
			elem.container.hide();
		}).on('touchmove', function(e){
			e.preventDefault();
		});
		$(window).resize(function(){
			setAuto();
		});
	}

	function setAuto(){
		var h = $(window).height() + 'px';
		elem.container.css('line-height', h).find('.swiper-slide').eq(data.swiper.activeIndex).css('line-height', h);
	}


	return pictureView;
});