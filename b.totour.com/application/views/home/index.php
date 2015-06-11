<h3 class="headline"><?php echo $currentUser['real_name'];?>，您好，欢迎回来！</h3>
<div class="clearfix mb20">
	<div class="welcomeLeft">
		<div class="p15" style="height: 127px;">
			<h4>帐务信息：</h4>
			<ul class="welcomeUl">
				<li class="f14">当前账户余额： <em>￥<?php echo $innInfo['account_balance'];?></em>
				<?php if($currentUser['role'] == ROLE_INNHOLDER):?>
					<a href="<?php echo $baseUrl;?>inns/cashout" class="submit-mini mr10" title="申请提现">申请提现</a>
					<a href="<?php echo $baseUrl;?>inns/cashin" class="submit-mini" title="在线充值">在线充值</a>
				<?php endif;?>
				</li>
				<li class="f14">当前订单分润：<em style="color:#360"><?php echo $innInfo['order_divide'];?>%</em></li>
				<li class="f14">今日订单： <a href="<?php echo $baseUrl;?>order">（<?php echo $total_order;?>）</a></li>
			</ul>
		</div>

	</div>
	<div class="welcomeRight">
		<div class="p15" style="height: 127px;">
			<h4>驿栈信息：</h4>
			<ul class="welcomeUl">
				<li><b>驿栈名称：</b> <?php echo $innInfo['inns_name'];?></li>
				<li><b>所属区域：</b> <?php echo $innInfo['province'].$innInfo['city'].' - '.$innInfo['dest_name'];?></li>
				<li><b>详细地址：</b> <?php echo $innInfo['inns_address'];?></li>
				<li><b>固定电话：</b> <?php echo $innInfo['inner_telephone'];?></li>
			</ul>
		</div>
	</div>
</div>
<h3 class="headline">最新订单 <a class="more" href="<?php echo $baseUrl;?>order">全部订单</a></h3>
<table class="orderList table table-border table-odd">
	<colgroup>
        <col class="wp15"/>
		<col class="wp10"/>
		<col class="wp20"/>
		<col class="wp15"/>
		<col class="wp8"/>
		<col class="wp8"/>
		<col class="wp8"/>
		<col class="wp8"/>
		<col class="wp8"/>
	</colgroup>
	<thead>
	<tr>
        <th>交易时间</th>
		<th>订单号</th>
		<th>商品名称</th>
		<th>入住/出行时间</th>
		<th>单价</th>
		<th>数量</th>
		<th>订单总额</th>
		<th>状态</th>
		<th>操作</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($orders as $key => $order):?>
	<tr>
        <td><?php echo date('Y-m-d H:i:s',$order['create_time']);?></td>
        <td><?php echo $order['order_num'];?></td>
        <?php foreach($order_products[$order['order_num']] as $k => $detail):?>
		<td>
            <?php echo $detail;?>
        </td>
		<?php endforeach;?>
        <td><em><?php echo '¥'.number_format($order['total'],2);?></em></td>
        <td><?php echo $order['state'];?></td>
        <td><a href="<?php echo $baseUrl.'order/view?oid='.$order['order_num']?>" target="_blank">查看详情</a></td>
    </tr>
	<?php endforeach;?>
	</tbody>
</table>
