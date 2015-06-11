<?php if($class =='banner'){?>
<h3 class="headline">Banner图片
<span class="more"><a href="<?php echo $baseUrl.'sysconfig/add_banner'?>"><input class="submit-mini addPqieyouBtn" type="submit" value="推荐Banner图片" /></a></span>
</h3>
<table class="orderList table table-border table-odd userLog">
    <colgroup>
        <col class="wp10">
        <col class="wp10">
        <col class="wp15">
        <col class="wp15">
        <col class="wp15">
        <col class="wp15">
        <col class="wp20">
        <col class="wp30">
    </colgroup>
    <thead>
    <tr>
        <th>排序</th>
        <th>Banner图片</th>
        <th>链接地址</th>
        <th>备注</th>
        <th>上架时间</th>
        <th>已上线时间</th>
        <th>排序</th> 
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
		<?php foreach($data as $key => $val):?>
        <tr>	
            <td><?php echo $val['sort'];?></td>
            <td><img height="50" width="100" src="<?php echo $staticUrl.$val['img'];?>" /></td>
            <td><a target="_blank" href="<?php echo $val['link'];?>"><?php echo $val['link'];?></a></td>
            <td><?php echo $val['note'];?></td>
            <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
            <td><?php echo $val['up_time'];?></td>
            <td><a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'up')">上</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'down')">下</a></td>
            <td><a href="<?php echo $baseUrl.'sysconfig/editinfo?id='.$val['id'];?>">编辑</a>&nbsp;<a href="javascript:void(0);" onclick="recommend(<?php echo $val['id'];?>)">删除</a></td>
        </tr>
        <?php endforeach;?>
	<?php endif;?>
    </tbody>
</table>
<?php }?>
<?php if($class =='group'){?>
<h3 class="headline">部落推荐
<span class="more"><a href="<?php echo $baseUrl.'sysconfig/add_re_group'?>" ><input class="submit-mini addPqieyouBtn" type="submit" value="推荐部落" /></a></span>
</h3>
<table class="orderList table table-border table-odd userLog">
    <colgroup>
        <col class="w5">
        <col class="wp10">
        <col class="wp10">
        <col class="wp25">
        <col class="wp10">
        <col class="wp10">
        <col class="wp20">
        <col class="wp10">
    </colgroup>
    <thead>
    <tr>
        <th>排序</th>
        <th>部落名称</th>
        <th>部落创建者</th>
        <th>部落介绍</th>
        <th>帖子数量</th>
        <th>关注人数</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
		<?php foreach($data as $key => $val):?>
        <tr>	
            <td><?php echo $val['sort'];?></td>
            <td><?php echo $val['group_name'];?></td>
            <td><?php echo $val['user_mobile'];?></td>
            <td><?php echo mb_substr($val['note'],0,38,'utf-8');if(mb_strlen($val['note'])>38) echo '...';?></td>
            <td><?php echo $val['group_topics'];?></td>
            <td><?php echo $val['members'];?></td>
            <td><a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'top')">置顶</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'up')">上移</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'down')">下移</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'bottom')">置底</a></td>
            <td>
            <input id="subbtn" onclick="recommend(<?php echo $val['id'];?>)" class="buttonG-mini applyButton"  type="button" value="取消推荐" />
            </td>
        </tr>
        <?php endforeach;?>
	<?php endif;?>
    </tbody>
