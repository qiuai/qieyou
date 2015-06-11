<h3 class="headline">添加商户</h3>
<form method="post" id="addInns">
    <input type="hidden" value="innholder" name="role">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
<!--    <tr>
        <td class="leftLabel"><cite>*</cite>用户名称：</td>
        <td><label><input type="text" value="" autocomplete="off" class="w300" name="user_name" id="userName"></label><div class="tips tips-info"><i class="tips-ico"></i><p>中、英文或数字，不超过12个字符</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>用户密码：</td>
        <td>
            <label><input type="password" value="" autocomplete="off" class="w300" name="user_pass" id="user_pass"></label><div class="tips tips-info"><i class="tips-ico"></i><p>英文或数字，不少于6个字符</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>重复密码：</td>
        <td>
            <label><input type="password" value="" class="w300" name="repeat_password"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>-->
    <tr>
        <td class="leftLabel"><cite>*</cite>商户联系人：</td>
        <td><label><input type="text" value="" class="w300" name="real_name"></label><div class="tips" style="display: none;"></div>
            <div class="tips tips-info"><i class="tips-ico"></i><p>填写商户联系人用户日常订单联系</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>联系人手机号：</td>
        <td><label><input type="text" value="" class="w300" name="user_name" id="user_name"></label>
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
			<?php if(isset($destArr)):?>
            <label>
                <select id="province" name="province">
                </select>
            </label>
            <label>
                <select name="city" id="city">
                </select>
            </label>
			<?php endif;?>
            <label>
                <select name="dest_id" id="dest">
                    <option value="0">请选择省市</option>
                </select>
            </label>
			<label>
                <select name="local_id" id="local">
                    <option value="0">请选择区域</option>
                </select>
            </label>
            <div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>详细地址：</td>
        <td><label><input type="text" value="" class="w300" name="inn_address" placeholder=""></label>
			<div class="tips tips-info"><i class="tips-ico"></i><p>镇/街道/门牌号</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel"><cite>*</cite>商户坐标：</td>
        <td><label><input type="text" value="" class="w134" name="lat"></label>
			 <label><input type="text" value="" class="w134" name="lnt"></label>
			 <div class="tips tips-info"><i class="tips-ico"></i><p>使用 <a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank" style="color:red;font-weight:bold;" title="点击打开">百度地图坐标拾取系统</a> 输入商户名称搜索，然后选取商户坐标</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel"><cite>*</cite>商户店铺名称：</td>
        <td><label><input type="text" value="" class="w300" name="inn_name"></label><div class="tips" style="display: none;"></div>
            <div class="tips tips-info"><i class="tips-ico"></i><p>商户店铺全称</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel">商户联系座机：</td>
        <td><label><input type="text" value="" class="w300" name="inner_telephone"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>开户银行：</td>
        <td><label><input type="text" value="" class="w300" name="bank_info"></label><div class="tips" style="display: none;"></div><div class="tips tips-info"><i class="tips-ico"></i><p>格式：XXXX银行XX市XX支行，例如：中国建设银行丽江市福慧路支行</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>银行账户、姓名：</td>
        <td><label style="margin-right: 13px;"><input type="text" value="" class="w200" name="bank_account_no"></label>
            <label><input type="text" value="" class="w70" name="bank_account_name"></label><div class="tips" style="display: none;"></div><div class="tips tips-info"><i class="tips-ico"></i><p>填写商户银行账户用于账号提现</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel"><cite>*</cite>商户订单分佣：</td>
        <td><label><input type="text" value="" class="w50" name="profit">&nbsp;&nbsp;%</label>
            <div class="tips tips-info"><i class="tips-ico"></i><p>商户订单分佣</p></div>
        </td>
    </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">账户状态：</td>
        <td>
			<label>
                <select name="state">
                    <option value="active">正常</option>
                    <option value="suspend">锁定</option>
                </select>
            </label>
			<div class="tips tips-info"><i class="tips-ico"></i><p>当选择锁定状态时，商户所有账号会被立即踢下线，且无法登陆</p></div>
        </td>
    </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td>
            <input class="submit mr20" type="submit" id="addInnsButton" value="确认添加"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
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
            var addInns = $('#addInns');
            var addSubmit = $('#addInnsButton');
            var formTips = $("#formTips");
			
			var destSelect = $('#dest_name');
			var currentCity = "<?php echo isset($current['city'])?$current['city']:'';?>";
			var currentProvince = "<?php echo isset($current['province'])?$current['province']:'';?>";
			var currentDestId = "<?php echo isset($current['dest_id'])?$current['dest_id']:'';?>";

			$.initLocalSelect("530000", "530700");
			
            /**添加驿栈老板表单前端验证**/
            addInns.validate({
                rules: {
                    real_name:{
                        required: true,
                        userName: true
                    },
                    user_name: {
                        required: true,
						isMobile: true,
                        byteRangeLength: [1,24],
                        remote: {
                            url: "<?php echo $baseUrl; ?>user/checkusername", //后台处理程序
                            type: "POST",  //数据发送方式
                            dataType: "json",       //接受数据格式
                            data: {                     //要传递的数据
                                userName: function () {
                                    return $("#user_name").val();
                                }
                            }
                        }
                    },
                    email:{
                        required: true,
                        email:true
                    },
                    identity_no:{
                        required: false,
                        isIdCardNo: true
                    },
                    mobile_phone:{
                        required: true
                   //     
                    },
                    local_id:{
                        required: true,
                        min: 0
                    },
                    inn_name:{
                        required: true
                    },
                    inn_address:{
                        required: true
                    },
					/*inner_moblie_number:{
                        required: true,
                        isMobile:true
                    },*/
                    inner_telephone:{
                        required: false,
                        isPhone:true
                    },
                    bank_info:{
                        required: false
                    },
                    bank_account_no:{
                        required: false
                    },
                    bank_account_name:{
                        required: false
                    }
                },
                messages: {
                    real_name:{
                        required: "请输入真实姓名"
                    },
                    user_name: {
                        required: "请输入用户名",
						isMobile: "手机号码格式不正确",
                        remote : "此手机号已注册！"
                    },
                    email:{
                        required: "请输入Email"
                    },
                    identity_no:{
                        required: "请输入身份证号码",
                        isIdCardNo: "请输入正确的身份证号码"
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
                    bank_info:{
                        required: "请输入开户银行与开户支行"
                    },
                    bank_account_no:{
                        required: "请输入银行账号"
                    },
                    bank_account_name:{
                        required: "请输入开户人姓名"
                    }
                }, errorPlacement: function(error, element) {

                    var tripEle = element.parent("label").siblings(".tips");

                    if(error.text()){
                        tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                        addSubmit.addClass("disabled");
                        addSubmit.attr("disabled",true);
                    }
                    else{
                        tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                        addSubmit.removeClass("disabled");
                        addSubmit.attr("disabled",false);
                    }

                },

                success:function(label){

                }
            });

            addInns.ajaxForm({
                dataType : 'json',
                success : function(data){
                    if(data.code == 1){

                        formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>添加用户成功！</p>").show().fadeOut(5000);
                        setTimeout(function(){
                            window.location.href='<?php echo $baseUrl.'user';?>';
                        },1000);
                    }
                    else{
                        layer.alert(data.msg ,3,"提示");
                    }
                }
            });

        });
    </script>