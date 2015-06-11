<h3 class="headline">商户查询</h3>
<div class="filter mb20" >
    <div class="form" style="padding:10px 36px;">
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
</div>
<?php if($inn_id):?>
<table class="innAccountList table table-border table-odd">
    <colgroup>
        <col class="wp10">
        <col class="wp15">
        <col class="wp15">
        <col class="wp15">
        <col class="wp10">
        <col class="wp10">
        <col class="wp25">
    </colgroup>
    <thead>
    <tr>
        <th>流水号</th>
        <th>账户余额</th>
        <th>相关金额</th>
        <th>操作描述</th>
        <th>操作人员</th>
        <th>操作时间</th>
        <th>备注</th>
    </tr>
    </thead>
    <tbody>
	<?php $type = array( 'sell'=> '销售' , 'agent' => '代售' , 'cashout' => '提现' , 'refund' =>'退款' , 'buy' => '购买' ,'recharge' => '充值');?>
	<?php foreach($records as $key => $val):?>
		<tr>
            <td><?php echo $val['record_id'];?></td>
            <td><?php echo $val['balance'];?></td>
            <td><?php echo $val['amount'];?></td>
            <?php echo '<td ';
			switch($val['record_type'])
			{
				case 'sell':
					echo 'style="color: #f60;">'; 
					break;
				case 'agent':
					echo 'style="color: #f60;">';
					break;
				case 'recharge':
					echo 'style="color: #066601;">';
					break;
				case 'buy':
					echo 'style="color: #f60;">'; 
					break;
				case 'cashout': 
					echo 'style="color: #066601;">'; 
					break;
				case 'refund': 
					echo 'style="color: #066601;">'; 
					break;
				default:
					echo 'style="color: #FF0000;">';
				break;
			} echo $type[$val['record_type']];?>
			</td>
            <td><?php echo empty($userinfo[$val['create_by']]['real_name'])?$userinfo[$val['create_by']]['user_name']:$userinfo[$val['create_by']]['real_name'];?></td>
            <td><?php echo $val['create_time'];?></td>
            <td><?php echo $val['comments'];?></td>
        </tr>
	<?php endforeach;?>
    </tbody>
</table>
<?php else:?>
<table class="orderList table table-border table-odd" style="width: 100%">
    <colgroup>
        <col class="wp20">
        <col class="wp15">
        <col class="wp15">
        <col class="wp15">
        <col class="wp15">
        <col class="wp10">
        <col class="wp10">
    </colgroup>
    <thead>
    <tr>
        <th>商户名称</th>
        <th>商户可用余额</th>
        <th>提现中余额</th>
        <th>销售收入</th>
        <th>代售收入</th>
        <th>开业时间</th>
        <th>商户账户状态</th>
    </tr>
    </thead>
    <tbody>
	<?php if($records):?>
	<?php foreach($records as $key => $val):?>
		<tr>
            <td><a href="<?php echo $baseUrl.'finance/account?sid='.$val['inn_id'];?>"><?php echo $val['inn_name'];?></td>
            <td><?php echo $val['account'];?></td>
            <td><?php echo $val['withdrawing'];?></td>
            <td><?php echo $val['order_divide'];?></td>
            <td><?php echo $val['balance_divide'];?></td>
            <td><?php echo date('Y-m-d',$val['create_time']);?></td>
            <td><?php echo $val['state']=='active'?'<span style="color: #066601;">正常</span>':'<span style="color: #f60;">停用</span>';?></td>
        </tr>
	<?php endforeach;?>
	<?php endif;?>
    </tbody>
</table>
<?php endif;?>
<!--分页样式开始-->
<div class="pageBar clearfix">
    <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage']?></em>条</p>
    <div class="pages fr" id="page">
    </div>
</div>
<!--分页样式结束-->
<script type="text/javascript" src="<?php echo $staticUrl?>js/citySelect.js" charset="utf-8"></script>   
<script type="text/javascript">
    $(function(){

        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());

		var destSelect = $('#dest');
        var localSelect = $('#local');
        var innSelect = $('#inn');
        var currentProvince = "<?php echo $destInfo['province'];?>";
        var currentCity = "<?php echo $destInfo['city'];?>";

        $.initLocalSelect(currentProvince,currentCity);

		destSelect.change(function(){
            var destVal = $(this).val();
            if (destVal!=''){
                window.location.href="<?php echo $baseUrl;?>finance/account?tid="+destVal;
            }
        });

        localSelect.change(function(){
            var localVal = $(this).val();
            if (localVal!='0'){
                window.location.href="<?php echo $baseUrl;?>finance/account?lid="+localVal;
            }
			else{
				var destVal = $("#dest").val();
                window.location.href="<?php echo $baseUrl;?>finance/account?&tid="+destVal;
			}
        });

        innSelect.change(function(){
            var innVal = $(this).val();
            if (innVal!='0'){
                window.location.href="<?php echo $baseUrl;?>finance/account?sid="+innVal;
            }
			else{
				var localVal = $("#local").val();
                window.location.href="<?php echo $baseUrl;?>finance/account?lid="+localVal;
			}
        });

	});
</script>