$(function() {
	var Accordion = function(el, multiple) {
		this.el = el || {};
		this.multiple = multiple || false;
		
		// Variables privadas
		var links = this.el.find('.menulink');
		
		// Evento
		links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
	}

	Accordion.prototype.dropdown = function(e) {
		var $el = e.data.el;
			$this = $(this),
			$next = $this.next();

		$next.slideToggle();
	//	$this.parent().toggleClass('open');
		console.log(e.data.multiple);
		if (!e.data.multiple) {
			$el.find('.submenu').not($next).slideUp().parent();
			
		}
	}	
	var accordion = new Accordion($('#accordion'), false);
	$('.menulink').click(function(){
		if($('.menu').hasClass('open')){
			$('.menulink img').attr('src',"images/menu4.png");
			$('.menulink dt').html("更多分类");
			$('.menu').removeClass("open");
		}else{		
			$('.menulink img').attr('src',"images/menu4a.png");
			$('.menulink dt').html("收起分类");
			$('.menu').addClass("open");
		}
	});
});