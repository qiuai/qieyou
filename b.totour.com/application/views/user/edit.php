<?php if($type == ROLE_INNHOLDER):?>
<h3 class="headline">编辑驿栈老板信息</h3>
<form method="post" id="editInns">
    <input type="hidden" value="<?php echo $userInfo['user_id'];?>" name="user_id">
    <input type="hidden" value="innholder" name="role">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel">用户名称：<input type="hidden" value="<?php echo $userInfo['user_name'];?>" name="user_name"></td>
        <td><?php echo $userInfo['user_name'];?>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">用户密码：</td>
        <td>
            <label><input type="password" value="" placeholder="********" autocomplete="off" class="w300" name="user_pass" id="user_pass"></label><div class="tips tips-info"><i class="tips-ico"></i><p>英文或数字，不少于6个字符</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">重复密码：</td>
        <td>
            <label><input type="password" value="" placeholder="********" class="w300" name="repeat_password"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>真实姓名：</td>
        <td><label><input type="text" value="<?php echo $userInfo['real_name'];?>" class="w300" name="real_name"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>性别：</td>
        <td>
            <label><input type="radio" class="radio" name="sex" value="M" <?php if ($userInfo['user_sex'] != 'F'): ?>checked="checked"<?php endif; ?>>男</label>
            <label><input type="radio" class="radio" name="sex" value="F" <?php if ($userInfo['user_sex'] == 'F'): ?>checked="checked"<?php endif; ?>>女</label>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>Email：</td>
        <td><label><input type="text" value="<?php echo $userInfo['email'];?>" class="w300" name="email"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>身份证号码：</td>
        <td><label><input type="text" value="<?php echo $userInfo['identity_no'];?>" class="w300" name="identity_no"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>手机号码：</td>
        <td><label><input type="text" value="<?php echo $userInfo['mobile_phone'];?>" class="w300" name="mobile_phone"></label><div class="tips" style="display: none;"></div>
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
                <select id="province" name="province">
                </select>
            </label>
            <label>
                <select name="city" id="city">
                </select>
            </label>
            <label>
                <select id="dest_name" name="dest_id">
                    <option value="-1">请先选择省市</option>
                </select>
            </label>
            <div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>驿栈名称：</td>
        <td><label><input type="text" value="<?php echo $innsInfo['inns_name'];?>" class="w300" name="inns_name"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>驿栈前台地址：</td>
        <td><label><input type="text" value="<?php echo $innsInfo['inns_url'];?>" class="w100" name="inns_url"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>驿栈详细地址：</td>
        <td><label><input type="text" value="<?php echo $innsDetailInfo['inns_address'];?>" class="w300" name="inns_address" placeholder=""></label><div class="tips tips-info"><i class="tips-ico"></i><p>区/村镇/街道/门牌</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel"><cite>*</cite>联系人姓名：</td>
        <td><label><input type="text" value="<?php echo $innsDetailInfo['inner_contacts'];?>" class="w300" name="inner_contacts"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel"><cite></cite>联系人手机号：</td>
        <td><label><input type="text" value="<?php echo $innsDetailInfo['inner_moblie_number'];?>" class="w300" name="inner_moblie_number"></label><div class="tips tips-info"><i class="tips-ico"></i><p>用于订单短信发送</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel"><cite>*</cite>驿栈订单分成：</td>
        <td><label><input type="text" value="<?php echo $innsInfo['order_divide'];?>" class="w50" name="order_divide"></label>%
            <div class="tips tips-info"><i class="tips-ico"></i><p>请填写驿栈订单分成</p></div>
        </td>
    </tr>
	<tr>
        <td class="leftLabel"><cite>*</cite>代预订订单分成：</td>
        <td><label><input type="text" value="<?php echo $innsInfo['balance_divide'];?>" class="w50" name="balance_divide"></label>%
            <div class="tips tips-info"><i class="tips-ico"></i><p>请填写代预订订单分成</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">前台座机：</td>
        <td><label><input type="text" value="<?php echo $innsDetailInfo['inner_telephone'];?>" class="w300" name="inner_telephone"></label><div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">开户银行：</td>
        <td><label><input type="text" value="<?php echo $innsInfo['bank_info'];?>" class="w300" name="bank_info"></label><div class="tips tips-info"><i class="tips-ico"></i><p>请填写开户银行与开户支行名称</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">银行账户：</td>
        <td><label><input type="text" value="<?php echo $innsInfo['bank_account_no'];?>" class="w200" name="bank_account_no"></label>
            <label><input type="text" value="<?php echo $innsInfo['bank_account_name'];?>" class="w70" name="bank_account_name"></label>
            <div class="tips tips-info"><i class="tips-ico"></i><p>请填写银行账号与开户人姓名</p></div>
        </td>
    </tr>

    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">账户状态：</td>
        <td><label>
                <select name="state">
                    <option value="active" <?php if ($userInfo['state'] == 'active'): ?>selected="selected"<?php endif; ?>>正常</option>
                    <option value="locked" <?php if ($userInfo['state'] == 'locked'): ?>selected="selected"<?php endif; ?>>锁定</option>
                    <option value="suspend" <?php if ($userInfo['state'] == 'suspend'): ?>selected="selected"<?php endif; ?>>停用</option>
                </select>
            </label>
        </td>
    </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td>
            <input class="submit mr20" type="submit" id="editInnsButton" value="提交编辑"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
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

            var destSelect = $('#dest_name');
            var citySelect = $('#city');
            var currentCity = "<?php echo $destInfo['city'];?>";
            var currentProvince = "<?php echo $destInfo['province'];?>";
            var currentDestId = "<?php echo $destInfo['dest_id'];?>";


            if(currentCity == ''){
                $.initProv("#province", "#city", "云南省", "");
            }
            else{
                $.initProv("#province", "#city", currentProvince, currentCity);
                $.ajax({
                    url: '<?php echo $baseUrl;?>destination/getDestinations',
                    type:'POST',
                    data:{province:currentProvince,city:currentCity},
                    dataType: 'json',
                    success: function(data){
                        if(data == ''){
                            destSelect.empty();
                            destSelect.append('<option value="-1">该地区暂无目的地</option>');
                        }
                        else{
                            destSelect.empty();
                            destSelect.append('<option value="-1">请选择目的地</option>');
                            for(var i=0;i<data.length;i++){
                                var tempObj = '';
                                if (data[i].dest_id ==currentDestId ){
                                    tempObj = '<option value="'+data[i].dest_id+'" selected="selected">'+data[i].dest_name+'</option>';
                                }
                                else{
                                    tempObj = '<option value="'+data[i].dest_id+'">'+data[i].dest_name+'</option>';
                                }
                                destSelect.append(tempObj);

                            }

                        }
                    }
                });
            }

            citySelect.change(function(){
                $.ajax({
                    url: '<?php echo $baseUrl;?>destination/getDestinations',
                    type:'POST',
                    data:{province:$('#province').val(),city:$('#city').val()},
                    dataType: 'json',
                    success: function(data){
                        if(data == ''){
                            destSelect.empty();
                            destSelect.append('<option value="-1">该地区暂无目的地</option>');
                        }
                        else{
                            destSelect.empty();
                            destSelect.append('<option value="-1">请选择目的地</option>');
                            for(var i=0;i<data.length;i++){

                                destSelect.append('<option value="'+data[i].dest_id+'">'+data[i].dest_name+'</option>');
                            }
                        }
                    }
                });
            });

            var editInns = $('#editInns');
            var editSubmit = $('#editInnsButton');
            var formTips = $("#formTips");

            /**编辑驿栈老板表单前端验证**/
            editInns.validate({
                rules: {

                    user_pass:{
                        minlength:6
                    },
                    repeat_password: {
                        minlength:6,
                        equalTo : "#user_pass"
                    },
                    real_name:{
                        required: true,
                        userName: true
                    },
                    email:{
                        required: true,
                        email:true
                    },
                    identity_no:{
                        required: true,
                        isIdCardNo: true
                    },
                    mobile_phone:{
                        required: true
                    },
                    dest_id:{
                        required: true,
                        min:0
                    },
                    inns_name:{
                        required: true
                    },
                    inns_url:{
                        required: true,
                        userName: true
                    },
                    inns_address:{
                        required: true
                    },
					inner_contacts:{
                        required: true
                    },
					/*inner_moblie_number:{
                        required: true,
                        isMobile:true
                    },*/
                    inner_telephone:{
                        required: true,
                        isPhone:true
                    },
                    bank_info:{
                        required: true
                    },
                    bank_account_no:{
                        required: true
                    },
                    bank_account_name:{
                        required: true
                    },
					order_divide:{
						required: true
					},
					balance_divide:{
						required: true
					}
                },
                messages: {

                    user_pass:{
                    },
                    repeat_password: {
                        equalTo : "两次密码不一致"
                    },
                    real_name:{
                        required: "请输入真实姓名"
                    },
                    email:{
                        required: "请输入Email"
                    },
                    identity_no:{
                        required: "请输入身份证号码",
                        isIdCardNo: "请输入正确的身份证号码"
                    },
                    mobile_phone:{
                        required: "请输入手机号码"
                    },
                    dest_id:{
                        required: "请选择目的地",
                        min: "请选择目的地"
                    },
                    inns_name:{
                        required: "请输入驿栈名称"
                    },
                    inns_url:{
                        required: "请输入驿栈前台访问的URL",
                        userName: "URL目录只能包括中文字、英文字母、数字和下划线"
                    },
                    inns_address:{
                        required: "请输入驿栈详细地址"
                    },
					inner_contacts:{
                        required: "请输入联系人姓名"
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
                    },
					order_divide:{
						required: "请填写驿栈订单分成"
					},
					balance_divide:{
						required: "请填写代预订订单分成"
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
                success : function(data){
                    if(data.code == 1){

                        formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>编辑用户成功！</p>").show().fadeOut(5000);
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
<?php elseif($type == ROLE_TREASURER || $type == ROLE_CUSTOM_SERVICE):?>
<h3 class="headline">编辑<?php echo get_role_name_by_role_key($type)?>信息</h3>
<form method="post" id="editOther">
    <input type="hidden" value="<?php echo $userInfo['user_id'];?>" name="user_id">
    <input type="hidden" value="<?php echo $type?>" name="role">
    <table class="form table-form">
        <colgroup>
            <col class="w120">
            <col>
        </colgroup>
        <tbody>

        <tr>
            <td class="leftLabel">用户名称：<input type="hidden" value="<?php echo $userInfo['user_name'];?>" name="user_name"></td>
            <td><?php echo $userInfo['user_name'];?>
            </td>
        </tr>
        <tr>
            <td class="leftLabel">用户密码：</td>
            <td>
                <label><input type="password" value="" placeholder="********" autocomplete="off" class="w300" name="user_pass" id="user_pass"></label><div class="tips tips-info"><i class="tips-ico"></i><p>英文或数字，不少于6个字符</p></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel">重复密码：</td>
            <td>
                <label><input type="password" value="" placeholder="********" class="w300" name="repeat_password"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr class="space">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>真实姓名：</td>
            <td><label><input type="text" value="<?php echo $userInfo['real_name'];?>" class="w300" name="real_name"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>性别：</td>
            <td>
                <label><input type="radio" class="radio" name="sex" value="M" <?php if ($userInfo['user_sex'] != 'F'): ?>checked="checked"<?php endif; ?>>男</label>
                <label><input type="radio" class="radio" name="sex" value="F" <?php if ($userInfo['user_sex'] == 'F'): ?>checked="checked"<?php endif; ?>>女</label>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>Email：</td>
            <td><label><input type="text" value="<?php echo $userInfo['email'];?>" class="w300" name="email"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>身份证号码：</td>
            <td><label><input type="text" value="<?php echo $userInfo['identity_no'];?>" class="w300" name="identity_no"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><cite>*</cite>手机号码：</td>
            <td><label><input type="text" value="<?php echo $userInfo['mobile_phone'];?>" class="w300" name="mobile_phone"></label><div class="tips" style="display: none;"></div>
            </td>
        </tr>
        <tr class="space">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td class="leftLabel">账户状态：</td>
            <td><label>
                    <select name="state">
                        <option value="active" <?php if ($userInfo['state'] == 'active'): ?>selected="selected"<?php endif; ?>>正常</option>
                        <option value="locked" <?php if ($userInfo['state'] == 'locked'): ?>selected="selected"<?php endif; ?>>锁定</option>
                        <option value="suspend" <?php if ($userInfo['state'] == 'suspend'): ?>selected="selected"<?php endif; ?>>停用</option>
                    </select>
                </label>
            </td>
        </tr>
        <tr class="space">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td class="leftLabel">&nbsp;</td>
            <td>
                <input class="submit mr20" type="submit" id="editOtherButton" value="提交编辑"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
            </td>
        </tr>
        </tbody>
    </table>
</form>