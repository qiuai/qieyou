<h3 class="headline">帖子的回复管理</h3>
<div class="tab">
	<ul class="clearfix">
		<li <?php if($is_del =='0') echo 'class="current"';?> ref="all"><a href="<?php echo $baseUrl.'forums/reply?is_del=0' ?>">回复</a></li>
		<li <?php if($is_del =='1') echo 'class="current"';?> ref="use"><a href="<?php echo $baseUrl.'forums/reply?is_del=1'?>">已屏蔽</a></li>
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
             创建时间：<label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('st'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $starttime?date('Y-m-d',$starttime):'';?>" name="starttime" title="请选择开始日期" id="startTime" class="Wdate" readOnly></label>
               <span class="mr10">至</span>
               <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('ed'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $endtime?date('Y-m-d',$endtime):'';?>" name="endtime" title="请选择结束日期" id="endTime" class="Wdate" readOnly></label>
               <label><input type="submit" value="查询" id="setTimeButton" class="buttonG"/></label>
               <div class="tips tips-ok" style="display: none;" id="timeTips"><i class="tips-ico"></i><p></p></div>
            </td>
        </tr>
        <tr>  
	     <td id="typeSelect">帖子类型：<label><input type="radio" class="radio" name="class" value="all" <?php if($class=='all') echo 'checked=""';?>>所有</label>
                <label><input type="radio" class="radio" name="class" value="wenda" <?php if($class =='wenda') echo 'checked=""';?>>问答</label>
                <label><input type="radio" class="radio" name="class" value="jianren" <?php if($class =='jianren') echo 'checked=""';?>>捡人</label>
                <label><input type="radio" class="radio" name="class" value="tour" <?php if($class =='tour') echo 'checked=""';?>>游记</label>
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
        <col class="wp8">
        <col class="wp8">
        <col class="wp8">
        <col class="wp10">
        <col class="wp15">
        <col class="wp8">
        <col class="wp8">
        <col class="wp8">
        <col class="wp8">
        <col class="wp8">
    </colgroup>
    <thead>
    <tr>
   		<th>ID</th>
   		<th>帖子类型</th>
        <th>所属部落</th>
        <th>用户ID</th>
        <th>回复谁</th>
        <th>回复时间</th>
        <th>回复内容</th>
        <th>图片(张)</th>
        <th>回复数</th>
        <th>点赞数</th>
        <th>获得打赏</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
    <?php $class=array('wenda'=>'问答','jianren'=>'捡人','tour'=>'游记')?>
		<?php foreach($data as $key => $val):?>
        <tr>	
            <td><?php echo $val['post_id'];?></td>
      	    <td><?php echo $class[$val['type']];?></td>
            <td><?php echo $val['group_name'];?></td>
            <td><?php echo $val['user_name'];?></td>
            <td><?php echo $val['reply_username'];?></td>
            <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
            <td><?php echo mb_substr($val['post_detail'],0,38,'utf-8');if(mb_strlen($val['post_detail'])>38) echo '...';?></td>
            <td><?php echo $val['pictures']?(substr_count($val['pictures'],',')+1):0;?></td>
      	    <td><?php echo $val['post_comments'];?></td>
            <td><?php echo $val['post_likes'];?></td>
            <td><?php echo $val['post_points'];?></td>
            <td>
				<a target="_blank" href="<?php echo $frontUrl.'forum/postdetail/'.$val['post_id'];?>">查看</a>&nbsp;&nbsp;   
				<a class="hide_post" href="javascript:void(0);" onclick="reply_delete(<?php echo $val['post_id'];?>)"><?php echo $val['is_delete']?'取消屏蔽':'屏蔽'?></a>
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
	var typeSelect = $('#typeSelect').find('input[name=class]');
    typeSelect.change(function(){
		var typeVal = $(this).val();
		var is_del = <?php echo $is_del;?>;
		window.location.href="<?php echo $baseUrl;?>forums/reply?&class="+typeVal+"&is_del="+is_del;
	});
	function reply_delete(post_id){
		$.ajax({
			type:"POST",
			dataType: "json",
			url: baseUrl+'forums/reply_delete',
			data: {post_id:post_id},
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
</script>