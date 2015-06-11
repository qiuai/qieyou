<form method="post" id="addSman">
    <h3 class="headline">添加店长</h3>
    <input type="hidden" value="smanager" name="role">
    <table class="form table-form">
        <colgroup>
            <col class="w120">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <td class="leftLabel"><cite>*</cite>用户名称：</td>
            <td><label><input type="text" value="" autocomplete="off" class="w300" name="user_name" id="userName"></label><div class="tips tips-info"><i class="tips-ico"></i><p>中、英文或数字，不超过12个字符</p></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>用户密码：</td>
            <td>
                <label><input type="password" value="" autocomplete="off" class="w300" name="user_pass" id="user_pass"></label><div class="tips tips-info"><i class="tips-ico"></i><p>英文或数字，不少于6个字符</p></div>
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
            <td class="leftLabel"><cite>*</cite>真实姓名：</td>
            <td><label><input type="text" value="" class="w300" name="real_name"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel">性别：</td>
            <td>
                <label><input type="radio" class="radio" name="sex" value="M" checked="">男</label>
                <label><input type="radio" class="radio" name="sex" value="F">女</label>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>Email：</td>
            <td><label><input type="text" value="" class="w300" name="email"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>身份证号码：</td>
            <td><label><input type="text" value="" class="w300" name="identity_no"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>手机号码：</td>
            <td><label><input type="text" value="" class="w300" name="mobile_phone"></label><div class="tips" style="display: none;"></div>
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
                        <option value="active">正常</option>
                        <option value="locked">锁定</option>
                        <option value="suspend">停用</option>
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
                <input class="submit mr20" type="submit" id="addSmanButton" value="确认添加"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
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
        var addSman = $('#addSman');
        var addSubmit = $('#addSmanButton');
        var formTips = $("#formTips");

        /**添加店长表单前端验证**/
        addSman.validate({
            rules: {
                user_name: {
                    required: true,
                    userName: true,
                    byteRangeLength: [1,24],
                    remote: {
                        url: "<?php echo $baseUrl; ?>user/checkusername", //后台处理程序
                        type: "POST",  //数据发送方式
                        dataType: "json",       //接受数据格式
                        data: {                     //要传递的数据
                            userName: function () {
                                return $("#userName").val();
                            }
                        }
                    }
                },
                user_pass:{
                    required: true,
                    minlength:6
                },
                repeat_password: {
                    required: true,
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

                user_name: {
                    required: "请输入用户名",
                    remote : "该用户名已存在，请重新输入！"
                },
                user_pass:{
                    required: "请输入密码"
                },
                repeat_password: {
                    required: "请再次输入密码",
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

        addSman.ajaxForm({
            dataType : 'json',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>添加用户成功！</p>").show().fadeOut(5000);
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