<div class="ordertop">提交订单</div>
<div class="ordercon" >
  <div id="tab">
    <div>
      <ul>
      	<a href="/item/<?php echo $product['product_id']; ?>">
        <li class="headpic"><img src="<?php echo $attachUrl.$product['thumb'];?>"/></li>
        <li class="con">
          <dl class="contop">
            <dt><?php echo $product['product_name']?></dt>
            <dd><span class="price"><?php $price = explode(".",$product['price']);  echo $price[0].'<i>.'.$price[1].'</i>';?></span>元</dd>
          </dl>
        </li>
        <span class="clear"></span>
        </a>
      </ul>
      
      <ul class="xline">
        <li class="left">库存：<?php echo $product['quantity']?></li>
        <li class="right">
          数量：<input class="min btnDisable" name="" type="button" value="—" ref="<?php echo $product['product_id'];?>"/>
          <input node-type="count" class="text_box" name="number" id="pid<?php echo $product['product_id'];?>" onchange="modifyCart(<?php echo $product['product_id'];?>)" type="text" value="1" />
          <input class="add <?php if(!($product['quantity']>1)) echo 'btnDisable';?>" name="" type="button" value="+" ref="<?php echo $product['product_id'];?>" />
        </li><span class="clear"></span>
      </ul>
       </div>
    <ul class="total">
      <li class="left">总价：</li>
      <li class="right">
        <label id="total"><?php echo $price[0].'<i>.'.$price[1].'</i>';?></label>
        元</li>
    </ul>
    <span class="clear"></span> </div>
    <input id="session_code" type="hidden" value="验证码">
	<?php if(empty($session['user_id'])):?>
	<div class="form">
		<ul>
			<!-- jq ajax 验证 -->
			<li>
				<input id="mobile" class="mobile" name="mobile" type="number" value="" placeholder="输入手机号" />
				<input id="verify_btn" class="verify" name="verify_btn" type="button" value="获取验证码" />
				<span class="clear"></span>
			</li>
			<li>
				<input id="verifycode" name="verifycode" type="number" value="" placeholder="输入验证码" /><!-- 输入之后ajax 后台验证 -->
			</li>
				<?php if($product['is_express']):?>
			<li>
				<input id="realname" name="realname" type="text" value="" placeholder="输入真实姓名" />
			</li>
            
			<li class="a-select">
				<select id="CS_province" name="province" value="<?php echo empty($address)?'':$address['local_array']['sheng']['area_id'];?>">
				<option >--请选择省份--</option>
				</select>
			</li>
			<li class="a-select">
				<select id="CS_city" name="city" value="<?php echo empty($address)?'':$address['local_array']['shi']['area_id'];?>">
				<option >--请选择城市--</option>
				</select>
			</li>
			<li class="a-select">
				<select id="CS_district" name="district" value="<?php echo empty($address)?'':$address['location_id'];?>">
				<option>--请选择区县--</option>
				</select>
			</li>
			<li>
				<input id="location" name="location" type="text" value="" placeholder="输入详细地址" />
			</li>

				<?php endif;?>
			<?php if($product['category'] == 7): //保险类?>
			<li>
				<input id="realname" name="realname" type="text" value="" placeholder="输入真实姓名" />
			</li>
			<li>
				<input id="idcard" name="idcard" type="text" value="" placeholder="输入身份证号" />
			</li>
			<?php endif;?>
			<!-- jq ajax 验证 -->
		</ul>
	</div>
	<?php elseif(!$product['is_express']):?>
	<div class="form">
		<ul>
			<li>
				<input id="mobile" name="mobile" type="hidden" value="<?php echo $session['band_mobile'];?>" placeholder="输入手机号" />
				<input name="mobile2" type="text" value="<?php echo substr_replace($session['band_mobile'],'****',3,4);?>" placeholder="输入手机号" />
			</li>
		</ul>
	</div>
	<?php endif;?>
	<?php if($product['is_express']&&!empty($session['user_id'])):?>
	<a href="<?php echo $baseUrl;?>home/address?type=1">
	<?php if($user_address):?>
		<div class="confirm-address">
			<div class="addrinfo">
				<p class="font12">收货人：<?php echo $user_address['real_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user_address['mobile']?></p>
				<p>收货地址：<?php echo $user_address['location'];?></p>
			</div>
			<div class="addrarrow"><img src="<?php echo $staticUrl;?>images/arrow2.png" /></div>
		</div>
		<input id="address" name="address" type="hidden" value="<?php echo $user_address['address_id'];?>">
	<?php else:?>
	<div class="confirm-address2">
		<img src="<?php echo $staticUrl;?>images/add2.png" />请添加收货地址
	</div>
	<?php endif;?>
	</a>
	<?php elseif($product['category'] == 7 && !empty($session['user_id'])):?>
	<a href="<?php echo $baseUrl;?>home/identify?type=1">
	<?php if($user_identify):?>
		<div class="confirm-address">
			<div class="addrinfo">
				<p class="font12">被保人：<?php echo $user_identify['real_name'];?></p>
				<p>身份证号<?php echo $user_identify['idcard'];?></p>
			</div>
			<div class="addrarrow"><img src="<?php echo $staticUrl;?>images/arrow2.png" /></div>
		</div>
		<input id="identify" name="identify" type="hidden" value="<?php echo $user_identify['identify_id'];?>">
	<?php else:?>
	<div class="confirm-address2">
		<img src="<?php echo $staticUrl;?>images/add2.png" />请添加被保人
	</div>
	<?php endif;?>
	</a>
	<?php endif;?>
	<div class="orderfoot">
		<span>支持随时退款，过期退款，实物商品线下退换货</span>
		<input id="submit_btn" name="submit" type="button" class="submit" value="<?php if(empty($session['user_id'])) echo '快捷下单';else echo '提交订单';?>" />
		<?php if(empty($session['user_id'])):?>
		<font>已有账号？<a href="<?php echo $baseUrl;?>login?url=<?php echo $baseUrl;?>order/confirm?pid=<?php echo $product['product_id'];?>">请登录》</a></font>
		<?php endif;?>
	</div>
</div>
<script>
	var total = <?php echo $product['quantity']?>;

	function check_num(id,num)
	{
		if(total == 0)
		{
			return false;
		}
		if(num == total)
		{
			$(".add").addClass("btnDisable");
		}
		else if(num == 1)
		{
			$(".min").addClass("btnDisable");
		}
		else if(num > total)
		{
			$('#pid'+id).val(total);
			$(".add").addClass("btnDisable");
			setTotal();
			return false;
		}
		else if(num < 1){
			$('#pid'+id).val(1);
			$(".min").addClass("btnDisable");
			setTotal();
			return false;
		}
		else{
			$(".add").removeClass("btnDisable");
			$(".min").removeClass("btnDisable");
		}
		$('#pid'+id).val(num);
		setTotal();
		return true;
	}

	function modifyCart(id)
	{
		var num = $('#pid'+id).val();
		if(!check_num(id,num))
		{
			QY.util.popup.error('输入数量有误！');
		}
	}


	function setTotal(){
		var s=0; 
		$("#tab div").each(function(){ 
			s+=parseInt($(this).find('input[class*=text_box]').val())*parseFloat($(this).find('span[class*=price]').text());
		}); 
		$("#total").html(s.toFixed(2)); 
	}
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/order/confirm'}, IS_LOGIN = <?php if(empty($session['user_id'])) echo 'false';else echo 'true'; ?>, ORDER_TYPE = <?php if($product['is_express']){echo 2;}elseif($product['category']==7){echo 3;}else{echo 1;} ?>, PID = <?php echo $product['product_id'];?></script>