</table>
<?php }?>
<?php if($class =='product'){?>
<h3 class="headline">商品推荐
<span class="more"><a href="<?php echo $baseUrl.'sysconfig/add_re_product'?>" ><input class="submit-mini addPqieyouBtn" type="submit" value="推荐商品" /></a></span>
</h3>
<table class="orderList table table-border table-odd userLog">
    <colgroup>
        <col class="wp5">
        <col class="wp10">
        <col class="wp5">
        <col class="wp5">
        
        <col class="wp5">
        <col class="wp10">
        <col class="wp5">
        <col class="wp5">
        
        <col class="wp10">   
        <col class="wp10">   
        <col class="wp10">   
        <col class="wp10">
    </colgroup>
    <thead>
    <tr>
        <th>排序</th>
        <th>商品名称</th>
        <th>商品类别</th>
        <th>区域</th>
        
        <th>街道</th>
        <th>商户名称</th>
        <th>当前状态</th>
        <th>可售数量</th>
        
        <th>单价</th>
        <th>链接</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
    <?php $category = array('1'=>'客栈酒店','2'=>'美食饕餮','3'=>'娱乐休闲','4'=>'当地行','5'=>'当地游','6'=>'当地购','7'=>'旅游险');?>
		<?php foreach($data as $key => $val):?>
        <tr>	
            <td><?php echo $val['sort'];?></td>
            <td><?php echo $val['product_name'];?></td>
            <td><?php echo $category[$val['category']];?></td>
            <td><?php echo $val['dest_name'];?></td>
            <td><?php echo $val['local_name'];?></td>
            <td><?php 
				if(in_array($session['role'],array('regionmanager','cservice','admin'))) 
					echo '<a class="viewInnsInfo" href="javascript:void(0);" ref="'.$val['inn_id'].'">'.$val['inn_name'].'</a>';
				else 
					echo $val['inn_name'];
			?></td>
            <td><?php echo $val['state']=='Y'?'<cite>在售</cite>':$val['state']=='T'?'<cite>团购中</cite>':'<em>停售</em>';?></td>
            <td><?php echo $val['quantity'].'&nbsp;&nbsp;(<cite>'.($val['quantity']+$val['bought_count'])
.'</cite>)';?></td>
            <td><cite>¥<?php echo number_format($val['price'],2);?></cite></td> 
            <td><a target="_blank" href="<?php echo $frontUrl.'item/'.$val['product_id']?>"><?php echo $frontUrl.'item/'.$val['product_id']?></a></td>
            <td><a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'top')">置顶</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'up')">上移</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'down')">下移</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'bottom')">置底</a></td>
            
          <td> <input id="subbtn" onclick="recommend(<?php echo $val['id'];?>)" class="buttonG-mini applyButton"  type="button" value="取消推荐" /></td>
        </tr>
        <?php endforeach;?>
	<?php endif;?>
    </tbody>
</table>
<?php }?>
<?php if($class =='jianren'){?>
<h3 class="headline">捡人推荐
<span class="more"><a href="<?php echo $baseUrl.'sysconfig/add_re_jianren'?>" ><input class="submit-mini addPqieyouBtn" type="submit" value="推荐捡人" /></a></span>
</h3>
<table class="orderList table table-border table-odd userLog">
    <colgroup>
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        
        <col class="wp10">
        <col class="wp5">
        <col class="wp10">
        <col class="wp15">
        
        <col class="wp10">
    </colgroup>
    <thead>
    <tr>
        <th>排序</th>
        <th>用户ID</th>
        <th>发布时间</th>
        <th>旅游线路</th>
        
        <th>出发时间</th>
        <th>旅游天数</th>
        <th>捡人说明</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
		<?php foreach($data as $key => $val):?>
        <tr>	
            <td><?php echo $val['sort'];?></td>
            <td><?php echo $val['user_name'];?></td>
            <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
            <td><?php echo $val['line'];?></td>
            <td><?php echo date('Y-m-d H:i:s',$val['start_time']);?></td>
            <td><?php echo $val['day'];?></td>
            <td><?php echo mb_substr($val['note'],0,38,'utf-8');if(mb_strlen($val['note'])>38) echo '...';?></td>
             <td><a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'top')">置顶</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'up')">上移</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'down')">下移</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="is_sort(<?php echo $val['id'];?>,'bottom')">置底</a></td>
           
          <td><input id="subbtn" onclick="recommend(<?php echo $val['id'];?>)" class="buttonG-mini applyButton"  type="button" value="取消推荐" /></td> 
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
	
	function is_sort(id,action){
		var type='<?php echo $class;?>';
		$.ajax({
			type:"POST",
			dataType: "json",
			url: "<?php echo $baseUrl.'sysconfig/is_sort';?>",
			data: {id:id,action:action,type:type},
			success: function(data){
				if(data.code == 1){
					setTimeout(function(){
					 window.location.href='<?php echo $baseUrl.'sysconfig?class='.$class;?>';
					},500);
				}else{
					layer.alert(data.msg ,3,"提示");
				}
			}
		})
	}
	function recommend(id){
		if(confirm("确定要删除")){
			var type='<?php echo $class;?>';
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'sysconfig/recommend';?>",
				data: {id:id,type:type},
				success: function(data){
					if(data.code == 1){
						setTimeout(function(){
						 window.location.href='<?php echo $baseUrl.'sysconfig?class='.$class;?>';
						},500);
					}else{
						layer.alert(data.msg ,3,"提示");
					}
				}
			})
		}
	}
	
</script>