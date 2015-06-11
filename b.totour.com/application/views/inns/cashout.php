<h3 class="headline">提现记录<span class="more"><b>当前账户余额：<em class="mr10">¥<?php echo $account['account_balance'];?></em>，累计提现金额：<em>¥<?php echo $totalAmout;?></em></b></span></h3>
<ul class="items">
    <li><b>可提现金额：<em class="mr10">¥<?php echo $account['account_balance'];?></em></b><?php if($currentUser['role'] == ROLE_INNHOLDER):?><input class="button" type="submit" value="申请提现" id="withdrawBtn" rel="<?php echo $account['account_balance'];?>" /><?php endif;?>
	<b class="ml40">提现中的金额：<span style="color: #f60;">¥<?php echo $account['unavailable_balance'];?></span></b>
	</li>
</ul>
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp5"/>
        <col class="wp15"/>
        <col class="wp15"/>
        <col class="wp10"/>
        <col class="wp10"/>
        <col class="wp35"/>
        <col class="wp20"/>
    </colgroup>
    <thead>
    <tr>
        <th>序号</th>
        <th>申请时间</th>
        <th>申请人</th>
        <th>提现金额</th>
        <th>状态</th>
        <th>处理记录</th>
        <th>记录人</th>
    </tr>
    </thead>
    <tbody>
	<?php $records = ($pageInfo['curpage']-1)*$pageInfo['perpage']+1;?>
    <?php foreach($data as $key => $val):?>
        <tr>
            <td><?php echo $key+$records;?></td>
            <td><?php echo $val['created_time'];?></td>
            <td><?php echo $val['applyUserName'];?></td>
            <td><em>¥<?php echo $val['amount'];?></em></td>
            <td><span class="<?php if($val['state']== 'settled') echo 'finish'; else echo 'wait';?>"><?php if($val['state']== 'settled') echo '已处理'; else echo '申请中';?></span></td>
            <td class="tl">
                <?php echo $val['comments'];?>
            </td>
            <td><?php echo $val['cashier'];?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<!--分页样式开始-->
<div class="pageBar clearfix">
    <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage'];?></em>条</p>
    <div class="pages fr" id="page">
    </div>
</div>
<!--分页样式结束-->

<div class="withdrawDom">
    <h3 class="headline">申请提现</h3>
    <form method="post" id="applyCash">
    <table class="form table-form">
        <colgroup>
            <col class="w150"/>
            <col/>
        </colgroup>
        <tbody>
        <tr>
            <td class="leftLabel"><b>目前可提现金额：</b></td>
            <td><em>¥<?php echo $account['account_balance'];?></em>
            </td>
        </tr>
        <tr>
            <td class="leftLabel"><b>申请提现：</b></td>
            <td><label><input type="text" class="w50" value="" name="apply_amount"> 元<cite>*</cite></label>
                <div class="tips tips-info">
                    <i class="tips-ico"></i>
                    <p>请输入不大于当前额度的数值！</p>
                </div>
            </td>
        </tr>
        <tr>
            <td class="leftLabel">&nbsp;</td>
            <td style="padding-top: 20px;"><input class="buttonG mr10" id="applyBtn" type="submit" value="提交" />
                <input class="buttonH close" type="button" value="取消" />
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


        /**申请提现**/
        $('#withdrawBtn').click(function(){
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

        var applyCash = $('#applyCash');
        var applyBtn = $('#applyBtn');
        applyCash.validate({
            rules: {


                apply_amount:{
                    required: true,
                    digits:true,
                    min:100,
                    max:<?php echo $account['account_balance'];?>
                }
            },
            messages: {


                apply_amount:{
                    required: "请输入提现金额",
                    digits:"请输入整数",
                    min:"最低提现金额100元",
                    max:"超出可提现金额"
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

        applyCash.ajaxForm({
            dataType : 'json',
            url:'<?php echo $baseUrl.'finance/apply_cashout';?>',
            type:'POST',
            success : function(data){
                if(data.code == 1){

                    layer.alert("申请成功！请等待财务审核。",1,"申请成功",function(){
                            location.reload();
                        }
                    );
                }
                else{
                    layer.alert(data.msg ,5,"提示");
                }
            }
        });


        <!--分页-->
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());


    });

</script>
