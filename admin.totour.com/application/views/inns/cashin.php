<h3 class="headline">充值记录<span class="more"><b>当前账户余额：<em class="mr10">¥<?php echo $balance;?></em>，累计充值金额：<em>¥<?php echo $totalAmout;?></em></b></span></h3>
<ul class="items">
    <li><b>当前账户余额：<em class="mr10">¥<?php echo $balance;?></em></b><?php if($currentUser['role'] == ROLE_INNHOLDER):?><a class="button" href="<?php echo $baseUrl;?>inns/addcashin" target="_blank">在线充值</a><?php endif;?></li>
</ul>
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp5"/>
        <col class="wp30"/>
        <col class="wp20"/>
        <col class="wp20"/>
        <col class="wp30"/>
    </colgroup>
    <thead>
    <tr>
        <th>序号</th>
        <th>充值时间</th>
        <th>操作人</th>
        <th>充值金额</th>
        <th>备注</th>
    </tr>
    </thead>
    <tbody>
	<?php $records = ($pageInfo['curpage']-1)*$pageInfo['perpage']+1;?>
    <?php foreach($data as $key => $val):?>
        <tr>
            <td><?php echo $key+$records;?></td>
            <td><?php echo $val['created_time'];?></td>
            <td><?php echo $val['operator'];?></td>
            <td><em>¥<?php echo $val['amount'];?></em></td>
            <td><?php echo $val['comments'];?></td>
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