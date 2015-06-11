<h3 class="headline">发布系统消息</h3>
<form method="post" id="form_sub">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel"><cite>*</cite>推送对象：</td>
        <td><label><input type="radio" value="all" name="role" checked="checked">所有人
        &nbsp;<input type="radio" value="user" name="role">普通用户
        &nbsp;<input type="radio" value="group" name="role">部落管理员</label>
        &nbsp;<input type="radio" value="innholder" name="role">商户</label>   
        <div class="tips" style="display: none;"></div> 
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>消息内容：</td>
        <td>
        <label><textarea class="w600 textEdit" rows="10" cols="103" name="note"></textarea></label><div class="tips tips-info"><i class="tips-ico"></i><p>限200字内</p></div></label>			
        </td>
    </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td>
            <input class="submit mr20" type="submit" id="add_Button" value="确认"><input class="cancel" type="button" id="cancel" value="取消"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
        </td>
    </tr>
    </tbody>
</table>
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js"></script>    
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/ajaxUpload.js"></script>

<script type="text/javascript">
	$(function(){
		var form_sub= $("#form_sub")
		var addSubmit = $('#add_Button');
		var formTips = $("#formTips");
		form_sub.validate({
			rules: {
				note:{
					required: true,
					maxlength: 1000
				},
				
			},
			messages: {			
				note:{
					required: "请输入内容"
				},		
			}, errorPlacement: function(error, element) {
				var tripEle = element.parent("label").siblings(".tips");
				if(error.text()){
					tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
					addSubmit.addClass("disabled");
					addSubmit.attr("disabled",true);
				}
				else{
					tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
					addSubmit.removeClass("disabled");
					addSubmit.attr("disabled",false);
				}
			},
			success:function(label){

			}
		});
		form_sub.ajaxForm({
            dataType : 'json',
			type:'POST',
            url:'<?php echo $baseUrl.'message/add_message'?>',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.href='<?php echo $baseUrl.'message';?>';
                    },1000);
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        });
		$('#cancel').click(function() {
			 history.go(-1);
		});
	});
	
</script>