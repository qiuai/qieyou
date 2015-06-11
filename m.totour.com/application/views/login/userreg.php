<div class="logcon">
    <form id="formBd">
            <div class="reginp">
                <label><span>手&nbsp;&nbsp;&nbsp;&nbsp;机：</span>
                    <input type="text" value=""  autocomplete="off" name="username" id="userName" placeholder="请输入11位手机号码"  />
                </label>
                <label><span>验证码：</span>
                   <input type="text" value="" class="code" autocomplete="off" name="code" id="identifyCode" placeholder="请输入验证码"  /><input id="identifyBtn" class="codebtn" name="" type="button" value="获取验证码" />
                </label>
                <label class="bordernone"><span>密&nbsp;&nbsp;&nbsp;&nbsp;码：</span>
                    <input type="password" value=""  autocomplete="off" name="password" id="password" placeholder="6-16个数字+字母" maxlength="16" />
                </label>
            </div>
            <span class="blank1"></span>
            <div class="logbtn">
                <input id="regbtn" class="redbtn" name="" type="submit" value="新用户注册" />
            </div>
            <div class="agreement"><div class="checkbox">
    				<input id="check1" type="checkbox" name="check" value="check1" checked="checked">
    				<label for="check1">
    					我已阅读并同意<a href="/login/agreement" class="green">《且游旅行网使用协议》</a>
    				</label>

    			</div></div>

        <div class="ologin">已有账号？<a href="<?php getUrl('login');?>" class="green">请登录》</a></div>
    </form>
</div>

<script type="text/javascript">
    var REQUIRE = {MODULE: 'page/login/register'}, DOMAIN = {identifyCode: '<?php echo $baseUrl."login/userRegSMS";?>'};
</script>