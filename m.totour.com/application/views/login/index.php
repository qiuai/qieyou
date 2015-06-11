<div class="login-top"><a href="javascript:history.back(-1)">取消</a></div>
<h1>且游旅行</h1>
<div class="logcon">
    <form id="formLogin" method="POST">
        <div class="loginp">
            <label>
                <img alt="" src="<?php echo $staticUrl;?>images/user3.png"/><input type="text" maxlength="16" value=""  autocomplete="off" name="username" id="userName" placeholder="请输入手机号码" />
            </label>
            <label class="bordernone">
                <img alt="" src="<?php echo $staticUrl;?>images/lock.png"/><input type="password" value=""  autocomplete="off" name="password" id="password" placeholder="请输入密码" />
            </label>
        </div>
<!--        <div class="logauto"> 
            <input type="checkbox" id="xuan" name="CheckboxGroup1" value=""  />
            <label for="xuan">自动登录</label>
        </div>-->
        <div class="logbtn2">
            <input id="logbtn" class="redbtn2" name="" type="submit" value="立即登录" />
        </div>
        <div class="log-text"><a href="/login/repassword">忘记密码</a>|<a href="<?php getUrl('userReg');?>">注册帐号</a></div>
        <div class="otherlog">

            <!-- <a href="#"><span><img alt="" src="<?php echo $staticUrl;?>images/weix.png"/></span>微信登录</a><a href="/login/thirdpart?to=qq"><span><img alt="" src="<?php echo $staticUrl;?>images/qq.png"/></span>QQ登录</a> <a href="/login/thirdpart?to=weibo"><span><img alt="" src="<?php echo $staticUrl;?>images/weibo.png"/></span>微博登录</a> </div> -->
            <span class="blank8"></span>
    </form>
</div>

<script type="text/javascript">
var REQUIRE = {MODULE: 'page/login/index'}, BACKURL = '<?php echo empty($_GET["url"]) ? "" : $_GET["url"];?>', DOMAIN = {LOGIN: '<?php getUrl("loginPost"); ?>'};
</script>