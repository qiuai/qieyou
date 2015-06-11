<h3 class="headline">商品列表
	<?php if($destInfo['inn_id']):?><span class="more"><input class="submit-mini addProductBtn" type="submit" ref="<?php echo $destInfo['inn_id'];?>" value="新增商品" /></span>
	<?php endif;?>
</h3>
<div class="filter mb20">
    <table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
		<tbody>
    <div class="form p10-20 ml5">
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
		<label>
			<select name="inn_id" id="inn">
				<option value="0">所有商户</option>
				<?php if($Innlist):?>
				<?php foreach($Innlist as $key => $row):?>
					<option <?php if($row['inn_id'] == $destInfo['inn_id']) echo 'selected="selected"';?> value="<?php echo $row['inn_id']?>"><?php echo $row['inn_name'];?></option>
				<?php endforeach;?>
				<?php endif;?>
			</select>
		</label>
	</div>
		<tr>
            <td class="leftLabel">商品类别：</td>
            <td id="typeSelect">
				<label><input type="radio" class="radio" name="cid" value="0" <?php if($search['cid'] =='0') echo 'checked=""';?>>所有</label>
                <label><input type="radio" class="radio" name="cid" value="1" <?php if($search['cid'] =='1') echo 'checked=""';?>>客栈酒店</label>
                <label><input type="radio" class="radio" name="cid" value="2" <?php if($search['cid'] =='2') echo 'checked=""';?>>美食饕餮</label>
                <label><input type="radio" class="radio" name="cid" value="3" <?php if($search['cid'] =='3') echo 'checked=""';?>>娱乐休闲</label>
				<label><input type="radio" class="radio" name="cid" value="4" <?php if($search['cid'] =='4') echo 'checked=""';?>>当地行</label>
                <label><input type="radio" class="radio" name="cid" value="5" <?php if($search['cid'] =='5') echo 'checked=""';?>>当地游</label>
                <label><input type="radio" class="radio" name="cid" value="6" <?php if($search['cid'] =='6') echo 'checked=""';?>>当地购</label>
                <label><input type="radio" class="radio" name="cid" value="7" <?php if($search['cid'] =='7') echo 'checked=""';?>>旅游险</label>
            </td>
        </tr>
		<tr>
            <td class="leftLabel">商品状态：</td>
            <td id="stateSelect">
				<label><input type="radio" class="radio" name="state" value="0" <?php if($search['state'] =='0') echo 'checked=""';?>>所有</label>
                <label><input type="radio" class="radio" name="state" value="1" <?php if($search['state'] =='1') echo 'checked=""';?>>在售</label>
                <label><input type="radio" class="radio" name="state" value="2" <?php if($search['state'] =='2') echo 'checked=""';?>>已下架</label>
                <label><input type="radio" class="radio" name="state" value="3" <?php if($search['state'] =='3') echo 'checked=""';?>>团购中</label>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="sorting">
    <b>排序：</b>
    <a href="javascript:void(0);" title="最近更新时间" ref="timed" <?php if($search['seq'] == 'timed') echo 'class="active"';?>>更新时间 ↓ </a>
    &nbsp;&nbsp; | &nbsp;&nbsp;<a href="javascript:void(0);" ref="timea" title="最远更新时间" <?php if($search['seq'] == 'timea') echo 'class="active"';?>>更新时间 ↑ </a>
    &nbsp;&nbsp; | &nbsp;&nbsp;<a href="javascript:void(0);" ref="ctimed" title="最近创建时间" <?php if($search['seq'] == 'ctimed') echo 'class="active"';?>>创建时间 ↓ </a>
    &nbsp;&nbsp; | &nbsp;&nbsp;<a href="javascript:void(0);" ref="ctimea" title="最远创建时间" <?php if($search['seq'] == 'ctimea') echo 'class="active"';?>>创建时间 ↑ </a> 
    &nbsp;&nbsp; | &nbsp;&nbsp;<a href="javascript:void(0);" ref="priced" title="价格由高到低" <?php if($search['seq'] == 'priced') echo 'class="active"';?>>价格 ↓ </a>
    &nbsp;&nbsp; | &nbsp;&nbsp;<a href="javascript:void(0);" ref="pricea" title="价格由低到高"<?php if($search['seq'] == 'pricea') echo 'class="active"';?>>价格 ↑ </a>
    &nbsp;&nbsp; | &nbsp;&nbsp;<a href="javascript:void(0);" ref="selld" title="销量由低到高"<?php if($search['seq'] == 'selld') echo 'class="active"';?>>销量 ↓ </a>
    &nbsp;&nbsp; | &nbsp;&nbsp;<a href="javascript:void(0);" ref="sella" title="销量由低到高"<?php if($search['seq'] == 'sella') echo 'class="active"';?>>销量 ↑ </a>
