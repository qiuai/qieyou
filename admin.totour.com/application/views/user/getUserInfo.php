<h3 class="headline">查看用户信息</h3>
<table class="form table-form">
    <colgroup>
        <col class="w120">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td class="leftLabel">用户名称：</td>
        <td><?php echo $userInfo['user_name'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">呢称：</td>
        <td><?php echo $userInfo['nick_name'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">真实姓名：</td>
        <td><?php echo $userInfo['real_name'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">联系电话：</td>
        <td><?php echo $userInfo['mobile_phone'];?></td>
    </tr>
    <tr>
        <td class="leftLabel">创建时间：</td>
        <td><?php echo date('Y-m-d H:i',$userInfo['create_time']);?></td>
    </tr>
    <tr>
        <td class="leftLabel">&nbsp;</td>
        <td style="padding: 20px 0 0 15px;">
            <input class="buttonH close" type="button" value="关闭">
        </td>
    </tr>
    </tbody>
</table>
