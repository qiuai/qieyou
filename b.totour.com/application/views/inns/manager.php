<?php if(in_array($currentUser['role'],array(ROLE_SHOP_MANAGER,ROLE_INNHOLDER))):?>
<h3 class="headline">我的驿栈</h3>
<div class="tab">
    <ul class="clearfix">
        <li><a href="<?php echo $baseUrl;?>inns/info">1.基本资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/picture">2.驿栈图片</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/story">3.驿栈故事</a></li>
        <li class="current"><a href="<?php echo $baseUrl;?>inns/manager">4.掌柜资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/bookingInfo">5.预订须知</a></li>
    </ul>
</div>
<?php else:?>
<h3 class="headline"><?php echo $innsInfo['inns_name'];?></h3>
<div class="tab">
    <ul class="clearfix">
        <li><a href="<?php echo $baseUrl;?>inns/info?innsid=<?php echo $innsInfo['inns_id'];?>">1.基本资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/picture?innsid=<?php echo $innsInfo['inns_id'];?>">2.驿栈图片</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/story?innsid=<?php echo $innsInfo['inns_id'];?>">3.驿栈故事</a></li>
        <li class="current"><a href="<?php echo $baseUrl;?>inns/manager?innsid=<?php echo $innsInfo['inns_id'];?>">4.掌柜资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/bookingInfo?innsid=<?php echo $innsInfo['inns_id'];?>">5.预订须知</a></li>
    </ul>
