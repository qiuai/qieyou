<h3 class="headline">目的地商户</h3>
<div class="filter mb20">
    <div class="form p10-20">
		按区域查找：
		<?php if($session['role'] == 'admin'):?>
		<label>
			<select id="province" name="province">
			</select>
		</label>
		<label>
			<select name="city" id="city">
			</select>
		</label>
		<?php else:?>
		<label style="display:none;">
			<select id="province" name="province">
			</select>
		</label>
		<label style="display:none;">
			<select name="city" id="city">
			</select>
		</label>
		<?php endif;?>
		<label>
			<select name="dest_id" id="dest">
				<option value="0">所有区域</option>
				<?php foreach($localArr['dest'] as $key => $row):?>
					<option <?php if($row['dest_id'] == $destInfo['dest_id']) echo 'selected="selected"';?> value="<?php echo $row['dest_id']?>"><?php echo $row['dest_name'];?></option>
				<?php endforeach;?>
			</select>
		</label>
		<label>
			<select name="local_id" id="local">
				<option value="0">所有街道</option>
				<?php foreach($localArr['local'] as $key => $row):?>
					<option <?php if($row['local_id'] == $destInfo['local_id']) echo 'selected="selected"';?> value="<?php echo $row['local_id']?>"><?php echo $row['local_name'];?></option>
				<?php endforeach;?>
			</select>
		</label>
	</div>
</div>
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp5">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp25">
        <col class="wp20">
        <col class="wp15">
    </colgroup>
    <thead>
    <tr>
        <th>序号</th>
        <th>区域</th>
        <th>街道</th>
        <th>商户名称</th>
        <th>商户老板</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($destInnsInfo['data'] as $key => $val):?>
        <tr>
            <td><?php echo $val['inn_id'];?></td>
            <td><?php echo $val['dest_name'];?></td>
            <td><?php echo $val['local_name'];?></td>
            <td><?php echo $val['inn_name'];?></td>
            <td><?php echo $val['user_name'];?></td>
            <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
            <td><a href="javascript:void(0);" class="viewInnsInfo" ref="<?php echo $val['inn_id'];?>">查看</a>
				<a href="<?php echo $baseUrl.'inns/editinfo?sid='.$val['inn_id'];?>" target="_blank">编辑</a>
				<a href="<?php echo $baseUrl.'product?sid='.$val['inn_id'];?>">商品管理</a>
			</td>
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
<!--分页样式结束-->
<script type="text/javascript" src="<?php echo $staticUrl;?>js/citySelect.js"></script>
<script type="text/javascript">
    $(function(){
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());

        var currentProvince = "<?php echo $destInfo['province'];?>";
        var currentCity = "<?php echo $destInfo['city'];?>";
        var destSelect = $('#dest');
        var localSelect = $('#local');

        $.initLocalSelect(currentProvince,currentCity);

        destSelect.change(function(){
            var destVal = $(this).val();
            if (destVal!=''){
                window.location.href="<?php echo $baseUrl;?>destination/searchDestInns?tid="+destVal;
            }
        });

        localSelect.change(function(){
            var localVal = $(this).val();
            if (localVal!='0'){
                window.location.href="<?php echo $baseUrl;?>destination/searchDestInns?lid="+localVal;
            }
			else{
				var destVal = $("#dest").val();
                window.location.href="<?php echo $baseUrl;?>destination/searchDestInns?tid="+destVal;
			}
        });
    });
</script>