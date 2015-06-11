<h3 class="headline">添加团购</h3>
<div class="menuWrap clearfix">
	<div class="content">
        <form id="addProductForm" action="<?php echo $baseUrl.'product/add'?>" method="post">
		<table class="form table-form">
			<colgroup>
				<col class="w120">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<td class="leftLabel"><cite>*</cite>商户名称：</td>
				<td>
				<?php if(isset($innInfo)):?>
					<?php echo $innInfo['inn_name'];?>
					<input type="hidden" value="<?php echo $innInfo['inn_id'];?>" name="sid">
				<?php else:?>
					<label>
						<select id="dest">
							<option value="0">所有区域</option>
							<?php foreach($localArr['dest'] as $key => $row):?>
								<option <?php if($row['dest_id'] == $destInfo['dest_id']) echo 'selected="selected"';?> value="<?php echo $row['dest_id']?>"><?php echo $row['dest_name'];?></option>
							<?php endforeach;?>
						</select>
					</label>
					<label>
						<select id="local">
							<option value="0">所有街道</option>
							<?php foreach($localArr['local'] as $key => $row):?>
								<option <?php if($row['local_id'] == $destInfo['local_id']) echo 'selected="selected"';?> value="<?php echo $row['local_id']?>"><?php echo $row['local_name'];?></option>
							<?php endforeach;?>
						</select>
					</label>
					<label>
						<select name="sid" id="inn">
							<option value="0">所有商户</option>
							<?php if($Innlist):?>
							<?php foreach($Innlist as $key => $row):?>
								<option <?php if($row['inn_id'] == $destInfo['inn_id']) echo 'selected="selected"';?> value="<?php echo $row['inn_id']?>"><?php echo $row['inn_name'];?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</label>
				<?php endif;?>
				</td>
			</tr>
			<tr>
				<td class="leftLabel"><cite>*</cite>商品名称：</td>
				<td><label><input type="text" value="" class="w200" name="product_name"></label><div class="tips tips-info"><i class="tips-ico"></i><p>名称30字以内</p></div>
				</td>
			</tr>
			<tr>
				<td class="leftLabel"><cite>*</cite>商品名称：</td>
				<td><label><input type="text" value="" class="w200" name="product_name"></label><div class="tips tips-info"><i class="tips-ico"></i><p>名称30字以内</p></div>
				</td>
			</tr>
			<tr>
				<td class="leftLabel"><cite>*</cite>商品分类：</td>
				<td>
					<label>
						<select name="category" id="cid">
						</select>
					</label>
					<label>
						<select name="category_id" id="ccid">
						</select>
					</label>
				</td>
			</tr>
			<tr>
                <td class="leftLabel"><cite>*</cite>可售数量：</td>
                <td>
					<label><input type="text" value="" class="w50 mr15" name="quantity" ></label>
					<div class="tips tips-info"><i class="tips-ico"></i><p>1~10亿</p></div>
				</td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>原始价格：</td>
                <td>
                    <label class="mr20"><input type="text" value="" class="w50" name="old_price"> 元</label>
                    <div class="tips tips-info"><i class="tips-ico"></i><p>1~99万</p>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>当前售价：</td>
                <td>
                    <label class="mr20"><input type="text" value="" class="w50" name="price"> 元</label>＝ &nbsp; 进货价格：
                    <label class="mr20"><input type="text" value="" class="w50" name="purchase_price"> 元</label>＋ &nbsp; 平台佣金：
                    <label class="mr20"><input type="text" value="" class="w50" name="profit"> 元</label>＋ &nbsp; 代售佣金：
                    <label class="mr20"><input type="text" value="" class="w50" name="agent"> 元</label>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>提供发票：</td>
                <td>
					<label><input type="radio" class="radio" name="receipt" value="1">可以提供</label>
					<label class="ml15"><input type="radio" class="radio" name="receipt" value="0"  checked="">无法提供</label>
                </td>
            </tr>
			<tr>
                <td class="leftLabel"><cite>*</cite>实物商品：</td>
                <td>
					<label><input type="radio" class="radio" name="is_express" value="1">是</label>
					<label class="ml15"><input type="radio" class="radio" name="is_express" value="0"  checked="">否</label>
					实物商品在下单时需要填写物流信息，收件人，收件地址，收件手机
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>截至日期：</td>
                <td>
                    <label class="mr20"><input type="text" id="tuan_end_time" onfocus="WdatePicker({startDate:'%y-%M-{%d+1} 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',doubleCalendar:true,minDate:'%y-%M-%d',maxDate:'{%y+1}-%M-%d'})" value="" name="tuan_end_time" title="请选择开始日期" class="Wdate" style="width:140px;background-position-x: 128px!important;"></label>
					<div class="tips tips-info"><i class="tips-ico"></i><p>最长一年</p>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>商品简介：</td>
                <td><label class="mr20"><input type="text" value="" class="w600" name="content"></label><div class="tips tips-info"><i class="tips-ico"></i><p>限50字内</p></div></td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>商品介绍：</td>
                <td><label><textarea class="w600 textEdit" rows="10" cols="103" name="note"></textarea></label><div class="tips tips-info"><i class="tips-ico"></i><p>限2万字内</p></div></td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>购买须知：</td>
                <td><label><textarea class="w600 textEdit" rows="10" cols="103" name="booking_info"><?php echo $product['booking_info']?></textarea></label><div class="tips tips-info"><i class="tips-ico"></i><p>限2万字内</p></div></td>
            </tr>
			<tr>
				<td class="leftLabel">照片上传提示：</td>
				<td>
					<label>【注】：图片最合适尺寸为：<cite>1200x825像素</cite> ，每张最大<cite>4MB</cite>，超出请自行压缩。<br/>
						1、点击“<cite>添加照片</cite>”，最多上传<cite>100 </cite>张照片；<br/>
						2、按着“<cite>ctrl</cite>” 键可以一次选择多张照片，选完后点击“<cite>开始上传</cite>”；<br/>
						3、全部上传成功后点击“<cite>全部插入</cite>”；点击“<cite>保存</cite>”按钮保存修改。</label>
				</td>
			</tr>
			
			<tr>
				<td class="leftLabel"><cite>*</cite>首页图片：</td>
				<td>
					<div class="fl wp50 pb10">
					<label><input type="button" value="图片批量上传" class="button" id="J_selectImage" name="name"></label>
					<div class="tips tips-info"><i class="tips-ico"></i><p>您还可以上传 <em id="imageLeft">5</em> 张图片</p></div>
					</div>
					<div class="fl wp50 pb10">
					详情图片：
					<label><input type="button" value="图片批量上传" class="button" id="D_selectImage" name="name"></label>
					<div class="tips tips-info"><i class="tips-ico"></i><p>您还可以上传 <em id="imageLeft2">100</em> 张图片</p></div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="leftLabel">图片预览编辑：</td>
				<td>
					<div class="imageEdit roomImage">
						<ul class="clearfix wp50" id="J_imageView" style="float:left">
							<?php if(!empty($product['product_images'])):?>
								<?php $images = explode(',',$product['product_images']); ?>
								<?php foreach($images as $key => $image):?>
								<li><div class="preview"><img src="<?php echo $staticUrl.$image;?>" alt=""/></div>
									<input type="hidden" name="images[]" value="<?php echo $image;?>"> <a href="javascript:void(0)" class="deleteImg">删除</a>
								</li>
								<?php endforeach;?>
								<?php $key += 1;?>
							<?php else:?>
							<?php $key = 0;?>
							<li>上传照片后将在此处预览和编辑</li>
							<?php endif;?>
						</ul>
					</div>
					<div class="imageEdit roomImage">
						<ul class="clearfix wp50" id="D_imageView" style="float:left">
							<?php if(!empty($product['product_images'])):?>
								<?php $images = explode(',',$product['product_images']); ?>
								<?php foreach($images as $key2 => $image):?>
								<li><div class="preview"><img src="<?php echo $staticUrl.$image;?>" alt=""/></div>
									<input type="hidden" name="images[]" value="<?php echo $image;?>"> <a href="javascript:void(0)" class="deleteImg2">删除</a>
								</li>
								<?php endforeach;?>
								<?php $key2 += 1;?>
							<?php else:?>
							<?php $key2 = 0;?>
							<li>上传照片后将在此处预览和编辑</li>
							<?php endif;?>
						</ul>
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="leftLabel">&nbsp;</td>
				<td style="padding-top: 15px;">
					<input class="submit editSubmit mr10" type="submit" value="保存">
				</td>
			</tr>
			</tbody>
		</table>
        </form>
	</div>