</div>
<?php endif;?>
<form method="post" id="editInnerInfo">
<input type="hidden" value="<?php echo $innsInfo['inns_id'];?>" name="sid">
<input type="hidden" value="manager" name="act">
<table class="form table-form">
    <colgroup>
        <col class="w150">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel"><cite>*</cite>掌柜名称：</td>
        <td><label><input type="text" value="<?php echo empty($inn_manager['manager_name'])?'':$inn_manager['manager_name'];?>" class="w400" name="manager_name"></label><div class="tips tips-info"><i class="tips-ico"></i><p>中英文皆可，12字以内</p> </div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>籍贯：</td>
        <td><label><input type="text" value="<?php echo empty($inn_manager['manager_native'])?'':$inn_manager['manager_native'];?>" class="w400" name="manager_native"></label><div class="tips tips-info"><i class="tips-ico"></i><p>中英文皆可，12字以内</p> </div>
        </td>
    </tr>

    <tr>
        <td class="leftLabel"><cite>*</cite>掌柜头像：</td>
        <td> 图片格式：jpg、png，图片推荐尺寸：210x310像素<br/>
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
                    <div class="tips" id="imgTips" style="display: none;margin-left: 10px;"></div>
                </div>
                <div class="files"></div>
                <div class="showImg"><?php if($inn_manager['manager_face']):?><img src="<?php echo $attachUrl.$inn_manager['manager_face'];?>"><span class="delImage" rel="imgFile" url="<?php echo $inn_manager['manager_face'];?>">删除</span><?php endif;?></div>
                <input type="hidden" value="<?php echo $inn_manager['manager_face'];?>" name="manager_face" class="imgUrl" id="imgValue" />
            </div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">个人空间：</td>
        <td>
            <table class="w400 mb5">
                <colgroup>
                    <col class="wp20">
                    <col class="wp30">
                    <col class="wp50">
                </colgroup>
                <tbody>
                <tr>
                    <th>
                        <span class="pl10">空间名称</span>
                    </th>
                    <th>
                        <span class="pl10">昵称</span>
                    </th>
                    <th>
                        <span class="pl10">访问地址</span>
                    </th>
                </tr>
                <tr>
                    <td>
                        <label><input class="w70" value="<?php echo $inn_manager['manager_homepage']['homepage_name1'];?>" name="homepage_name1" type="text"></label>
                    </td>
                    <td>
                        <label><input class="w100" value="<?php echo $inn_manager['manager_homepage']['homepage_desc1'];?>" name="homepage_desc1" type="text"></label>
                    </td>
                    <td>
                        <label><input class="w200" value="<?php echo $inn_manager['manager_homepage']['homepage_url1'];?>" name="homepage_url1" type="text"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><input class="w70" value="<?php echo $inn_manager['manager_homepage']['homepage_name2'];?>" name="homepage_name2" type="text"></label>
                    </td>
                    <td>
                        <label><input class="w100" value="<?php echo $inn_manager['manager_homepage']['homepage_desc2'];?>" name="homepage_desc2" type="text"></label>
                    </td>
                    <td>
                        <label><input class="w200" value="<?php echo $inn_manager['manager_homepage']['homepage_url2'];?>" name="homepage_url2" type="text"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><input class="w70" value="<?php echo $inn_manager['manager_homepage']['homepage_name3'];?>" name="homepage_name3" type="text"></label>
                    </td>
                    <td>
                        <label><input class="w100" value="<?php echo $inn_manager['manager_homepage']['homepage_desc3'];?>" name="homepage_desc3" type="text"></label>
                    </td>
                    <td>
                        <label><input class="w200" value="<?php echo $inn_manager['manager_homepage']['homepage_url3'];?>" name="homepage_url3" type="text"></label>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="tips tips-info"><i class="tips-ico"></i><p>空间名称：如新浪微博，豆瓣，QQ空间等；访问地址：请填写不带http:// 的地址</p> </div> </td>
    </tr>

    <tr>
        <td class="leftLabel">掌柜的话：</td>
        <td><label><textarea class="w400" rows="5" cols="103" name="manager_word"><?php echo empty($inn_manager['manager_word'])?'':$inn_manager['manager_word'];?></textarea></label><div class="tips tips-info"><i class="tips-ico"></i><p>100字以内</p> </div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td style="padding-top: 15px;"><input class="submit editSubmit mr10" type="submit" value="保存"/><input class="button editSubmit mr20" type="submit" id="saveNext" value="保存并进入下一步" /><div class="tips tips-ok" id="formTips" style="display: none;"></div></td>
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

        var editForm = $('#editInnerInfo');
        var editSubmit = $('.editSubmit');
        var imgTips = $('#imgTips');
        var imgValue= $('#imgValue');
        var formTips = $("#formTips");

        /**编辑掌柜信息表单前端验证**/
        editForm.validate({
            rules: {
                manager_name: {
                    required: true,
                    byteRangeLength: [1,24]
                },
                manager_native:{
                    required: true,
                    byteRangeLength: [1,24]
                },
                manager_face:{
                    required: true
                },
                manager_word:{
                    byteRangeLength: [1,200]
                }
            },
            messages: {

                manager_name: {
                    required: "请输入掌柜名称",
                    byteRangeLength : "不能超过12字"
                },
                manager_native:{
                    required: "请输入籍贯",
                    byteRangeLength : "不能超过12字"
                },
                manager_face:{
                    required: "请上传掌柜头像"
                },
                manager_word:{
                    byteRangeLength: "掌柜的话不能超过100中文或200字符"
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

        editSubmit.click(function(){

            validateForm(imgValue.val(),imgTips,editSubmit,'掌柜头像');
        });

        imgValue.change(function(){

            validateForm($(this).val(),imgTips,editSubmit,'掌柜头像');
        });

        editForm.ajaxForm({
            dataType : 'json',
			url:'<?php echo $baseUrl.'inns/updateInninfo'?>',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存掌柜信息成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.reload()
                    },1000);
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        });

        $("#saveNext").click(function(){
            editForm.ajaxForm({
                dataType : 'json',
				url:'<?php echo $baseUrl.'inns/updateInninfo'?>',
                success : function(data){
                    if(data.code == 1){
                        formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存基本资料成功！</p>").show().fadeOut(5000);
                        setTimeout(function(){
                            window.location.href="<?php echo $baseUrl;?>inns/bookingInfo?innsid=<?php echo $innsInfo['inns_id'];?>";
                        },1000);

                    }
                    else{
                        layer.alert(data.msg ,3,"提示");
                    }
                }
            });
        });


    });
</script>