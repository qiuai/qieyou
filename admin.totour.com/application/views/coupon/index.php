<h3 class="headline">抵用券管理
<span class="more"><a href="<?php echo $baseUrl.'coupon/add_coupon'?>"><input class="submit-mini addPqieyouBtn" type="submit" value="添加抵用券" /></a></h3>

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
            <td>
             抵用券名称：<input type="text" value="" id="keyword" name="keyword" class="mr30">
             创建时间：<label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('st'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $starttime?date('Y-m-d',$starttime):'';?>" name="starttime" title="请选择开始日期" id="startTime" class="Wdate" readOnly></label>
               <span class="mr10">至</span>
             <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('ed'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $endtime?date('Y-m-d',$endtime):'';?>" name="endtime" title="请选择结束日期" id="endTime" class="Wdate" readOnly></label>
             <label><input type="submit" value="查询" id="setTimeButton" class="buttonG"/></label>
              <div class="tips tips-ok" style="display: none;" id="timeTips"><i class="tips-ico"></i><p></p></div>
            </td>
        </tr>
        <tr>  
	     <td id="typeSelect">状态：<label><input type="radio" class="radio" name="status" value="all" <?php if($status=='all') echo 'checked=""';?>>全部</label>
                <label><input type="radio" class="radio" name="status" value="N" <?php if($status =='N') echo 'checked=""';?>>待发放</label>
                <label><input type="radio" class="radio" name="status" value="Y" <?php if($status =='Y') echo 'checked=""';?>>已发放</label>
                <label><input type="radio" class="radio" name="status" value="Z" <?php if($status =='Z') echo 'checked=""';?>>已过期</label>
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
        <col class="wp5">
        
        <col class="wp5">
        <col class="wp5">
        <col class="wp10">  
        <col class="wp5">
        
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">
        <col class="wp15">
    </colgroup>
    <thead>
    <tr>
        <th>抵用券名称</th>
        <th>创建者</th>
        <th>创建时间</th>
        <th>面额(元)</th>
        
        <th>张数</th>
        <th>总额</th>
        <th>有效期</th>
        <th>已领取张数</th>
        
        <th>剩余张数</th>
        <th>使用张数</th>
        <th>使用总额(元)</th>
        <th>使用率(%)</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
	<?php foreach($data as $key => $val):?>
	<tr>	
		<td><?php echo $val['quan_name'];?></td>
		<td><?php echo $val['user_name'];?></td>
		<td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td> 
		<td><?php echo $val['amount'];?></td>
        
		<td><?php echo $val['total']?$val['total']:'-';?></td>
        <td><?php echo $val['total']?$val['amount']*$val['total']:'-';?></td>
		<td><?php echo date('Y-m-d',$val['end_time']);?></td>
        <td><?php echo $val['quantity'];?></td>
        
		<td><?php echo $val['total']?$val['total']-$val['quantity']:'-';?></td>
        <td><?php echo $val['use_num']?$val['use_num']:'0';?></td>
		<td><?php echo $val['amount']*$val['use_num'];?></td>
		<td><?php echo $val['use_liu']?$val['use_liu'].'%':'-';//$val['total']?sprintf("%.2f", ($val['quantity']/$val['total'])*100).'%':'-';?></td>
        <td><?php if($val['end_time']>time()){
			if($val['is_public']==0){ 
				echo '待发放';
	        }elseif($val['is_public']==1){
				echo '已发放';
		    }
	    }else{
			echo "<span style='color:red'>已过期</span>";
		}?></td>
        <td>
   		<?php if($val['end_time']>time()){
			if($val['is_public']==0){ ?>
				<a href="javascript:void(0);" onclick="is_provide(<?php echo $val['quan_id'];?>)">发放</a>&nbsp;
				<a href="<?php echo $baseUrl.'coupon/edit_coupon?quan_id='.$val['quan_id'];?>">编辑</a>&nbsp;
				<a href="javascript:void(0);" onclick="is_del(<?php echo $val['quan_id'];?>)">删除</a>
	    <?php }elseif($val['is_public']==1){
				echo '-';
		    }
	    }else{
			echo '-';
		}?>
        </td>
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
	var typeSelect = $('#typeSelect').find('input[name=status]');
    typeSelect.change(function(){
		var typeVal = $(this).val();
		window.location.href="<?php echo $baseUrl;?>coupon?status="+typeVal;
	});
	function is_provide(quan_id){
		var msg = "确定要发放吗？"; 
		if (confirm(msg)==true){ 
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