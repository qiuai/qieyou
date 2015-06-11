<h3 class="headline">用户查询</h3>
<div class="filter mb20">
 <form method="get">
    <table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
        <tbody>
        <tr>   
            <td>
       		   账户类型： <label>
                    <select name="type" id="type">
                        <option <?php if($type =='all') echo 'selected=""';?> value="all">全部</option>
	                    <option <?php if($type =='user') echo 'selected=""';?> value="user">普通用户</option>
						<option <?php if($type =='innholder') echo 'selected=""';?> value="innholder">商户</option>
						<option <?php if($type =='smanager') echo 'selected=""';?> value="smanager">店长</option>
						<option <?php if($type =='cservice') echo 'selected=""';?> value="cservice">且游</option>
                    </select>
                </label>
                  用户账号：<input type="text" value="" id="user_name" name="user_name"  class="mr30">
                呢称：<input type="text" value="" id="nick_name" name="nick_name" class="mr30">
                  开户时间：
                 <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('st'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $starttime?date('Y-m-d',$starttime):'';?>" name="starttime" title="请选择开始日期" id="startTime" class="Wdate" readOnly></label>
                <span class="mr10">至</span>
                <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick('ed'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $endtime?date('Y-m-d',$endtime):'';?>" name="endtime" title="请选择结束日期" id="endtime" class="Wdate" readOnly></label>
				<div class="tips tips-ok" style="display: none;" id="timeTips"><i class="tips-ico"></i><p></p></div> 
                <label><input class="buttonG" type="submit" id="setTimeButton" value="查询"></label>
            </td>
        </tr>
        </tbody>
    </table>
    </form>
</div>
<table class="orderList table table-border table-odd">
<?php if($type =='innholder'):?>
    <colgroup>
		<col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
		<col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp5">
        <col class="wp10">
        <col class="wp5">
        <col class="wp10">
    </colgroup>
    <thead>
    <tr>
        <th>所属区域</th>
		<th>目的地</th>
        <th>驿栈名</th>
		<th>用户ID</th>
        <th>真实姓名</th>
        <th>手机号码</th>
        <th>最后登录</th>
        <th>登录次数</th>
        <th>开户时间</th>
        <th>账户状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($users):?>
	<?php foreach($users as $key => $row):?>
	<tr>
        <td><?php echo $row['dest_name'];?></td>
        <td><?php echo $row['local_name'];?></td>
        <td><?php echo $row['inn_name'];?></td>
		<td><?php echo $row['user_name'];?></td>
        <td><?php echo $row['real_name'];?></td>
        <td><?php echo $row['user_mobile'];?></td>
        <td><?php echo empty($row['last_login_time'])?' ':date('Y-m-d H:i:s',$row['last_login_time']);?></td>
        <td><?php echo $row['login_count'];?></td>
        <td><?php echo date('Y-m-d H:i:s',$row['create_time']);?></td>
        <td><?php switch($row['state']){ 
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
        <td>
            <a href="<?php echo $baseUrl; ?>user/editinfo?type=<?php echo $row['role'];?>&uid=<?php echo $row['user_id'];?>">编辑</a>
           <!-- <a href="javascript:void(0);" ref="<?php echo $row['user_id'];?>" <?php if($row['state']=='locked') echo 'class="activeUser">解锁</a>';else echo 'class="lockedUser">锁定</a>';?>
            <a href="javascript:void(0);" ref="<?php echo $row['user_id'];?>" <?php if($row['state']=='suspend') echo 'class="activeUser">恢复</a>';else echo 'class="suspendUser">停用</a>';?>-->
        </td>
    </tr>	
	<?php endforeach;?>
	<?php endif;?>
<?php else:?>
    <colgroup>
		<col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
		<col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
        <col class="wp10">
    </colgroup>
    <thead>
    <tr>
		<th>用户账号</th>
        <th>昵称</th>
        <th>真实姓名</th>
        <th>手机号码</th>
        <th>用户角色</th>
        <th>最后登录</th>
        <th>登录次数</th>
        <th>开户时间</th>
        <th>账户状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if($users):?>
	<?php foreach($users as $key => $BackUser):?>
		<tr>
		<td><a href="javascript:void(0);" class="viewUserInfo" ref="<?php echo $BackUser['user_id']?>"><?php echo $BackUser['user_name'];?></a></td>
        <td><?php echo $BackUser['nick_name'];?></td>
        <td><?php echo $BackUser['real_name'];?></td>
        <td><?php echo $BackUser['user_mobile'];?></td>
        <td> <?php switch($BackUser['role']){ 
			case 'user': 
				echo '普通用户';
				break;
			case 'innholder':
				echo '商户';
				break;
			case 'smanager':
				echo '商户店长';
				break;
			}?>
		</td>
        <td><?php echo empty($BackUser['last_login_time'])?' ':date('Y-m-d H:i:s',$BackUser['last_login_time']);?></td>
        <td><?php echo $BackUser['login_count'];?></td>
        <td><?php echo date('Y-m-d H:i:s',$BackUser['create_time']);?></td>
        <td><?php switch($BackUser['state']){ 
			case 'active': 
				echo '<span class="finish">正常</span>';
				break;
			case 'first': 
				echo '<span class="error">尚未激活</span>';
				break;
			case 'locked':
				echo '<span class="error">锁定</span>';
				break;
			case 'suspend':
				echo '<span class="over">停用</span>';
				break;
			}?>
		</td>
        <td>
            <a href="<?php echo $baseUrl; ?>user/editinfo?type=<?php echo $BackUser['role'];?>&uid=<?php echo $BackUser['user_id'];?>">编辑</a>
            <!--<a href="javascript:void(0);" ref="<?php echo $BackUser['user_id'];?>" <?php if($BackUser['state']=='locked') echo 'class="activeUser">解锁</a>';else echo 'class="lockedUser">锁定</a>';?>-->
        </td>
    </tr>	
	<?php endforeach;?>
	<?php endif;?>
<?php endif;?>
    </tbody>
</table>
<!--分页样式开始-->
<div class="pageBar clearfix">
    <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage']?></em>条</p>
    <div class="pages fr" id="page">
    </div>
</div>
<script type="text/javascript">
$(function(){
	//翻页
	var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
	$('#page').html(page.createPage());
	
	var setTimeButton = $('#setTimeButton');
	var startTime = $('#starttime');
	var endTime = $('#endtime');

	$("#type").change(function(){
		var typeVal = $(this).val();
		window.location.href="<?php echo $baseUrl;?>user?type="+typeVal+getTime();
	});

	function getTime()
	{
		var url ='';
		var st = startTime.val();
		var ed = endTime.val();
		var start = st?datetimeToUnix(st+" 00:00:00"):'';
		var end = ed?datetimeToUnix(ed+" 23:59:59"):'';
		url = (st?"&starttime="+start:"")+(ed?"&endTime="+end:"");
		return url;
	}
	

	//锁定，停用,恢复正常账户
	$('.lockedUser').click(function(){
		var uid = $(this).attr("ref");
		$.ajax({
			url: "<?php echo $baseUrl;?>user/update_state",
			data: {state:'locked',uid:uid},
			type: 'POST',
			success: function(data){
				if(data.code == 1){
					layer.alert("用户锁定成功！点击确定返回用户列表",1,"操作成功",function(){
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
					layer.alert("用户停用成功！点击确定返回用户列表",1,"操作成功",function(){
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
					layer.alert("用户恢复正常！点击确定返回用户列表",1,"操作成功",function(){
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
<!--分页样式结束-->
<!--Date Picker-->
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js" charset="utf-8"></script>