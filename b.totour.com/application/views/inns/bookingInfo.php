<?php if(in_array($currentUser['role'],array(ROLE_SHOP_MANAGER,ROLE_INNHOLDER))):?>
<h3 class="headline">我的驿栈</h3>
<div class="tab">
    <ul class="clearfix">
        <li><a href="<?php echo $baseUrl;?>inns/info">1.基本资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/picture">2.驿栈图片</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/story">3.驿栈故事</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/manager">4.掌柜资料</a></li>
        <li class="current"><a href="<?php echo $baseUrl;?>inns/bookingInfo">5.预订须知</a></li>
    </ul>
</div>
<?php else:?>
<h3 class="headline"><?php echo $innsInfo['inns_name'];?></h3>
<div class="tab">
    <ul class="clearfix">
        <li><a href="<?php echo $baseUrl;?>inns/info?innsid=<?php echo $innsInfo['inns_id'];?>">1.基本资料</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/picture?innsid=<?php echo $innsInfo['inns_id'];?>">2.驿栈图片</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/story?innsid=<?php echo $innsInfo['inns_id'];?>">3.驿栈故事</a></li>
        <li><a href="<?php echo $baseUrl;?>inns/manager?innsid=<?php echo $innsInfo['inns_id'];?>">4.掌柜资料</a></li>
        <li class="current"><a href="<?php echo $baseUrl;?>inns/bookingInfo?innsid=<?php echo $innsInfo['inns_id'];?>">5.预订须知</a></li>
    </ul>
</div>
<?php endif;?>
<form method="post" id="editBookingInfo">
<input type="hidden" value="<?php echo $innsInfo['inns_id'];?>" name="sid">
<input type="hidden" value="booking" name="act">
<table class="form table-form">
    <colgroup>
        <col class="w150">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel">关于入住：</td>
        <td><label><textarea class="w500 textEdit"  name="booking_info_1" rows="5" cols="103"><?php echo $innsInfo['booking_info_1'];?></textarea></label></td>
    </tr>
    <tr>
        <td class="leftLabel">关于续住：</td>
        <td><label><textarea class="w500 textEdit" name="booking_info_2" rows="5" cols="103"><?php echo $innsInfo['booking_info_2'];?></textarea></label></td>
    </tr>
    <tr>
        <td class="leftLabel">关于退房：</td>
        <td><label><textarea class="w500 textEdit" name="booking_info_3" rows="5" cols="103"><?php echo $innsInfo['booking_info_3'];?></textarea></label></td>
    </tr>
    <tr>
        <td class="leftLabel">关于退款：</td>
        <td><label><textarea class="w500 textEdit" name="booking_info_4" rows="5" cols="103"><?php echo $innsInfo['booking_info_4'];?></textarea></label></td>
    </tr>
    <tr>
        <td class="leftLabel">其他说明：</td>
        <td><label><textarea class="w500 textEdit" name="booking_info_5" rows="5" cols="103"><?php echo $innsInfo['booking_info_5'];?></textarea></label></td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td><input class="submit mr20" type="submit" value="保存" /><div class="tips tips-ok" id="formTips" style="display: none;"></div></td>
    </tr>
    </tbody>
</table>
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript">

    $(function(){
        var textEdit = $('.textEdit');
        var editForm = $('#editBookingInfo');
        var formTips = $("#formTips");

        textEdit.each(function(index, domEle){
            var newVal = replaceBrToEnter($(domEle).val());
            $(domEle).val(newVal);

        });

        textEdit.focus(function(){
            var newVal = replaceBrToEnter($(this).val());
            $(this).val(newVal);
        });

        editForm.submit(function(){
            textEdit.each(function(index, domEle){
                var newVal = replaceEnterToBr($(domEle).val());
                $(domEle).val(newVal);

            });
        });

        editForm.ajaxForm({
            dataType : 'json',
			url:'<?php echo $baseUrl.'inns/updateInninfo'?>',
            success : function(data){
                if(data.code == 1){

                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>保存预订须知成功！</p>").show().fadeOut(5000);
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
</script>