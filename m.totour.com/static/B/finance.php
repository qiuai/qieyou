<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<title>财富管理</title>
	<link rel="stylesheet" href="./css/base.css">
	<link rel="stylesheet" href="./css/finance.css">
</head>
<body class="finance-body">

<header>
    <div class="fl"><a href="#"><img src="images/back.png"></a></div>
    <div class="fm">财富管理</div>
    <div class="fr"></div>
</header>
<div class="wrap">
<div class="finance-wrap">
	<div class="finance-total dark-bg">
		<button class="ext-btn">提现</button>
		<span class="label">账户余额：</span>
		<span class="price">2323.6</span>
	</div>
	<div class="finance-stati">
		<div class="stati-item">
			<p class="label">累计收入</p>
			<p class="num">14938.8</p>
		</div>
		<div class="stati-item">
			<p class="label">自营收入</p>
			<p class="num">2578.9</p>
		</div>
		<div class="stati-item">
			<p class="label">代销收入</p>
			<p class="num">2323.6</p>
		</div>
		<div class="stati-item">
			<p class="label">待确认</p>
			<p class="num">1825.9</p>
		</div>
		<div class="stati-item">
			<p class="label">提现中</p>
			<p class="num">300.5</p>
		</div>
		<div class="stati-item">
			<p class="label">日提现</p>
			<p class="num">189.6</p>
		</div>
	</div>
	<div class="finance-name dark-bg">交易流水</div>
	<div class="finance-list">
		<div class="months">
			<div class="month">
				<span class="date-lg">05</span>
				<span class="date-sm">月 23日</span>
				<span class="flow"><span class="checkin"><i class="flow">收</i><em> 60.00</em></span><span class="checkout"><i class="flow">支</i> <em>4.80</em></span></span>
			</div>
			<div class="days">
				<table class="day">
					<tr>
						<td><i class="flow checkin">收</i> <span class="text">订单交易</span></td>
						<td><img class="clock" src="./images/clock.png"> <span class="text">12:45</span></td>
						<td>5452346885678</td>
						<td>义安居客栈观景房一天房</td>
						<td><span class="checkin">+60.00</span></td>
					</tr>
					<tr>
						<td><i class="flow checkin">支</i> <span class="text">订单退款</span></td>
						<td><img class="clock" src="./images/clock.png"> <span class="text">10:32</span></td>
						<td>5452346885678</td>
						<td>嘉华鲜花饼 500g</td>
						<td><span class="checkout">-4.80</span></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="months">
			<div class="month">
				<span class="date-lg">05</span>
				<span class="date-sm">月 23日</span>
				<span class="flow"><span class="checkin"><i class="flow">收</i><em> 60.00</em></span><span class="checkout"><i class="flow">支</i><em> 4.80</em></span></span>
			</div>
			<div class="days">
				<table class="day">
					<tr>
						<td><i class="flow checkin">收</i> <span class="text">订单交易</span></td>
						<td><img class="clock" src="./images/clock.png"> <span class="text">12:45</span></td>
						<td>5452346885678</td>
						<td>义安居客栈观景房一天房</td>
						<td><span class="checkin">+60.00</span></td>
					</tr>
					<tr>
						<td><i class="flow checkin">支</i> <span class="text">订单退款</span></td>
						<td><img class="clock" src="./images/clock.png"> <span class="text">10:32</span></td>
						<td>5452346885678</td>
						<td>嘉华鲜花饼 500g</td>
						<td><span class="checkout">-4.80</span></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">var REQUIRE = {MODULE: 'page/finance'};</script>
<?php include "./resourceMap.php"; ?>
</body>
</html>