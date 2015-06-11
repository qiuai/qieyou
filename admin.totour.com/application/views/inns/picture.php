<link rel="stylesheet" href="<?php echo $baseUrl;?>kindeditor/themes/default/default.css" />
<script src="<?php echo $baseUrl;?>kindeditor/kindeditor.js"></script>
<script src="<?php echo $baseUrl;?>kindeditor/lang/zh_CN.js"></script>
<?php if(in_array($currentUser['role'],array(ROLE_SHOP_MANAGER,ROLE_INNHOLDER))):?>
<h3 class="headline">我的驿栈</h3>
<div class="tab">
    <ul class="clearfix">
        <li><a href="<?php echo $baseUrl;?>inns/info">1.基本资料</a></li>
        <li class="current"><a href="<?php echo $baseUrl;?>inns/picture">2.驿栈图片</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/story">3.驿栈故事</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/manager">4.掌柜资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/bookingInfo">5.预订须知</a></li>
    </ul>
</div>
<?php else:?>
<h3 class="headline"><?php echo $innsInfo['inns_name'];?></h3>
<div class="tab">
    <ul class="clearfix">
        <li><a href="<?php echo $baseUrl;?>inns/info?innsid=<?php echo $innsInfo['inns_id'];?>">1.基本资料</a></li>
        <li class="current"><a href="<?php echo $baseUrl;?>inns/picture?innsid=<?php echo $innsInfo['inns_id'];?>">2.驿栈图片</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/story?innsid=<?php echo $innsInfo['inns_id'];?>">3.驿栈故事</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/manager?innsid=<?php echo $innsInfo['inns_id'];?>">4.掌柜资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/bookingInfo?innsid=<?php echo $innsInfo['inns_id'];?>">5.预订须知</a></li>
    </ul>
</div>
<?php endif;?>
<form method="post" id="editInnsImage">
<table class="form table-form">
    <colgroup>
        <col class="w150">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel"><cite>*</cite>驿栈图片：</td>
        <td><label><input type="button" value="驿栈图片批量上传" class="button" id="J_selectImage" name="name"></label>
            <div class="tips tips-info"><i class="tips-ico"></i><p>您还可以上传 <em id="imageLeft">5</em> 张图片</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">照片上传提示：</td>
        <td><label>【注】图片最小尺寸为：<cite>996px*332px</cite> ，宽高比：<cite>3：1</cite>，每张最大<cite>2MB</cite>，超出请自行压缩。<br/>
            1、点击“<cite>添加照片</cite>”，最多上传<cite>5 </cite>张照片；<br/>
            2、按着“<cite>ctrl</cite>” 键可以一次选择多张照片，选完后点击“<cite>开始上传</cite>”；<br/>
            3、全部上传成功后点击“<cite>全部插入</cite>”；<br/>
            4、输入每张照片的描述，可为空；点击“<cite>保存</cite>”按钮保存修改。</label>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">图片预览编辑：</td>
        <td>
		<input type="hidden" value="<?php echo $innsInfo['inns_id'];?>" name="sid">
		<input type="hidden" value="pics" name="act">
            <div class="imageEdit innImage">
                <ul class="clearfix" id="J_imageView">
					<?php if(empty($innsInfo['banner_pic_list'])):?>
                    <?php $key = 0;?>
                    <li>上传照片后将在此处预览和编辑</li>
					<?php else:?>
					<?php foreach($innsInfo['banner_pic_list'] as $key => $image):?>
                    <li><div class="preview"><img src="<?php echo $attachUrl.$image['image'];?>" alt=""/></div>
                        <input type="hidden" name="imgUrl[]" value="<?php echo $image['image'];?>">
                        <label>描述：<input type="text" class="w200" name="imgTxt[]" value="<?php echo $image['desc'];?>" > </label><br/>
                        <a href="javascript:void(0)" class="deleteImg">删除</a>
                    </li>
					<?php endforeach;?>
                    <?php $key += 1;?>
					<?php endif;?>
                </ul>
            </div>

        </td>
    </tr>
    <tr>
        <td class="leftLabel"><input name="imgNum" id="imgNum" type="hidden" value="<?php echo isset($key)?$key:0;?>"></td>
        <td><input class="submit editSubmit mr10" type="submit" value="保存" /><input class="button editSubmit mr20" type="submit" id="saveNext" value="保存并进入下一步" /><div class="tips tips-ok" id="formTips" style="display: none;"></div></td>
    </tr>
    </tbody>
