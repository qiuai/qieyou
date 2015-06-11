<?php if($state == 'applying'):?>
<h3 class="headline">退款管理</h3>
<div class="tab">
    <ul class="clearfix">
        <li class="current"><a href="<?php echo $baseUrl;?>finance/refund?state=applying">申请中</a></li>
        <li><a href="<?php echo $baseUrl;?>finance/refund?state=settled">已处理</a></li>
    </ul>
</div>
<div class="tabContent form">
    <table class="orderList table table-border table-odd">
        <colgroup>
            <col class="wp8">
            <col class="wp13">
            <col class="wp10">
            <col class="wp5">
            <col class="wp5">
            <col class="wp7">
            <col class="wp5">
            <col class="wp8">
            <col class="wp7">
            <col class="wp5">
            <col class="wp5">
            <col class="wp11">
            <col class="wp11">
        </colgroup>
        <thead>
        <tr>
            <th>订单号</th>
            <th>商品名称</th>
            <th>商户名称</th>
            <th>支付方式</th>
            <th>交易号</th>
            <th>支付时间</th>
            <th>订单总额</th>
            <th>申请退款时间</th>
            <th>申请人</th>
            <th>退款金额</th>
            <th>订单收入</th>
            <th>退款理由</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>	
        <?php foreach($data as $key => $val):?>
            <tr>
                <td><a href="<?php echo $baseUrl.'order/view?oid='.$val['order_num'];?>" target="_blank"><?php echo $val['order_num'];?></td>
                <td class="tl">
                    <?php foreach($val['products_info'] as $k => $product):?>
                        <p><a href="<?php echo $baseUrl.'product/edit?pid='.$product['product_id'];?>" target="_blank"><?php echo $product['product_name'];?></a></p>
                    <?php endforeach;?>
                </td>
                <td><?php echo $val['inn_name'];?></td>
                <td><?php switch($val['pay_type']){case 'alipay': echo '支付宝';break;case 'null': echo '未支付';break;default: echo '余额付款';break;};?></td>
                <td><?php echo $val['code'];?></td>
                <td><?php echo date('Y-m-d H:i:s',$val['pay_time']);?></td>
                <td><i>¥<?php echo $val['total'];?></i></td>
                <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
                <td><?php echo $val['apply_user_name'];?></td>
                <td><em>¥<?php echo $val['refund_amount'];?></em></td>
                <td><cite>¥<?php echo $val['total']-$val['refund_amount'];?></cite></td>
                <td><?php echo $val['comments'];?></td>
                <td><input class="buttonG-mini <?php if(isset($readOnly))echo 'disabled'; else echo 'applyButton';?>" type="button" value="同意退款" ref="<?php echo $val['refund_id'];?>" />
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
    <!--分页样式开始-->
    <div class="pageBar clearfix">
        <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage']?></em>条</p>
        <div class="pages fr" id="page">
        </div>
    </div>
    <!--分页样式结束-->
<div class="withdrawDom">
    <h3 class="headline">退款申请处理</h3>
    <form method="post" id="applyRefund">
        <input type="hidden" value="" id="applyId" name="refund_id"/>
        <input type="hidden" name="action" value="settled">
        <table class="form table-form">
            <colgroup>
                <col class="w100"/>
                <col/>
            </colgroup>
            <tbody>
            <!--<tr>
                <td class="leftLabel"><b>是否退款：</b></td>
                <td>
                    <label><input type="radio" class="radio" name="action" value="approve">同意退款</label>
                    <label><input type="radio" class="radio" name="action" value="reject">拒绝退款</label>
                    <div class="tips tips-info" id="actionTips">
                        <i class="tips-ico"></i>
                        <p>请确认是否同意退款！</p>
                    </div>
                </td>
            </tr>-->
            <tr>
                <td class="leftLabel"><b>备注：</b></td>
                <td><label><textarea id="sanction" class="w350" rows="3" cols="" name="sanction"></textarea></label>
                    <div class="tips tips-info" id="commentsTips">
                        <i class="tips-ico"></i>
                        <p>请输入退款备注！</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">&nbsp;</td>
                <td>
                    <input class="buttonG mr10" id="applyBtn" type="submit" value="确认处理" />
                    <input class="button mr20 close" type="button" value="取消" /><div class="tips tips-ok" id="formTips" style="display: none;"></div>
                </td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript">
    $(function(){

        <!--分页-->
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());

        /**处理退款申请**/
        var applyId =$('#applyId');
        var applyRefund = $('#applyRefund');
        var applyBtn = $('#applyBtn');
        var sanction = $('#sanction');
        var commentsTips = $('#commentsTips');
        var formTips = $("#formTips");

        //弹出窗口
        $('.applyButton').click(function(){
            applyId.val($(this).attr("ref"));
            sanction.val('');
            $(".radio").attr("checked",false);

            $.layer({
                shade : [0.4 , "#000" , true],
                type : 1,
                area : ['auto','auto'],
                title : false,
                page : {dom : '.withdrawDom'},
                close : function(index){
                    layer.close(index);
                }
            });
        });

        //同意或拒绝退款申请
        applyRefund.validate({
            rules: {
                comments:{
                    required: true
                }
            },
            messages: {
                comments:{
                    required: "请输入退款备注！"
                }
            }, errorPlacement: function(error, element) {
                if(error.text()){
                    commentsTips.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                    applyBtn.addClass("disabled");
                    applyBtn.attr("disabled",true);
                }
                else{
                    commentsTips.hide();
                    applyBtn.removeClass("disabled");
                    applyBtn.attr("disabled",false);
                }
            },
            success:function(label){

            }
        });

        applyRefund.ajaxForm({
            dataType : 'json',
            url:'<?php echo $baseUrl.'finance/orderRefund';?>',
            type:'POST',
            success : function(data){
                if(data.code == 1){

                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>处理退款成功！</p>").show().fadeOut(5000);
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }
                else{
                    layer.alert(data.msg ,5,"提示");
                }
            }
        });
    });