</div>
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp8">
        <col class="wp8">
        <col class="wp8">
        <col class="wp8">
        <col class="wp8">
    </colgroup>
    <thead>
    <tr>
        <th>商品名称</th>
        <th>商品类别</th>
        <th>区域</th>
        <th>街道</th>
        <th>商户名称</th>
        <th>当前状态</th>
        <th>可售数量</th>
        <th>单价</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($products):?>
	<?php $category = array('1'=>'客栈酒店','2'=>'美食饕餮','3'=>'娱乐休闲','4'=>'当地行','5'=>'当地游','6'=>'当地购','7'=>'旅游险');?>
	<?php foreach($products as $key => $product):?>
	<tr>
        <td class="tl"><a href="<?php echo $baseUrl.'product/edit?pid='.$product['product_id'];?>" target="_blank"><?php echo $product['product_name'];?></a></td>
		<td><?php echo $category[$product['category']];?></td>
		<td><?php echo $product['dest_name'];?></td>	
		<td><?php echo $product['local_name'];?></td>		
        <td><?php 
				if(in_array($session['role'],array('regionmanager','cservice','admin'))) 
					echo '<a class="viewInnsInfo" href="javascript:void(0);" ref="'.$product['inn_id'].'">'.$product['inn_name'].'</a>';
				else 
					echo $product['inn_name'];
			?>
		</td>
		<td><?php echo $product['state']=='Y'?'<cite>在售</cite>':($product['state']=='T'?'<cite>团购中</cite>':'<em>停售</em>');?></td>	
		<td><?php echo ($product['quantity']-$product['bought_count']).'&nbsp;&nbsp;(<cite>'.$product['quantity'].'</cite>)';?></td>	
		<td><cite>¥<?php echo number_format($product['price'],2);?></cite></td>
		<td><?php echo date('Y-m-d H:i:s',$product['create_time']);?></td>	
		<td><?php echo date('Y-m-d H:i:s',$product['update_time']);?></td>	
		<td>
            <a href="<?php echo $baseUrl.'product/'.($product['state']=='Y'?'edit':'tuanedit').'?pid='.$product['product_id'];?>" target="_blank">修改</a>
			<?php if($product['state'] == 'N'):?>
            <a class="changeProductBtn" href="javascript:void(0)" <?php echo 'ref="'.$product['product_id'].'"';?> change="Y">上架</a>
			<?php else:?>
            <a class="changeProductBtn" href="javascript:void(0)" <?php echo 'ref="'.$product['product_id'].'"';?> change="N">下架</a>
			<?php endif;?>
			<?php if($product['state'] =='Y'):?>
            <a href="<?php echo $baseUrl.'product/tuanedit?pid='.$product['product_id'];?>" target="_blank">上架成团购</a>
			<?php endif;?>
        </td>
	</tr>
	<?php endforeach;?>
	<?php endif;?>
    </tbody>
</table>
<div class="viewInnsInfoDom">
    <p class="tc">正在加载...</p>
</div>
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

        var destSelect = $('#dest');
        var localSelect = $('#local');
        var innSelect = $('#inn');
        var currentProvince = "<?php echo $destInfo['province'];?>";
        var currentCity = "<?php echo $destInfo['city'];?>";
		var currentSeq = "<?php echo $search['seq'];?>";
		var currentCid = "<?php echo $search['cid'];?>";
		var currentState = "<?php echo $search['state'];?>";

        $.initLocalSelect(currentProvince,currentCity);

        destSelect.change(function(){
            var destVal = $(this).val();
            if (destVal!=''){
                window.location.href="<?php echo $baseUrl;?>product?"+geturlcid('none')+"&tid="+destVal;
            }
        });

        localSelect.change(function(){
            var localVal = $(this).val();
            if (localVal!='0'){
                window.location.href="<?php echo $baseUrl;?>product?"+geturlcid('none')+"&lid="+localVal;
            }
			else{
				var destVal = $("#dest").val();
                window.location.href="<?php echo $baseUrl;?>product?"+geturlcid('none')+"&tid="+destVal;
			}
        });

        innSelect.change(function(){

            var innVal = $(this).val();
            if (innVal!='0'){
                window.location.href="<?php echo $baseUrl;?>product?"+geturlcid('none')+"&sid="+innVal;
            }
			else{
				var localVal = $("#local").val();
                window.location.href="<?php echo $baseUrl;?>product?"+geturlcid('none')+"&lid="+localVal;
			}
        });

		function geturlcid(noval)
		{
			var url = '';
			if(noval != 'seq')
			{
				var url = 'seq='+currentSeq;
			}
			if(currentCid != '0' && noval != 'cid')
			{
				url += '&cid=' + currentCid;
			}
			if(currentState != '0' && noval != 'state')
			{
				url += '&state=' + currentState;
			}
			return url;
		}

		function geturl(noval)
		{
			var url = geturlcid(noval);
			var innVal = innSelect.val();
			if(innVal != '0')
			{
				return url+'&sid='+innVal;
			}
			
            var localVal = localSelect.val();
			if(localVal != '0')
			{
				return url+'&lid='+localVal;
			}
			
            var destVal = destSelect.val();
			if(destVal != '0')
			{
				return url+'&tid='+destVal;
			}
			return url;
		}

        var typeSelect = $('#typeSelect').find('input[name=cid]');
        var stateSelect = $('#stateSelect').find('input[name=state]');

		typeSelect.change(function(){
            var typeVal = $(this).val();
            window.location.href="<?php echo $baseUrl;?>product?"+geturl('cid')+"&cid="+typeVal;
        });

		stateSelect.change(function(){
            var typeVal = $(this).val();
            window.location.href="<?php echo $baseUrl;?>product?"+geturl('state')+"&state="+typeVal;
        });

		
        $('.sorting a').click(function(){
            var seqVal = $(this).attr('ref');
            window.location.href = "<?php echo $baseUrl;?>product?seq="+seqVal+geturl('seq');
        });

    });
</script>