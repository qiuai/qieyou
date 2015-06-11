define('page/order/comment', function(){
	var elem = {
			uploadPic: $('#upload_pic'),
			uploadAdd: $('#upload_add'),
			comStarValue: $('#com_star_value'),
			comStarImg: $('#com_star img'),
			ratingText: $('#rating_text'),
			note: $('#note')
		},
		data = {imgs: '', comStarNum: elem.comStarImg.size(), ratingMap: ['非常不满意', '不满意', '一般', '满意', '非常满意']},
		popup = QY.util.popup;

	$('#com_star').on('tap', 'img', function(){
		var tmp, self = $(this), index = self.index(), dark = QY.domain.resource + 'images/star_single_dark.png', light = QY.domain.resource + 'images/star_single.png';
		elem.ratingText.html(data.ratingMap[index]);
		if( self.data('light') == 1 ){
			elem.comStarImg.slice(index, data.comStarNum).attr('src', dark).data('light', 0);
			elem.comStarValue.val(index);
			return;
		}
		elem.comStarImg.slice(0, index + 1).attr('src', light).data('light', 1);
		elem.comStarValue.val(index + 1);
	});
	$('#submit_btn').click(function(e){
		var formData = {
			stars: elem.comStarValue.val(),
			note: $.trim(elem.note.val()),
			images: data.imgs.replace(/(^\,|\,$)/, '')
		};
		e.preventDefault();

		if( !+formData.stars ){
			popup.error('请先选择满意度');
			return;
		}
		if( formData.note == '' ){
			popup.error('请输入评论');
			elem.note.focus();
			return;
		}
		/*if( data.images == '' ){
			popup.error('至少上传一张图片哦');
			return;
		}*/

		data.isSend || QY.util.request({
			type: 'POST',
			url: QY.util.url('order/commentPost'),
			data: formData,
			beforeSend: function(){
				data.isSend = true;
			},
			success: function(response){
				if( response.code == 1 ){
					popup.success('商品评价成功');
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSend = false;
			}
		});
	});

	elem.uploadPic.on('click', '[node-type="del"]', function(e){
		var self = $(this), src = self.attr('data-src');
		e.preventDefault();
		data.imgs = data.imgs.replace(src + ',', '');
		self.closest('li').remove();
	});
	$('#upload_img').change(function(){
		var maxSize = 5 * 1024 * 1024, file = this.files[0];
		if( !file ) return;
        if( !/^image\//.test(file.type) ){
            QY.util.popup.error('只能上传gif,jpg,jpeg,png,bmp格式的图片');
            return;
        }
        if( file.size > maxSize ){
            QY.util.popup.error('图片不能大于5M哦');
            return;
        }
		var xhr = new XMLHttpRequest(), formData = new FormData(), addLine = elem.uploadAdd.find('.add-line'), progress = elem.uploadAdd.find('.progress'), progressBar = elem.uploadAdd.find('.progress-bar'), percent = elem.uploadAdd.find('.progress-percentage');
		formData.append('imgFile', file);
		xhr.open('POST', QY.util.url('upload?type=topic'));
		xhr.onreadystatechange = function(){
			var tpl, info, response;
			if( xhr.readyState == 4 && xhr.status == 200 ){
				response = JSON.parse(xhr.responseText);
				if( !QY.util.checkLogin(response.code) ) return;
				if( response.code == 1 ){
					info = response.msg;
                    tpl = '<li><a href="javascript:;"><img src="' + QY.domain.attach + info + '" onload="QY.util.setImageMiddle(this)" /></a><a data-src="' + info + '" node-type="del" href="#" class="close"><img src="' + QY.domain.resource + 'images/close4.png" /></a></li>';
					elem.uploadAdd.before(tpl);
					data.imgs += info + ',';
				} else {
					popup.error(response.msg);
				}
			}
			progress.hide();
			addLine.show();
			progressBar.css('width', '');
			percent.html('上传中...');
		};
		addLine.hide();
		progress.show();
		xhr.upload.onprogress = function(e){
			var percentage = e.loaded / e.total * 100;
			progressBar.css('width', percentage + '%');
            percent.html(percentage.toFixed(0) + '%');
		};
		xhr.send(formData);
	});
});