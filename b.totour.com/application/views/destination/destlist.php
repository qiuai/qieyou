<h3 class="headline">目的地管理
    <span class="more"><a class="submit-mini" href="<?php echo $baseUrl;?>destination/create">新增目的地</a></span>
</h3>

<div class="filter mb20">
    <div class="form p10-20">
        按街区查找：<label>
            <select id="province" name="province">
            </select>
        </label>
        <label>
            <select name="city" id="city">
            </select>
        </label>
    </div>
</div>

<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp10">
        <col class="wp15">
        <col class="wp25">
        <col class="wp30">
      <!--   <col class="wp10">-->
        <col class="wp10">
        <col class="wp10">
    </colgroup>
    <thead>
    <tr>
        <th>序号</th>
        <th>省</th>
        <th>市</th>
        <th>目的地名称</th>
       <!-- <th>是否显示</th>-->
        <th>地区商户数量</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($destInfo['data'] as $key => $val):?>
        <tr>
            <td><?php echo $val['dest_id'];?></td>
            <td><?php echo $val['province'];?></td>
            <td><?php echo $val['city'];?></td>
            <td><?php echo $val['dest_name'];?></td>
            <!-- <td><?php if( $val['is_display']=='Y') echo'是' ; else echo '否';?></td> --> 
            <td><?php echo $val['count'];?></td>
            <td><a href="<?php echo $baseUrl;?>destination/innlist?local=<?php echo $val['dest_id'];?>">查看</a></td>
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
<script type="text/javascript" src="<?php echo $staticUrl;?>js/destSelect.js"></script>
<script type="text/javascript">
    $(function(){
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());


        var province = $('#province');
        var city = $('#city');
        var currentCity = "<?php echo $destInfo['cityName'];?>";
        var currentProvince = "<?php echo $destInfo['provinceName'];?>";

        if(currentCity == ''){
            $.initProv("#province", "#city", "", "");
        }
        else{
            $.initProv("#province", "#city", currentProvince, currentCity);

        }

        city.change(function(){
            var provinceVal = province.val();
            var cityVal = $(this).val();

            window.location.href="<?php echo $baseUrl;?>destination?province="+provinceVal+"&city="+cityVal;

        });

    });
</script>