</script>
<?php elseif($state == 'settled'):?>
    <h3 class="headline">退款管理</h3>
    <div class="tab">
        <ul class="clearfix">
            <li><a href="<?php echo $baseUrl;?>finance/refund?state=applying">申请中</a></li>
            <li class="current"><a href="<?php echo $baseUrl;?>finance/refund?state=settled">已处理</a></li>
        </ul>
    </div>
    <div class="tabContent form">
        <table class="orderList table table-border table-odd">
            <colgroup>
                <col class="wp8">
                <col class="wp13">
                <col class="wp10">
                <col class="wp8">
                <col class="wp5">
                <col class="wp8">
                <col class="wp5">
                <col class="wp8">
                <col class="wp5">
                <col class="wp5">
                <col class="wp5">
                <col class="wp10">
                <col class="wp10">
            </colgroup>
            <thead>
            <tr>
                <th>订单号</th>
                <th>商品名称</th>
                <th>商户名称</th>
                <th>支付时间</th>
				<th>订单总额</th>
				<th>申请退款时间</th>
                <th>申请人</th>
                <th>处理完成时间</th>
                <th>经办人</th>
                <th>退款金额</th>
                <th>订单收入</th>
				<th>退款理由</th>
				<th>批注</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data as $key => $val):?>
                <tr>
                    <td><a href="<?php echo $baseUrl.'order/view?oid='.$val['order_num'];?>" target="_blank"><?php echo $val['order_num'];?></td>
                    <td class="tl">
                        <?php foreach($val['products_info'] as $k => $product):?>
                            <p><a href="<?php echo $baseUrl.'product/edit?pid='.$product['product_id'];?>" target="_blank"><?php echo $product['product_name'];?></a></p>
                        <?php endforeach;?>
                    </td>
                    <td><?php echo $val['inn_name'];?></td>
					<td><?php echo date('Y-m-d H:i:s',$val['pay_time']);?></td>
                	<td><i>¥<?php echo $val['total'];?></i></td>
					<td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
					<td><?php echo $val['apply_user_name'];?></td>
                	<td><?php echo date('Y-m-d H:i:s',$val['settlement_time']);?></td>
               		<td><?php echo $val['cashier_user_name'];?></td>
                	<td><em>¥<?php echo $val['refund_amount'];?></em></td>
                	<td><cite>¥<?php echo $val['total']-$val['refund_amount'];?></cite></td>
                    <td><?php echo $val['comments'];?></td>
                    <td><?php echo $val['sanction'];?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <!--分页样式开始-->
    <div class="pageBar clearfix">
        <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage']?></em>条</p>
        <div class="pages fr" id="page">
        </div>
    </div>
    <!--分页样式结束-->

    <script type="text/javascript">
        $(function(){
            <!--分页-->
            var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
            $('#page').html(page.createPage());

            //查看备注
            $('.viewComments').click(function(){
                layer.alert($(this).attr('ref'),11,"备注");
            });
        });
    </script>
<?php endif;?>