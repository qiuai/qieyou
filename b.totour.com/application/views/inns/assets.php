<h3 class="headline">账号管理<span class="more"><a class="submit-mini" href="<?php echo $baseUrl;?>user/add_shop_manager?action=create" title="新增店长帐号">新增店长帐号</a></span></h3>
<table class="orderList table table-border table-odd">
	<?php if($users):?>
	<colgroup>
		<col class="wp10"/>
		<col class="wp15"/>
		<col class="wp15"/>
		<col class="wp15"/>
		<col class="wp10"/>
        <col class="wp15"/>
        <col class="wp10"/>
		<col class="wp10"/>
	</colgroup>
	<thead>
	<tr>
		<th>用户名</th>
		<th>真实姓名</th>
		<th>手机号码</th>
		<th>最后登录</th>
		<th>登录总计</th>
        <th>开户时间</th>
        <th>帐号状态</th>
		<th>操作</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($users as $key => $user):?>
	<tr>
	<td><?php echo $user['user_name']?></td>
	<td><?php echo $user['real_name']?></td>
	<td><?php echo $user['mobile_phone']?></td>
	<td><?php echo empty($user['last_login_time'])?'该账号还未登陆过系统':date('Y-m-d H:i:s',$user['last_login_time']);?></td>
	<td><?php echo $user['login_count'];?></td>
	<td><?php echo date('Y-m-d H:i:s',$user['created_time']);?></td>
	<td><?php switch($user['state']){ 
			case 'active': 
				echo '<span class="finish">正常</span>';
				break;
			case 'del':
				echo '<span class="over">被删除</span>';
				break;
			case 'locked':
				echo '<span class="error">锁定</span>';
				break;
			case 'suspend':
				echo '<span class="over">停用</span>';
				break;
		}?>
	</td>
	<td><a href="<?php echo $baseUrl; ?>user/edit_shop_manager?uid=<?php echo $user['user_id'];?>">编辑</a>
        <a href="javascript:void(0);" ref="<?php echo $user['user_id'];?>" <?php if($user['state']=='locked') echo 'class="activeUser">解锁</a>';else echo 'class="lockedUser">锁定</a>';?>
        <a href="javascript:void(0);" ref="<?php echo $user['user_id'];?>" <?php if($user['state']=='suspend') echo 'class="activeUser">恢复</a>';else echo 'class="suspendUser">停用</a>';?>
	</td>
	</tr>
	<?php endforeach;?>
	</tbody>
	<?php else:?>
	您暂时没有添加店长账号！
	<?php endif;?>
</table>

<script type="text/javascript">
    $(function(){



        //锁定，停用,恢复正常账户
        $('.lockedUser').click(function(){
            var uid = $(this).attr("ref");

            $.ajax({
                url: "<?php echo $baseUrl;?>user/update_state",
                data: {state:'locked',uid:uid},
                type: 'POST',
                success: function(data){
                    if(data.code == 1){

                        layer.alert("用户锁定成功！点击确定返回账号管理",1,"操作成功",function(){
                                window.location.reload();
                            }
                        );
                    }
                    else{
                        layer.alert(data.msg ,3,"提示");
                    }
                }
            });
        });

        $('.suspendUser').click(function(){
            var uid = $(this).attr("ref");

            $.ajax({
                url: "<?php echo $baseUrl;?>user/update_state",
                data: {state:'suspend',uid:uid},
                type: 'POST',
                success: function(data){
                    if(data.code == 1){

                        layer.alert("用户停用成功！点击确定返回账号管理",1,"操作成功",function(){
                                window.location.reload();
                            }
                        );
                    }
                    else{
                        layer.alert(data.msg ,3,"提示");
                    }
                }
            });
        });

        $('.activeUser').click(function(){
            var uid = $(this).attr("ref");

            $.ajax({
                url: "<?php echo $baseUrl;?>user/update_state",
                data: {state:'active',uid:uid},
                type: 'POST',
                success: function(data){
                    if(data.code == 1){

                        layer.alert("用户恢复正常！点击确定返回账号管理",1,"操作成功",function(){
                                window.location.reload();
                            }
                        );
                    }
                    else{
                        layer.alert(data.msg ,3,"提示");
                    }
                }
            });
        });

    })
</script>