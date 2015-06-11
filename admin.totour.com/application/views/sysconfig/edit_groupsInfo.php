<h3 class="headline">编辑部落</h3>
<form id="form_sub">
    <input type="hidden" value="<?php echo $info['group_id'];?>" name="id">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel"><cite>*</cite>部落名称：</td>
        <td><label><input type="text" value="<?php echo $info['group_name'];?>" class="w300" name="group_name"></label>
        <div class="tips" style="display: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>创建者：</td>
        <td><label><input type="text" value="<?php echo $info['admin_mobile'][$info['create_by']];?>" class="w300" name="admins" id="admins"></label>
        <div class="tips tips-info"><i class="tips-ico"></i><p>填写用户手机号</p></div> 
        </td>
    </tr>
     <tr>
        <td class="leftLabel">部落管理员：</td>
        <td id="admindiv">
        <a href="javascript:void(0);" onclick="add_admins()" >+添加管理员</a>
        <?php 
			if($info['admin_mobile']){
				foreach($info['admin_mobile'] as $k=>$v){
					if($k == $info['create_by'])
               			 continue;?> 
					 <div class="item" style='margin-bottom:5px'>
					  <label><input type='text' value='<?php echo $v;?>'  onchange='check_mobile(this)' class='w200' name='admins_id[]'></label>
					  <a href='javascript:void(0);' onclick='del_admins(this)'>删除</a><div class='tips tips-info ml10'><i class='tips-ico'></i><p>填写用户手机号</p></div>
					 </div>
        <?php         
				}
			}
		?>
        </td>
    </tr> 
    <tr>
        <td class="leftLabel"><cite>*</cite>Banner图片：</td>
        <td>
            <p>图片尺寸： 400*400px，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
            <div class="ajaxUpload">
                <div class="uploadInfo">
                    <div class="uploadBtn">
                        <span>请选择图片</span>
                        <input class="fileUpload" type="file" accept="image/*" ref="grouphead" name="imgFile"  />
                    </div>
                    <div class="progress">
                        <span class="progressBar" style="width: 0px;"></span>
                        <span class="percent">100%</span>
                    </div>
                    <div class="tips" id="imgThumbTips" style="display: none;margin-left: 10px;"></div>
                </div>
                <div class="files"></div>
                <div class="showImg"><?php if($info['group_img']):?><img src="<?php echo $staticUrl.$info['group_img'];?>"><span class="delImage" rel="imgFile" url="<?php echo $info['group_img'];?>">删除</span><?php endif;?></div>
                <input type="hidden" value="<?php echo $info['group_img'];?>" name="group_img" class="imgUrl" />
            </div>
        </td>
    </tr>
     <tr>
        <td class="leftLabel"><cite>*</cite>部落介绍：</td>
        <td>
        <label><textarea class="w600 textEdit" rows="10" cols="103" name="note"><?php echo $info['note'];?></textarea></label><div class="tips tips-info"><i class="tips-ico"></i><p>限1000字内</p></div></label>			
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>加入方式：</td>
        <td>
        <label><input name="join_method" type="radio" value="able" <?php if($info['join_method']=="able"){?> checked="checked" <?php }?>/>无需审核，任何人可加入
        <br /><input name="join_method" type="radio" value="verify" <?php if($info['join_method']=="verify"){?> checked="checked" <?php }?>/>需要管理员审核
        <br /><input name="join_method" type="radio" value="noable" <?php if($info['join_method']=="noable"){?> checked="checked" <?php }?>/>不允许任何人加入</label>
        </td>
    </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td>
            <input class="submit mr20" type="submit" id="add_Button" value="确认"><input class="cancel" type="button" id="cancel" value="取消"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
        </td>
    </tr>
    </tbody>
</table>
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js"></script>    
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/ajaxUpload.js"></script>

<script type="text/javascript">
	$(function(){
		var form_sub= $("#form_sub")
		var addSubmit = $('#add_Button');
		var formTips = $("#formTips");
		form_sub.validate({
			rules: {
				group_name:{
					required: true,
					maxlength: 200
				},	
				admins:{	
					required: true,
					isMobile: true,
					remote: {
						url: "<?php echo $baseUrl; ?>sysconfig/checkusername", //后台处理程序
						type: "POST",			//数据发送方式
						dataType: "json",       //接受数据格式
						data: {                 //要传递的数据
							admins: function () {
								return $("#admins").val();
							}
						}
					}
				},
				note:{
					required: true,
					maxlength: 1000
				},
			},
			messages: {	
				group_name:{
					required: "请输入部落名称"
				},
				admins:{	
					required: "请输入用户手机号",
					isMobile: "手机号格式不正确",
					remote : "此用户手机号不存在！"
				},	
				note:{
					required: "请输入部落介绍"
				},		
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
		form_sub.ajaxForm({
            dataType : 'json',
			type:'POST',
            url:'<?php echo $baseUrl.'sysconfig/edit_groupsInfo?id='.$info['group_id'];?>',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.href='<?php echo $baseUrl.'sysconfig/groups';?>';
                    },1000);
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        });
		$('#cancel').click(function() {
			 history.go(-1);
		});	
	});
	var num=<?php echo count($info['admin_mobile'])-1;?>;
	function add_admins(){
		if(num<3){
			 var htmltxt =" <div class='item' style='margin-bottom:5px'>";
			 htmltxt +="<label><input type='text' value='' class=' w200' onchange='check_mobile(this)'  name='admins_id[]' ></label>";
			 htmltxt +="<a href='javascript:void(0);' onclick='del_admins(this)'>删除</a><div class='tips tips-info ml10'><i class='tips-ico'></i><p>填写用户手机号</p></div>";
			 htmltxt +="</div>"
			 num=num+1;
			 $("#admindiv").append(htmltxt);
		 }else{
			alert('最多添加三个管理员');
		 }
	}
	function del_admins(self){
		$(self).closest('.item').remove();
		num=num-1;
	}
	function check_mobile(self){
		var addSubmit = $('#add_Button'), flag;
		var tripEle = $(self).parent("label").siblings(".tips");
		var mobile=$(self).val();
		$(self).closest('.item').siblings('.item').each(function(){
			var t = $(this).find('input').val();
			if( mobile == t){
				var msg='重复添加手机号';
				tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+msg+"</p>");
				addSubmit.addClass("disabled");
				addSubmit.attr("disabled",true);
				flag=true;
			}
		});
		if(flag) return;
		$.ajax({
			type:"POST",
			dataType: "json",
			url: "<?php echo $baseUrl.'sysconfig/check_mobile';?>",
			data: {mobile:mobile},
			success: function(data){	
				if(data.code == 1){
					tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
					addSubmit.removeClass("disabled");
					addSubmit.attr("disabled",false);
				}else{
					tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+data.msg+"</p>");
					addSubmit.addClass("disabled");
					addSubmit.attr("disabled",true);
				
				}
			}
		})
	}
</script>