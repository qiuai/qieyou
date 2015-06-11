<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<div class="user-nav">
	<div class="user-nav-list">
		<a href="<?php echo $baseUrl.'group/groupseting?group='.$group['group_id'];?>">
		<ul>
			<li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/set2.png"/></li>
			<li class="uright"><span class="left">部落设置</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
		</ul>
		</a>
		<a href="<?php echo $baseUrl.'group/admintopic?group='.$group['group_id'];?>">
		<ul>
			<li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/huati.png"/></li>
			<li class="uright"><span class="left">贴子管理</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
		</ul>
		</a>
		<a href="<?php echo $baseUrl.'group/adminmember?group='.$group['group_id'];?>">
		<ul>
			<li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/chenyuan.png"/></li>
			<li class="uright none"><span class="left">成员管理</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
		</ul>
		</a>
	</div>
</div>
<!-- <div class="group-btn"><a href="#" class="redbtn">退出部落</a></div> -->