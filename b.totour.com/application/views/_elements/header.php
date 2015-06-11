<div class="frameTop">
	<div class="logo">
		<div class="image"><a href="<?php echo $baseUrl;?>" title="且游后台管理系统首页"><!--<img src="<?php echo $staticUrl?>images/logo.png" alt=""/>--></a></div>
		<h2>且游后台管理系统首页</h2>
	</div>
	<div class="info">
		<span class="fr logout"><a href="<?php echo $baseUrl.'login/logout';?>" title="退出登录">退出登录</a></span>
		<span class="fr pr15 city">当前区域：
		<?php if($session['role'] == 'admin'):?><a href="javascript:void(0);" class="name" title="切换区域">丽江</a><?php else:?><i class="name"><?php echo $session['city_name'];?></i><?php endif;?></span>
		<p>欢迎您， <strong><?php echo $session['realname'];?></strong>！<?php echo $session['lastlogintime']?'您上次登录时间：'.date('Y年m月d日 H:i',$session['lastlogintime']).'，登录IP：'.$session['lastloginip']:'';?></p>
	</div>
</div>