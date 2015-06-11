<h3 class="headline">在线充值<span class="more"><b>当前账户余额：<em class="mr10">¥<?php echo $balance;?></em>，累计充值金额：<em>¥<?php echo $totalAmout;?></em></b></span></h3>
<form method="post" action="<?php echo $baseUrl.'inns/addcashin';?>" id="applyCharge" style="min-height: 300px;">
    <table class="form table-form">
        <colgroup>
            <col class="w120"/>
            <col/>
        </colgroup>
        <tbody>
        <tr>
            <td class="leftLabel"><b>请输入金额：</b></td>
            <td><label><input type="text" class="w50" value="" name="amount"> 元<cite>*</cite></label>
                <div class="tips tips-info">
                    <i class="tips-ico"></i>
                    <p>单次充值金额需大于100元，小于5000元</p>
                </div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><b>备注：</b></td>
            <td><label><input type="text" class="w350" value="" name="comments" placeholder="选填，20字内"/></label>
            </td>
        </tr>
        <tr>
            <td class="leftLabel">&nbsp;</td>
            <td style="padding-top: 20px;"><input class="buttonG mr10"  id="applyBtn" type="submit" value="确认充值" />
                <input class="buttonH" type="reset" value="重置" />
            </td>
        </tr>
    </table>
</form>
<form action="https://secure.doyouhike.net/trans/" method="post" id="payment">
    <input type="hidden" name="trans" id="paymentInfo" value="" />
</form>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript">
    $(function(){

        var applyCharge = $('#applyCharge');
        var applyBtn = $('#applyBtn');
        var payment = $('#payment');
        var paymentInfo =  $('#paymentInfo');

        applyCharge.validate({
            rules: {


                amount:{
                    required: true,
                    digits:true,
                    min:100,
                    max:5000
                }
            },
            messages: {


                amount:{
                    required: "请输入充值金额",
                    digits:"请输入整数",
                    min:"最低充值金额100元",
                    max:"最高充值金额5000元"
                }
            }, errorPlacement: function(error, element) {

                var tripEle = element.parent("label").siblings(".tips");

                if(error.text()){
                    tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                    applyBtn.addClass("disabled");
                    applyBtn.attr("disabled",true);
                }
                else{
                    tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                    applyBtn.removeClass("disabled");
                    applyBtn.attr("disabled",false);
                }

            },

            success:function(label){

            }
        });

        applyCharge.ajaxForm({
            dataType : 'json',
            type:'POST',
            success : function(data){
                if(data.code == 1){
                    paymentInfo.val(data.msg);
                    payment.submit();
                }
                else{
                    layer.alert(data.msg ,5,"提示");
                }
            }
        });
    })
</script>