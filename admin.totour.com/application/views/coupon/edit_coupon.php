<h3 class="headline">编辑抵用券</h3>
<form method="post" id="form_sub">
 <input type="hidden" value="<?php echo $info['quan_id'];?>" name="quan_id">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
	<tr>
        <td class="leftLabel"><cite>*</cite>抵用券名称：</td>
        <td><label><input type="text" value="<?php echo $info['quan_name']?>" class="w200" name="quan_name" ></label>
         <div class="tips" style="display: none;"></div> 	
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>抵用券面额：</td>
        <td><label><input type="text" value="<?php echo $info['amount']?>" class="w100" name="amount">&nbsp;元</label>   
         <div class="tips" style="display: none;"></div>  
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>张数：</td>
        <td><label><input type="text" value="<?php echo $info['total']?>" class="w100" name="total">&nbsp;张</label>
        <div class="tips tips-info"><i class="tips-ico"></i><p>如果不填,不限张数</p></div>	    
        </td>
    </tr>
     <tr>
        <td class="leftLabel"><cite>*</cite>需要积分：</td>
        <td><label><input type="text" value="<?php echo $info['require']?>" class="w100" name="require">&nbsp;分</label>  <div class="tips tips-info"><i class="tips-ico"></i><p>需要多少积分能兑换券(0分则不需要积分)</p></div>		
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>有效期：</td>
        <td>
          <label class="mr20"><input type="text" id='end_time' name="end_time" onfocus="WdatePicker({startDate:'%y-%M-{%d+1}0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',doubleCalendar:true,minDate:'%y-%M-%d',maxDate:'{%y+1}-%M-%d'})"  value="<?php echo date('Y-m-d H:i:s',$info['end_time']);?>" name="end_time" title="请选择开始日期" class="Wdate" style="width:140px;background-position-x: 128px!important;"></label>
          <div class="tips" style="display: none;"></div>
        </td>
     </tr>
    <tr class="space">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td>
            <input class="submit mr20" type="submit" id="add_Button" value="确认"><input class="cancel" type="button" id="cancel" value="取消"><div 
class="tips tips-ok" id="formTips" style="display: none;"></div>
        </td>
    </tr>
    </tbody>
</table>
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js"></script>    
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>

<script type="text/javascript">
	$(function(){
		var form_sub= $("#form_sub")
		var addSubmit = $('#add_Button');
		var formTips = $("#formTips");
		form_sub.validate({
			rules: {
				quan_name:{
					required: true,
					maxlength: 200
				},	
				amount:{
					required: true,
					digits:true,
					maxlength: 10
				},
				total:{
					required: false,
					digits:true,
					maxlength: 10
				},
				require:{
					required: false,
					digits:true,
					maxlength: 10
				},
				end_time:{
					required: true,
				}
			},
			messages: {	
				quan_name:{
					required: "请输入抵用券名称"
				},
				amount:{
					required: "请输入抵用券面额"
				},	
				end_time:{
					required: "请输入有效期"
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
            url:'<?php echo $baseUrl.'coupon/edit_coupon'?>',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.href='<?php echo $baseUrl.'coupon';?>';
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
</script>