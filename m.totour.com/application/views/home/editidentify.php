<div class="editiden">
	<ul>
		<input id="class" name="class" value="identify" type="hidden">
		<input id="classid" name="classid" value="<?php echo empty($identify['identify_id'])?'':$identify['identify_id'];?>" type="hidden">
		<input id="act" name="act" value="<?php echo empty($identify['identify_id'])?'add':'edit';?>" type="hidden">
		<li><label>真实姓名</label><input id="real_name" name="real_name" value="<?php echo empty($identify['real_name'])?'':$identify['real_name'];?>" type="text" placeholder="请输入真实姓名"/></li>
		<li><label>身份证号码</label><input id="idcard" name="idcard" value="<?php echo empty($identify['idcard'])?'':$identify['idcard'];?>" type="text" placeholder="请输入真实身份证号码" /></li>
	</ul>
</div>
<div class="editbtn"><a id="submit_btn" href="#" class="redbtn">保存</a></div>
<article class="editfoot">
    <h1>个人隐私及信息使用说明</h1>
<p>1、仅用于且游网站内使用，且游网保证不将用户信息，包含身份证号码、手机号码等个人隐私泄露给任何第三方；<br />
2、用户身份证信息主要用于购买旅游保险，收货地址主要用于快递送货，请确保信息填写的准确性，如因用户个人原因，网站无法承担因此产生的其他责任。
</p>
</article>
</div>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/editidentify'}, IDENTIFY_TYPE = <?php echo $type;?>, PAGE_CLASS = 'identify';</script>