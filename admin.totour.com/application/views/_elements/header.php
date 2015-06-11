<div class="frameTop">
	<div class="logo">
		<div class="image"><a href="<?php echo $baseUrl;?>" title="且游后台管理系统<?php echo $staticVer;?>"><!--<img src="<?php echo $staticUrl?>images/logo.png" alt=""/>--></a></div>
		<h2>且游后台管理系统<?php echo $staticVer;?></h2>
	</div>
	<div class="info">
		<span class="fr logout"><a href="<?php echo $baseUrl.'login/logout';?>" title="退出登录">退出登录</a></span>
        <span class="fr editpwd"><a href="<?php echo $baseUrl.'user/changepwd';?>" title="退出登录">修改密码</a></span> 
		<span class="fr pr15 city">当前区域：
		<?php if($session['role'] == 'admin'):?><a href="javascript:void(0);" class="name" title="切换区域">丽江</a><?php else:?><i class="name"><?php echo $session['city_name'];?></i><?php endif;?></span>
       
        <!--<span class="fr back">当前后台：
        <?php if(empty($_COOKIE['changeSlider'])):?>
        <a href="javascript:void(0);" ref='shop' class="show_change" title="切换">商城</a>
        <?php else:?>
        <a href="javascript:void(0);" ref='forum' class="show_change" title="切换">社区</a>
        <?php endif;?>
        </span>-->
        当前后台：<div id="nav">
                <div >
                    <a href="#"> <?php if(empty($_COOKIE['changeSlider'])){echo '商城';}else{echo '社区';};?> <span></span></a>
                        <ul class="dd">
                            <li><a href="javascript:void(0);" ref='shop' class="show_change">商城</a></li>
                            <li><a href="javascript:void(0);" ref='forum' class="show_change">社区</a></li>
                        </ul>
                </div>
            </div>
		&nbsp;<p>
  欢迎您，<strong><?php echo $session['realname'];?></strong>！<?php echo $session['lastlogintime']?'您上次登录时间：'.date('Y年m月d日 H:i',$session['lastlogintime']).'，登录IP：'.$session['lastloginip']:'';?></p>
	</div>
</div>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.cookie.js" charset="utf-8"></script>
<script type="text/javascript">
	$(".show_change").click(function(){
		var state = $(this).attr('ref');
		if(state == 'shop')
		{
			$.cookie("changeSlider", '', { path: '/' });
		}
		else
		{
			$.cookie("changeSlider", '1', { path: '/' });
		}
		window.location.href=baseUrl; 
	})
</script>