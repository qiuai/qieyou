<h3 class="headline">推荐商品</h3>
<form method="post" id="form_sub">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
	<tr>
        <td class="leftLabel"><cite>*</cite>商品ID：</td>
        <td><label><input type="text" value="" class="w200" name="product_id" id="product_id"></label>
         <input type="button" id="re_product" value="搜索"></div>  
        <div class="tips tips-info"><i class="tips-ico"></i><p>请先搜索</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>商品名称：</td>
        <td><label><input type="text" value="" class="w300" name="product_name" id="product_name" readonly="readonly"></label>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>商品类别：</td>
        <td><label><input type="text" value="" class="w300" name="category" id="category" 

readonly="readonly"></label>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>商户名称：</td>
        <td><label><input type="text" value="" class="w300" name="inn_name" id="inn_name" 
readonly="readonly" ></label>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>单价：</td>
        <td><label><input type="text" value="" class="w300" name="price" id="price" 

readonly="readonly"></label>
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
				product_id:{
					required: true,
					digits:true,
					maxlength: 10
				},	
			},
			messages: {	
				product_id:{
					required: "请输入商品编码"
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
            url:'<?php echo $baseUrl.'sysconfig/add_re_product'?>',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.href='<?php echo $baseUrl.'sysconfig?class=product';?>';
                    },1000);
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        });
		$('#re_product').click(function() {
			var product_id= $('#product_id').val();	
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'sysconfig/re_product';?>",
				data: {product_id:product_id},
				success: function(date){
					 if(date.product_name){    	 
						$("#product_name").val(date.product_name);
						$("#category").val(date.category);
						$("#inn_name").val(date.inn_name);
						$("#price").val(date.price);
					 }else{
						layer.alert(date.msg ,3,"提示");
					 }
				}
			})	
		});
		$('#cancel').click(function() {
			 history.go(-1);
		});
	});
</script>