<div class="orderView form">
    <h2 class="headline">订单详情</h2>
    <ul class="items">
        <li><b>订单编号：</b><cite><?php echo $order['order_num'];?></cite> <b class="ml50">订单总额：</b><em>￥<?php echo number_format($order['total'],2);?></em><b class="ml50">&nbsp交易号（支付宝)：</b><em><?php echo $order['code'];?></em></li>
        <li><b>订单状态：</b><?php echo $order['order_state'];?><b class="ml60">订单收益：</b><?php echo $order['profit'].' 元';?><b class="ml60">订单联系人：</b><?php echo $order['contact']?><b class="ml60">联系人电话：</b><?php echo substr($order['telephone'],0,3).'&nbsp;'.substr($order['telephone'],3,4).'&nbsp;'.substr($order['telephone'],7)?></li>
    </ul>
    <h5 class="headline">商户信息：</h5>
    <table class="wp100" style="line-height: 25px; margin-bottom: 15px;">
        <colgroup>
            <col class="wp50"/>
            <col class="wp50"/>
        </colgroup>
        <tbody>
        <tr>
            <td>商户名称：<a class="viewInnsInfo" href="javascript:void(0);" ref="<?php echo $order_inninfo['inn_id'];?>"><?php echo $order_inninfo['inn_name'];?></a></td>
        <?php if($order['seller_inn']):?>
			<td>销售商户：<a class="viewInnsInfo" href="javascript:void(0);" ref="<?php echo $order['seller_inn'];?>"><?php echo $order['seller']['inn']['inn_name'];?></a>
			</td>
		<?php endif;?>
        </tr>
        <tr>
            <td>商户地址：<?php echo $order_inninfo['inn_address'];?></td>
        <?php if($order['seller_inn']):?>
			<td>销售账号：<a href="javascript:void(0);" class="viewUserInfo" ref="<?php echo $userinfo['user_id']?>"><?php echo $userinfo[$order['user_id']]['real_name']?$userinfo[$order['user_id']]['real_name']:$userinfo[$order['user_id']]['user_name'];?></a>
			</td>
		<?php endif;?>
        </tr>
        </tbody>
    </table>
    <h5 class="headline">商品明细：</h5>
    <table class="orderList table table-border table-odd">
        <colgroup>
            <col class="wp40"/>
            <col class="wp20"/>
            <col class="wp20"/>	
            <col class="wp20"/>
        </colgroup>
        <thead>
        <tr>
            <th>商品名称</th>
            <th>单价</th>
            <th>数量</th>
            <th>总额</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach($order_products as $product):?>
        <tr>
            <td class="tl">
                <p class="name"><?php echo $product['product_name'];?></p>
            </td>
            <td><cite class="f14">¥<?php echo $product['price'];?></cite></td>
            <td><?php echo $product['quantity'];?></td>
            <td><em class="f14">¥<?php echo $product['subtotal'];?></em></td>
        </tr>
		<?php if($product['coupon_info']) $used_coupon[$product['product_id']] = json_decode($product['coupon_info'],TRUE);?>
		<?php endforeach;?>
        </tbody>
    </table>
	<?php if($order_coupon||isset($used_coupon)):?>
	<h5 class="headline">代金券明细：</h5>
    <table class="orderList table table-border table-odd">
        <colgroup>
            <col class="wp50"/>
            <col class="wp50"/>
        </colgroup>
        <thead>
        <tr>
            <th>券码</th>
            <th>使用时间</th>
        </tr>
        </thead>
        <tbody>
		<?php if($order_coupon):?>
			<?php foreach($order_coupon as $k => $r):?><tr>
			<td><?php echo chunk_split($r['code'],4,'&nbsp;&nbsp;');?></td>
			<td>尚未使用</td>
			<?php endforeach;?>
		<?php endif?>
		<?php if(isset($used_coupon)):?>
			<?php foreach($used_coupon as $coupon):?>
			<?php foreach($coupon as $key => $row):?>
			<td><?php echo chunk_split($row['code'],4,'&nbsp;&nbsp;');?></td>
			<td><?php echo date('Y-m-d H:i',$row['time']);?></td>
			<?php endforeach;?>
			<?php endforeach;?>
		<?php endif?>
        </tbody>
    </table>
	<?php endif?>
	<?php if($order_profiles):?>
    <h5 class="headline mt20">出行人员信息：</h5>
    <table class="orderList table table-border table-odd">
        <colgroup>
            <col class="wp10"/>
            <col class="wp20"/>
            <col class="wp25"/>
            <col class="wp25"/>
            <col class="wp20"/>
        </colgroup>
        <thead>
        <tr>
            <th>序号</th>
            <th>姓名</th>
            <th>身份证</th>
            <th>手机号码</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach($order_profiles as $key => $profile):?>
        <tr>
            <td><?php echo $key+1;?></td>
            <td><cite class="f14"><?php echo $profile['real_name'];?></cite></td>
            <td><?php echo $profile['identity_no']?></td>
            <td><em class="f14"><?php echo $profile['mobile_phone'];?></em></td>
            <td><?php echo $profile['email'];?></td>
        </tr>
		<?php endforeach;?>
        </tbody>
    </table>
	<?php endif;?>
    <h5 class="headline mt20">订单日志：</h5>
    <table class="orderList table table-border table-odd">
        <colgroup>
            <col class="wp20"/>
            <col class="wp60"/>
            <col class="wp20"/>
        </colgroup>
        <thead>
        <tr>
            <th>时间</th>
            <th>内容</th>
            <th>操作人</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach($order_logs as $log):?>
        <tr>
            <td><?php echo date('Y-m-d H:i:s',$log['create_time']);?></td>
            <td><?php echo $log['note'];?></td>
            <td><?php echo $userinfo[$log['user_id']]['real_name'];?></td>
        </tr>
		<?php endforeach;?>
        </tbody>
    </table>
    <p class="mb10">
		<?php if(in_array($session['role'],array(ROLE_CUSTOM_SERVICE,ROLE_ADMIN))&&in_array($order['state'],array('P','U'))&&empty($used_coupon)):?>
        <input class="buttonH mr10" type="button" id="cancelOrder" value="订单取消" />
		<?php endif;?>
		<?php if(isset($needs)):?>
        <input class="buttonG mr10" type="button" id="lockedOrder" value="<?php if($order['is_lock']) echo '解锁订单" lock="Y';else echo '锁定订单" lock="N';?>" />
		<?php endif;?>
        <input class="button" onclick="closeWindow();" type="button" value="关闭窗口" />
    </p>
