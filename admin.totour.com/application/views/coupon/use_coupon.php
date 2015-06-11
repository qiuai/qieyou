<h3 class="headline">抵用券管理</h3>
<div class="tab">
	<ul class="clearfix">	
		<li <?php if($state =='all') echo 'class="current"';?> ref="all"><a href="<?php echo $baseUrl.'coupon' ?>">发放记录</a></li>
		<li <?php if($state =='use') echo 'class="current"';?> ref="use"><a href="<?php echo $baseUrl.'coupon/use_coupon'?>">使用记录</a></li>
	</ul>
</div>
<div class="filter mb20">
    <form method="get">
    <table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <td class="leftLabel">使用时间：</td>
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
    </form>
</div>
<table class="orderList table table-border table-odd userLog">
   <colgroup>
        <col class="wp15"/>
        <col class="wp15"/>
		<col class="wp20"/>
        <col class="wp20"/>
        <col class="wp20"/>
        <col class="wp10"/> 
    </colgroup>
    <thead>
    <tr>
        <th>用户ID</th>
		<th>抵用券名称</th>  
        <th>领取时间</th>
        <th>使用时间</th>
        <th>订单号</th>
        <th>面额(元)</th>    
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
	<?php foreach($data as $key => $val):?>
	<tr>	
		<td><?php echo $val['user_name'];?></td>
		<td><?php echo $val['quan_name'];?></td>
        <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
        <td><?php echo empty($val['use_time'])?0:date('Y-m-d H:i:s',$val['use_time']);?></td>
        <td><?php echo $val['order_number'];?></td>
        <td><?php echo $val['amount'];?></td>
	</tr>
	<?php endforeach;?>
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
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){	
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());
    })
</script>