define('page/home/editavatar', function(){
	var elem = {},
		data = {};

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
		var xhr = new XMLHttpRequest(), formData = new FormData(), progress = $('.progress'), progressBar = $('.progress-bar');
		formData.append('imgFile', file);
		xhr.open('POST', QY.util.url('upload?type=headimg'));
		xhr.onload = function(){
			var tpl, info, response = JSON.parse(xhr.responseText);
			if( !QY.util.checkLogin(response.code) ) return;
			info = response.msg;
			data.Send || QY.util.request({
				type: 'POST',
				url: QY.util.url('home/editUserinfo'),
				data: {type: 'headimg', headimg: info},
				beforeSend: function(){
					data.isSend = true;
				},
				success: function(response){
					if( response.code == 1 ){
						QY.util.redirect('home/edituser');
					} else {
						QY.util.popup.error(response.msg);
					}
				},
				complete: function(){
					data.isSend = false;
				}
			});
			progress.hide();
		};
		xhr.upload.onprogress = function(e){
			var percentage = e.loaded / e.total * 100;
			progressBar.css('width', percentage + '%');
		};
		progress.show();
		progressBar.css('width', 0);
		xhr.send(formData);
	});
});