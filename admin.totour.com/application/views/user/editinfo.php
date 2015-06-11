<h3 class="headline">编辑个人信息</h3>
<form method="post" id="editUser">
    <input type="hidden" value="<?php echo $userInfo['user_id'];?>" name="user_id">
    <input type="hidden" value="innholder" name="role">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel">用户账号：<input type="hidden" value="<?php echo $userInfo['user_name'];?>" name="user_name"></td>
        <td><?php echo $userInfo['user_name'];?>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">昵称：</td>
        <td>
            <label><input type="text" value="<?php echo $userInfo['nick_name'];?>" class="w300" name="nick_name"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">真实姓名：</td>
        <td>
             <label><input type="text" value="<?php echo $userInfo['real_name'];?>" class="w300" name="real_name"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
   
    <tr>
        <td class="leftLabel">绑定手机：</td>
        <td><label><input type="text" value="<?php echo $userInfo['user_mobile'];?>" class="w300" name="user_mobile"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <!--<tr>
        <td class="leftLabel">账户状态：</td>
        <td>
            <label><input type="radio" class="radio" name="state" value="active" <?php if ($userInfo['state'] == 'active'): ?>checked="checked"<?php endif; ?>>启用</label>
            <label><input type="radio" class="radio" name="state" value="locked" <?php if ($userInfo['state'] == 'locked'): ?>checked="checked"<?php endif; ?>>锁定</label>
        </td>
    </tr>-->
     <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td>
            <input class="submit mr20" type="submit" id="editUserButton" value="提交编辑"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
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
            var editUser = $('#editUser');
            var editSubmit = $('#editUserButton');
            var formTips = $("#formTips");
			
            /**编辑驿栈老板表单前端验证**/
            editUser.validate({
                rules: {
                    nick_name:{
                        required: true
                    },	
					real_name:{
                        required: true
                    },
                    user_mobile:{
                        required: true
                    },
                },
                messages: {
                    nick_name:{
                        required: "请输入昵称"
                    },
                    real_name:{
                        required: "请输入真实姓名"
                    },
                    user_mobile:{
                        required: "请输入手机号码"
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

            editUser.ajaxForm({
                dataType : 'json',
				type:'POST',
				url:'<?php echo $baseUrl.'user/editinfo?uid='.$userInfo['user_id'];?>',
			    success : function(data){
                    if(data.code == 1){
                        formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>编辑用户成功！</p>").show().fadeOut(5000);
                        setTimeout(function(){
                             window.location.href='<?php echo $baseUrl.'user';?>';
                        },1000);
                    }
                    else{
                        layer.alert(data.msg ,3,"提示");
                    }
                }
            });

        });
    </script>
</form>
