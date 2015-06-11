<h3 class="headline">帖子抓取</h3>
<form method="post" id="form_sub">
<div class="form table-form">
<label><input type="text" value="" class="w450" style="margin:45px;margin-bottom:45px;margin-left:300px" name="f_url" id="f_url" placeholder="请输入帖子链接，目前仅支持8264网站帖子内容抓取">   <input type="button"  class="buttonG" id="re_forum" value="抓取" style="width:80px;">  </label>
	<div class="tips"  style=" margin-top:45px;display: none;"></div>
</div>

<div style="float:left;border-right:1px solid #ccc;">  
<table class="form table-form">
    <colgroup>
        <col class="w50">
        <col>
    </colgroup>
    <tbody>
	<tr>
        <td class="leftLabel"><cite>*</cite>标题：</td>
        <td><label><input type="text" value="" class="w450" name="title" id="title" ></label>
         <div class="tips" style="display: none;"></div> 	
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>内容：</td>
        <td><label><textarea class="w450" rows="20" cols="50" name="content" id="content"></textarea></label>   
         <div class="tips" style="display: none;"></div>  
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>图片：</td>
        <td><label>
            <ul class="fetch-tie-list" id="img">
                <li class="add"> 
                <div class="ajaxUpload">
                    <div class="uploadInfo">
                        <div class="uploadBtn">
                            <span>请选择图片</span>
                            <input class="fileUpload" type="file"  ref="grouphead" accept="image/*" name="imgFile"  />
                        </div>
                        <div class="tips" id="imgThumbTips" style="display: none;margin-left: 10px;"></div>
                    </div>
                    <div class="files"></div>
                    <div class="showImg"></div>
              <input type="hidden" value="" name="group_img" class="imgUrl" />
            </div></li>
            </ul>
       	 </label>  
        </td>
    </tr>
    </tbody>
</table>
</div>
<div style="width:500px;float:left;">
<table class="form table-form">
    <tbody>
	<tr>
        <td class="leftLabel"><cite>*</cite>发帖人账号：</td>
        <td><label><input type="text" value="<?php echo $info['user_name'];?>" class="w200" name="user_name" id="user_name" ></label>
          <input class="buttonG" type="button" id="re_user" value="搜索">&nbsp;<input class="buttonG" type="button" id="suiji_user" value="随机"></td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>头像：</td>
        <td>
        	<label><?php if($info['headimg']){?><img id="headimg" name="headimg" src="<?php echo $staticUrl.$info['headimg'];?>"><?php }?><label>
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite>*</cite>呢称：</td>
        <td><label><input type="text" value="<?php echo $info['nick_name'];?>" class="w200" readonly="readonly" name="nick_name" id="nick_name" ></label>    
        </td>
    </tr>
     <tr>
        <td class="leftLabel"><cite></cite>性别：</td>
        <td>
 			 <label><input type="text" value="<?php echo $info['sex'];?>" class="w70" readonly="readonly" name="sex" id="sex" ></label> 
            年龄：<label><input type="text" value="" class="w60" readonly="readonly" name="age" id="age" ></label>  
        </td>
    </tr>
     <tr>
        <td class="leftLabel"><cite>*</cite>帖子类型：</td>
        <td><label>
                <select name="type" id="type">
                    <option value="tour">游记</option>
                </select>
            </label>  
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>标签：</td>
        <td><label><input type="text" value="" class="w200" name="tags" id='tags'></label>    
        </td>
    </tr>
     <tr>
        <td class="leftLabel"><cite></cite>发表至部落(ID)：</td>
        <td><label><input type="text" value="<?php echo $info['group_id'];?>" class="w200" id="group_id" name="group_id"></label>    
       <input class="buttonG" type="button" id="re_group" value="搜索">&nbsp;<input class="buttonG" id="suiji_group" type="button" value="随机"> </td>
    </tr>
     <tr>
        <td class="leftLabel"><cite>*</cite>发帖时间：</td>
        <td><label class="mr20"><input type="text" id="create_time" name="create_time" onfocus="WdatePicker({startDate:'%y-%M-%d 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',doubleCalendar:true,minDate:'{%y-1}-%M-%d',maxDate:'%y-%M-%d'})" value="<?php echo $info['time']?date('Y-m-d H:i:s',$info['time']):'';?>" name="create_time" title="请选择开始日期" class="Wdate" style="width:140px;background-position-x: 128px!important;"></label>
        </td>
    </tr>
     <tr>
        <td class="leftLabel"><cite></cite>发帖地点：</td>
        <td><label><input type="text" value="<?php echo $info['address'];?>" class="w200" name="address" id="address"></label>  <input class="buttonG" type="button" id="suiji_address" value="随机">  
        </td>
    </tr>
    <tr>
        <td class="leftLabel"><cite></cite>坐标：</td>
        <td>经度：<label><input type="text" value="<?php echo $info['lon'];?>" class="w100" name="lon" id="lon"></label>
			纬度：<label><input type="text" value="<?php echo $info['lat'];?>" class="w100" name="lat" id="lat"> </label>
			</br><div class="tips tips-info" style="margin-top:5px; "><i class="tips-ico"></i><p>如果自填地址，使用 <a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank" style="color:red;font-weight:bold;" title="点击打开">百度地图坐标拾取系统</a> </p></div>
        </td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td>
            <input class="submit mr20" type="submit" id="add_Button" value="确认发布"><div class="tips tips-ok" id="formTips" style="display: none;"></div>
        </td>
    </tr>
    </tbody>
