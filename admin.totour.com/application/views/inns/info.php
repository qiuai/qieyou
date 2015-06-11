<?php if(in_array($currentUser['role'],array(ROLE_SHOP_MANAGER,ROLE_INNHOLDER))):?>
<h3 class="headline">我的驿栈</h3>
<div class="tab">
    <ul class="clearfix">
        <li class="current"><a href="<?php echo $baseUrl;?>inns/info">1.基本资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/picture">2.驿栈图片</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/story">3.驿栈故事</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/manager">4.掌柜资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/bookingInfo">5.预订须知</a></li>
    </ul>
</div>
<?php else:?>
<h3 class="headline"><?php echo $innsInfo['inns_name'];?></h3>
<div class="tab">
    <ul class="clearfix">
        <li class="current"><a href="<?php echo $baseUrl;?>inns/info?innsid=<?php echo $innsInfo['inns_id'];?>">1.基本资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/picture?innsid=<?php echo $innsInfo['inns_id'];?>">2.驿栈图片</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/story?innsid=<?php echo $innsInfo['inns_id'];?>">3.驿栈故事</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/manager?innsid=<?php echo $innsInfo['inns_id'];?>">4.掌柜资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/bookingInfo?innsid=<?php echo $innsInfo['inns_id'];?>">5.预订须知</a></li>
    </ul>
