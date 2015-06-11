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
            <td class="leftLabel">时间区间：</td>
            <td>
                <label><input type="text" id="startTime" onfocus="WdatePicker({doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $starttime;?>" name="st" title="请选择开始日期" class="Wdate"></label>
                <span class="mr10">至</span>
                <label><input type="text" id="endTime" onfocus="WdatePicker({doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $endtime;?>" name="ed" title="请选择结束日期" class="Wdate"></label>
                <label><input type="submit" value="查询" class="buttonG"/></label>
                <div class="tips tips-ok" style="display: none;" id="timeTips"><i class="tips-ico"></i><p></p></div>
            </td>
        </tr>
        <!--<tr>
            <td class="leftLabel">用户ID：</td>
            <td>
                <label><input type="text" value="" class="w200" name="name"/></label>
            </td>
        </tr>-->
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
	<?php foreach($data as $key => $val):?>
	<tr>
		<td class="<?php echo $val['event_level'];?>"><strong><?php echo $event_level[$val['event_level']];//事件级别：C: 普通 I: 敏感 D: 危险 U: 高危险?></strong></td>
		<td><?php echo $users[$val['user_id']];?></td>
		<td><?php echo $val['ip_addr'];?></td>
		<td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td> 
		<td class="tl"><?php echo $val['note'];?></td>
	</tr>
	<?php endforeach;?>
    </tbody>
</table>
<!--分页样式开始-->
<div class="pageBar clearfix">
    <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage']?></em>条</p>
    <div class="pages fr" id="page">
    </div>
</div>
<div class="viewInnsInfoDom">
    <p class="tc">正在加载...</p>
</div>
<script type="text/javascript">
    $(function(){	
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());
    })
</script>
<!--分页样式结束-->
<!--Date Picker-->
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){

        /**开始时间与结束时间选择判断**/
        $(".Wdate").on("blur",function(){
            var thisEle = $(this);
            var startTime = datetimeToUnix($('#startTime').val()+" 00:00:00");
            var endTime = datetimeToUnix($('#endTime').val()+" 00:00:00");
            var timeTips = $("#timeTips");
            var timeSubmit = $("#timeSubmit");
            if(startTime>endTime){
                if (thisEle.attr("id")=="startTime"){
                    timeTips.show().addClass("tips-warn").html("<i class='tips-ico'></i><p>开始日期须小于结束日期</p>").fadeOut(5000);
                    $('#endTime').val(getMoreDate(1,$("#startTime").val()));
                }
                else{
                    timeTips.show().addClass("tips-warn").html("<i class='tips-ico'></i><p>结束日期须大于开始日期</p>").fadeOut(5000);
                    $('#startTime').val(getMoreDate(-1,$("#endTime").val()));
                }
            }
            else{
                timeTips.hide();
            }

        });
    });
</script>