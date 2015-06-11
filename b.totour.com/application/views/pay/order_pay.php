<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>订单支付</title>
<meta name="format-detection" content="telephone=no"/>
<meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />
</head>
<style type="text/css">
body,div,p,ul{margin:0;border:0;padding:0}
body{background:#ededec;text-align:center;color: #333;font-family:"Hiragino Sans GB",arial,helvetica,clean;margin:0px auto;}
.nav{color:#9d9b9c;height:35px;padding:12px 0; text-align:left;overflow:hidden;background-color: #fff;}
.nav .info{line-height:35px;font-size:16px;font-weight:bold;padding-left:8px;margin-left:10px;border-left:3px solid #ff7300;}
.nav-wrap{height:72px;margin:14px 10px; font-size:12px;text-align:left;}
.nav-wrap p{height:24px;line-height:24px; color:#a1a1a1;}
.nav-wrap p i{font-style:normal; color:#333;}
.nav-wrap .price {color:red;font-weight:bold;}
.select_pay {color:#666;}
.select_pay ul {background-color: #fff;text-align: left;padding:17px 0;}
.submit_pay {margin:16px 10px 0;}
.button_pay {padding:0;height:36px;line-height:36px;background-color:#EC1921;border-radius: 4px;color:#fff;font-size:16px;border:0;width:100%;}
.alipay_img {background-image:url('/static/images/alipay.png');background-size: 100%;margin: 0 10px 0 12px;height: 36px;width: 60px;display:inline-block;vertical-align: middle;background-repeat: no-repeat;}
.radio_alipay{position: relative;line-height:36px;color: #999;font-weight: bold;letter-spacing: 1px;display: block;}
.radio_select {position: absolute;display: inline-block;float:right;vertical-align: middle;height: 20px;width: 20px;border: 1px solid #ddd8c6;-webkit-border-radius: 12px;border-radius: 12px;overflow: hidden;right: 10px;top: 8px;border-color: #32a43d;background: #32a43d;transform: rotate(-135deg);-webkit-transform: rotate(-135deg);}
.radio_select:after,.radio_select:before {background-color: #fff;content: "";height: 50%;position: relative;width:3px;display: block;left: 35%;top: 24%;}
.radio_select:before {height: 3px;width: 26%;padding-left: 3px;}
</style>
<body>
<div class="nav">
    <p class="info">且游商家支付中心</p>
</div>
<div class="nav-wrap">
	<p>商品名称：<i><?php echo $order['product_name'];?></i></p>
	<p>商品数量：<i><?php echo $order['quantity'];?></i></p>
	<p>付款金额：<i class="price"><?php echo number_format($order['total'],2);?></i>&nbsp;元</p>
</div>
<div class="select_pay">
    <!--form start-->
    <form action="/trans/payCenter" method="get" id="payFrom">
    <ul class="form-wrap">
		<!--交易信息 start-->
		<input type="hidden" name="oid" value="<?php echo $order['order_num']?>"/>
		<!--交易信息 end-->
		<div class="radio_alipay">
			<i class="alipay_img"></i>支付宝支付
			<em class="radio_select"></em>
		</div>
    </ul>
    </form>
	<div class="submit_pay">
		<button id="subButton" class="button_pay" onclick="Button_Click()">立即支付</button>
	</div>
    <!--form end-->
</div>
<!--popbox style end-->
<div class="" style="position:absolute;height:18px;line-height:18px;bottom:10px;width:100%;">
</div>
<script>
	function Button_Click(){ 
		document.getElementById('payFrom').submit();
	}
</script>
</body>
</html>
