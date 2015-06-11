<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/item.css"/>
<div class="od-top">
    <?php $total = explode('.',$order['total']); // $cut_price = explode('.',$order['favorable_price']);?>
    <?php switch($order['state'])
	{
		case 'A':
			echo '<p class="red font1">待支付</p><p>待支付：<font class="red">'.$total[0].'<i>.'.$total[1].'</i></font>元</p>'.
                 '<div class="od-time"><img src="'.$staticUrl.'images/time.png"/>剩余<em id="end_time">00:00:00</em></div>';
			break;
		case 'S':
			echo '<p class="red font1">已完成</p><p>订单金额：<font class="red">'.$total[0].'<i>.'.$total[1].'</i></font>元</p>';
			break;
		case 'P':
		case 'U':
			echo '<p class="red font1">已付款</p><p>订单金额：<font class="red">'.$total[0].'<i>.'.$total[1].'</i></font>元</p>';
			break;
		case 'R':
			echo '<p class="red font1">待退款</p><p>订单金额：<font class="red">'.$total[0].'<i>.'.$total[1].'</i></font>元</p><div class="tuik"><p>订单取消时间：2015-03-22  12:23:23</p><p class="fgray">我们将在一周内完成您的退款请求，退款将放在[我的余额]</p></div>';
			break;
		case 'N':
			echo '<p class="red font1">已取消</p><p>订单金额：<font class="red">'.$total[0].'<i>.'.$total[1].'</i></font>元</p>';
			break;
		case 'C':
			echo '<p class="red font1">已退款</p><p>订单金额：<font class="red">'.$total[0].'<i>.'.$total[1].'</i></font>元</p>';
			break;
		default:
			break;
	}
	?>
</div>

<div class="od-con">
    <div class="otit">订单信息</div>
    <div class="order">
        <dl>
            <dt>订单编号</dt>
            <dd><?php echo $order['order_num'];?></dd>
        </dl>
        <?php if($order['contact']):?>
        <dl>
            <dt>联系人</dt>
            <dd><?php echo $order['contact'];?></dd>
        </dl>
        <?php endif;?>
        <?php if($order['telephone']):?>
        <dl>
            <dt>手机</dt>
            <dd><?php echo $order['telephone'];?></dd>
        </dl>
        <?php endif;?>
        <?php if(!empty($order['order_profiles'])):?>
        <dl>
            <dt>地址</dt>
            <dd><?php echo $order['order_profiles']['location'];?></dd>
        </dl>
        <?php endif;?>
        <dl>
            <dt>付款时间</dt>
            <dd>
                <?php if($order['pay_time']) echo date('Y-m-d H:i',$order['pay_time']);else '未支付';?>
            </dd>
        </dl>
    </div>
</div>
<?php if(!in_array($order['state'],array('A','N'))&&!$order['is_express']&&$order['category'] != 7):?>
<div class="od-con">
    <div class="otit">电子凭证</div>
    <div class="ping">
        <ul>
            <?php if($unspend_coupon):?>
            <?php foreach($unspend_coupon as $key => $row):?>
            <li><span class="left fgray">密码<?php echo $key+1;?>：</span><span class="left"><?php echo chunk_split($row['code'],4," ");?>
                <p class="fgray">（有效期至<?php echo date('Y-m-d H:i',$row['limit_time']);?>）</p>
                </span><span class="right red">未使用</span><span class="clear"></span></li>
            <?php endforeach;?>
            <?php endif;?>
            <?php $coupon = json_decode($order['coupon_info'],true);?>
            <?php if($coupon):?>
            <?php foreach($coupon as $k => $r):?>
            <li><span class="left fgray">密码<?php echo $k+2;?>：</span><span class="left"><?php echo chunk_split($r['code'],4," ");?> </span><span class="right red">已失效</span><span class="clear"></span></li>
            <?php endforeach;?>
            <?php endif;?>
        </ul>
    </div>
</div>
<?php endif;?>
<div class="od-con">
    <div class="otit">商品信息</div>
    <div class="product"><a href="/item/<?php echo $order['product_id']; ?>">
        <div class="left">
            <div class="pic"><img alt="" src="<?php echo $attachUrl.$order['product_thumb'];?>"/></div>
            <div class="text"><?php echo $order['product_name']?></div>
        </div>
        <div class="right">
            <?php $perprice = explode('.',$order['price']);?>
            <p><font class="font1 red"><?php echo $perprice[0]?><i>.<?php echo $perprice[1]?></i></font>元</p>
            <p>x<?php echo $order['quantity']?></p>
        </div></a>
        <span class="clear"></span> </div>
</div>
<div class="od-con">
    <div class="otit">商家信息</div>
    <div class="o-inn">
        <dl>
            <dt><?php echo $inninfo['inn_name'];?></dt>
            <dd><span class="oleft"><?php echo $inninfo['inn_address'];?></span>
        </dl>
        <div class="o-pos">
            <?php if(!empty($session['lat'])):?>
            <img src="<?php echo $staticUrl;?>images/pos3.png" /><?php echo echoDistance($session['lat'],$session['lon'],$inninfo['lat'],$inninfo['lon']);?></span>
            <?php endif;?>
        </div>
    </div>
    <a href="tel:<?php echo $inninfo['inner_moblie_number'];?>" class="telephone"><?php echo ($inninfo['inner_telephone']?($inninfo['inner_telephone'].' / '):'').$inninfo['inner_moblie_number'];?></a> </div>
<div class="od-con">
    <div class="otit">订单金额</div>
    <div class="order textr">
        <dl>
            <dt>商品总额</dt>
            <dd><?php echo $order['subtotal'];?>元</dd>
        </dl>
        <dl>
            <dt>优惠券</dt>
            <dd><?php echo $order['favorable_price']>0?'-'.$order['favorable_price']:'0.00';?>元</dd>
        </dl>
        <dl>
            <dt>余额</dt>
            <dd><?php echo $order['balance_price']>0?'-'.$order['balance_price']:'0.00';?>元</dd>
        </dl>
    </div>
    <div class="oprice"><span class="opleft">实际付款</span><span class="opright red"><?php echo $order['total'];?><font>元</font></span></div>
</div>
<?php switch($order['state'])
	{
		case 'A':
			echo '<div id="opr_btn" class="obtn"><a id="cancel_order" data-oid="' . $order['order_num'] . '" href="#" class="gbtn">取消订单</a><a href="/order/pay?order=' . $order['order_num'] . '" class="rbtn">去支付</a></div>';
			break;
		case 'P':
		case 'U':
			echo '<div class="obtn"><a id="cancel_order" data-oid="' . $order['order_num'] . '" href="#" class="gbtn">取消订单</a></div>';
			break;
		case 'S':
			if(!$order['commented']){
				//echo '<div class="obtn"><a href="/order/comment/' . $order['order_num'] . '" class="gbtn">评价</a></div>';
			}
			break;
		case 'R':
		//	echo '<p class="red font1">待退款</p><p>订单金额：<font class="red">'.$total[0].'<i>.'.$total[1].'</i></font>元</p>';
			break;
		case 'N':
		//	echo '<p class="red font1">已取消</p><p>订单金额：<font class="red">'.$total[0].'<i>.'.$total[1].'</i></font>元</p>';
			break;
		case 'C':
		//	echo '<p class="red font1">已退款</p><p>订单金额：<font class="red">'.$total[0].'<i>.'.$total[1].'</i></font>元</p>';
			break;
		default:
			break;
	}
?>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/order/view'}, ORDER_TYPE = "<?php echo $order['state']; ?>", CREATE_TIME = <?php echo $order['create_time']; ?></script>
<span class="blank1"></span>