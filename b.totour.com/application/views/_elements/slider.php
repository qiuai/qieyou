<div class="frameLeft">
	<div class="slider">
		<dl <?php if($locations['controllerTag'] == 'qieyou') echo 'class="show"';?>>
			<dt><i></i>且游自营</dt>
			<dd <?php if($locations['moduleTag'] == 'qieyouList') echo 'class="current"';?>><a href="<?php echo $baseUrl.'order/qieyou';?>" title="且游优品">优品订单</a></dd>
			<dd <?php if($locations['moduleTag'] == 'pqieyoulist') echo 'class="current"';?>><a href="<?php echo $baseUrl.'product/qieyou';?>" title="商品列表">优品管理</a></dd>
			<dd <?php if($locations['moduleTag'] == 'qieyoubalance') echo 'class="current"';?>><a href="<?php echo $baseUrl.'finance/qieyoubalance';?>" title="优品账单">优品账单</a></dd>
            <dd <?php if($locations['moduleTag'] == 'add_pqieyou') echo 'class="current"';?>><a href="<?php echo $baseUrl.'product/addqieyou'?>" title="添加且游优品">添加优品</a></dd>
		</dl>
		<dl <?php if($locations['controllerTag'] == 'order') echo 'class="show"';?>>
			<dt><i></i>订单管理</dt>
			<dd <?php if($locations['moduleTag'] == 'tuanList') echo 'class="current"';?>><a href="<?php echo $baseUrl.'order/tuan';?>" title="商城订单">商城订单</a></dd>
			<dd <?php if($locations['moduleTag'] == 'orderList') echo 'class="current"';?>><a href="<?php echo $baseUrl.'order';?>" title="商户订单">商户订单</a></dd>
		</dl>
		<dl <?php if($locations['controllerTag'] == 'product') echo 'class="show"';?>>
			<dt><i></i>商品管理</dt>
			<dd <?php if($locations['moduleTag'] == 'productlist') echo 'class="current"';?>><a href="<?php echo $baseUrl.'product';?>" title="商品列表">商品列表</a></dd>
		</dl>
		<dl <?php if($locations['controllerTag'] == 'finance') echo 'class="show"';?>>
			<dt><i></i>财务管理</dt>
			<dd <?php if($locations['moduleTag'] == 'cashout') echo 'class="current"';?>><a href="<?php echo $baseUrl.'finance/cashout';?>" title="提现管理">提现管理</a></dd>
			<dd <?php if($locations['moduleTag'] == 'refund') echo 'class="current"';?>><a href="<?php echo $baseUrl.'finance/refund';?>" title="退款管理">退款管理</a></dd>
			<dd <?php if($locations['moduleTag'] == 'balance') echo 'class="current"';?>><a href="<?php echo $baseUrl.'finance/balance';?>" title="账单查询">账单查询</a></dd>
			<dd <?php if($locations['moduleTag'] == 'account') echo 'class="current"';?>><a href="<?php echo $baseUrl.'finance/account';?>" title="商户账户">商户账户</a></dd>
		</dl>
		<dl <?php if($locations['controllerTag'] == 'inn') echo 'class="show"';?>>
			<dt><i></i>商户管理</dt>
            <dd <?php if($locations['moduleTag'] == 'addUser') echo 'class="current"';?>><a href="/user/add?type=innholder" title="新建用户">新建商户</a></dd>
			<dd <?php if($locations['moduleTag'] == 'searchDestInns') echo 'class="current"';?>><a href="<?php echo $baseUrl.'destination/searchDestInns';?>" title="区域商户">区域商户</a></dd>
		</dl>
		
<!--		<dl <?php if($locations['controllerTag'] == 'destination') echo 'class="show"';?>>
			<dt><i></i></dt>
		<dd <?php if($locations['moduleTag'] == 'destinationList') echo 'class="current"';?>><a href="<?php echo $baseUrl.'destination';?>" title="目的地管理">目的地管理</a></dd>
		</dl>-->
		<dl <?php if($locations['controllerTag'] == 'user'|| $locations['controllerTag'] == 'system') echo 'class="show"';?>>
			<dt><i></i>系统管理</dt>
			<dd <?php if($locations['moduleTag'] == 'userIndex') echo 'class="current"';?>><a href="<?php echo $baseUrl.'user';?>" title="用户查询">用户查询</a></dd>
			<dd <?php if($locations['moduleTag'] == 'userlog') echo 'class="current"';?>><a href="<?php echo $baseUrl.'sysmanage/userlog';?>" title="系统日志">系统日志</a></dd>
		</dl>
	</div>
</div>