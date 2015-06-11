<div class="login">
    <div class="loginBox">
        <form method="post" id="loginForm" action="<?php echo $baseUrl.'login/userlogin';?>">
        <h1>且游后台管理系统<?php echo $staticVer;?> </h1>
        <table class="form table-form wp100">
            <colgroup>
                <col class="w100">
                <col/>
            </colgroup>
            <tr>
                <td class="leftLabel">用户名：</td>
                <td><label><input type="text" value="" class="w200" autocomplete="off" name="username" id="userName" /><cite>*</cite></label>
                    <div class="tips tips-info" id="nameMsg">
                        <span class="tips-ico"></span>
                        <p>请输入用户名</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">密码：</td>
                <td><label><input type="password" value="" class="w200" autocomplete="off" name="password" id="password" /><cite>*</cite></label>
                    <div class="tips tips-info" id="passwordMsg">
                        <span class="tips-ico"></span>
                        <p>请输入密码</p>
                    </div>
                </td>
            </tr>
            <!--<tr>
                 <td class="leftLabel">验证码：</td>
                 <td><label><input type="text" value="" class="w100" name="validate" /><cite>*</cite></label>
                     <div class="tips tips-err">
                         <span class="tips-ico"></span>
                         <p>验证码错误</p>
                     </div>
                 </td>
             </tr>-->
            <tr>
                <td class="leftLabel"></td>
                <td><label><input type="checkbox" class="checkbox" checked="checked" name="rememberUsername" id="rememberUsername">记住用户名</label>
                    <label><a href="javascript:void(0)" id="forgotPw">忘记密码？</a></label>
                    <div class="tips tips-info" id="loginMsg" style="display: none;">
                        <span class="tips-ico"></span>
                        <p>&nbsp;</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"></td>
                <td>
                    <input class="buttonG w250" type="submit" value="登录" id="loginButton" />
                </td>
            </tr>

        </table>
        </form>
    </div>
    <div class="findPass">
        <h2>请拨打电话：<em>0755-86337572</em>与我们联系</h2>
		<h2>（周一至周日09:00-18:00）</h2>
        <h2>由客服电话确认身份后重置并发送密码到您的邮箱！</h2>
    </div>
    <div class="footer">
        <p>且游网<?php echo $staticVer;?> 版权所有  © 2014-2015 京ICP备15000312号-1</p>
    </div>
</div>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript">
    $(function(){
        var rememberUsername = $('#rememberUsername');
        var userName = $('#userName');
        var password = $('#password');
        var loginButton = $('#loginButton');
        var nameMsg = $('#nameMsg');
        var passwordMsg = $('#passwordMsg');
        var loginMsg = $('#loginMsg');
        var loginForm = $('#loginForm');


        //如果这个cookie变量确实存在,把cookie变量的值设置为username的值；
        if ($.cookie("rmbUser") == "true"){
            rememberUsername.attr("checked", true);
            userName.val($.cookie("userName"));
            nameMsg.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
        }
        else{
            rememberUsername.attr("checked", false);
        }

        /**登录表单前端验证**/
        loginForm.validate({
            rules: {

                username: {
                    required: true
                },
                password:{
                    required: true,
                    minlength: 6
                }
            },
            messages: {

                username: {
                    required: "请输入用户名"
                },
                password:{
                    required: "请输入密码",
                    minlength: "密码不能少于6位"
                }
            }, errorPlacement: function(error, element) {

                var tripEle = element.parent("label").siblings(".tips");

                if(error.text()){
                    tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                    loginButton.addClass("disabled");
                    loginButton.attr("disabled",true);
                }
                else{
                    tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                    loginButton.removeClass("disabled");
                    loginButton.attr("disabled",false);
                }

            },

            success:function(label){

                /**记住用户名**/
                if(rememberUsername.attr("checked") == "checked"){
                    $.cookie("rmbUser","true", { path: '/', expires: 10});//存储一个带10天期限的 cookie
                    $.cookie("userName",userName.val(), { path: '/', expires: 10});
                }
                else{
                    $.cookie("rmbUser","false", { path: '/', expires: -1});
                    $.cookie("userName",'', { path: '/', expires: -1});
                }
            }
        });

        /**登录表单提交，后端验证**/
        loginForm.ajaxForm({
            dataType: "json",
            beforeSubmit: function(){

                loginButton.attr("disabled",true);
                loginMsg.removeClass("tips-err").addClass("tips-info").html("<i class=\"tips-ico\"></i><p>正在登录，请稍后...</p>").show().fadeOut(5000);

            },
            success: function(data){
                switch(data)
                {
                    case 1:
                        loginMsg.removeClass("tips-info").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>登录成功！</p>").show().fadeOut(5000);
                        setTimeout(function(){
                            location.href="<?php echo $baseUrl;?>";
                        },500);
                        break;
                    case -1:
                        loginButton.attr("disabled",false);
                        loginMsg.removeClass("tips-info").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>必须输用户名和密码</p>").show().fadeOut(5000);
                        break;

                    case -2:
                        loginButton.attr("disabled",false);
                        loginMsg.removeClass("tips-info").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>账号已被锁定</p>").show().fadeOut(5000);
                        break;
					case -3:
                        loginButton.attr("disabled",false);
                        loginMsg.removeClass("tips-info").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>账号已被停用</p>").show().fadeOut(5000);
                        break;
					case -4:
                        loginButton.attr("disabled",false);
                        loginMsg.removeClass("tips-info").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>账号不存在</p>").show().fadeOut(5000);
                        break;
					case -5:
                        loginButton.attr("disabled",false);
                        loginMsg.removeClass("tips-info").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>商户账号不能登录管理后台</p>").show().fadeOut(5000);
                        break;
                    default:
                        loginButton.attr("disabled",false);
                        loginMsg.removeClass("tips-info").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>用户名或密码错误</p>").show().fadeOut(5000);
                }
            }
        });

        /**忘记密码**/
        $('#forgotPw').click(function(){
            $.layer({
                shade : [0.4 , "#000" , true],
                type : 1,
                area : ['auto','auto'],
                title : false,
                page : {dom : '.findPass'},
                close : function(index){
                    layer.close(index);
                }
            });
        });
    })
</script>