</table>
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript">
    var imageNum =  <?php echo 5-(isset($key)?$key:0);?>;
    var imgHasNum = <?php echo isset($key)?$key:0;?>;
    var selectImageBtn = $("#J_selectImage");
    var imageLeft = $('#imageLeft');
    var imageValidate = $('#imgNum');
    var editInnsImage = $('#editInnsImage');
    var editSubmit = $('.editSubmit');
    var formTips = $("#formTips");

	$(function(){
        //如果已经传了5张，则上传按钮禁用
        imageLeft.html(imageNum);
        if(imageNum == 0 ){
            selectImageBtn.attr("disabled",true).addClass("disabled");
        }

        validateForm();

        editSubmit.submit(function(){
            validateForm();
        });

		editInnsImage.ajaxForm({
			dataType : 'json',
			url:'<?php echo $baseUrl.'inns/updateInninfo'?>',
			success : function(data){
				if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.reload()
                    },1000);
				}
				else{
					layer.alert(data.msg ,3,"提示");
				}
			}
		});

        $("#saveNext").click(function(){
            editInnsImage.ajaxForm({
                dataType : 'json',
                url:'<?php echo $baseUrl.'inns/updateInninfo'?>',
                success : function(data){
                    if(data.code == 1){

                        formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存成功！</p>").show().fadeOut(5000);
                        setTimeout(function(){
                            window.location.href="<?php echo $baseUrl;?>inns/story?innsid=<?php echo $innsInfo['inns_id'];?>";
                        },1000);

                    }
                    else{
                        layer.alert(data.msg ,3,"提示");
                    }
                }
            });
        });
	});

    //验证是否有上传图片，没有上传图片则不能提交表单
    function validateForm(){

        if(imgHasNum<1){
            formTips.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>请至少上传1张驿栈图片!</p>");
            editSubmit.addClass("disabled");
            editSubmit.attr("disabled",true);
            return false;
        }
        else{
            formTips.hide();
            editSubmit.removeClass("disabled");
            editSubmit.attr("disabled",false);
            return true;
        }
    }

    KindEditor.ready(function(K) {

        K('#J_selectImage').click(function() {
            var editor = K.editor({
                allowFileManager : false,
                uploadJson : "<?php echo $baseUrl;?>bkupload/swfImageUpload?auth=<?php echo $key_auth;?>&uid=<?php echo $currentUser['user_id']?>&sid=<?php echo $this->session->userdata('session_id');?>",
                imageUploadLimit:imageNum,
                imageSizeLimit:'2MB'
            });

            editor.loadPlugin('multiimage', function() {
                editor.plugin.multiImageDialog({
                    clickFn : function(urlList) {
                        var elem = K('#J_imageView');
                        K.each(urlList, function(i, data) {

                            elem.append('<li><div class="preview"><img src="<?php echo $attachUrl;?>' + data.url + '" /></div><input type="hidden" name="imgUrl[]" value="'+ data.url +'"><label>描述：<input type="text" class="w200" name="imgTxt[]" value=""/> </label><br/><a href="javascript:void(0)" class="deleteImg">删除</a></li>');
                            imageNum -= 1;
                            imgHasNum += 1;
                        });
                        imageLeft.html(imageNum);
                        imageValidate.val(imgHasNum);
                        validateForm();
                        if(imageNum == 0 ){
                            selectImageBtn.attr("disabled",true).addClass("disabled");
                        }
                        editor.hideDialog();
                    }
                });
            });
        });

        $(".deleteImg").live("click",function(){
            var parentLi = $(this).parent('li');
            parentLi.remove();
            imageNum += 1;
            imgHasNum -= 1;
            imageLeft.html(imageNum);
            imageValidate.val(imgHasNum);
            validateForm();
            selectImageBtn.attr("disabled",false).removeClass("disabled");
        })
    });
	   
</script>