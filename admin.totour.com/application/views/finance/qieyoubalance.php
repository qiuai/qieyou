<h3 class="headline">账单查询
	<span class="more"><input class="submit-mini" id="downLoadCsv" type="submit" value="下载" /></span>
</h3>
<div class="filter mb20">
    <table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <td class="leftLabel">交易时间：</td>
            <td>
                <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('st'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $starttime?date('Y-m-d',$starttime):'';?>" name="starttime" title="请选择开始日期" id="startTime" class="Wdate" readOnly></label>
				<span class="mr10">至</span>
                <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('ed'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $endtime?date('Y-m-d',$endtime):'';?>" name="endtime" title="请选择结束日期" id="endTime" class="Wdate" readOnly></label>
                <label><input type="submit" value="查询" id="setTimeButton" class="buttonG"/></label>
				<div class="tips tips-ok" style="display: none;" id="timeTips"><i class="tips-ico"></i><p></p></div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp8">
        <col class="wp8">
        <col class="wp15">
        <col class="wp5">
        <col class="wp8">
        <col class="wp5">
        <col class="wp5">
        <col class="wp6">
        <col class="wp10">
        <col class="wp10">
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">	
    </colgroup>
    <thead>
    <tr>
        <th>订单号</th>
        <th>商户名称</th>
        <th>商品名称</th>
        <th>商品数量</th>
        <th>销售方</th>
        <th>订单状态</th>
        <th>联系人</th>
        <th>联系人手机号</th>
        <th>交易时间</th>
        <th>结算时间</th>
        <th>订单总额</th>
        <th>商户分润</th>
        <th>代预订佣金</th>
        <th>平台收入</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data): ?>
	<?php $orderState = array('P' => '已支付','U' => '待消费' , 'S' => '已结算','R' => '退款中','C' => '已退款');?>
	<?php $page_total['total'] = 0;$page_total['inns_profit'] = 0; $page_total['profit'] = 0; $page_total['agent_commission'] = 0;?>
    <?php foreach($data as $key => $val):?>
        <tr>
            <td><a href="<?php echo $baseUrl.'order/view?oid='.$val['order_num'];?>" target="_blank"><?php echo $val['order_num'];?></td>
			<td><?php echo $val['inn_name'];?></td>
            <td class="tl"><?php echo $val['product_name'];?></td>
			<td><?php echo $val['quantity'];?></td>
			<td><?php echo $val['seller']?$val['seller']:'且游平台';?></td>
            <td><?php echo $orderState[$val['state']];?></td>
            <td><?php echo $val['contact'];?></td>
            <td><?php echo $val['telephone'];?></td>
            <td><?php echo date('Y-m-d H:i',$val['create_time']);?></td>
            <td><?php echo $val['settlement_time']?date('Y-m-d H:i',$val['settlement_time']):'';?></td>
            <td><i>¥<?php echo $val['total'];?></i></td>
            <td><cite>¥<?php echo $val['inns_profit'];?></cite></td>
            <td><cite>¥<?php echo $val['agent_commission'];?></cite></td>
            <td><cite>¥<?php echo $val['profit'];?></cite></td>
        </tr>
		<?php $page_total['total'] +=$val['total'];$page_total['inns_profit'] +=$val['inns_profit'];$page_total['profit'] +=$val['profit'];$page_total['agent_commission'] +=$val['agent_commission'];?>
    <?php endforeach;?>
		<tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right;color: #E02239;font-weight: bold;font-size: 18px;">合计：</td>
            <td><i>¥<?php echo number_format($page_total['total'],2);?></i></td>
            <td><cite>¥<?php echo $page_total['inns_profit'];?></cite></td>
            <td><cite>¥<?php echo $page_total['agent_commission'];?></cite></td>
            <td><cite>¥<?php echo $page_total['profit'];?></cite></td>
        </tr>
	<?php endif;?>
    </tbody>
</table>

<!--分页样式开始-->
<div class="pageBar clearfix">
    <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage']?></em>条</p>
    <div class="pages fr" id="page">
    </div>
</div>
<!--分页样式结束-->
<!--Date Picker-->
<script type="text/javascript" src="<?php echo $staticUrl;?>js/citySelect.js"></script>   
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){

		var setTimeButton = $('#setTimeButton');
		var startTime = $('#startTime');
		var endTime = $('#endTime');
		var downLoadCsv = $('#downLoadCsv');

        var innSelect = $('#inn');
		setTimeButton.click(function(){
			window.location.href="<?php echo $baseUrl;?>finance/qieyoubalance?"+getTime();
		});

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

		downLoadCsv.click(function(){
			window.location.href="<?php echo $baseUrl;?>finance/downloadBalance?sid=100"+getTime();
		});

        <!--分页-->
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());


    });
</script>