</div>
<?php endif;?>
<form method="post" id="editInnsInfo">	
<input type="hidden" value="<?php echo $innsInfo['inns_id'];?>" name="sid">
<input type="hidden" value="info" name="act">
<table class="form table-form innInfo">
    <colgroup>
        <col class="w150">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel">区域：</td>
        <td> <?php echo $innsInfo['province'].'/' .$innsInfo['city'] ?></td>
    </tr>
    <tr>
        <td class="leftLabel">目的地：</td>
        <td><?php echo $innsInfo['dest_name']?></td>
    </tr>
    <tr>
        <td class="leftLabel">详细地址：</td>
        <td><?php echo $innsInfo['inns_address']?></td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>价格区间：</td>
        <td><label><input type="text" value="<?php echo $innsInfo['price_range']?>" class="w100" name="price_section"/></label><div class="tips tips-info"><i class="tips-ico"></i><p>如：100~500元</p></div></td>
    </tr>
	<tr>
        <td class="leftLabel"><cite>*</cite>网站首页推荐图片：</td>
        <td>
            <p>图片尺寸： 123*167px，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
            <div class="ajaxUpload">
                <div class="uploadInfo">
                    <div class="uploadBtn">
                        <span>请选择图片</span>
                        <input class="fileUpload" type="file" accept="image/*" name="imgFile"  />
                    </div>
                    <div class="progress">
                        <span class="progressBar" style="width: 0px;"></span>
                        <span class="percent">100%</span>
                    </div>
                    <div class="tips" id="imgThumbTips" style="display: none;margin-left: 10px;"></div>
                </div>
                <div class="files"></div>
                <div class="showImg"><?php if($innsInfo['inns_thumb']):?><img src="<?php echo $attachUrl.$innsInfo['inns_thumb'];?>"><span class="delImage" rel="imgFile" url="<?php echo $innsInfo['inns_thumb'];?>">删除</span><?php endif;?></div>
                <input type="hidden" value="<?php if($innsInfo['inns_thumb']) echo $innsInfo['inns_thumb'];?>" name="thumb" class="imgUrl" id="imgThumbValue" />
            </div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>目的地页面推荐图片：</td>
        <td>
            <p>图片尺寸： 648*244px，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
            <div class="ajaxUpload">
                <div class="uploadInfo">
                    <div class="uploadBtn">
                        <span>请选择图片</span>
                        <input class="fileUpload" type="file" accept="image/*" name="imgFile"  />
                    </div>
                    <div class="progress">
                        <span class="progressBar" style="width: 0px;"></span>
                        <span class="percent">100%</span>
                    </div>
                    <div class="tips" id="imgInnTips" style="display: none;margin-left: 10px;"></div>
                </div>
                <div class="files"></div>
                <div class="showImg"><?php if($innsInfo['inns_pic_list']):?><img src="<?php echo $attachUrl.$innsInfo['inns_pic_list'];?>"><span class="delImage" rel="imgFile" url="<?php echo $innsInfo['inns_pic_list'];?>">删除</span><?php endif;?></div>
                <input type="hidden" value="<?php if($innsInfo['inns_pic_list']) echo $innsInfo['inns_pic_list'];?>" name="inns_pic" class="imgUrl" id="imgInnValue" />
            </div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>驿栈地图：</td>
        <td>
            <p>图片尺寸推荐： 宽度不超过800像素，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
            <div class="ajaxUpload">
                <div class="uploadInfo">
                    <div class="uploadBtn">
                        <span>请选择图片</span>
                        <input class="fileUpload" type="file" accept="image/*" name="imgFile"  />
                    </div>
                    <div class="progress">
                        <span class="progressBar" style="width: 0px;"></span>
                        <span class="percent">100%</span>
                    </div>
                    <div class="tips" id="imgMapTips" style="display: none;margin-left: 10px;"></div>
                </div>
                <div class="files"></div>
                <div class="showImg"><?php if($innsInfo['inns_map_pic']):?><img src="<?php echo $attachUrl.$innsInfo['inns_map_pic'];?>"><span class="delImage" rel="imgFile" url="<?php echo $innsInfo['inns_map_pic'];?>">删除</span><?php endif;?></div>
                <input type="hidden" value="<?php if($innsInfo['inns_map_pic']) echo $innsInfo['inns_map_pic'];?>" name="inns_map_pic" class="imgUrl" id="imgMapValue" />
            </div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>周边商圈：</td>
        <td><label><input type="text" value="<?php echo $innsInfo['business_circle'];?>" class="w400" name="business_circle" ></label><div class="tips" style="display: none;"></div></td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>附近景点：</td>
        <td><label><input type="text" value="<?php echo $innsInfo['attraction'];?>" class="w400" name="attraction" ></label><div class="tips" style="display: none;"></div></td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>附近娱乐场所：</td>
        <td><label><input type="text" value="<?php echo $innsInfo['entertainment'];?>" class="w400" name="entertainment" ></label><div class="tips" style="display: none;"></div></td>
    </tr>
    <tr>
        <td class="leftLabel">服务设施：</td>
        <td ref="<?php echo $innsInfo['facilities'];?>" id="facilities">
            <p>
				<label><input type="checkbox" class="checkbox" name="facilities[]" value="1">免费WIFI</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="2">24小时入住</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="3">吹风机</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="4">传真</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="5">长途电话</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="6">餐厅</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="7">茶室/咖啡厅</label>
			</p>
			<p>
				<label><input type="checkbox" class="checkbox" name="facilities[]" value="8">桌球</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="9">烧烤场</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="10">酒吧</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="11">棋牌室</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="12">停车场</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="13">可携带宠物</label>
                <label><input type="checkbox" class="checkbox" name="facilities[]" value="14">可吸烟</label>
            </p>
            <label><textarea class="w400" rows="3" cols="103" name="facilities_more"><?php echo $innsInfo['facilities_more'];?></textarea></label>
            <div class="tips tips-info"><i class="tips-ico"></i><p>如：优惠提供本地各类特产，<br/>免费向导，陪同景点游览</p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>到店方式：</td>
        <td><label><textarea class="w400 textEdit" rows="3" cols="103" name="traffic_info"><?php echo $innsInfo['traffic_info'];?></textarea></label><div class="tips tips-info"><i class="tips-ico"></i><p>如：自驾线路，打的线路与公交线路</p></div></td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td style="padding-top: 15px;"><input class="submit editSubmit mr10" type="submit" value="保存" /><input class="button editSubmit mr20" type="submit" id="saveNext" value="保存并进入下一步" /><div class="tips tips-ok" id="formTips" style="display: none;"></div></td>
    </tr>
    </tbody>
