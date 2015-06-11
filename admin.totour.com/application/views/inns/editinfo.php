<h3 class="headline">编辑商户店铺</h3>
<form id="editInns">
    <input type="hidden" value="<?php echo $inninfo['inn_id'];?>" name="sid">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel"><cite>*</cite>商户联系人：</td>
        <td><label><input type="text" value="<?php echo $inninfo['inner_contacts'];?>" class="w300" name="inner_contacts"></label>
            <div class="tips tips-info"><i class="tips-ico"></i><p>填写商户联系人用户日常订单联系</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>联系人手机号：</td>
        <td><label><input type="text" value="<?php echo $inninfo['inner_moblie_number'];?>" class="w300" name="inner_moblie_number"></label>
            <div class="tips tips-info"><i class="tips-ico"></i><p>填写联系人手机号，手机号将作为账号登录</p></div>
        </td>
    </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>所属区域：</td>
        <td>
            <label>
                <select name="dest_id" id="dest">
                </select>
            </label>
            <div class="tips" style="display: none;"></div>
        </td>
    </tr> 
	<tr>
        <td class="leftLabel"><cite>*</cite>所属街道：</td>
        <td>
			<label>
                <select name="local_id" id="local">
                </select>
            </label>
            <div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>详细地址：</td>
        <td><label><input type="text" value="<?php echo $inninfo['inn_address'];?>" class="w300" name="inn_address" placeholder=""></label>
			<div class="tips tips-info"><i class="tips-ico"></i><p>镇/街道/门牌号</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel"><cite>*</cite>商户坐标：</td>
        <td>经度：<label><input type="text" value="<?php if($inninfo['bdgps']) {$bdgps = explode(',',$inninfo['bdgps']); echo $bdgps[0];}?>" class="w134" name="bdlon"></label>
			纬度：<label><input type="text" value="<?php if($inninfo['bdgps']) {echo $bdgps[1];}?>" class="w134" name="bdlat"></label>
			 <div class="tips tips-info"><i class="tips-ico"></i><p>使用 <a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank" style="color:red;font-weight:bold;" title="点击打开">百度地图坐标拾取系统</a> 输入商户名称搜索，然后选取商户坐标</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel"><cite>*</cite>商户店铺名称：</td>
        <td><label><input type="text" value="<?php echo $inninfo['inn_name'];?>" class="w300" name="inn_name"></label>
            <div class="tips tips-info"><i class="tips-ico"></i><p>商户店铺全称</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel">商户联系座机：</td>
        <td><label><input type="text" value="<?php echo $inninfo['inner_telephone'];?>" class="w300" name="inner_telephone"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>开户银行：</td>
        <td><label><input type="text" value="<?php echo $inninfo['bank_info'];?>" class="w300" name="bank_info"></label><div class="tips tips-info"><i class="tips-ico"></i><p>格式：XXXX银行XX市XX支行，例如：中国建设银行丽江市福慧路支行</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>银行账户、姓名：</td>
        <td><label style="margin-right: 13px;"><input type="text" class="w200" value="<?php echo $inninfo['bank_account_no'];?>" name="bank_account_no" onkeyup="this.value=this.value.replace(/\D/g,'').replace(/....(?!$)/g,'$& ')" /></label>
            <label><input type="text" value="<?php echo $inninfo['bank_account_name'];?>" class="w70" name="bank_account_name"></label><div class="tips tips-info"><i class="tips-ico"></i><p>填写商户银行账户用于账号提现</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel"><cite>*</cite>商户订单分佣：</td>
        <td><label><input type="text" value="<?php echo $inninfo['profit'];?>" class="w50" name="profit">&nbsp;&nbsp;%</label>
            <div class="tips tips-info"><i class="tips-ico"></i><p>商户订单分佣</p></div>
        </td>
    </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td>
            <input class="submit mr20" type="submit" id="editInnsButton" value="确认添加"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
        </td>
    </tr>
    </tbody>
