<h3 class="headline">且游优品</h3>
<div class="filter mb20">
    <div class="form p10-20" style="margin-left: 16px;">
        开始日期：<input type="text" id="startTime" onfocus="WdatePicker({onpicked:WdataOnPick('st'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php if($starttime) echo date('Y-m-d',$starttime);?>" name="st" title="请选择开始日期" class="Wdate mr40">
        截止日期：<input type="text" id="endTime" onfocus="WdatePicker({onpicked:WdataOnPick('ed'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php if($endtime) echo date('Y-m-d',$endtime);?>" name="ed" title="请选择结束日期" class="Wdate mr40">
		<input class="submit" type="submit" id="setTimeButton" value="确认">
        <div class="fr">搜索：
			<select class="searchOrder" name="key" id="key">
				<option value="1">订单号</option>
				<option value="2">联系人手机</option>
			</select>
        <input type="text" value="" id="keyword" name="keyword" class="mr30">
		<input class="submit" type="submit" id="keywordButton" value="确认">
        </div>
		<div class="tips tips-ok" style="display: none;" id="timeTips"><i class="tips-ico"></i><p></p></div>
    </div>
	<table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
		<tbody>
		<tr>
            <td class="leftLabel">商品类别：</td>
			<td id="typeSelect">
				<label><input type="radio" class="radio" name="cid" value="0" <?php if($cid =='0') echo 'checked=""';?>>所有</label>
                <label><input type="radio" class="radio" name="cid" value="1" <?php if($cid =='1') echo 'checked=""';?>>客栈酒店</label>
                <label><input type="radio" class="radio" name="cid" value="2" <?php if($cid =='2') echo 'checked=""';?>>美食饕餮</label>
                <label><input type="radio" class="radio" name="cid" value="3" <?php if($cid =='3') echo 'checked=""';?>>娱乐休闲</label>
				<label><input type="radio" class="radio" name="cid" value="4" <?php if($cid =='4') echo 'checked=""';?>>当地行</label>
                <label><input type="radio" class="radio" name="cid" value="5" <?php if($cid =='5') echo 'checked=""';?>>当地游</label>
                <label><input type="radio" class="radio" name="cid" value="6" <?php if($cid =='6') echo 'checked=""';?>>当地购</label>
                <label><input type="radio" class="radio" name="cid" value="7" <?php if($cid =='7') echo 'checked=""';?>>旅游险</label>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="tab">
	<ul class="clearfix">
		<li <?php if($state =='all') echo 'class="current"';?> ref="all"><a href="javascript:void(0);">全部订单</a></li>
		<li <?php if($state =='unpaid') echo 'class="current"';?> ref="unpaid"><a href="javascript:void(0);">未支付订单</a></li>
		<li <?php if($state =='paid') echo 'class="current"';?> ref="paid"><a href="javascript:void(0);">已支付订单</a></li>
		<li <?php if($state =='waiting') echo 'class="current"';?> ref="waiting"><a href="javascript:void(0);">待消费订单</a></li>
		<li <?php if($state =='finished') echo 'class="current"';?> ref="finished"><a href="javascript:void(0);">已完成订单</a></li>
		<li <?php if($state =='refund') echo 'class="current"';?> ref="refund"><a href="javascript:void(0);">待退款订单</a></li>
		<li <?php if($state =='refunded') echo 'class="current"';?> ref="refunded"><a href="javascript:void(0);">已退款订单</a></li>
		<li <?php if($state =='cancel') echo 'class="current"';?> ref="cancel"><a href="javascript:void(0);">已取消订单</a></li>
	</ul>
</div>
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp10"/>
        <col class="wp10"/>
		<col class="wp10"/>
        <col class="wp10"/>
        <col class="wp20"/>
        <col class="wp5"/>
        <col class="wp5"/>
        <col class="wp5"/>
        <col class="wp5"/>
        <col class="wp10"/>
    </colgroup>
    <thead>
    <tr>
        <th>交易时间</th>
		<th>交易号（支付宝）</th>
        <th>订单号</th>
        <th>商户名称</th>
        <th>商品名称</th>
        <th>单价</th>
        <th>数量</th>
        <th>订单总额</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php $last_time = 0;?>
	<?php if($orders):?>
	<?php foreach($orders as $key => $order):?>
	<tr>
		<?php if($last_time != $order['create_time']):?>
        <td><?php echo date('Y-m-d H:i:s',$order['create_time']);$last_time = $order['create_time'];?></td>
		<?php else:?>
		<td style="border-top: 0px;"></td>
		<?php endif;?>
		<td><?php switch($order['pay_type']){case 'alipay': echo $order['code'];break;case 'balance':echo '余额付款'; break; default: echo ''; }?></td>
        <td><?php echo $order['order_num'];?></td>
        <td><?php echo $order['inn_name'];?></td>
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
	<?php endif;?>
    </tbody>
</table>
<!--分页样式开始-->
<div class="pageBar clearfix">
	<p>共<em><?php echo $pageInfo['total'];?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage'];?></em>条</p>
	<div class="pages fr" id="page">
	</div>
</div>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js" charset="utf-8"></script>    
<script type="text/javascript">
    $(function(){	
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());

		var startTime = $('#startTime');
		var endTime = $('#endTime');
		var setTimeButton = $('#setTimeButton');
		var currentState = "<?php echo $state;?>";
		var currentCid = "<?php echo $cid;?>";

		function getTime()
		{
			var url ='';
			var st = startTime.val();
			var ed = endTime.val();
			var start = st?datetimeToUnix(st+" 00:00:00"):'';
			var end = ed?datetimeToUnix(ed+" 23:59:59"):'';
			url = (st?"&st="+start:"")+(ed?"&ed="+end:"");
			return url;
		}

		function geturl()
		{
			var url = '';
			if(currentCid != '0')
			{
				url += '&cid=' + currentCid;
			}
			return url;
		}

		var typeSelect = $('#typeSelect').find('input[name=cid]');
        typeSelect.change(function(){
            var typeVal = $(this).val();
            window.location.href="<?php echo $baseUrl;?>order/tuan?state="+currentState+getTime()+"&cid="+typeVal;
        });

		setTimeButton.click(function(){
			window.location.href="<?php echo $baseUrl;?>order/tuan?state="+currentState+getTime()+geturl();
		});

		$(".tab li").click(function(){ 
			var state = $(this).attr('ref');
			window.location.href="<?php echo $baseUrl;?>order/tuan?state="+state+getTime()+geturl();
		});
		/* 订单号、手机搜索 */
		$('#keywordButton').click(function(){
			var key = $('#key').val();
			var keyword = $('#keyword').val();
			var s = /^[0-9]*$/;
			if((key == '1'|| key == '2') && s.test(keyword))
			{
				window.location.href="<?php echo $baseUrl;?>order/tuan?key="+key+"&keyword="+keyword;
			}
			else
			{
				alert('error');	
			}
		});
    })
</script>
<!--分页样式结束-->
