<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/user.css"/>
<div class="user-top user-top2">
    <div class="top">
        <div class="left"><a href="javascript:history.back(-1)" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"/></a></div>
        <div class="middle">
            <div class="headpic"><img alt="" src="<?php echo empty($user['headimg'])?($attachUrl.'images/head.jpg'):($attachUrl.$user['headimg']);?>"/></div>
            <ul>
                <li class="shenf">
				<!--	<span><img alt="" src="<?php echo $staticUrl;?>images/admin.png"/><i>管理员</i></span>-->
				<?php if($user['local']):?>	<span><img alt="" src="<?php echo $staticUrl;?>images/dangd.png"/><i>当地人</i></span><?php endif;?>
				</li>

                <li><img alt="" src="<?php echo $staticUrl.($user['sex']=='F'?'images/gril.png':'images/boy.png');?>"/><font><?php $age = getAge($user['birthday']); echo $age?($age.'岁'):'';?></font><?php echo $user['nick_name'];?></li>
            </ul>
        </div>
    </div>
</div>
<div class="user-nav">
    <div class="user-nav-list" >
		<a href="<?php echo $baseUrl.'user/'.$user['user_id'].'/group';?>">
        <ul style="height:3rem">
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/group.png"/></li>
            <li class="uright <?php if(!$user['group_count']) echo 'none';?>" style="padding-right:1rem; box-sizing:border-box"><span class="left">部落</span><span class="right fgray"><?php echo $user['group_count'];?><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a>
		<?php if($user['group_count']):?>
        <div id="group_list">
		<?php foreach($group_list as $key => $row):?>
            <div class="groupcon bordernone" style="padding-bottom:0;">
				<a href="<?php getUrl('groupDetail'); echo $row['group_id'];?>">
                <div class="gleft" style="margin-left:0"><span><img alt="" src="<?php echo $attachUrl.$row['group_img'];?>"/></span></div>
                <div class="gright" style="padding-right:0;"> 
                <div class="gright-con border-b" style="padding-right:1rem;">
					<span class="cleft">
						<p class="tit"><?php echo $row['group_name'];?></p>
						<p class="con">帖子<span><?php echo $row['group_topics'];?></span>关注<span><?php echo $row['members'];?></span></p>
                    </span>
					<?php if(empty($row['join_time'])):?>
					<a data-attention="join" data-gid="<?php echo $row['group_id'];?>" href="#" class="right gz">加入部落</a>
					<?php else:?>
					<a data-attention="quit" data-gid="<?php echo $row['group_id'];?>" href="#" class="right gz">已加入</a>
					<?php endif;?>
                    </div>
				</div>
				</a>
            </div>
		<?php endforeach;?>
		</div>
		<?php endif;?>
    </div>
    <div class="user-nav-list"> <a href="<?php echo $baseUrl.'user/'.$user['user_id'].'/wenda';?>">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/ask3.png"/></li>
            <li class="uright"><span class="left">问答</span><span class="right fgray"><?php echo $user['wenda_count'];?><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> <a href="<?php echo $baseUrl.'user/'.$user['user_id'].'/jianren';?>">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/jianren3.png"/></li>
            <li class="uright"><span class="left">捡人</span><span class="right fgray"><?php echo $user['jianren_count'];?><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> <a href="<?php echo $baseUrl.'user/'.$user['user_id'].'/tour';?>">
        <ul>
            <li class="uleft"><img alt="" src="<?php echo $staticUrl;?>images/youji2.png"/></li>
            <li class="uright none"><span class="left">游记</span><span class="right fgray"><?php echo $user['tour_count'];?><img alt="" src="<?php echo $staticUrl;?>images/arrow2.png"/></span> </li>
        </ul>
        </a> </div>
</div>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/user/card'};</script>
