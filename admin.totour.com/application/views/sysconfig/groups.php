<h3 class="headline">部落管理
<span class="more"><a href="<?php echo $baseUrl.'sysconfig/groups_add'?>"><input class="submit-mini addPqieyouBtn" type="submit" value="新增部落" /></a></span></h3>
<div class="form filter mb10"> 
    <form method="get">
    <table class="form table-form">
        <tbody>
        <tr>    
        部落名称：
         <input type="text" value="" id="keyword" name="keyword" class="mr30">
		 <input class="buttonG" type="submit" id="keywordButton"  value="查询">  
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
        <col class="wp10">
        
        <col class="wp15">
        <col class="wp10">
        <col class="wp10">
        <col class="wp15">
        
        <col class="wp10">
    </colgroup>
    <thead>
    <tr>
     	<th>部落ID</th>
        <th>部落名称</th>
        <th>创建时间</th>
        <th>创建者</th> 
        
  		<th>部落介绍</th>
        <th>帖子数量</th>
        <th>关注人数</th>
        <th>操作</th>
        
        <th>推荐</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
		<?php foreach($data as $key => $val):?>
        <tr>	
            <td><?php echo $val['group_id'];?></td>
            <td><?php echo $val['group_name'];?></td>
            <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
            <td><?php echo $val['create_by'];?></td>
            
            <td><?php echo mb_substr($val['note'],0,38,'utf-8');if(mb_strlen($val['note'])>38) echo '...';?></td>
            <td><?php echo $val['group_topics'];?></td>
            <td><?php echo $val['members'];?></td>
            <td><a href="<?php echo $baseUrl.'sysconfig/edit_groupsInfo?id='.$val['group_id'];?>">编辑</a>&nbsp;
            <?php if($val['allow_del']==1){?><a href="javascript:void(0);" onclick="is_del(<?php echo $val['group_id'];?>)">删除</a><?php }?></td>
            <td><a href="javascript:void(0);" onclick="recommend(<?php echo $val['group_id'];?>)"><?php echo $val['is_recommend']?'取消推荐':'推荐到首页'?></a></td> 
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
	function is_del(group_id){
		if(confirm("确定要删除")){
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'sysconfig/is_del_group';?>",
				data: {group_id:group_id},
				success: function(data){
					if(data.code == 1){
						setTimeout(function(){
						 window.location.href='<?php echo $baseUrl.'sysconfig/groups';?>';
						},500);
					}else{
						layer.alert(data.msg ,3,"提示");
					}
				}
			})
		}
	}
	function recommend(group_id){
		$.ajax({
			type:"POST",
			dataType: "json",
			url: "<?php echo $baseUrl.'sysconfig/recommend_group';?>",
			data: {group_id:group_id},
			success: function(data){
				if(data.code == 1){
					setTimeout(function(){
					 window.location.href='<?php echo $baseUrl.'sysconfig/groups';?>';
					},500);
				}else{
					layer.alert(data.msg ,3,"提示");
				}
			}
		})

	}
</script>