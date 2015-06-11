<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/user.css"/>
<?php if(empty($session['user_id'])):?>
<div class="user-top user-top2">
    <div class="top"> 
    <div class="left"><a href="javascript:history.back(-1)" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"/></a></div>
        <div class="middle">
            <div class="headpic"><img alt="" src="<?php echo $staticUrl;?>images/head.jpg"/></div>
            <ul>
                <li><a href="<?php getUrl('login');?>?url=<?php getUrl('my');?>">登录</a><a href="<?php getUrl('userReg');?>">注册</a></li>
            </ul>
        </div>
    </div>
</div>
<?php else:?>
<!--已登录-->
<div class="user-top user-top2">
    <div class="top"> 
    <div class="left"><a href="javascript:history.back(-1)" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"/></a></div>
        <div class="middle">
            <div class="headpic"><a class="a-edit" href="<?php getUrl('userEdit');?>" ><img alt="" src="<?php echo empty($user['headimg'])?($attachUrl.'images/head.jpg'):($attachUrl.$user['headimg']);?>"/></a></div>
            <ul>
				<li class="shenf">
				<!--	<span><img alt="" src="<?php echo $staticUrl;?>images/admin.png"/><i>管理员</i></span>-->
				<?php if($user['local']):?>	<span><img alt="" src="<?php echo $staticUrl;?>images/dangd.png"/><i>当地人</i></span><?php endif;?>
				</li>
                <li><img alt="" src="<?php echo $staticUrl.($user['sex']=='F'?'images/gril.png':'images/boy.png');?>"/><font><?php $age = getAge($user['birthday']); echo $age?($age.'岁'):'';?></font><?php echo $user['nick_name'];?></li>
            </ul>
            <a href="<?php getUrl('userEdit');?>" class="edit"><img alt="" src="<?php echo $staticUrl;?>images/edit.png"/></a>
            <?php if(empty($user['nick_name'])): ?>
            <div class="edittips"><span><img alt="" src="<?php echo $staticUrl;?>images/arrow3.png"/></span><img alt="" src="<?php echo $staticUrl;?>images/tips2.png"/></div>
	        <?php endif; ?>
        </div>
	</div>
</div>
<?php endif;?>
<div class="user-nav">
	<div class="user-nav-list">
		<a href="<?php getUrl('userGroup');?>">
		<ul>
			<li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/group.png"/></li>
			<li class="uright <?php if(empty($session['inn_id'])) echo 'none';?>"><span class="left">我的部落</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
		</ul>
		</a>
    <?php if(!empty($session['inn_id'])):?>
        <a href="/group/newgroup">
		<ul>
			<li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/newgroup.png"/></li>
			<li class="uright none"><span class="left">创建部落</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
		</ul>
		</a>
	<?php endif;?>
	</div>
	<div class="user-nav-list">
		<a href="<?php getUrl('userWenda');?>">
		<ul>
			<li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/ask3.png"/></li>
			<li class="uright"><span class="left">我的问答</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
		</ul>
		</a>
		<a href="<?php getUrl('userJianren');?>">
		<ul>
			<li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/jianren3.png"/></li>
			<li class="uright"><span class="left">我的捡人</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
		</ul>
		</a>
		<a href="<?php getUrl('userTour');?>">
		<ul>
			<li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/youji2.png"/></li>
			<li class="uright none"><span class="left">我的游记</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
		</ul>
		</a>
	</div>
	<div class="user-nav-list">
		<a href="<?php getUrl('userMsg');?>">
		<ul>
			<li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/info2.png"/></li>
			<li class="uright none"><span class="left">我的消息</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
		</ul>
		</a>
	</div>
	<div class="user-nav-list">
		<a href="<?php getUrl('userJifen');?>">
		<ul>
			<li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/integral.png"/></li>
			<li class="uright none">
            
            <span class="left">我的积分</span>
            <span class="right"><!--<font>526积分</font>--><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> 
            </li>
		</ul>
		</a>
	</div>
</div>
