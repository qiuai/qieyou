<h3 class="headline">积分管理</h3>
<div class="filter mb10">
    <form method="get">
    <table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
        <tbody>
        <tr>  
            <td class="leftLabel">时间区间：</td>
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
<div class="filter mb10">
   总和：<?php echo $starttime?date('Y-m-d',$starttime):'';?>到<?php echo $endtime?date('Y-m-d',$endtime):'';?>&nbsp;&nbsp;共送出<span style="color:#09F"><?php echo $send_point?$send_point:0;?> </span>社区积分，共扣除<span style="color:#F00"> <?php echo $reduce_point?$reduce_point:0;?></span>社区积分
</div>
<table class="orderList table table-border table-odd userLog">
    <colgroup>
        <col class="wp20">
        <col class="wp20">
        <col class="wp20">
        <col class="wp15">
        <col class="wp15">
    </colgroup>
    <thead>
    <tr>
        <th>日期</th>
		<th>总发放积分</th>
        <th>总使用积分</th>
		<th>获取积分人数</th>
        <th>使用积分人数</th> 
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
		<?php foreach($data as $key => $val):?>
        <tr>	   
            <td><?php echo date('Y-m-d',$val['create_time']);?></td>
            <td><?php echo $val['send_point'];?></td>
            <td><?php echo $val['use_point'];?></td>
            <td><?php echo $val['get_user'];?></td>
            <td><?php echo $val['use_user'];?></td>
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