<div class="repass">
    <ul>
        <li>
            <span class="left"><input id="mobile" name="" type="number" class="grayinp" placeholder="请输入手机号"/></span>
            <span class="right"><input id="verify_btn" type="button" class="greenbtn" value="获取验证码"/></span>
            <span class="clear"></span>
        </li>
        <li>
            <input id="verifycode" name="" type="number" class="grayinp" placeholder="输入验证码" />
        </li>
        <li>
            <input id="password" name="" type="password" class="grayinp"  placeholder="新密码(6-20位字母、数字、特殊符号)"/>
        </li>
    </ul>
</div>
<span class="blank1"></span>
<input id="token" value="<?php echo $token?>" type="hidden"/>
<div class="repass-btn">
    <!-- <input id="regbtn" class="graybtn2" name="" type="submit" value="下一步" /> -->
    <input id="submit_btn" class="redbtn" name="" type="submit" value="提交" />
</div>
<div class="repass-tis">收不到验证码？请拨打 <a href="tel:4008857171">400-885-7171</a></div>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/login/forgetPassword'};</script>