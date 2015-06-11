<h3 class="headline">查看商户信息</h3>
<table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel">商户名称：</td>
        <td><?php echo $innsInfo['inn_name'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">所属区域：</td>
        <td><?php echo $innsInfo['dest_name'];?>&nbsp;&nbsp;&nbsp;<?php echo $innsInfo['local_name'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">详细地址：</td>
        <td><?php echo $innsInfo['inn_address'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">前台座机：</td>
        <td><?php echo $innsInfo['inner_telephone'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">联系人：</td>
        <td><?php echo $innsInfo['inner_contacts'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">联系电话：</td>
        <td><?php echo $innsInfo['inner_moblie_number'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">开户时间：</td>
        <td><?php echo date('Y-m-d H:i',$innsInfo['create_time']);?></td>
    </tr>
    <tr>
        <td class="leftLabel">老板姓名：</td>
        <td><?php echo $innsInfo['real_name'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">性别：</td>
        <td><?php echo $innsInfo['sex']=="M"?"男":$innsInfo['sex']=="F"?"女":"未知"?></td>
    </tr>
    <tr>
        <td class="leftLabel">手机号码：</td>
        <td><?php echo $innsInfo['mobile_phone'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">账号状态：</td>
        <td class="orderList"><?php switch($innsInfo['state']){
			case 'active':
				echo '<span class="finish">正常</span>';
				break;
			case 'del':
				echo '<span class="over">被删除</span>';
				break;
			case 'locked':
				echo '<span class="error">锁定</span>';
				break;
			case 'suspend':
				echo '<span class="over">停用</span>';
				break;
		}?>
		</td>
    </tr>
	<tr>
		<td class="leftLabel">商铺状态：</td>
		<td class="orderList">
		<?php switch($innsInfo['front_show']){
			case 'Y':
				echo '<span class="finish">正常</span><!--<a href="javascript:void(0);" ref="N" class="ml50" id="change_show">设置为：不在前台目的地展示</a>-->';
				break;
			case 'N':
				echo '<span class="error">不在目的地展示</span><!--<a href="javascript:void(0);" ref="Y" class="ml50" id="change_show">设置为：可在前台目的地展示</a>-->';
				break;
		}?>
		</td>
	</tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td style="padding: 20px 0 0 55px;">
            <input class="buttonH close" type="button" value="关闭">
        </td>
    </tr>
    </tbody>
</table>
<script type="text/javascript">
	var changeShow = $('#change_show');
	changeShow.click(function(){
		var innsId = <?php echo $innsInfo['inn_id'];?>;
		var state = $(this).attr('ref');
		$.ajax({
            type: "POST",
			url: "<?php echo $baseUrl;?>inns/updateInninfo",
			data:{sid:innsId,front_show:state,act:'front_show'},
			cache: false,
			success: function(data){
				if(data.code == 1){
					layer.alert("修改成功" ,1,"修改成功");
					setTimeout(function(){
                            window.location.reload();
                    },1000);
				}
				else {
					layer.alert(data.msg ,8,"修改失败");
				}
			}
		});
	});
</script>