<h3 class="headline">优品列表
	<span class="more"><a href="<?php echo $baseUrl.'product/addqieyou'?>" target="_about"><input class="submit-mini addPqieyouBtn" type="submit" value="新增优品" /></a></span>
</h3>
<div class="filter mb20">
    <table class="form table-form">
        <colgroup>
            <col class="w80">
            <col>
        </colgroup>
		<tbody>
		<tr>
            <td class="leftLabel">类别：</td>
            <td id="typeSelect">
				<label><input type="radio" class="radio" name="cid" value="0" <?php if($search['cid'] =='0') echo 'checked=""';?>>所有</label>
                <label><input type="radio" class="radio" name="cid" value="1" <?php if($search['cid'] =='1') echo 'checked=""';?>>客栈酒店</label>
                <label><input type="radio" class="radio" name="cid" value="2" <?php if($search['cid'] =='2') echo 'checked=""';?>>美食饕餮</label>
                <label><input type="radio" class="radio" name="cid" value="3" <?php if($search['cid'] =='3') echo 'checked=""';?>>娱乐休闲</label>
				<label><input type="radio" class="radio" name="cid" value="4" <?php if($search['cid'] =='4') echo 'checked=""';?>>当地行</label>
                <label><input type="radio" class="radio" name="cid" value="5" <?php if($search['cid'] =='5') echo 'checked=""';?>>当地游</label>
                <label><input type="radio" class="radio" name="cid" value="6" <?php if($search['cid'] =='6') echo 'checked=""';?>>当地购</label>
                <label><input type="radio" class="radio" name="cid" value="7" <?php if($search['cid'] =='7') echo 'checked=""';?>>旅游险</label>
           <div class="fr">
            商品名称：
             <input type="text" value="" id="keyword" name="keyword" class="mr30">
             <input class="submit" type="submit" id="keywordButton" value="搜索">
            </div>
            </td>
        </tr>
        
		<tr>
            <td class="leftLabel">状态：</td>
            <td id="stateSelect">
				<label><input type="radio" class="radio" name="state" value="0" <?php if($search['state'] =='0') echo 'checked=""';?>>所有</label>
                <label><input type="radio" class="radio" name="state" value="3" <?php if($search['state'] =='3') echo 'checked=""';?>>团购中</label>
                <label><input type="radio" class="radio" name="state" value="2" <?php if($search['state'] =='2') echo 'checked=""';?>>已下架</label>
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
        <col class="wp5">
        <col class="wp15">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
    </colgroup>
    <thead>
    <tr>
   	    <th>商品ID</th>
        <th>名称</th>
        <th>类别</th>
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
   		 <td><?php echo $product['product_id'];?></td>
        <td class="tl"><a href="<?php echo $baseUrl.'product/edit?pid='.$product['product_id'];?>" target="_blank"><?php echo $product['product_name'];?></a></td>
		<td><?php echo $category[$product['category']];?></td>	
        <td><?php 
				if(in_array($session['role'],array('regionmanager','cservice','admin'))) 
					echo '<a class="viewInnsInfo" href="javascript:void(0);" ref="'.$product['inn_id'].'">'.$product['inn_name'].'</a>';
				else 
					echo $product['inn_name'];
			?>
		</td>
		<td><?php echo $product['state']=='Y'?'<cite>在售</cite>':$product['state']=='T'?'<cite>团购中</cite>':'<em>停售</em>';?></td>	
		<td><?php echo $product['quantity'];?></td>	
		<td><cite>¥<?php echo number_format($product['price'],2);?></cite></td>
		<td><?php echo date('Y-m-d H:i:s',$product['create_time']);?></td>	
		<td><?php echo date('Y-m-d H:i:s',$product['update_time']);?></td>	
		<td>
            <a href="<?php echo $baseUrl.'product/tuanedit?pid='.$product['product_id'];?>" class="mr5" target="_blank">修改</a>
			<?php if($product['state'] != 'N'):?>
            <a class="changeProductBtn mr5" href="javascript:void(0)" <?php echo 'ref="'.$product['product_id'].'"';?> change="N" target="_blank">下架</a>
			<?php else:?>
            <a class="changeProductBtn mr5" href="javascript:void(0)" <?php echo 'ref="'.$product['product_id'].'"';?> change="Y" target="_blank">上架</a>
			<?php endif;?>
            <a class="changeProductBtn" href="javascript:void(0)" <?php echo 'ref="'.$product['product_id'].'"';?> change="D">删除</a>
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
<script type="text/javascript">
    $(function(){
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());

		var currentSeq = "<?php echo $search['seq'];?>";
		var currentCid = "<?php echo $search['cid'];?>";
		var currentState = "<?php echo $search['state'];?>";


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
			return url;
		}

        var typeSelect = $('#typeSelect').find('input[name=cid]');
        var stateSelect = $('#stateSelect').find('input[name=state]');

		typeSelect.change(function(){
            var typeVal = $(this).val();
            window.location.href="<?php echo $baseUrl;?>product/qieyou?"+geturl('cid')+"&cid="+typeVal;
        });

		stateSelect.change(function(){
            var typeVal = $(this).val();
            window.location.href="<?php echo $baseUrl;?>product/qieyou?"+geturl('state')+"&state="+typeVal;
        });

		
        $('.sorting a').click(function(){
            var seqVal = $(this).attr('ref');
            window.location.href = "<?php echo $baseUrl;?>product/qieyou?seq="+seqVal+geturl('seq');
        });
		
		$('#keywordButton').click(function(){
			var keyword = $('#keyword').val();
			if(keyword)
			{
				window.location.href="<?php echo $baseUrl;?>product/qieyou?keyword="+keyword;
			}
			else
			{
				alert('error');	
			}	
		});

    });
</script>