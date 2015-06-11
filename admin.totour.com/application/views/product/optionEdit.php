<h3 class="headline">编辑商品</h3>
<div class="menuWrap clearfix">
	<div class="content">
        <form id="editProductForm" action="<?php echo $baseUrl.'product/add'?>" method="post">
        <input type="hidden" value="<?php echo $product['product_id']?>" name="pid">
		<table class="form table-form">
			<colgroup>
				<col class="w120">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<td class="leftLabel"><cite>*</cite>商品名称：</td>
				<td><label><input type="text" value="<?php echo $product['product_name'];?>" class="w200" name="product_name"></label><div class="tips tips-info"><i class="tips-ico"></i><p>名称30字以内</p></div>
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
					<label><input type="text" value="<?php echo $product['quantity']?>" class="w60" name="quantity" ></label>
					<div class="tips tips-info" style="display: none;"><i class="tips-ico"></i><p>1~10亿</p></div>
				</td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>原始价格：</td>
                <td>
                    <label class="mr20"><input type="text" value="<?php echo $product['old_price']?>" class="w60" name="old_price"> 元</label>
                    <div class="tips tips-info" style="display: none;"><i class="tips-ico"></i><p>1~99万</p></div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>当前售价：</td>
                <td>
                    <label class="mr20"><input type="text" value="<?php echo $product['price'];?>" class="w60" name="price"> 元</label>
					<div class="tips tips-info" style="display: none;"><i class="tips-ico"></i><p>1~99万</p></div>
					<input type="hidden" value="0" class="w50" name="purchase_price">
					<input type="hidden" value="0" class="w50" name="agent">
                </td>
            </tr>
			<tr>
                <td class="leftLabel"><cite>*</cite>实物商品：</td>
                <td>
					<label><input type="radio" class="radio" name="is_express" value="1" <?php if($product['is_express']) echo 'checked=""';?>>是</label>
					<label class="ml15"><input type="radio" class="radio" name="is_express" value="0" <?php if(!$product['is_express']) echo 'checked=""';?>>否</label>
					实物商品在下单时需要填写物流信息，收件人，收件地址，收件手机
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>截至日期：</td>
                <td>
                    <label class="mr20"><input type="text" id="tuan_end_time" onfocus="WdatePicker({startDate:'%y-%M-{%d+1} 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',doubleCalendar:true,minDate:'%y-%M-%d',maxDate:'{%y+1}-%M-%d'})" value="<?php echo date('Y-m-d H:i:s',$product['tuan_end_time']);?>" name="tuan_end_time" title="请选择开始日期" class="Wdate" style="width:140px;background-position:128px 6px !important;"></label>
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
						1、点击“<cite>添加图片</cite>”，最多上传<cite>5 </cite>张照片；<br/>
						2、按着“<cite>ctrl</cite>” 键可以一次选择多张照片，选完后点击“<cite>开始上传</cite>”；<br/>
						3、全部上传成功后点击“<cite>全部插入</cite>”；点击“<cite>保存</cite>”按钮保存修改。</label>
				</td>
			</tr>
			<tr>
				<td class="leftLabel"><cite>*</cite>首页图片：</td>
				<td>
					<div class="fl wp50 pb10">
					<label><input type="button" value="图片批量上传" class="button" id="J_selectImage" name="name"></label>
					<div class="tips tips-info"><i class="tips-ico"></i><p>您还可以上传 <em id="imageLeft"><?php echo $product['product_images']?(5-count(explode(',',$product['product_images']))):5;?></em> 张图片</p></div>
					</div>
					<div class="fl wp50 pb10">
					详情图片：
					<label><input type="button" value="图片批量上传" class="button" id="D_selectImage" name="name"></label>
					<div class="tips tips-info"><i class="tips-ico"></i><p>您还可以上传 <em id="imageLeft2"><?php echo $product['detail_images']?(100-count(explode(',',$product['detail_images']))):100;?></em> 张图片</p></div>
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
							<?php if(!empty($product['detail_images'])):?>
								<?php $images = explode(',',$product['detail_images']); ?>
								<?php foreach($images as $key => $image):?>
								<li><div class="preview"><img src="<?php echo $staticUrl.$image;?>" alt=""/></div>
									<input type="hidden" name="detail_images[]" value="<?php echo $image;?>"> <a href="javascript:void(0)" class="deleteImg2">删除</a>
								</li>
								<?php endforeach;?>
								<?php $key += 1;?>
							<?php else:?>
							<?php $key = 0;?>
							<li>上传照片后将在此处预览和编辑</li>
							<?php endif;?>
						</ul>
					</div>
				</td>
			</tr>
			<tr>
				<td class="leftLabel">当前商品状态：</td>
				<td>
					<?php switch($product['state']){case 'Y': echo '在商户店铺中<em class="g">上架</em>';break;case 'T': echo '在且游团购中上架';break;case 'N': echo '已下架';break;}?>
				</td>
			</tr>
			<tr>
				<td class="leftLabel">&nbsp;</td>
				<td class="submitBtnList">
					<input class="submit editSubmit mr10" type="submit" value="保存">
					<?php if($product['state'] == 'N'):?>
					<input class="submit buttonG mr10" type="submit" value="上架成商户普通商品">
					<a class="submit buttonG mr10" href="<?php echo $baseUrl.'product/tuanedit?pid='.$product['product_id'].'&act=addtuan';?>">上架成团购</a>
					<?php endif;?>
					<?php if($product['state'] == 'Y'):?>
					<a class="submit buttonG mr10" href="<?php echo $baseUrl.'product/tuanedit?pid='.$product['product_id'].'&act=addtuan';?>">上架成团购</a>
					<input class="changeProductBtn buttonH" <?php echo 'ref="'.$product['product_id'].'"';?> change="N"  value="下架">
					<?php endif;?>
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

    var imageNum =  <?php echo 5-(isset($key)?$key:0);?>;
    var imgHasNum = <?php echo isset($key)?$key:0;?>;
    var imageNum2 =  <?php echo 100-(isset($key)?$key:0);?>;
    var imgHasNum2 = <?php echo isset($key)?$key:0;?>;
    var selectImageBtn = $("#J_selectImage");
    var selectImageBtn2 = $("#D_selectImage");
    var imageLeft = $('#imageLeft');
    var imageValidate = $('#imgNum');
    var editProductForm = $('#editProductForm');
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

        $.initLocalSelect(<?php echo $product['category'];?>,<?php echo $product['category_id'];?>);
		editProductForm.validate({
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
					maxlength: 50		
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
					required: "请输入商品简介"
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

        editProductForm.ajaxForm({
            dataType : 'json',
            url:'<?php echo $baseUrl.'product/editProduct'?>',
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

    KindEditor.ready(function(K) {
        K('#J_selectImage').click(function(){
            var editor = K.editor({
                allowFileManager : false,
                uploadJson : "<?php echo $baseUrl; ?>bkupload/swfImageUpload?type=product",
                imageUploadLimit:imageNum,
                imageSizeLimit:'2MB'
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
                     //   validateForm();
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
                imageSizeLimit:'2MB'
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
                   //     validateForm2();
                        if(imageNum2 == 0 ){
                            selectImageBtn2.attr("disabled",true).addClass("disabled");
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