</div>
<div class="withdrawDom">
    <h3 class="headline">取消订单</h3>
    <form method="post" id="cancelOrderForm">
        <input type="hidden" value="<?php echo $order['order_num'];?>" id="applyId" name="order_num"/>
        <table class="form table-form">
            <colgroup>
                <col class="w100"/>
                <col/>
            </colgroup>
            <tbody>
            <tr>
                <td class="leftLabel"><b>备注：</b></td>
                <td><label><textarea id="comment" class="w350" rows="3" cols="" name="comment"></textarea></label>
                    <div class="tips tips-info">
                        <i class="tips-ico"></i>
                        <p>请输入不少于10字备注，说明取消订单，锁定或解锁的操作原因，取消后不可恢复，请谨慎操作</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">&nbsp;</td>
                <td>
                    <input class="buttonG mr10" id="cancelBtn" type="submit" value="确认取消订单" />
                    <input class="button mr20 close" type="button" value="关闭" /><div class="tips tips-ok" id="formTips" style="display: none;"></div>
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
        var commentEle = $("#comment");
        var orderNum = '<?php echo $order['order_num'];?>';
        var lockedOrder = $('#lockedOrder');
        //锁定，解锁订单
        lockedOrder.click(function(){
            var state = $(this).attr("lock");
            if (state =="N"){
                $.ajax({
                    url: "<?php echo $baseUrl;?>order/order_lock",
                    data: {order_num:orderNum,comment:commentEle.val()},
                    type: 'POST',
                    success: function(data){
                        if(data.code == 1){
                            lockedOrder.val("解锁订单").attr("lock","Y");
                            layer.alert("订单锁定成功！点击确定返回查看订单",1,"操作成功",function(){
                                window.location.reload();}
							);
                        }
                        else{
                            layer.alert(data.msg ,3,"提示");
                        }
                    }
                });
            }
            else{
                $.ajax({
                    url: "<?php echo $baseUrl;?>order/order_unlock",
                    data: {order_num:orderNum,comment:commentEle.val()},
                    type: 'POST',
                    success: function(data){
                        if(data.code == 1){
                            lockedOrder.val("锁定订单").attr("lock","N");
                            layer.alert("订单解锁成功！点击确定返回查看订单",1,"操作成功",function(){
                                window.location.reload();}
							);
                        }
                        else{
                            layer.alert(data.msg ,3,"提示");
                        }
                    }
                });
            }
        });

        //订单退订
        var cancelOrderBtn = $("#cancelOrder");
        var cancelOrderForm = $("#cancelOrderForm");
        var cancelBtn = $('#cancelBtn');
        var formTips = $("#formTips");

        cancelOrderBtn.click(function(){
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

        cancelOrderForm.validate({
            rules: {
                comment:{
                    required: true,
                    byteRangeLength: [20,200]
                }
            },
            messages: {
                comment:{
                    required: "请输入备注",
                    byteRangeLength :"备注不少于10字，最多100字"
                }
            }, errorPlacement: function(error, element) {
                if(error.text()){
                    formTips.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                    cancelBtn.addClass("disabled");
                    cancelBtn.attr("disabled",true);
                }
                else{
                    formTips.hide();
                    cancelBtn.removeClass("disabled");
                    cancelBtn.attr("disabled",false);
                }
            },
            success:function(label){
            }
        });

        cancelOrderForm.ajaxForm({
            dataType : 'json',
            url:'<?php echo $baseUrl;?>order/cancel',
            type:'POST',
            success : function(data){
                if(data.code == 1){
                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>取消订单成功！</p>").show().fadeOut(5000);
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