<h3 class="headline">用户日志</h3>
<div class="filter mb20">
 <form method="get">
    <table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
        <tbody>
        <tr>   
            <td>
       		   事件级别： <label>
                    <select name="type" id="type">
                        <option <?php if($type =='all') echo 'selected=""';?> value="all">全部</option>
	                    <option <?php if($type =='C') echo 'selected=""';?> value="C">普通</option>
						<option <?php if($type =='I') echo 'selected=""';?> value="I">敏感</option>
						<option <?php if($type =='D') echo 'selected=""';?> value="D">危险</option>
						<option <?php if($type =='U') echo 'selected=""';?> value="U">高危险</option>
                    </select>
                </label>
                   时间区间：
                  <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('st'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $starttime?date('Y-m-d',$starttime):'';?>" name="starttime" title="请选择开始日期" id="startTime" class="Wdate" readOnly></label>
                <span class="mr10">至</span>
                <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('ed'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $endtime?date('Y-m-d',$endtime):'';?>" name="endtime" title="请选择结束日期" id="endtime" class="Wdate" readOnly></label>
				<div class="tips tips-ok" style="display: none;" id="timeTips"><i class="tips-ico"></i><p></p></div> 
                <label><input class="buttonG" type="submit" id="setTimeButton" value="查询"></label>
            </td>
        </tr>
        </tbody>
    </table>
    </form>
</div>
<table class="orderList table table-border table-odd userLog">
    <colgroup>
        <col class="wp10">
        <col class="wp10">
        <col class="wp15">
        <col class="wp15">
        <col class="wp50">
    </colgroup>
    <thead>
    <tr>
        <th>事件级别</th>
        <th>用户名</th>
        <th>使用IP</th>
        <th>操作时间</th>
        <th>操作记录</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
	<?php $event_level = array('C'=>'普通','I'=>'敏感','D'=>'危险','U'=>'高危险');?>
	<?php foreach($data as $key => $val):?>
	<tr>
		<td class="<?php echo $val['event_level'];?>"><strong><?php echo $event_level[$val['event_level']];//事件级别：C: 普通 I: 敏感 D: 危险 U: 高危险?></strong></td>
		<td><?php echo $users[$val['user_id']];?></td>
		<td><?php echo $val['ip_addr'];?></td>
		<td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td> 
		<td class="tl"><?php echo $val['note'];?></td>
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
	var setTimeButton = $('#setTimeButton');
	var startTime = $('#starttime');
	var endTime = $('#endtime');

	$("#type").change(function(){
		var typeVal = $(this).val();
		window.location.href="<?php echo $baseUrl;?>sysmanage/userlog?type="+typeVal+getTime();
	});

	function getTime()
	{
		var url ='';
		var st = startTime.val();
		var ed = endTime.val();
		var start = st?datetimeToUnix(st+" 00:00:00"):'';
		var end = ed?datetimeToUnix(ed+" 23:59:59"):'';
		url = (st?"&starttime="+start:"")+(ed?"&endtime="+end:"");
		return url;
	}
	setTimeButton.click(function(){
		var type = $("#type").val();
		window.location.href="<?php echo $baseUrl;?>sysmanage/userlog?type="+ $("#type").val()+getTime();
	});
</script>