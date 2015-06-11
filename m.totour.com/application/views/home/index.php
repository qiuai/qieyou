<!--未登录-->
<?php if(empty($session['user_id'])):?>

<div class="user-top">
    <div class="top">
        <div class="left"><!--<a href="javascript:history.back(-1)" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"/></a>-->&nbsp;</div>
        <div class="middle">
            <div class="headpic"><img alt="" src="<?php echo $staticUrl;?>images/head.jpg"/></div>
            <ul>
                <li><a href="<?php getUrl('login');?>?url=<?php getUrl('user');?>">登录</a><a href="<?php getUrl('userReg');?>">注册</a></li>
            </ul>
        </div>
    </div>
    <div class="topnav"><a href="<?php getUrl('userOrder');?>#O"><img alt="" src="<?php echo $staticUrl;?>images/order.png"/>全部订单</a><a href="<?php getUrl('userOrder');?>#A"><img alt="" src="<?php echo $staticUrl;?>images/pay.png"/>待支付</a> <a href="<?php getUrl('userOrder');?>#U"><img alt="" src="<?php echo $staticUrl;?>images/consume.png"/>已支付</a> <a href="<?php getUrl('userOrder');?>#R"><img alt="" src="<?php echo $staticUrl;?>images/refund.png"/>待退款</a>  </div>
</div>
<?php else:?>
<!--已登录-->
<div class="user-top">
    <div class="top">
        <div class="left"><!--<a href="javascript:history.back(-1)" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"/></a>-->&nbsp;</div>
        <div class="middle">
            <div class="headpic"><a class="a-edit" href="<?php getUrl('userEdit');?>" ><img alt="" src="<?php echo empty($user['headimg'])?($attachUrl.'images/head.jpg'):($attachUrl.$user['headimg']);?>"/></a></div>
            <ul>
                <li class="shenf">
				<!--	<span><img alt="" src="<?php echo $staticUrl;?>images/admin.png"/><i>管理员</i></span> -->
					<?php if($user['local']):?>
					<span><img alt="" src="<?php echo $staticUrl;?>images/dangd.png"/><i>当地人</i></span>
					<?php endif;?>
				</li>
                <li class="sex"><img alt="" src="<?php echo $staticUrl.($user['sex']=='F'?'images/gril.png':'images/boy.png');?>"/><font><?php $age = getAge($user['birthday']); echo $age?($age.'岁'):'';?></font><?php echo $user['nick_name'];?></li>
            </ul>
            <a href="<?php getUrl('userEdit');?>" class="edit"><img alt="" src="<?php echo $staticUrl;?>images/edit.png"/></a>
            <?php if(empty($user['nick_name'])): ?>
            <div class="edittips"><span><img alt="" src="<?php echo $staticUrl;?>images/arrow3.png"/></span><img alt="" src="<?php echo $staticUrl;?>images/tips2.png"/></div>
            <?php endif; ?>
        </div>
        <!--        <div class="right"><a href="<?php getUrl('userEdit');?>" class="right"><img alt="" src="<?php echo $staticUrl;?>images/set.png"/></a></div>--> 
    </div>
    <div class="topnav"> <a href="<?php getUrl('userOrder');?>#O"><img alt="" src="<?php echo $staticUrl;?>images/order.png"/>全部订单</a> <a href="<?php getUrl('userOrder');?>#A"><img alt="" src="<?php echo $staticUrl;?>images/pay.png"/>待支付<!--<span>0</span>--></a> <a href="<?php getUrl('userOrder');?>#U"><img alt="" src="<?php echo $staticUrl;?>images/consume.png"/>已支付</a> <a href="<?php getUrl('userOrder');?>#R"><img alt="" src="<?php echo $staticUrl;?>images/refund.png"/>待退款</a> </div>
</div>
<?php endif;?>
<div class="user-nav">
    <?php if(!empty($session['inn_id'])):?>
    <div class="user-nav-list"> <a href="<?php getUrl('userFinance');?>">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/balance.png"/></li>
            <li class="uright none"><span class="left">账户余额</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> </div>
    <?php endif;?>
    <div class="user-nav-list"> <a href="<?php getUrl('userLike');?>">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/collect2.png"/></li>
            <li class="uright"><span class="left">我的收藏</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> <a href="<?php getUrl('userJifen');?>">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/integral.png"/></li>
            <li class="uright"><span class="left">我的积分(
                <?php //<a> 待补充</a>;?>
                查看积分规则)</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> <a href="<?php getUrl('userQuan');?>">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/quan.png"/></li>
            <li class="uright none"><span class="left">我的抵用券</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> </div>
    <div class="user-nav-list"> <a href="<?php getUrl('userIdcard');?>">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/card.png"/></li>
            <li class="uright"><span class="left">常用证件信息</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> <a href="<?php getUrl('userAddress');?>">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/address.png"/></li>
            <li class="uright none"><span class="left">常用收货地址</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> </div>
    <div class="user-nav-list"> <a href="tel:400-8857171">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/server.png"/></li>
            <li class="uright"><span class="left">且游客服</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> <a href="/home/feedback">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/feedback.png"/></li>
            <li class="uright"><span class="left">意见反馈</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> <a href="<?php getUrl('aboutQieyou');?>">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/about.png"/></li>
            <li class="uright none"><span class="left">关于且游</span><span class="right"><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> </div>
</div>

<script type="text/javascript">var REQUIRE = {MODULE: 'page/index'}, G = {pos: {<?php if(!empty($session['lat'])) echo 'lat:'.$session['lat'].',lon:'.$session['lon']; ?>}};</script>