<div class="group" >
    <ul id="nav_tabs">
        <li style="border:0" class="active"><span>加入的部落（<?php echo count($groups);?>）</span></li>
        <li><span>管理的部落（<?php echo count($admin);?>）</span></li>
    </ul>
</div>
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide ">
            <div id="content_attention">
                <?php if($groups):?>
                <?php foreach($groups as $key => $row):?>
                <div class="groupcon">
                    <div class="gleft"><a href="/group/<?php echo $row['group_id']; ?>"><span><img alt="" src="<?php echo $attachUrl.$row['group_img'];?>"/></span></a></div>
                    <div class="gright"><div class="gright-con"> <span class="cleft"> <a href="/group/<?php echo $row['group_id']; ?>">
                        <p class="tit"><?php echo $row['group_name'];?></p>
                        <p class="con"><!--今日贴子<span><?php echo $row['today_topics'];?></span>--><!--精华<span>33</span>--> 帖子<span><?php echo $row['group_topics'];?></span>关注<span><?php echo $row['members'];?></span></p>
                        </a> </span> <div class="cright"><a data-attention="quit" data-gid="<?php echo $row['group_id']; ?>" href="#" >退出部落</a></div> 
                        <!-- <a data-attention="join" data-gid="<?php echo $row['group_id']; ?>" href="#" class="right">加入部落</a> --></div>
                    </div>
                </div>
                <?php endforeach;?>
                <?php else:?>
                <div class="rs-empty">暂无数据</div>
                <?php endif;?>
            </div>
        </div>
        <div class="swiper-slide">
            <div id="content_admin">
                <?php if($admin):?>
                <?php foreach($admin as $key => $row):?>
                <div class="groupcon">
                    <div class="gleft"><a href="/group/<?php echo $row['group_id']; ?>"><span><img alt="" src="<?php echo $attachUrl.$row['group_img'];?>"/></span></a></div>
                    <div class="gright"> 
                    <div class="gright-con">
                    <span class="cleft">
                    <a href="/group/<?php echo $row['group_id']; ?>">
                        <p class="tit"><?php echo $row['group_name'];?></p>
                        <p class="con"><!--今日贴子<span><?php echo $row['today_topics'];?></span>--><!--精华<span>33</span>-->帖子<span><?php echo $row['group_topics'];?></span>关注<span><?php echo $row['members'];?></span></p>
                        </span></a> <div class="cright"><a href="<?php echo $baseUrl.'group/groupadmin?group='.$row['group_id'];?>" >管理</a> </div>
                        <!--<a href="#" class="right">已加入</a> --> 
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
                <?php else:?>
                <div class="rs-empty">暂无数据</div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/user/group'};</script>