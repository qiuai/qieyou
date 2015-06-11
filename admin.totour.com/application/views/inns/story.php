<?php if(in_array($currentUser['role'],array(ROLE_SHOP_MANAGER,ROLE_INNHOLDER))):?>
<h3 class="headline">我的驿栈</h3>
<div class="tab">
    <ul class="clearfix">
        <li><a href="<?php echo $baseUrl;?>inns/info">1.基本资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/picture">2.驿栈图片</a></li>
        <li class="current"><a href="<?php echo $baseUrl;?>inns/story">3.驿栈故事</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/manager">4.掌柜资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/bookingInfo">5.预订须知</a></li>
    </ul>
</div>
<?php else:?>
<h3 class="headline"><?php echo $innsInfo['inns_name'];?></h3>
<div class="tab">
    <ul class="clearfix">
        <li><a href="<?php echo $baseUrl;?>inns/info?innsid=<?php echo $innsInfo['inns_id'];?>">1.基本资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/picture?innsid=<?php echo $innsInfo['inns_id'];?>">2.驿栈图片</a></li>
        <li class="current"><a href="<?php echo $baseUrl;?>inns/story?innsid=<?php echo $innsInfo['inns_id'];?>">3.驿栈故事</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/manager?innsid=<?php echo $innsInfo['inns_id'];?>">4.掌柜资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/bookingInfo?innsid=<?php echo $innsInfo['inns_id'];?>">5.预订须知</a></li>
    </ul>
</div>
<?php endif;?>
<form method="post" id="editInnsStory">
<input type="hidden" value="<?php echo $innsInfo['inns_id'];?>" name="sid">
<input type="hidden" value="story" name="act">
<table class="form table-form">
    <colgroup>
        <col class="w150">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel"><cite>*</cite>驿栈简介：</td>
        <td><label><textarea class="textEdit" style="width: 690px;" rows="5" name="inns_summary"><?php echo $innsInfo['inns_summary'];?></textarea></label><div class="tips tips-info"><i class="tips-ico"></i><p>限100字，前台显示5排，每排20字</p></div></td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>驿栈故事：</td>
        <td><textarea name="content" style="width:700px;height:500px;visibility:hidden;"><?php echo $innsInfo['inns_story'];?></textarea>
            <div class="tips tips-info" style="margin-top:5px;" id="editorTips"><i class="tips-ico"></i><p>如果网速较慢，建议上传200~300kb图片，最大宽度620px</p></div>
        </td>
    </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td><input class="submit mr10 editSubmit" type="submit" id="editInnsStoryBtn" value="保存" /><input class="button editSubmit mr20" type="submit" id="saveNext" value="保存并进入下一步" /><div class="tips tips-ok" id="formTips" style="display: none;"></div></td>
    </tr>
    </tbody>
</table>
</form>
<link rel="stylesheet" href="<?php echo $baseUrl;?>kindeditor/themes/default/default.css" />
<script type="text/javascript" src="<?php echo $baseUrl;?>kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript">
    var editor = '';
    var editorTips = $('#editorTips');
    var editSubmit = $('.editSubmit');

    $(function(){
        var editForm = $('#editInnsStory');
        var formTips = $("#formTips");

        editForm.validate({
            rules: {
                inns_summary: {
                    required: true,
                    byteRangeLength: [1,200]
                }
            },
            messages: {
                inns_summary: {
                    required: "请输入驿栈简介",
                    byteRangeLength: "最多100中文或200字符"
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

        editSubmit.click(function(){
            checkEditor();
        });

        editForm.ajaxForm({
            dataType : 'json',
			url:'<?php echo $baseUrl.'inns/updateInninfo'?>',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存驿栈故事成功！</p>").show().fadeOut(5000);
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
            editForm.ajaxForm({
                dataType : 'json',
				url:'<?php echo $baseUrl.'inns/updateInninfo'?>',
                success : function(data){
                    if(data.code == 1){
                        formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存驿栈故事成功！</p>").show().fadeOut(5000);
                        setTimeout(function(){
                            window.location.href="<?php echo $baseUrl;?>inns/manager?innsid=<?php echo $innsInfo['inns_id'];?>";
                        },1000);
                    }
                    else{
                        layer.alert(data.msg ,3,"提示");
                    }
                }
            });
        });
    });

    function checkEditor(){
        if(editor.isEmpty()){
            editorTips.removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class='tips-ico'></i><p>驿栈故事不能为空！</p>").show();
            editSubmit.addClass("disabled");
            editSubmit.attr("disabled",true);
            return false;
        }
        else{
            editorTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>ok</p>").show();
            editSubmit.removeClass("disabled");
            editSubmit.attr("disabled",false);
            return true;
        }
    }

    KindEditor.ready(function(K) {

        KindEditor.ready(function(K) {
            editor = K.create('textarea[name="content"]', {
                afterBlur: function(){this.sync();checkEditor();},
                resizeType : 2,
                allowPreviewEmoticons : false,
                allowFileManager : false,
                uploadJson : '<?php echo $baseUrl; ?>bkupload/editorUploadImage',
                items : [
                    'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'image', 'link']
            });
        });

    });
</script>