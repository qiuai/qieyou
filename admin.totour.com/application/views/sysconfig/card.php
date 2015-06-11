<h3 class="headline">首页设置</h3>
<div class="tab">
	<ul class="clearfix">
		<li <?php if($class =='banner') echo 'class="current"';?> ref="all"><a href="<?php echo $baseUrl;?>sysconfig?class=banner">banner管理</a></li>
		<li <?php if($class =='group') echo 'class="current"';?> ref="unpaid"><a href="<?php echo $baseUrl;?>sysconfig?class=group">圈子推荐</a></li>
		<li <?php if($class =='talent') echo 'class="current"';?> ref="paid"><a href="<?php echo $baseUrl;?>sysconfig?class=talent">达人推荐</a></li>
		<li <?php if($class =='product') echo 'class="current"';?> ref="waiting"><a href="<?php echo $baseUrl;?>sysconfig?class=product">商品推荐</a></li>
		<li <?php if($class =='jianren') echo 'class="current"';?> ref="finished"><a href="<?php echo $baseUrl;?>sysconfig?class=jianren">捡人推荐</a></li>
	</ul>
</div>
<?php if($class =='group'){?>
<table class="orderList table table-border table-odd userLog">
    <colgroup>
        <col class="wp10">
        <col class="wp10">
        <col class="wp15">
        <col class="wp15">
        <col class="wp15">
        <col class="wp15">
        <col class="wp50">
    </colgroup>
    <thead>
    <tr>
        <th>排序</th>
        <th>圈子名称</th>
        <th>圈子管理员</th>
        <th>关注人数</th>
        <th>话题人数</th>
        <th>圈子介绍</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
		<?php foreach($data as $key => $val):?>
        <tr>	
            <td><?php echo $val['sort'];?></td>
            <td><?php echo $val['name'];?></td>
            <td><?php echo $val['administrators'];?></td>
            <td><?php echo $val['like_num'];?></td>
            <td><?php echo $val['topic_num'];?></td>
            <td><?php echo $val['note'];?></td>
            <td><input id="subbtn" onclick="recommend('1')" class="buttonG-mini applyButton"  type="button" value="推荐到首页" ref="<?php echo $val['id'];?>" /></td>
        </tr>
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
</script>