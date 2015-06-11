<h3 class="headline">首页管理</h3>

<div class="tab">
    <ul class="clearfix">
        <li <?php if($recommend =='perfectday') echo 'class="current"';?>><a href="<?php echo $baseUrl.'page?recommend=perfectday';?>">玩美一天推荐</a></li>
        <li <?php if($recommend =='inns') echo 'class="current"';?>><a href="<?php echo $baseUrl.'page?recommend=inns';?>">驿栈推荐</a></li>
        <li <?php if($recommend =='games') echo 'class="current"';?>><a href="<?php echo $baseUrl.'page?recommend=games';?>">玩法推荐</a></li>
    </ul>
</div>

<table class="table table-border table-odd">
    <colgroup>
        <col class="wp10"/>
        <col class="wp40"/>
        <col class="wp40"/>
        <col class="wp10"/>
    </colgroup>
    <thead>
    <tr>
        <th>排序</th>
        <th>标题</th>
        <th>链接</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <label><input type="text" class="w10" name="sort[]" value="1"/></label>
        </td>
        <td>
            Freesa的完美一天
        </td>
        <td>
            http://www.doyouhike.net/yizhan/perfectday/view/1
        </td>
        <td>
            <a href="javascript:void(0);" class="mr10">修改</a>
            <a href="javascript:void(0);" class="">删除</a>
        </td>
    </tr>
    </tbody>
</table>