<h3 class="headline">账户交易流水<span class="more"><b>当前账户余额：<em class="mr10">¥<?php echo $accountBalance;?></em></b></span></h3>
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp10">
        <col class="wp15">
        <col class="wp10">
        <col class="wp15">
        <col class="wp15">
        <col class="wp15">
        <col class="wp20">
    </colgroup>
    <thead>
    <tr>
        <th>流水号</th>
        <th>记录时间</th>
        <th>科目</th>
        <th>交易金额</th>
        <th>账户余额</th>
        <th>操作者</th>
        <th>备注</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data as $key => $val):?>
        <tr>
            <td><?php echo $val['seq_id'];?></td>
            <td><?php echo $val['created_time'];?></td>
            <td><?php echo $val['record_desc'];?></td>
            <td><?php echo $val['amount'];?></td>
            <td>¥<?php echo $val['balance'];?></td>
            <td><?php echo $val['user_name'];?></td>
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
<!--Date Picker-->
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){



        <!--分页-->
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());


    });

</script>