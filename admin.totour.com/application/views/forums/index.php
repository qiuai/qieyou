<?php if($class =='wenda'){?>
<h3 class="headline">问答帖</h3>
<div class="tab">
	<ul class="clearfix">
		<li <?php if($is_del =='0') echo 'class="current"';?> ref="all"><a href="<?php echo $baseUrl.'forums?is_del=0' ?>">问答</a></li>
		<li <?php if($is_del =='1') echo 'class="current"';?> ref="use"><a href="<?php echo $baseUrl.'forums?is_del=1'?>">已屏蔽</a></li>
	</ul>
</div>
<div class="filter mb10">
    <form method="get">
    <table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
        <tbody>
        <tr>   
            <td>
            <input type="hidden" value="<?php echo $is_del;?>" id="is_del" name="is_del">
             所属部落：<input type="text" value="" id="keyword" name="keyword" class="mr30">
             发布时间：
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
     	<col class="wp5">
        <col class="wp8">
        <col class="wp8">
        <col class="wp8">
        
        <col class="wp8">
        <col class="wp15">
        <col class="wp8">
        <col class="wp5">
        
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">
        
        <col class="wp15">
    </colgroup>
    <thead>
    <tr>
    	<th>ID</th>
        <th>所属部落</th>
        <th>用户</th>
        <th>发布时间</th>
        
        <th>标题</th>
        <th>内容</th>
        <th>图片(张)</th>
        <th>标签</th>
        
        <th>浏览数</th>
        <th>回复数</th>
        <th>收藏数</th>
        <th>转发</th> 
        
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
		<?php foreach($data as $key => $val):?>
        <tr>	
            <td><?php echo $val['forum_id'];?></td>
            <td><?php echo $val['group_name'];?></td>
            <td><?php echo $val['user_name'];?></td>
            <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
            <td><?php echo $val['forum_name'];?></td>
            <td><?php echo mb_substr($val['note'],0,38,'utf-8');if(mb_strlen($val['note'])>38) echo '...';?></td>
            <td><?php echo $val['pictures']?(substr_count($val['pictures'],',')+1):0;?></td>
            <td><?php echo $val['tags']?></td>
            <td><?php echo $val['look'];?></td>
            <td><?php echo $val['comments'];?></td>
            <td><?php echo $val['favorites'];?></td>
            <td><?php echo $val['shares'];?></td>
            <td>
            <?php if($val['is_delete']==1){?>
            <a href="javascript:void(0);" onclick="is_delete(<?php echo $val['forum_id'];?>)"><?php echo $val['is_delete']?'取消屏蔽':'屏蔽'?></a>
            <?php }else{ ?>
            <a target="_blank" href="<?php echo $frontUrl.'forum/'.$val['forum_id'];?>">查看</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_delete(<?php echo $val['forum_id'];?>)"><?php echo $val['is_delete']?'取消屏蔽':'屏蔽'?></a>&nbsp;&nbsp;
            <a href="javascript:void(0);" onclick="is_top(<?php echo $val['forum_id'];?>)"><?php echo $val['is_top']?'取消置顶':'置顶'?></a>
            <?php }?>
            </td>
        </tr>
        <?php endforeach;?>
	<?php endif;?>
    </tbody>
</table>
<?php }?>
<?php if($class =='jianren'){?>
<h3 class="headline">捡人管理</h3>
<div class="tab">
	<ul class="clearfix">
		<li <?php if($is_del =='0') echo 'class="current"';?> ref="all"><a href="<?php echo $baseUrl.'forums?class=jianren&is_del=0' ?>">捡人</a></li>
		<li <?php if($is_del =='1') echo 'class="current"';?> ref="use"><a href="<?php echo $baseUrl.'forums?class=jianren&is_del=1'?>">已屏蔽</a></li>
	</ul>
</div>
<div class="filter mb10">
    <form method="get">
    <table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
        <tbody>
        <tr>  
            <td>
            <input type="hidden" value="<?php echo $is_del;?>" id="is_del" name="is_del">
             所属部落：<input type="text" value="" id="keyword" name="keyword" class="mr30">
             发布时间：
                <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('st'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $starttime?date('Y-m-d',$starttime):'';?>" name="starttime" title="请选择开始日期" id="startTime" class="Wdate" readOnly></label>
                <span class="mr10">至</span>
                <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('ed'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $endtime?date('Y-m-d',$endtime):'';?>" name="endtime" title="请选择结束日期" id="endTime" class="Wdate" readOnly></label>
                <input type="hidden" name="class" value="jianren" />
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
     	<col class="wp5">
        <col class="wp5">
        <col class="wp5">
        <col class="wp8">
        
        <col class="wp5">
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
        <col class="wp12">
        <col class="wp5">
    </colgroup>
    <thead>
    <tr>
   		<th>ID</th>
   	    <th>所属部落</th>
        <th>用户</th>
        <th>发布时间</th>
        
        <th>标题</th>
        <th>旅游线路</th>
        <th>出发时间</th>
        <th>旅游天数</th>
        
        <th>捡人说明</th>
        <th>图片(张)</th>
        <th>标签</th>
        <th>浏览数</th>
        
        <th>回复数</th>
        <th>收藏数</th>
        <th>转发</th> 
        <th>操作</th>
       <?php if(empty($is_del)){?> <th>推荐</th><?php }?>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
		<?php foreach($data as $key => $val):?>
        <tr>	
            <td><?php echo $val['forum_id'];?></td>
       	    <td><?php echo $val['group_name'];?></td>
            <td><?php echo $val['user_name'];?></td>
            <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
            <td><?php echo $val['forum_name'];?></td>
            <td><?php echo $val['line'];?></td>
            <td><?php echo date('Y-m-d',$val['start_time']);?></td>
            <td><?php echo $val['day']?></td>
            <td><?php echo mb_substr($val['note'],0,38,'utf-8');if(mb_strlen($val['note'])>38) echo '...';?></td>
            <td><?php echo $val['pictures']?(substr_count($val['pictures'],',')+1):0;?></td>
            <td><?php echo $val['tags']?></td>
            <td><?php echo $val['look'];?></td>
            <td><?php echo $val['comments'];?></td>
            <td><?php echo $val['favorites'];?></td>
            <td><?php echo $val['shares'];?></td>
            <td>
            <?php if($val['is_delete']==1){?>
            <a href="javascript:void(0);" onclick="is_delete(<?php echo $val['forum_id'];?>)"><?php echo $val['is_delete']?'取消屏蔽':'屏蔽'?></a>
            <?php }else{ ?>
            <a target="_blank" href="<?php echo $frontUrl.'forum/'.$val['forum_id'];?>">查看</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_delete(<?php echo $val['forum_id'];?>)"><?php echo $val['is_delete']?'取消屏蔽':'屏蔽'?></a>&nbsp;&nbsp;
            <a href="javascript:void(0);" onclick="is_top(<?php echo $val['forum_id'];?>)"><?php echo $val['is_top']?'取消置顶':'置顶'?></a>
            <?php }?>
            </td>
           <?php if(empty($is_del)){?>  <td>
            <a href="javascript:void(0);" onclick="recommend(<?php echo $val['forum_id'];?>)"><?php echo $val['is_recommend']?'取消推荐':'推荐到首页'?></a>
            </td> <?php }?>
        </tr>
        <?php endforeach;?>
	<?php endif;?>
    </tbody>
</table>
<?php }?>
<?php if($class =='tour'){?>
<h3 class="headline">游记管理</h3>
<div class="tab">
	<ul class="clearfix">
		<li <?php if($is_del =='0') echo 'class="current"';?> ref="all"><a href="<?php echo $baseUrl.'forums?class=tour&is_del=0' ?>">游记</a></li>
		<li <?php if($is_del =='1') echo 'class="current"';?> ref="use"><a href="<?php echo $baseUrl.'forums?class=tour&is_del=1'?>">已屏蔽</a></li>
	</ul>
</div>
<div class="filter mb10">
    <form method="get">
    <table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
        <tbody>
        <tr>        
            <td>
            <input type="hidden" value="<?php echo $is_del;?>" id="is_del" name="is_del">
            所属部落：<input type="text" value="" id="keyword" name="keyword" class="mr30">
            发布时间：
               <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('st'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $starttime?date('Y-m-d',$starttime):'';?>" name="starttime" title="请选择开始日期" id="startTime" class="Wdate" readOnly></label>
               <span class="mr10">至</span>
               <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('ed'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $endtime?date('Y-m-d',$endtime):'';?>" name="endtime" title="请选择结束日期" id="endTime" class="Wdate" readOnly></label>
                <input type="hidden" name="class" value="tour" />
                <label><input type="submit" value="查询" id="setTimeButton" class="buttonG"/></label>
                <div class="tips tips-ok" style="display: none;" id="timeTips"><i class="tips-ico"></i><p></p></div>
            </td>
        </tr>
          <tr>        
            <td id='typeSelect'>
            帖子来源：
            <label><input type="radio" class="radio" name="user_from" value="all" <?php if($user_from=='all') echo 'checked=""';?>>全部</label>
            <label><input type="radio" class="radio" name="user_from" value="min_user" <?php if($user_from =='min_user') echo 'checked=""';?>>小号产生</label>
            <label><input type="radio" class="radio" name="user_from" value="user" <?php if($user_from =='user') echo 'checked=""';?>>用户产生</label> 
            </td>
        </tr>
        </tbody>
    </table>
    </form>
</div>
<table class="orderList table table-border table-odd userLog">
    <colgroup>
     	<col class="wp6">
        <col class="wp6">
        <col class="wp8">
        <col class="wp8">
        <col class="wp8">
        <col class="wp15">
        <col class="wp6">
        <col class="wp6">
        <col class="wp6">
        <col class="wp6">
        <col class="wp6">
        <col class="wp6">
        <col class="wp13">
    </colgroup>
    <thead>
    <tr>
    	<th>ID</th>
   	    <th>所属部落</th>
        <th>用户</th>
        <th>发布时间</th>
        <th>标题</th>
        <th>内容</th>
        <th>图片(张)</th>
        <th>标签</th>
        <th>浏览数</th>
        <th>回复数</th>
        <th>收藏数</th>
        <th>转发</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
		<?php foreach($data as $key => $val):?>
        <tr>	
      	    <td><?php echo $val['forum_id'];?></td>
            <td><?php echo $val['group_name'];?></td>
            <td><?php echo $val['user_name'];?></td>
            <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
            <td><?php echo $val['forum_name'];?></td>
            <td><?php echo mb_substr($val['note'],0,38,'utf-8');if(mb_strlen($val['note'])>38) echo '...';?></td>
            <td><?php echo $val['pictures']?(substr_count($val['pictures'],',')+1):0;?></td>
            <td><?php echo $val['tags']?></td>
            <td><?php echo $val['look'];?></td>
            <td><?php echo $val['comments'];?></td>
            <td><?php echo $val['favorites'];?></td>
            <td><?php echo $val['shares'];?></td>
           <td>
            <?php if($val['is_delete']==1){?>
            <a href="javascript:void(0);" onclick="is_delete(<?php echo $val['forum_id'];?>)"><?php echo $val['is_delete']?'取消屏蔽':'屏蔽'?></a>
            <?php }else{ ?>
            <a target="_blank" href="<?php echo $frontUrl.'forum/'.$val['forum_id'];?>">查看</a><a style="margin-left:10px" href="javascript:void(0);" onclick="is_delete(<?php echo $val['forum_id'];?>)"><?php echo $val['is_delete']?'取消屏蔽':'屏蔽'?></a>
           <a style="margin-left:10px" href="javascript:void(0);" onclick="is_top(<?php echo $val['forum_id'];?>)"><?php echo $val['is_top']?'取消置顶':'置顶'?></a>
           <a style="margin-left:10px" href="<?php echo $baseUrl.'forums/edit_forum?forum_id='.$val['forum_id'];?>">编辑</a>
            <?php }?>
            </td>
        <?php endforeach;?>
	<?php endif;?>
    </tbody>
</table>
<?php }?>

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
	var typeSelect = $('#typeSelect').find('input[name=user_from]');
    typeSelect.change(function(){
		var is_del = <?php echo $is_del;?>;
		var typeVal = $(this).val();
		window.location.href="<?php echo $baseUrl;?>forums?class=tour&user_from="+typeVal+"&is_del="+is_del;
	});
	var forumClass = '<?php echo $class;?>';
	function is_delete(forum_id){
		$.ajax({
			type:"POST",
			dataType: "json",
			url: baseUrl+'forums/is_delete',
			data: {forum_id:forum_id},
			success: function(data){
				if(data.code == 1){
					setTimeout(function(){
					 window.location.reload();
					},500);
				}else{
					layer.alert(data.msg ,3,"提示");
				}
			}
		})
	}
	function is_top(forum_id){
		$.ajax({
			type:"POST",
			dataType: "json",
			url: baseUrl+'forums/is_top',
			data: {forum_id:forum_id},
			success: function(data){
				if(data.code == 1){
					setTimeout(function(){
					 window.location.reload();
					},500);
				}else{
					layer.alert(data.msg ,3,"提示");
				}
			}
		})
	}
	function recommend(forum_id){
		$.ajax({
			type:"POST",
			dataType: "json",
			url: "<?php echo $baseUrl.'forums/recommend';?>",
			data: {forum_id:forum_id},
			success: function(data){
				if(data.code == 1){
					setTimeout(function(){
					 window.location.href='<?php echo $baseUrl.'forums?class=jianren';?>';
					},500);
				}else{
					layer.alert(data.msg ,3,"提示");
				}
			}
		})

	}
	
</script>