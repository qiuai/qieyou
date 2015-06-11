<form method="post" id="editSman">
    <h3 class="headline">编辑店长信息</h3>
    <input type="hidden" value="<?php echo $userInfo['user_id'];?>" name="user_id">
    <input type="hidden" name="role" value="<?php echo $userInfo['role'];?>" >
    <table class="form table-form">
        <colgroup>
            <col class="w120">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <td class="leftLabel">用户名称：<input type="hidden" value="<?php echo $userInfo['user_name'];?>" name="user_name"></td>
            <td><?php echo $userInfo['user_name'];?>
            </td>
        </tr>
        <tr>
            <td class="leftLabel">用户密码：</td>
            <td>
                <label><input type="password" value="" placeholder="********" autocomplete="off" class="w300" name="user_pass" id="user_pass"></label><div class="tips tips-info"><i class="tips-ico"></i><p>英文或数字，不少于6个字符</p></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel">重复密码：</td>
            <td>
                <label><input type="password" value="" placeholder="********" class="w300" name="repeat_password"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr class="space">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>真实姓名：</td>
            <td><label><input type="text" value="<?php echo $userInfo['real_name'];?>" class="w300" name="real_name"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>性别：</td>
            <td>
                <label><input type="radio" class="radio" name="sex" value="M" <?php if ($userInfo['user_sex'] != 'F'): ?>checked="checked"<?php endif; ?>>男</label>
                <label><input type="radio" class="radio" name="sex" value="F" <?php if ($userInfo['user_sex'] == 'F'): ?>checked="checked"<?php endif; ?>>女</label>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>Email：</td>
            <td><label><input type="text" value="<?php echo $userInfo['email'];?>" class="w300" name="email"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>身份证号码：</td>
            <td><label><input type="text" value="<?php echo $userInfo['identity_no'];?>" class="w300" name="identity_no"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>手机号码：</td>
            <td><label><input type="text" value="<?php echo $userInfo['mobile_phone'];?>" class="w300" name="mobile_phone"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr class="space">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td class="leftLabel">账户状态：</td>
            <td><label>
                    <select name="state">
                        <option value="active" <?php if ($userInfo['state'] == 'active'): ?>selected="selected"<?php endif; ?>>正常</option>
                        <option value="locked" <?php if ($userInfo['state'] == 'locked'): ?>selected="selected"<?php endif; ?>>锁定</option>
                        <option value="suspend" <?php if ($userInfo['state'] == 'suspend'): ?>selected="selected"<?php endif; ?>>停用</option>
                    </select>
                </label>
            </td>
        </tr>
        <tr class="space">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td class="leftLabel">&nbsp;</td>
            <td>
                <input class="submit mr20" type="submit" id="editSmanButton" value="提交编辑"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
            </td>
        </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript">
    $(function(){
        var editSman = $('#editSman');
        var editSubmit = $('#editSmanButton');
        var formTips = $("#formTips");

        /**编辑店长表单前端验证**/
        editSman.validate({
            rules: {
                user_pass:{
                    minlength:6
                },
                repeat_password: {
                    minlength:6,
                    equalTo : "#user_pass"
                },
                real_name:{
                    required: true,
                    userName: true
                },
                email:{
                    required: true,
                    email:true
                },
                identity_no:{
                    required: true,
                    isIdCardNo: true
                },
                mobile_phone:{
                    required: true,
                    isMobile:true
                }
            },
            messages: {


                user_pass:{
                },
                repeat_password: {
                    equalTo : "两次密码不一致"
                },
                real_name:{
                    required: "请输入真实姓名"
                },
                email:{
                    required: "请输入Email"
                },
                identity_no:{
                    required: "请输入身份证号码",
                    isIdCardNo: "请输入正确的身份证号码"
                },
                mobile_phone:{
                    required: "请输入手机号码"
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

        editSman.ajaxForm({
            dataType : 'json',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>编辑用户成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.href='<?php echo $baseUrl.'inns/assets';?>';
                    },1000);
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        });

    });
</script>