</table>
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/ajaxUpload.js"></script>
<script type="text/javascript">
    $(function(){

        var textEdit = $('.textEdit');
        var editForm = $('#editInnsInfo');
        var editSubmit = $('.editSubmit');
		var imgThumbTips = $('#imgThumbTips');
        var imgThumbValue= $('#imgThumbValue');
        var imgInnTips = $('#imgInnTips');
        var imgInnValue= $('#imgInnValue');
        var imgMapTips = $('#imgMapTips');
        var imgMapValue= $('#imgMapValue');
        var formTips = $("#formTips");

        textEdit.each(function(index, domEle){
            var newVal = replaceBrToEnter($(domEle).val());
            $(domEle).val(newVal);

        });

        textEdit.focus(function(){
            var newVal = replaceBrToEnter($(this).val());
            $(this).val(newVal);
        });

        /**编辑基本资料表单前端验证**/
        editForm.validate({
            rules: {
                price_section:{
                    required: true
                },
                business_circle: {
                    required: true
                },
                attraction:{
                    required: true
                },
                entertainment:{
                    required: true
                },
                traffic_info:{
                    required: true
                }
            },
            messages: {
                price_section:{
                    required: "请输入价格区间"
                },
                business_circle: {
                    required: "请输入周边商圈"
                },
                attraction:{
                    required: "请输入附近景点"
                },
                entertainment:{
                    required: "请输入附近娱乐场所"
                },
                traffic_info:{
                    required: "请输入到店方式"
                }
            }, errorPlacement: function(error, element) {

                var tripEle = element.parent("label").siblings(".tips");

                if(error.text()){
                    tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                    editSubmit.addClass("disabled").attr("disabled",true);
                }
                else{
                    tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                    editSubmit.removeClass("disabled").attr("disabled",false);
                }

            },
            ignore:'',
            success:function(label){

            }
        });
        imgThumbValue.change(function(){

            validateForm($(this).val(),imgThumbTips,editSubmit,'网站首页推荐图片');
        });

        imgInnValue.change(function(){

            validateForm($(this).val(),imgInnTips,editSubmit,'目的地页面推荐图片');
        });

        imgMapValue.change(function(){

            validateForm($(this).val(),imgMapTips,editSubmit,'驿栈地图');
        });
        editForm.submit(function(){
            textEdit.each(function(index, domEle){
                var newVal = replaceEnterToBr($(domEle).val());
                $(domEle).val(newVal);

            });

        });

        editSubmit.click(function(){

            validateForm(imgThumbValue.val(),imgThumbTips,editSubmit,'网站首页推荐图片')&&
            validateForm(imgInnValue.val(),imgInnTips,editSubmit,'目的地页面推荐图片')&&
            validateForm(imgMapValue.val(),imgMapTips,editSubmit,'驿栈地图');

            editForm.ajaxForm({
                dataType : 'json',
                url:'<?php echo $baseUrl.'inns/updateInninfo'?>',
                success : function(data){
                    if(data.code == 1){

                        formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存基本资料成功！</p>").show().fadeOut(5000);
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


        $("#saveNext").click(function(){
            editForm.ajaxForm({
                dataType : 'json',
                url:'<?php echo $baseUrl.'inns/updateInninfo'?>',
                success : function(data){
                    if(data.code == 1){

                        formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存基本资料成功！</p>").show().fadeOut(5000);
                        setTimeout(function(){
                            window.location.href="<?php echo $baseUrl;?>inns/picture?innsid=<?php echo $innsInfo['inns_id'];?>";
                         },1000);

                    }
                    else{
                        layer.alert(data.msg ,3,"提示");
                    }
                }
            });
        });


        /**循环服务设施Checkbox选中状态**/
        var checkbox = $('#facilities .checkbox');
        var valueArr =  $('#facilities').attr("ref").split(',');
        if(valueArr.length>0){
            for(var i=0;i<valueArr.length;i++){
                checkbox.eq(valueArr[i]-1).attr("checked",true);
            }
        }


    });
</script>