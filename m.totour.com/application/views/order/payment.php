<div class="ordertop">确认订单</div>
<div class="ordercon" >
    <div id="tab">
        <div>
            <ul>
                <li class="headpic"><img src="<?php echo $attachUrl.$order['product_thumb'];?>" /></li>
                <li class="con">
                    <dl class="contop">
                        <dt><?php echo $order['product_name'];?></dt>
                        <dd><span class="price">
                            <?php $price = explode(".",$order['price']);  echo $price[0].'<i>.'.$price[1].'</i>';?>
                            </span>元</dd>
                    </dl>
                </li>
                <span class="clear"></span>
            </ul>
        </div>
        <div class="cprice"><span>共<?php echo $order['quantity'];?>件商品</span>总价：<font>
            <?php $total = explode(".",$order['total']);  echo $total[0].'<i>.'.$total[1].'</i>';?>
            </font>元</div>
    </div>
    <div class="payprice">
        <?php $quanmount = 0; ?>
        <?php if($quan):?>
        <?php $quanHtml = '<div id="select_quan" class="cquan" style="display:none;">
                            <div data-type="no" class="noquan"><label for="quan1">
                                <ul>
                                    <li class="left">
                                        不使用抵用券
                                    </li>
                                    <li  class="right">
                                        <input data-id="0" data-amount="0" class="u-radio u-light" name="quan" id="quan1" type="radio">
                                        <label class="u-btn" for="quan1"><img src="' . $staticUrl . 'images/radio.png" /></label>
                                    </li>
                                    <span class="clear"></span>
                                </ul></label>
                            </div>'; ?>
        <?php foreach($quan as $key => $row):?>
<?php $quanHtml .= '<div data-type="item" class="yquan">
                    <div class="qlist">
                       <label for="quan' . $row['coupon_id'] . '">
                        <ul>
                            <li class="lquan">
                                <span class="left">
                                <P>￥<font>' . $row['amount'] . '</font></P>
                                <p>优惠券</p>
                                </span> <span class="right"> <font>全场通用</font> 有效期：' . date('Y.m.d', $row['start_time']) . '-' . date('Y.m.d', $row['end_time']) . ' </span>
                            </li>
                            <li class="rquan">
                                <input data-id="' . $row['coupon_id'] . '" data-amount="' . $row['amount'] . '" class="u-radio u-light" name="quan" id="quan' . $row['coupon_id'] . '" type="radio">
                                <label class="u-btn" for="quan' . $row['coupon_id'] . '"><img src="' . $staticUrl . 'images/radio.png" /></label>
                            </li>
                            <span class="clear"></span>
                        </ul>
                        </label>
                    </div>
                </div>'; ?>
        <?php if($quanmount < $row['amount']) {$quanmount = $row['amount']; $select = $row['coupon_id'];}; //金额 name = "quan"; quan=$row['coupon_id'];?>
        <?php endforeach;?>
        <?php $quanHtml .= '</div>'; ?>
        <?php if($quanmount): ?>
        <dl id="select_quan_btn" >
            <a href="#">
            <dt id="quan_selected"> 抵用券：您已经使用<span><?php echo $quanmount;?></span>元优惠券 </dt>
            <input id="quan_value" name="quan" value = "<?php echo $select?>" type="hidden" >
            <dd><img src="<?php echo $staticUrl;?>images/arrow2.png" /></dd>
            </a> <span class="clear"></span>
        </dl>
        <?php endif;?>
        <?php else: $quanmount = 0;?>
        <?php endif;?>
        <?php if($inn):?>
        <ul style="display:none;">
            <li>您的可用余额<span>
                <?php $live = $live2 = $inn['account']-$inn['withdrawing']; if(!$live) echo '<em id="balance_w">0<i>.00</i></em>'; else{ $live = explode(".",$live);  echo '<em id="balance_w">'.$live[0].'<i>.'.$live[1].'</i></em>'; }?>
                </span>元，使用
                <input id="account_price" name="" type="number" />
                元</li>
        </ul>
        <?php endif;?>
        <!--<div class="zprice">应付金额:<span>130<i>.00</i></span>元</div>--> 
    </div>
    <div class="paymode">
        <dl>
            <dt>选择支付方式</dt>
            <dd>
                <ul>
                    <li class="img">
                        <label for="cb1"><img src="<?php echo $staticUrl;?>images/zi2.jpg" /></label>
                    </li>
                    <li class="text">
                        <label for="cb1">支付宝网页支付<span>快捷收银台mclient.alipay</span></label>
                    </li>
                    <li class="right">
                        <input class="u-radio u-light" name="paytype" id="cb1" type="radio" checked value="alipay">
                        <label class="u-btn" for="cb1"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
                    </li>
                    <span class=" clear"></span>
                </ul>
                <!--  <ul>
                    <li class="img"><label for="cb2"><img src="<?php echo $staticUrl;?>images/zi1.jpg" /></label></li>
                    <li class="text"><label for="cb2">支付宝客户端支付<span>安装支付宝客户端使用</span></label></li>
                    <li class="right"><input class="u-radio u-light" name="u-radio" id="cb2" type="radio">
    <label class="u-btn" for="cb2"><img src="<?php echo $staticUrl;?>images/radio.png" /></label></li>
                    <span class=" clear"></span>
                </ul>
                <ul>
                    <li class="img">
                        <label for="cb3"><img src="<?php echo $staticUrl;?>images/weixin.jpg" /></label>
                    </li>
                    <li class="text">
                        <label for="cb3">微信客户端支付<span>安装微信客户端使用</span></label>
                    </li>
                    <li class="right">
                        <input class="u-radio u-light" name="paytype" id="cb3" type="radio" value="weixin">
                        <label class="u-btn" for="cb3"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
                    </li>
                    <span class=" clear"></span>
                </ul>-->
            </dd>
        </dl>
    </div>
</div>



<!--选择抵用券-->
<?php if(isset($quanHtml)) echo $quanHtml; ?>
<div style="height:5rem;"></div>

<div class="payfoot"> <span>合计：<font>
    <?php if($quanmount){
		$sum = $order['total'] - $quanmount; 
		if($sum < 0){ 
			$sum = 0;
		}
		$sum = number_format($sum,2);
		$sum=explode(".",$sum); 
		echo '<em id="item_total">'.$sum[0].'<i>.'.$sum[1].'</i></em>';
        $quanPrice = $quanmount;
	} 
	else{
        $quanPrice = 0;
		echo '<em id="item_total">'.$total[0].'<i>.'.$total[1].'</i></em>';
	}?>
    </font></span>
    <input name="" type="button" value="确认支付" onclick="window.location.href='/order/pay?order=<?php echo $order['order_num']; ?>'" />
    <!-- <input id="submit_btn" name="" type="button" value="确认支付" /> -->
</div>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/order/payment', DATA: {order: <?php echo $order['order_num']; ?>,price: <?php echo $order['total']; ?>, quan: <?php echo $quanPrice; ?>, acp: 0, balance: <?php echo isset($live2)?$live2:0; ?>}};</script>