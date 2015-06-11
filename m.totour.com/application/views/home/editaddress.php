<div class="editadd">
	<form id="form1">
		<input id="class" name="class" value="address" type="hidden">
		<input id="classid" name="classid" value="<?php echo empty($address['address_id'])?'':$address['address_id'];?>" type="hidden">
		<input id="act" name="act" value="<?php echo empty($address['address_id'])?'add':'edit';?>" type="hidden">
		<ul>
			<li>
				<label>真实姓名</label><input id="name" name="name" type="text" value="<?php echo empty($address)?'':$address['real_name']?>" placeholder="请输入真实姓名"/>
			</li>
			<li>
				<label>手机号</label><input id="mobile" name="mobile" type="text" value="<?php echo empty($address)?'':$address['mobile']?>"  placeholder="请输入11位手机号码" />
			</li>
			<li>
				<label>省份</label><select id="CS_province" name="province" value="<?php echo empty($address)?'':$address['local_array']['sheng']['area_id'];?>">
				<option >--请选择省份--</option>
				</select>
				<span></span>
			</li>
			<li>
				<label>城市</label><select id="CS_city" name="city" value="<?php echo empty($address)?'':$address['local_array']['shi']['area_id'];?>">
				<option >--请选择城市--</option>
				</select>
				<span></span>
			</li>
			<li>
				<label>区县</label><select id="CS_district" name="district" value="<?php echo empty($address)?'':$address['location_id'];?>">
				<option>--请选择区县--</option>
				</select>
				<span></span>
			</li>
			<li>
				<label>详细地址</label><input id="address" name="address" type="text" value="<?php echo empty($address)?'':$address['address']?>"  placeholder="请输入详细地址" />
			</li>
		</ul>
	</form>
</div>
<div class="editbtn"><a id="submit_btn" href="#" class="redbtn">保存</a></div>
<article class="editfoot">
    <h1>个人隐私及信息使用说明</h1>
<p>1、仅用于且游网站内使用，且游网保证不将用户信息，包含身份证号码、手机号码等个人隐私泄露给任何第三方；<br />
2、用户身份证信息主要用于购买旅游保险，收货地址主要用于快递送货，请确保信息填写的准确性，如因用户个人原因，网站无法承担因此产生的其他责任。
</p>
</article>
</div>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/editaddress'}, ADDRESS_TYPE = <?php echo $type;?>, initLoca = {initProvince: <?php echo empty($address)?'""':$address['local_array']['sheng']['area_id'];?>, initCity: <?php echo empty($address)?'""':$address['local_array']['shi']['area_id'];?>, initDistrict: <?php echo empty($address)?'""':$address['location_id'];?>}, PAGE_CLASS = 'address';</script>