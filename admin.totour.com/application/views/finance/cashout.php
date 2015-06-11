<?php if($state == 'applying'):?>
<h3 class="headline">提现管理</h3>
    <div class="tab">
        <ul class="clearfix">
            <li class="current"><a href="<?php echo $baseUrl;?>finance/cashout?state=applying">申请中</a></li>
            <li><a href="<?php echo $baseUrl;?>finance/cashout?state=settled">已处理</a></li>
        </ul>
    </div>
    <table class="orderList table table-border table-odd">
        <colgroup>
            <col class="wp20">
            <col class="wp20">
            <col class="wp20">
            <col class="wp10">
            <col class="wp15">
            <col class="wp15">
        </colgroup>
        <thead>
        <tr>
            <th>申请时间</th>
            <th>商户名称</th>
            <th>申请人</th>
            <th>金额</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data as $key => $val):?>
            <tr>
                <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
                <td><?php echo $val['innsName'];?></td>
                <td><?php echo $users[$val['apply_user_id']]['real_name'];?></td>
                <td><em>¥<?php echo $val['amount'];?></em></td>
                <td><span class="wait"><?php if($val['state']== 'settled') echo '已处理'; else echo '申请中';?></span></td>
                <td>
                    <input class="buttonG-mini applyButton" type="button" value="处理申请" ref="<?php echo $val['id'];?>" />
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <div class="withdrawDom">
        <h3 class="headline">提现申请处理</h3>
        <form method="post" id="applyCash">
            <input type="hidden" value="" id="applyId" name="apply_id"/>
            <input type="hidden" value="settled" name="action"/>
            <table class="form table-form">
                <colgroup>
                    <col class="w100"/>
                    <col/>
                </colgroup>
                <tbody>
                <tr>
                    <td class="leftLabel"><b>处理记录：</b></td>
                    <td><label><textarea id="comments" class="w350" rows="3" cols="" name="comments" placeholder=""></textarea></label>
                        <div class="tips tips-info" id="commentsTips" style="display: none;">
                            <i class="tips-ico"></i>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><div class="tips tips-info">
                            <i class="tips-ico"></i>
                            <p>请输入银行转账时间，金额与收款账号！<br/>如：2015-01-24 17:06 已成功转账 ¥1000,00 至 招商银行 账号 6225666688887856</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="leftLabel">&nbsp;</td>
                    <td style="padding-top: 20px;"><input class="buttonG mr10" id="applyBtn" type="submit" value="同意申请" />
                        <input class="button mr20 close" type="button" value="取消" /><div class="tips tips-ok" id="formTips" style="display: none;"></div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <!--分页样式开始-->
    <div class="pageBar clearfix">
        <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage']?></em>条</p>
        <div class="pages fr" id="page">
        </div>
    </div>
    <!--分页样式结束-->
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript">
    $(function(){

        <!--分页-->
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());


        /**处理提现申请**/
        var applyId =$('#applyId');
        var applyCash = $('#applyCash');
        var applyBtn = $('#applyBtn');
        var comments = $('#comments');
        var commentsTips = $('#commentsTips');
        var formTips = $("#formTips");

        $('.applyButton').click(function(){
            applyId.val($(this).attr("ref"));
            comments.val('');

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


        applyCash.validate({
            rules: {
                comments:{
                    required: true
                }
            },
            messages: {
                comments:{
                    required: "请输入处理记录"
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

        applyCash.ajaxForm({
            dataType : 'json',
            url:'<?php echo $baseUrl.'finance/settleCashout';?>',
            type:'POST',
            success : function(data){
                if(data.code == 1){

                    formTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>处理提现成功！</p>").show().fadeOut(5000);
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
    <h3 class="headline">提现管理</h3>
    <div class="tab">
        <ul class="clearfix">
            <li><a href="<?php echo $baseUrl;?>finance/cashout?state=applying">申请中</a></li>
            <li class="current"><a href="<?php echo $baseUrl;?>finance/cashout?state=settled">已处理</a></li>
        </ul>
    </div>
    <table class="orderList table table-border table-odd">
        <colgroup>
            <col class="wp10">
            <col class="wp10">
            <col class="wp10">
            <col class="wp10">
            <col class="wp10">
            <col class="wp10">
            <col class="wp30">
            <col class="wp10">
        </colgroup>
        <thead>
        <tr>
            <th>申请时间</th>
            <th>商户名称</th>
            <th>申请人</th>
            <th>金额</th>
            <th>状态</th>
            <th>处理时间</th>
            <th>处理记录</th>
            <th>经办人</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data as $key => $val):?>
            <tr>
                <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
                <td><?php echo $val['innsName'];?></td>
                <td><?php echo $users[$val['apply_user_id']]['real_name'];?></td>
                <td><em>¥<?php echo $val['amount'];?></em></td>
                <td><span class="finish"><?php if($val['state']== 'settled') echo '已处理'; else echo '申请中';?></span></td>
                <td><?php echo date('Y-m-d H:i:s',$val['settlement_time']);?></td>
                <td><?php echo $val['comments'];?></td>
                <td><?php echo $users[$val['cashier_id']]['real_name'];?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
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
        });

    </script>
<?php endif;?>