</table>
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/citySelect.js"></script>
<script type="text/javascript">
	$(function(){
		var editInns = $('#editInns');
		var editSubmit = $('#editInnsButton');
		var formTips = $("#formTips");
		
		var destSelect = $('#dest');
		var localSelect = $('#local');
		var cityId = <?php echo isset($current['city'])?$current['city']:'530700';?>;
		var destId = <?php echo $inninfo['dest_id'];?>;
		var localId = <?php echo $inninfo['local_id'];?>;

		
		/**修改商户表单验证**/
		editInns.validate({
			rules: {
				inner_contacts:{
					required: true,
					userName: true
				},
				inner_moblie_number: {
					required: true,
					isMobile: true,
					byteRangeLength: [11,24],
				},
				/*identity_no:{
					required: false,
					isIdCardNo: true
				},*/
				dest_id:{
					required: true,
					min: 1
				},
				local_id:{
					required: true,
					min: 1
				},
				inn_name:{
					required: true
				},
				inn_address:{
					required: true
				},
				inner_telephone:{
					required: false,
					isPhone:true
				},
				lat:{
					required: false,
					number:true
				},
				lnt:{
					required: false,
					number:true
				},
			/*	bank_info:{
					required: true
				},
				bank_account_no:{
					required: true,
					creditcard: true
				},
				bank_account_name:{
					required: true
				},*/
				profit:{
					required: true
				}
			},
			messages: {
				inner_contacts:{
					required: "请输入商户联系人称呼"
				},
				inner_moblie_number: {
					required: "请输入商户联系人手机号码",
					isMobile: "手机号码格式不正确"
				},
			/*	identity_no:{
					required: "请输入身份证号码",
					isIdCardNo: "请输入正确的身份证号码"
				},*/
				dest_id:{
					required: "请选择区域",
					min: "请选择区域"
				},
				local_id:{
					required: "请选择街道",
					min: "请选择街道"
				},
				inn_name:{
					required: "请输入商户名称"
				},
				inn_address:{
					required: "请输入驿栈详细地址"
				},
				/*inner_moblie_number:{
					required: "请输入正确的手机号"
				},*/
				inner_telephone:{
					required: "请输入电话号码"
				},
				lat:{
					required: "请输入坐标",
					number:"坐标格式错误"
				},
				lnt:{
					required: "请输入坐标",
					number:"坐标格式错误"
				},
				bank_info:{
					required: "请输入开户银行与开户支行"
				},
				bank_account_no:{
					required: "请输入银行账号",
					creditcard: "银行账户校验失败，填写有误"
				},
				bank_account_name:{
					required: "请输入开户人姓名"
				},
				profit:{
					required: "请输入分佣比例"
				}
			}, errorPlacement: function(error, element) {

				var tripEle = element.parent("label").siblings(".tips");

				if(error.text()){
					tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
					editSubmit.addClass("disabled");
					editSubmit.attr("disabled",true);
				}
				else{
					tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
					editSubmit.removeClass("disabled");
					editSubmit.attr("disabled",false);
				}

			},

			success:function(label){

			}
		});

		editInns.ajaxForm({
            dataType : 'json',
			type:'POST',
            url:'<?php echo $baseUrl.'inns/editinfo?sid='.$inninfo['inn_id'];?>',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.reload()
                    },1000);
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        });

		$.ajax({
			url: baseUrl+'destination/getDestinations',
			type:'POST',
			data:{city_id:cityId},
			dataType: 'json',
			success: function(data){
				if(data == ''){
					destSelect.empty();
					destSelect.append('<option value="-1">该地区暂无登记</option>');
				}
				else{
					destSelect.empty();
					destSelect.append('<option value="0">所有区域</option>');
					for(var i=0;i<data.length;i++){
						destSelect.append('<option value="'+data[i].dest_id+'"' + (destId==data[i].dest_id?" selected='selected' ":"")+'>'+data[i].dest_name+'</option>');
					}
				}
				localSelect.empty();
				localSelect.append('<option value="0">所有街道</option>');
				$.ajax({
					url: baseUrl+'destination/getLocations',
					type:'POST',
					data:{dest_id:destId},
					dataType: 'json',
					success: function(data){
						if(data == ''){
							localSelect.empty();
							localSelect.append('<option value="-1">该地区暂无登记</option>');
						}
						else{
							localSelect.empty();
							localSelect.append('<option value="0">请选择街道</option>');
							for(var i=0;i<data.length;i++)
							{
								localSelect.append('<option value="'+data[i].local_id+'"' + (localId==data[i].local_id?" selected='selected' ":"")+'>'+data[i].local_name+'</option>');
							}
						}
					}
				});
			}
		});
		destSelect.change(function(){
			var destValue = destSelect.val();
			if(destValue <1)
			{
				return false;
			}
			$.ajax({
				url: baseUrl+'destination/getLocations',
				type:'POST',
				data:{dest_id:destValue},
				dataType: 'json',
				success: function(data){
					if(data == ''){
						localSelect.empty();
						localSelect.append('<option value="-1">该地区暂无登记</option>');
					}
					else{
						localSelect.empty();
						localSelect.append('<option value="0">请选择街道</option>');
						for(var i=0;i<data.length;i++)
						{
							localSelect.append('<option value="'+data[i].local_id+'">'+data[i].local_name+'</option>');
						}
					}
				}
			});
		});
	});
</script>