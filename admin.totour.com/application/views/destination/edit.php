<h3 class="headline">编辑目的地</h3>

<form method="post" id="editDestination">
    <input type="hidden" value="<?php echo $destInfo['dest_id'];?>" name="dest_id">
    <table class="form table-form">
        <colgroup>
            <col class="w120">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <td class="leftLabel">所属区域：</td>
            <td><label><?php echo $destInfo['province'];?>
                </label>
                <label><?php echo $destInfo['city'];?>
                </label>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>目的地名称：</td>
            <td><label><input type="text" value="<?php echo $destInfo['dest_name'];?>" class="w400" name="dest_name"></label><div class="tips" style="display: none;"><i class="tips-ico"></i><p></p></div></td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>目的地简介：</td>
            <td>
                <label><textarea class="w400 textEdit" rows="3" cols="103" name="summary"><?php echo $destInfo['summary'];?></textarea></label><div class="tips tips-info"><i class="tips-ico"></i><p>120字以内</p></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>Banner：</td>
            <td>
                <label><input type="button" value="Banner批量上传" class="button" id="J_selectImage" name="name"></label>
                <div class="tips tips-info"><i class="tips-ico"></i><p>您还可以上传 <em id="imageLeft">5</em> 张图片</p></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel">上传提示：</td>
            <td><label>【注】图片最小尺寸为：<cite>996px*330px</cite> ，每张最大<cite>2MB</cite>，超出请自行压缩。<br/>
                    1、点击“<cite>添加照片</cite>”，最多上传<cite>5 </cite>张照片；<br/>
                    2、按着“<cite>ctrl</cite>” 键可以一次选择多张照片，选完后点击“<cite>开始上传</cite>”；<br/>
                    3、全部上传成功后点击“<cite>全部插入</cite>”；<br/>
                    4、输入每张照片的描述，可为空；点击“<cite>保存</cite>”按钮保存修改。</label>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>图片预览编辑：</td>
            <td>
                <div class="imageEdit bannerImage">
                    <ul class="clearfix" id="J_imageView">
                        <?php $destInfo['banner_list'] = json_decode($destInfo['banner_list'],TRUE); if(!empty($destInfo['banner_list'])):?>
                            <?php foreach($destInfo['banner_list'] as $key => $image):?>
                                <li><div class="preview"><img src="<?php echo $attachUrl.$image;?>" alt=""/></div>
                                    <input type="hidden" name="banner_list[]" value="<?php echo $image;?>"> <a href="javascript:void(0)" class="deleteImg" title="点击删除该图">删除</a>
                                </li>
                            <?php endforeach;?>
                            <?php $key += 1;?>
                        <?php else:?>
                            <?php $key = 0;?>
                            <li>上传照片后将在此处预览和编辑</li>
                        <?php endif;?>
                    </ul>
                </div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel">是否显示：</td>
            <td><label>
                    <select name="is_display" class="w100">
                        <option value="Y" <?php if ($destInfo['is_display']=='Y') echo'selected="selected"';?>>是</option>
                        <option value="N" <?php if ($destInfo['is_display']=='N') echo'selected="selected"';?>>否</option>
                    </select>
                </label>
            </td>
        </tr>
        <tr>
            <td class="leftLabel">&nbsp;</td>
            <td><input class="submit mr10" type="submit" id="editSubmit" value="保存修改" /><a class="button mr10" href="javascript:history.go(-1);">返回上一页</a><div class="tips tips-ok" id="formTips" style="display: none;"></div></td>
        </tr>
    </table>
</form>
<link rel="stylesheet" href="<?php echo $baseUrl;?>kindeditor/themes/default/default.css" />
<script type="text/javascript" src="<?php echo $baseUrl;?>kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript">

    var imageNum =  <?php echo 5-(isset($key)?$key:0);?>;
    var imgHasNum = <?php echo isset($key)?$key:0;?>;
    var selectImageBtn = $("#J_selectImage");
    var imageLeft = $('#imageLeft');
    var imageValidate = $('#imgNum');
    var editDestination = $('#editDestination');
    var editSubmit = $('#editSubmit');
    var formTips = $("#formTips");

    $(function(){
        //如果已经传了5张，则上传按钮禁用
        imageLeft.html(imageNum);
        if(imageNum == 0 ){
            selectImageBtn.attr("disabled",true).addClass("disabled");
        }



        var textEdit = $('.textEdit');

        textEdit.each(function(index, domEle){
            var newVal = replaceBrToEnter($(domEle).val());
            $(domEle).val(newVal);

        });

        textEdit.focus(function(){
            var newVal = replaceBrToEnter($(this).val());
            $(this).val(newVal);
        });

        /**编辑目的地表单前端验证**/
        editDestination.validate({
            rules: {

                dest_name: {
                    required: true,
                    userName: true
                },
                summary: {
                    required: true,
                    byteRangeLength:[1,240]
                }
            },
            messages: {

                dest_name: {
                    required: "请输入目的地名称"
                },
                summary: {
                    required: "请输入目的地简介",
                    byteRangeLength: "最多可输入120中文或240字符"
                }
            }, errorPlacement: function(error, element) {

                var tripEle = element.parent("label").siblings(".tips");

                if(error.text()){
                    tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                    editSubmit.addClass("disabled");
                    editSubmit.attr("disabled",true);
                }
                else{
                    tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                    editSubmit.removeClass("disabled");
                    editSubmit.attr("disabled",false);
                }

            },

            success:function(label){

            }
        });

        editDestination.submit(function(){
            textEdit.each(function(index, domEle){
                var newVal = replaceEnterToBr($(domEle).val());
                $(domEle).val(newVal);

            });
            validateForm();
        });

        editDestination.ajaxForm({
            dataType : 'json',
            success : function(data){
                if(data.code == 1){

                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>编辑目的地成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.reload()
                    },1000);

                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        });

    });

    //验证是否有上传图片，没有上传图片则不能提交表单
    function validateForm(){

        if(imgHasNum<1){
            formTips.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>请至少上传1张Banner图片!</p>");
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
                uploadJson : "<?php echo $baseUrl; ?>bkupload/swfImageUpload?auth=<?php echo $key_auth;?>&uid=<?php echo $currentUser['user_id']?>&sid=<?php echo $this->session->userdata('session_id');?>",
                imageUploadLimit:imageNum,
                imageSizeLimit:'2MB'
            });

            editor.loadPlugin('multiimage', function() {
                editor.plugin.multiImageDialog({
                    clickFn : function(urlList) {
                        var elem = K('#J_imageView');
                        K.each(urlList, function(i, data) {

                            elem.append('<li><div class="preview"><img src="<?php echo $attachUrl;?>' + data.url + '" /></div><input type="hidden" name="banner_list[]" value="'+ data.url +'"> <a href="javascript:void(0)" class="deleteImg">删除</a></li>');
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