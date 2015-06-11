<h3 class="headline">系统消息管理
<span class="more"><a href="<?php echo $baseUrl.'message/add_message'?>"><input class="submit-mini addPqieyouBtn" type="submit" value="发布系统消息" /></a></span></h3>
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
             发布时间：<label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('st'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $starttime?date('Y-m-d',$starttime):'';?>" name="starttime" title="请选择开始日期" id="startTime" class="Wdate" readOnly></label>
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
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp20">
        <col class="wp20">
        <col class="wp10">
        <col class="wp10">  
        <col class="wp10">   
    </colgroup>
    <thead>
    <tr>
        <th>发布者ID</th>
        <th>消息类型</th>
        <th>推送对象</th>
        <th>发布时间</th>
        <th>发布内容</th>
        <!--<th>图片</th>-->
        <th>发送到(人)</th>
        <th>阅读(人)</th>  
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
    <?php $role=array('all'=>'所有人','user'=>'普通用户','group'=>'部落管理员','innholder'=>'商户');?>
	<?php foreach($data as $key => $val):?>
	<tr>	
		<td><?php echo $val['user_name']?></td>
		<td><?php echo $val['type']?></td> 
        <td><?php echo $role[$val['role']]?></td> 
		<td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
		<td><?php echo mb_substr($val['note'],0,38,'utf-8');if(mb_strlen($val['note'])>38) echo '...';?></td>
      <!--  <td><?php if($val['img']){?><img height="80" width="80" src="<?php echo $staticUrl.$val['img'];?>" /><?php }?></td>-->
		<td><?php echo $val['receive_num']?></td>
        <td><?php echo $val['look_num']?></td>	
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
	function is_provide(quan_id){
		$.ajax({
			type:"POST",
			dataType: "json",
			url: "<?php echo $baseUrl.'coupon/is_provide';?>",
			data: {quan_id:quan_id},
			success: function(data){
				if(data.code == 1){
                    setTimeout(function(){
					 window.location.href='<?php echo $baseUrl.'coupon';?>';
                    },500);
                }else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
		})
	}
	
	function is_del(quan_id){
		var msg = "确定要删除吗？"; 
		if (confirm(msg)==true){ 
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'coupon/is_del';?>",
				data: {quan_id:quan_id},
				success: function(data){
					if(data.code == 1){
						setTimeout(function(){
						 window.location.href='<?php echo $baseUrl.'coupon';?>';
						},500);
					}else{
						layer.alert(data.msg ,3,"提示");
					}
				}
			})
		}
	}
</script>