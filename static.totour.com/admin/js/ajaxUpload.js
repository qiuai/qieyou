$(function(){

    /**ajax图片上传功能**/
    var fileUpload = $(".fileUpload");
    fileUpload.change(function(){
        $(this).wrap("<form class='myUpload' action='' method='post' enctype='multipart/form-data'></form>");
        var ajaxUpload = $(this).parents(".ajaxUpload");
        var inputName = $(this).attr("name");
		var getType = $(this).attr("ref");
        var ajaxUrl = baseUrl+"bkupload/uploadImage";
        var progressBar = ajaxUpload.find(".progressBar");
        var showImg = ajaxUpload.find(".showImg");
        var progress = ajaxUpload.find(".progress");
        var percent = ajaxUpload.find(".percent");
        var files = ajaxUpload.find(".files");
        var imgUrl = ajaxUpload.find(".imgUrl");
        var btn = ajaxUpload.find(".uploadBtn span");
        var myUpload = ajaxUpload.find(".myUpload");
        var fileUploading = $(this);
        myUpload.ajaxSubmit({
            dataType:  'json',
            url: ajaxUrl+ (getType?('?type='+getType):''),
            beforeSend: function() {
                showImg.empty();
                btn.html("上传中...");
                files.html("<b>请耐心等候...</b>");
                fileUploading.hide();
				progress.show();
				var percentVal = '0%';
				progressBar.width(percentVal);
				percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                progressBar.width(percentVal);
                percent.html(percentVal);
            },
            // complete: function(xhr) {
            //  $(".files").html(xhr.responseText);
            //  },
            success: function(data) {
                if(data.error == 0){
                    files.html("");
                    showImg.html("<img src='"+staticUrl+data.url+"'><span class='delImage' rel='"+inputName+"' url='"+data.url+"'>删除</span>");
                    btn.html("上传成功！");
                    imgUrl.val(data.url).change();
                }
                else{
                    btn.html("上传失败！");
                    progress.hide();
                    files.html("<b>请重新选择文件上传！</b>");
                }
				var ua=navigator.userAgent.toLowerCase();
				var isIE11 = false;

				if (ua.match(/msie/) != null || ua.match(/trident/) != null) {
					//浏览器类型
					browserType = "IE";
					//浏览器版本
					browserVersion = ua.match(/msie ([\d.]+)/) != null ? ua.match(/msie ([\d.]+)/)[1] : ua.match(/rv:([\d.]+)/)[1];
					if(Math.round(browserVersion) == 11)
					{
						isIE11 = true;
					}
				}
				if(!isIE11)
				{
					fileUploading.unwrap().val("").show();
				}
            },
            error:function(xhr){
                btn.html("上传失败！");
                progress.hide();
                files.html("<b>请重新选择文件上传！</b>");
                fileUploading.unwrap().val("").show();
            }
        });
    });
    $(document).on('click','.delImage',function(){
        var ajaxUpload = $(this).parents(".ajaxUpload");
        var showImg = ajaxUpload.find(".showImg");
        var progress = ajaxUpload.find(".progress");
        var files = ajaxUpload.find(".files");
        var btn = ajaxUpload.find(".uploadBtn span");
        var imgUrl = ajaxUpload.find(".imgUrl");

        files.html("删除成功！请重新选择图片上传。");
        btn.html("请选择图片");
        showImg.empty();
        progress.hide();
        imgUrl.val("").change();

    });

});

//验证是否有上传图片，没有上传图片则不能提交表单
function validateForm(imgValue,imgTips,editSubmit,error){

    if(imgValue == '' || imgValue == undefined){
        imgTips.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>请上传"+error+"!</p>");
        editSubmit.addClass("disabled");
        editSubmit.attr("disabled",true);
        return false;
    }
    else{
        imgTips.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
        editSubmit.removeClass("disabled");
        editSubmit.attr("disabled",false);
        return true;
    }
}