</div>
<link rel="stylesheet" href="<?php echo $staticUrl;?>kindeditor/themes/default/default.css" />

<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/ajaxUpload.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/destSelect.js"></script>
<script type="text/javascript">

    var imageNum =  <?php echo 100-(isset($key)?$key:0);?>;
    var imgHasNum = <?php echo isset($key)?$key:0;?>;
    var imageNum2 =  <?php echo 100-(isset($key2)?$key2:0);?>;
    var imgHasNum2 = <?php echo isset($key2)?$key2:0;?>;
    var selectImageBtn = $("#J_selectImage");
    var selectImageBtn2 = $("#D_selectImage");
    var imageLeft = $('#imageLeft');
    var imageValidate = $('#imgNum');
    var addProductForm = $('#addProductForm');
	var textEdit =$('.textEdit');

	var editor = $('.note');
	var editorTips = $('#editorTips');
	var editSubmit = $('.editSubmit');
		
	var editGame = $('#editGame');
	var imgThumbTips = $('#imgThumbTips');
	var imgTips = $('#imgTips');
	var imgThumbValue = $('#imgThumbValue');
	var imgValue= $('#imgValue');
	var formTips = $('#formTips');

    $(function(){

        $.initLocalSelect(1,1);
        //如果已经传了6张，则上传按钮禁用

/*
		textEdit.each(function(index, domEle){
			var newVal = replaceBrToEnter($(domEle).val());
			$(domEle).val(newVal);
		});

		textEdit.focus(function(){
			var newVal = replaceBrToEnter($(this).val());
			$(this).val(newVal);
		});
		*/
		addProductForm.validate({
			rules: {
				product_name: {
					required: true,
				},
				quantity: {
					required: true,
					range:[0,999999]
				},
				content:{
					required: true,
					byteRangeLength: [1,200]
				},
				price:{
					required: true,
					byteRangeLength: [0.01,999999.99]
				},
				old_price:{
					required: true,
					byteRangeLength: [0.01,999999.99]
				},
				agent:
				{
					required: true,
				},
				content:{
					required: true,
					maxlength: 50
				},
				note:{
					required: true
				}
			},
			messages: {
				product_name: {
					required: "请输入玩法名称",
				},
				quantity: {
					required: "请输入商品数量",
					range:"请输入商品数量，1-999999内"
				},
				content:{
					required: "请输入玩法简介",
					byteRangeLength: "最多100中文或200字符"
				},
				price:{
					required: "请输入商品价格",
					byteRangeLength: "请输入商品价格，最多0.01-999999.99内"
				},
				old_price:{
					required: "请输入商品原价",
					byteRangeLength: "请输入商品原价，最多0.01-999999.99内"
				},
				agent:
				{
					required: "请输入代售佣金",
				},
				content:{
					required: "请输入商品简介"
				},
				note:{
					required: "请输入商品描述"
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
		
        addProductForm.ajaxForm({
            dataType : 'json',
            url:'<?php echo $baseUrl.'product/add_product'?>',
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
    });

	function checkEditor(){
		if(editor.isEmpty()){
			editorTips.removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class='tips-ico'></i><p>预定须知不能为空！</p>").show();
			editSubmit.addClass("disabled");
			editSubmit.attr("disabled",true);
			return false;
		}
		else{
			editorTips.hide();
			editSubmit.removeClass("disabled");
			editSubmit.attr("disabled",false);
			return true;
		}
	}
/*
    //验证是否有上传图片，没有上传图片则不能提交表单
    function validateForm(){
        if(imgHasNum<1){
            formTips.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>请至少上传1张玩法图片!</p>");
            editSubmit.addClass("disabled");
            editSubmit.attr("disabled",true);
            return false;
        }
        else{
            formTips.hide();
            editSubmit.removeClass("disabled");
            editSubmit.attr("disabled",false);
            return true;
        }
    }*/

    KindEditor.ready(function(K) {
        K('#J_selectImage').click(function(){
            var editor = K.editor({
                allowFileManager : false,
                uploadJson : "<?php echo $baseUrl; ?>bkupload/swfImageUpload?type=product",
                imageUploadLimit:imageNum,
                imageSizeLimit:'4MB'
            });

            editor.loadPlugin('multiimage', function() {
                editor.plugin.multiImageDialog({
                    clickFn : function(urlList) {
                        var elem = K('#J_imageView');
                        K.each(urlList, function(i, data) {
                            elem.append('<li><div class="preview"><img src="<?php echo $staticUrl;?>' + data.url + '" /></div><input type="hidden" name="images[]" value="'+ data.url +'"> <a href="javascript:void(0)" class="deleteImg">删除</a></li>');
                            imageNum -= 1;
                            imgHasNum += 1;
                        });
                        imageLeft.html(imageNum);
                        imageValidate.val(imgHasNum);
                    //    validateForm();
                        if(imageNum == 0 ){
                            selectImageBtn.attr("disabled",true).addClass("disabled");
                        }
                        editor.hideDialog();
                    }
                });

            });

        });
	   K('#D_selectImage').click(function(){
            var editor = K.editor({
                allowFileManager : false,
                uploadJson : "<?php echo $baseUrl; ?>bkupload/swfImageUpload?type=product",
                imageUploadLimit:imageNum2,
                imageSizeLimit:'4MB'
            });

            editor.loadPlugin('multiimage', function() {
                editor.plugin.multiImageDialog({
                    clickFn : function(urlList) {
                        var elem = K('#D_imageView');
                        K.each(urlList, function(i, data) {
                            elem.append('<li><div class="preview"><img src="<?php echo $staticUrl;?>' + data.url + '" /></div><input type="hidden" name="detail_images[]" value="'+ data.url +'"> <a href="javascript:void(0)" class="deleteImg2">删除</a></li>');
                            imageNum2 -= 1;
                            imgHasNum2 += 1;
                        });
                        imageLeft.html(imageNum2);
                        imageValidate.val(imgHasNum2);
                 //       validateForm();
                        if(imageNum2 == 0 ){
                            selectImageBtn.attr("disabled",true).addClass("disabled");
                        }
                        editor.hideDialog();
                    }
                });

            });
			
        });
		$(document).on('click','.deleteImg',function(){
            var parentLi = $(this).parent('li');
            parentLi.remove();
            imageNum += 1;
            imgHasNum -= 1;
            imageLeft.html(imageNum);
            imageValidate.val(imgHasNum);
        //    validateForm();
            selectImageBtn.attr("disabled",false).removeClass("disabled");
        })
		$(document).on('click','.deleteImg2',function(){
            var parentLi = $(this).parent('li');
            parentLi.remove();
            imageNum2 += 1;
            imgHasNum2 -= 1;
            imageLeft.html(imageNum2);
            imageValidate.val(imgHasNum2);
        //    validateForm();
            selectImageBtn.attr("disabled",false).removeClass("disabled");
        })
    });
</script>