
<div class="edit-tit"><font<?php if($step=='verify'): ?> class="green"<?php endif; ?>>验证原手机</font> > <font<?php if($step=='set'): ?> class="green"<?php endif; ?>>输入新手机号码</font></div>
<?php if($step=='verify'): ?>
<div class="repass">
    <ul>
        <li>
            <span class="left"><input id="mobile" data-mobile="18612540330" name="" disabled="disabled" type="text" class="grayinp"  placeholder="186****1682" readonly /></span>
            <span class="right"><input id="verify_btn" type="button" class="greenbtn" value="获取验证码"/></span>
            <span class="clear"></span>
        </li>
        <li>
            <input id="verifycode" name="" type="text" class="grayinp" placeholder="输入验证码" />
        </li>
    </ul>
</div>
<?php else: ?>
<div class="repass">
    <ul>
        <li>
            <span class="left"><input id="mobile" name="" type="text" class="grayinp"  placeholder="请输入新手机号码"/></span>
            <span class="right"><input id="verify_btn" type="button" class="greenbtn" value="获取验证码"/></span>
            <span class="clear"></span>
        </li>
        <li>
            <input id="verifycode" name="" type="text" class="grayinp" placeholder="输入验证码" />
        </li>
    </ul>
</div>
<?php endif; ?>

<span class="blank1"></span>
<div class="repass-btn">
    <input id="submit_btn" class="redbtn" name="" type="submit" value="提交" />
</div>


<div class="repass-tis">收不到验证码？请拨打 <a href="tel:4008857171">400-885-7171</a></div>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/editmobile'};</script>