</table>
</div>
<div style="clear:both;"></div>
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js"></script>    
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<!-- <script type="text/javascript" src="<?php echo $staticUrl;?>js/ajaxUpload.js"></script> -->

<script type="text/javascript">
	$(function(){
		var form_sub= $("#form_sub");
		var addSubmit = $('#add_Button');
		var formTips = $("#formTips");
		form_sub.validate({
			rules: {
				f_url:{
					required: true,
					maxlength: 200
				},	
			},
			messages: {	
				f_url:{
					required: "请输入帖子URL"
				},
			}, errorPlacement: function(error, element) {
				var tripEle = element.parent("label").siblings(".tips");
				if(error.text()){
					tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
					addSubmit.addClass("disabled").val('发布中...');
					addSubmit.attr("disabled",true);
				}
				else{
					tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
					addSubmit.removeClass("disabled").val('确认发布');
					addSubmit.attr("disabled",false);
				}
			},
			success:function(label){
			}
		});
        var isPost = 0;
		form_sub.ajaxForm({	
          	dataType : 'json',
			type:'POST',
            url:'<?php echo $baseUrl.'forums/add_forum'?>',
            beforeSubmit: function(){
                if( isPost ) return;
                isPost = 1;
                addSubmit.addClass("disabled").val('发布中...');
            },
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.href='<?php echo $baseUrl.'forums/add_forum';?>';
                    },1000);
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
                isPost = 0;
                addSubmit.removeClass("disabled").val('确认发布');
            }
        });
		
        var isSubmit = 0;
		$('#re_forum').click(function() {
            if( isSubmit ) return;
            var self = $(this);
			var f_url= $('#f_url').val();	
			if(f_url==''){
				alert('请输入帖子URL');
				return false;
			}
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'forums/re_forum';?>",
				data: {f_url:f_url},
                beforeSend: function(){
                    isSubmit = 1;
                    self.addClass('disabled').val('抓取中...');
                },
				success: function(date){
					 if(date.title){    	 
						$("#title").val(date.title);
						$("#content").val(date.content);
                        var imgs = date.img, tpl = [];
                        $.each(imgs, function(k, v){
                            v && tpl.push('<li><img src="'+v+'"><a class="del" href="javascript:;"></a><input type="hidden" name="img[]" value="'+v+'"></li>');
                        });
						add = $("#img .add");
                        add.siblings('li').remove();
                        add.before(tpl.join(''));
					 }else{
						layer.alert(date.msg ,3,"提示");
					 }
				},
                complete: function(){
                    isSubmit = 0;
                    self.removeClass('disabled').val('抓取');
                }
			})	
		});
		$('#re_user').click(function() {
			var user_name= $('#user_name').val();	
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'forums/re_user';?>",
				data: {user_name:user_name},
				success: function(date){
					 if(date.nick_name){   
					 	$("#nick_name").val(date.nick_name);
						$("#sex").val(date.sex);
						$("#age").val(date.age);
						$("#headimg").attr('src','<?php echo $staticUrl;?>'+date.headimg);
					 }else{
						layer.alert(date.msg ,3,"提示");
					 }
				}
			})	
		});
		$('#suiji_user').click(function() {
			var user_name= $('#user_name').val();	
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'forums/suiji_user';?>",
				data: {user_name:user_name},
				success: function(date){
					 if(date.nick_name){    	 
						$("#user_name").val(date.user_name);
					 	$("#nick_name").val(date.nick_name);
						$("#sex").val(date.sex);
						$("#age").val(date.age);
						$("#headimg").attr('src','<?php echo $staticUrl;?>'+date.headimg);
					 }else{
						layer.alert(date.msg ,3,"提示");
					 }
				}
			})	
		});
		$('#re_group').click(function() {
			var group_id= $('#group_id').val();	
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'forums/re_group';?>",
				data: {group_id:group_id},
				success: function(date){
					layer.alert(date.msg ,3,"提示");
				}
			})	
		});
		$('#suiji_group').click(function() {
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'forums/suiji_group';?>",
				success: function(date){
					 if(date.group_id){    	 
						 $("#group_id").val(date.group_id);
					 }else{
						layer.alert(date.msg ,3,"提示");
					 }
				}
			})	
		});
		$('#suiji_address').click(function() {
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'forums/suiji_address';?>",
				success: function(date){
					 if(date.address){    	 
						 $("#address").val(date.address);
						 $("#lon").val(date.lon);
					 	 $("#lat").val(date.lat);
					 }else{
						layer.alert(date.msg ,3,"提示");
					 }
				}
			})	
		});
		
        $('#img').on('click', '.del', function(){
            //if( !confirm('是否删除此图片？') )return;
            $(this).closest('li').remove();
        });

        /**ajax图片上传功能**/
        var fileUpload = $(".fileUpload");
        fileUpload.change(function(){
            $(this).wrap("<form class='myUpload' action='' method='post' enctype='multipart/form-data'></form>");
            var ajaxUpload = $(this).parents(".ajaxUpload");
            var inputName = $(this).attr("name");
            var getType = $(this).attr("ref");
            var ajaxUrl = baseUrl+"bkupload/uploadImage";
            var progressBar = ajaxUpload.find(".progressBar");
            var showImg = ajaxUpload.closest('li');
            var progress = ajaxUpload.find(".progress");
            var percent = ajaxUpload.find(".percent");
            var files = ajaxUpload.find(".files");
            var imgUrl = ajaxUpload.find(".imgUrl");
            var btn = ajaxUpload.find(".uploadBtn span");
            var myUpload = ajaxUpload.find(".myUpload");
            var fileUploading = $(this);
            myUpload.ajaxSubmit({
                dataType:  'json',
                url: ajaxUrl+ (getType?('?type='+getType):''),
                beforeSend: function() {
                    btn.html("上传中...");
                    files.html("<b>请耐心等候...</b>");
                    fileUploading.hide();
                    progress.show();
                    var percentVal = '0%';
                    progressBar.width(percentVal);
                    percent.html(percentVal);
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    progressBar.width(percentVal);
                    percent.html(percentVal);
                },
                // complete: function(xhr) {
                //  $(".files").html(xhr.responseText);
                //  },
                success: function(data) {
                    if(data.error == 0){
                        files.html("");
                        showImg.before('<li><img src="'+staticUrl+data.url+'"><a class="del" href="javascript:;"></a><input type="hidden" name="img[]" value="'+staticUrl+data.url+'"></li>');
                        btn.html("上传成功！");
                        imgUrl.val(data.url).change();
                    }
                    else{
                        btn.html("上传失败！");
                        progress.hide();
                        files.html("<b>请重新选择文件上传！</b>");
                    }
                    var ua=navigator.userAgent.toLowerCase();
                    var isIE11 = false;

                    if (ua.match(/msie/) != null || ua.match(/trident/) != null) {
                        //浏览器类型
                        browserType = "IE";
                        //浏览器版本
                        browserVersion = ua.match(/msie ([\d.]+)/) != null ? ua.match(/msie ([\d.]+)/)[1] : ua.match(/rv:([\d.]+)/)[1];
                        if(Math.round(browserVersion) == 11)
                        {
                            isIE11 = true;
                        }
                    }
                    if(!isIE11)
                    {
                        fileUploading.unwrap().val("").show();
                    }
                },
                error:function(xhr){
                    btn.html("上传失败！");
                    progress.hide();
                    files.html("<b>请重新选择文件上传！</b>");
                    fileUploading.unwrap().val("").show();
                }
            });
        });

    //验证是否有上传图片，没有上传图片则不能提交表单
    function validateForm(imgValue,imgTips,editSubmit,error){

        if(imgValue == '' || imgValue == undefined){
            imgTips.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>请上传"+error+"!</p>");
            editSubmit.addClass("disabled");
            editSubmit.attr("disabled",true);
            return false;
        }
        else{
            imgTips.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
            editSubmit.removeClass("disabled");
            editSubmit.attr("disabled",false);
            return true;
        }
    }

	});
		
</script>