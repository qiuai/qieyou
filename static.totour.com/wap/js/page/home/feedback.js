define('page/home/feedback', function(){
	var elem = {submitBtn: $('#submit_btn'), note: $('#note'), uploadPic: $('#upload_pic'), uploadAdd: $('#upload_add')},
		data = {imgs: ''},
		popup = QY.util.popup;

	elem.submitBtn.click(function(e){
		e.preventDefault();
		var formData = {
			note: $.trim(elem.note.val())
		};

		if( formData.note == '' ){
			popup.error('请输入内容');
			elem.note.focus();
			return;
		}
		/*if( data.imgs == '' ){
			popup.error('请上传图片');
			return;
		}*/

		formData.imgs = data.imgs;
		data.isSend || QY.util.request({
			url: QY.util.url('home/userFeedback'),
			data: formData,
			beforeSend: function(){
				data.isSend = true;
				elem.submitBtn.html('提交中......');
			},
			success: function(response){
				if( response.code == 1 ){
					popup.success('您的意见提交成功');
					setTimeout(function(){
						window.location.reload();
					}, 1000);
				} else {
					popup.error(response.msg);
				}
			},
			complete: function(){
				data.isSend = false;
				elem.submitBtn.html('提交');
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
		xhr.open('POST', QY.util.url('upload?type=feedback'));
		xhr.onreadystatechange = function(){
			var tpl, info, response;
			if( xhr.readyState == 4 && xhr.status == 200 ){
				response = JSON.parse(xhr.responseText);
				if( !QY.util.checkLogin(response.code) ) return;
				if( response.code == 1 ){
					info = response.msg;
					tpl = '<li><img src="' + QY.domain.attach + info + '"/><a data-src="' + info + '" node-type="del" href="#" class="close"><img src="' + QY.domain.resource + 'images/close4.png" /></a></li>';
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