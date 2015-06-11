<h3 class="headline">推荐部落</h3>
<form method="post" id="form_sub">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
	<tr>
        <td class="leftLabel"><cite>*</cite>部落ID：</td>
        <td><label><input type="text" value="" class="w200" name="group_id" id="group_id"></label>
         <input type="button" id="re_group" value="搜索"></div>  
          <div class="tips tips-info"><i class="tips-ico"></i><p>请先搜索</p></div>
        </td>
    </tr>   
    <tr>
        <td class="leftLabel"><cite></cite>部落名称：</td>
        <td><label><input type="text" value="" class="w300" name="group_name" id='group_name' 
readonly="readonly"></label> 
        </td>
    </tr>
   <tr>
        <td class="leftLabel"><cite></cite>部落管理员：</td>
        <td><label><input type="text" value="" class="w300" name="admins" id='admins' 
readonly="readonly"></label> 
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>部落介绍：</td>
        <td>
        <label><textarea class="w600 textEdit" rows="10" cols="103" name="note" id='note'
readonly="readonly"></textarea></label>
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
				group_id:{
					required: true,
					digits:true,
					maxlength: 10
				},	
			},
			messages: {	
				group_id:{
					required: "请输入部落ID"
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
            url:'<?php echo $baseUrl.'sysconfig/add_re_group'?>',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.href='<?php echo $baseUrl.'sysconfig?class=group';?>';
                    },1000);
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        });
		$('#re_group').click(function() {
			var group_id= $('#group_id').val();	
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'sysconfig/re_group';?>",
				data: {group_id:group_id},
				success: function(date){
					 if(date.group_name){    	 
						$("#group_name").val(date.group_name);
						$("#admins").val(date.admins);
						$("#note").val(date.note);
					 }else{
						layer.alert(date.msg ,3,"提示");
					 }
				}
			})	
		});
		
	});
</script>