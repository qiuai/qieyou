<h3 class="headline">编辑Banner图片</h3>
<form method="post" id="form_sub">
    <input type="hidden" value="<?php echo $info['id'];?>" name="id">
    <table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel"><cite>*</cite>Banner图片：</td>
        <td>
            <p>图片尺寸：400*400px，大小>=100KB(仅参考)，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
            <div class="ajaxUpload">
                <div class="uploadInfo">
                    <div class="uploadBtn">
                        <span>请选择图片</span>
                        <input class="fileUpload" type="file" ref="grouphead" accept="image/*" name="imgFile"  />
                    </div>
                    <div class="progress">
                        <span class="progressBar" style="width: 0px;"></span>
                        <span class="percent">100%</span>
                    </div>
                    <div class="tips" id="imgThumbTips" style="display: none;margin-left: 10px;"></div>
                </div>
                <div class="files"></div>
                <div class="showImg"><?php if($info['img']):?><img src="<?php echo $staticUrl.$info['img'];?>"><span class="delImage" rel="imgFile" url="<?php echo $info['img'];?>">删除</span><?php endif;?></div>
                <input type="hidden" value="<?php echo $info['img'];?>" name="img" class="imgUrl" />
            </div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>链接地址：</td>
        <td><label><input type="text" value="<?php echo $info['link'];?>" class="w300" name="link"></label>    
        </td>
    </tr>
     <tr>
        <td class="leftLabel"><cite>*</cite>备注：</td>
        <td>
        <label><textarea class="w600 textEdit" rows="10" cols="103" name="note"><?php echo $info['note'];?></textarea></label><div class="tips tips-info"><i class="tips-ico"></i><p>限1000字内</p></div></label>			
        </td>
    </tr>
     <tr>
        <td class="leftLabel"><cite>*</cite>排序：</td>
        <td>
        <label><input type="text" value="<?php echo $info['sort'];?>" class="w50" name="sort" ></label> 
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
            <input class="submit mr20" type="submit" id="add_Button" value="确认添加"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
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
				link:{
					required: true,
					url:true,
					maxlength: 200
				},
				note:{
					required: false,
					maxlength: 1000
				},
				sort:{
					required: true,
					digits:true,
					maxlength: 1000
				},
			},
			messages: {			
				
				link:{
					required: "请输入链接地址"
				},
				sort:{
					required: "请输入排序"
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
            url:'<?php echo $baseUrl.'sysconfig/editinfo?id='.$info['id'];?>',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.href='<?php echo $baseUrl.'sysconfig';?>';
                    },1000);
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        });
	});
</script>