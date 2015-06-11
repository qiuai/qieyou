<h3 class="headline">修改密码</h3>
<form method="post" id="changepwd">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel"><cite>*</cite>原密码：</td>
        <td><label><input type="password" value="" autocomplete="off" class="w300" name="user_pass" id="user_pass"></label><div class="tips tips-info"><i class="tips-ico"></i><p>英文或数字，不少于6个字符</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>新密码：</td>
        <td>
            <label><input type="password" value="" autocomplete="off" class="w300" name="new_password" id="new_password"></label><div class="tips tips-info"><i class="tips-ico"></i><p>英文或数字，不少于6个字符</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>重复密码：</td>
        <td>
            <label><input type="password" value="" class="w300" name="repeat_password"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
   
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td>
            <input class="submit mr20" type="submit" id="editButton" value="修改密码"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
        </td>
    </tr>
    </tbody>
</table>
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/citySelect.js"></script>
<script type="text/javascript">
	 $(function(){   
            var changepwd = $('#changepwd');
            var editSubmit = $('#editButton');
            var formTips = $("#formTips");
			
            /**编辑驿栈老板表单前端验证**/
            changepwd.validate({
                rules: {
                    user_pass:{
                        required: true,
						minlength:6,
						maxlength:16
                    },	
					new_password:{
                        required: true,
						minlength:6,
						maxlength:16
                    },
					repeat_password:{
                        required: true,
						equalTo:"#new_password",
						minlength:6,
						maxlength:16
                    },        
                },
                messages: {
                    user_pass:{
                        required: "请输入原密码"
                    },
                    new_password:{
                        required: "请输入新密码"
                    },
                        
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

            changepwd.ajaxForm({
                dataType : 'json',
				type:'POST',
				url:'<?php echo $baseUrl.'user/changepwd';?>',
			    success : function(data){
                    if(data.code == 1){
                        formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>修改密码成功！</p>").show().fadeOut(5000);
                        setTimeout(function(){
                             //window.location.href='<?php echo $baseUrl.'user';?>';
                        },1000);
                    }
                    else{
                        layer.alert(data.msg ,3,"提示");
                    }
                }
            });

        });
</script>