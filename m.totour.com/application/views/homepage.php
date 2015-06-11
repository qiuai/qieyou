<!DOCTYPE html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<!-- <link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/component.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/content.css" /> -->
<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/homepage.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/base.css"/>
<link rel="shortcut icon" href="<?php echo $attachUrl;?>favicon.ico" type="image/x-icon" />
<style type="text/css">#ringimg .swiper-slide{width:100%;}</style>
<script type="text/javascript">window.QY = {domain: {base: '<?php echo $baseUrl;?>', resource: '<?php echo $staticUrl;?>', attach: '<?php echo $attachUrl;?>'}}</script>
<title>且游旅行</title>
</head>
<body>
<header>
    <div class="top">
        <div id="adress">
            <ul>
                <li>
                    <div id="select_city" class="adress_li">
                        <div><?php if(empty($_COOKIE['city'])): ?>丽江<?php else: ?><?php echo $_COOKIE['city']; ?><?php endif; ?></div>
                        <div><img src="<?php echo $staticUrl;?>images/arrow.png"></div>
                    </div>
                    <span id="city_list" style="display:none;"> <!-- span包div 不推荐  -->
                    <div class="arrow"></div>
                    <div class="alist">
                        <a data-cid="530700" data-cname="丽江" href="#">丽江</a>
                        <a data-cid="532900" data-cname="大理" href="#">大理</a>
                    </div>
                    </span>
                </li>
            </ul>
            <span class="clear"></span>
        </div>
        <h1 id="logo">且游旅行</h1>
        <div id="mine">
            <?php if(isset($session['user_id'])):?>
            <a href="<?php getUrl('user');?>">
            <div class="pic"><img src="<?php echo $attachUrl.$session['headimg'];?>"/></div>
            </a> 
            <?php else:?>
            <a href="<?php getUrl('login');?>">登录</a>
            <?php endif;?>
        </div>
        <a href="<?php getUrl('search');?>" id="search"><img src="<?php echo $staticUrl?>images/serach.png" /></a>
    </div>
    <div class="hicon">
        <div class="tline"></div>
        <ul>
            <li><a href="<?php getUrl('group');?>#tour"><img src="<?php echo $staticUrl?>images/homepage_tour.png"/>游记</a></li>
            <li><a href="<?php getUrl('group');?>#jianren"><img src="<?php echo $staticUrl?>images/homepage_jian.png"/>捡人</a></li>
            <li><a href="<?php getUrl('group');?>#wenda"><img src="<?php echo $staticUrl?>images/homepage_ask.png"/>问答</a></li>
        </ul>
        <span class="clear"></span>
    </div>
</header>
<div class="footer_front"><img src="<?php echo $staticUrl?>images/front.png"></div>
<div class="frameMain">
<div class="wrap2">
	<?php if(!empty($groups)):?>
    <div class="footer_front"><img src="<?php echo $staticUrl;?>images/front.png" width="100%" height="100%"></div>
    <div class="ring">
        <label>热门部落</label>
        <a href="<?php getUrl('group');?>#rank">全部&nbsp;></a>
	</div>
    <div class="ringpic">
        <div id="ringimg" class="swiper-container imgs">
            <ul class="swiper-wrapper imgBox" style="list-style: none;">
				<li class="swiper-slide">
			<?php foreach($groups as $key => $row):?>
            <?php if($key && $key%3 == 0):?>
				</li>
				<li class="swiper-slide">
			<?php endif;?>
				<dl>
					<a href="<?php echo $baseUrl.'group/'.$row['group_id'];?>">
					<dt><img alt="" src="<?php echo $attachUrl.$row['group_img'];?>"/></dt>
					<dd><?php echo $row['group_name']?></dd>
					</a>
				</dl>
			<?php endforeach?>
				</li>
            </ul>
        </div>
        <div class="extalBox">
            <ul class="btnBox" id="btnBox2">
			<?php foreach($groups as $key => $row):?>
            <?php if($key%3 == 0):?>
                <li <?php if(!$key) echo 'class="swiper-active-switch"';?>></li>
			<?php endif;?>
			<?php endforeach?>
            </ul>
        </div>
    </div>
	<?php endif;?>
	<?php if(!empty($jianren)):?>
    <div class="find">
        <label>正在捡人</label>
        <a href="<?php getUrl('group');?>#jianren">全部&nbsp;></a>
	</div>
    <div id="jianren_list" class="findcon">
		<?php foreach($jianren as $key => $row):?>
        <ul data-pos="<?php echo $row['lat'].','.$row['lon'];?>">
            <a href="<?php echo $baseUrl.'forum/'.$row['forum_id'];?>">
            <li class="headpic"><span class="outer"><img src="<?php echo $attachUrl.$row['headimg'];?>"/></span></li>
            <li class="con">
                <dl class="contop">
                    <dt><?php echo $row['nick_name'];?><img src="<?php echo $staticUrl;?>images/<?php if($row['sex']=='F'): ?>grilred<?php else: ?>boyred<?php endif; ?>.png"  />
						<font><?php echo $row['age']?($row['age'].'岁'):'';?></font>
					</dt>
                    <dd node-type="pos"></dd>
                </dl>
                <dl class="conbottom">
                    <dt><?php echo $row['city'];?></dt>
                    <dd><?php echo showTime($row['create_time']);?></dd>
                </dl>
            </li>
            </a> 
			<span class="clear"></span>
        </ul>
		<?php endforeach;?>
    </div>
	<?php endif;?>
	<?php if(!empty($products)):?>
    <span class="gray"></span>
    <div class="sale">
        <label>今日上新</label>
        <a href="<?php getUrl('special');?>">全部&nbsp;></a>
	</div>
	<?php foreach($products as $key => $row):?>
	<a href="<?php echo $baseUrl.'item/'.$row['product_id']?>">
    <div class="salecon">
        <dl>
            <dt><?php echo $row['product_name'];?></dt>
            <dd>
                <div><img src="<?php echo $staticUrl;?>images/time.png" width="32" height="32" />剩余<?php echo round(($row['tuan_end_time']-TIME_NOW)/86400).'天';?></div>
            </dd>
        </dl>
        <ul>
		<?php $images = explode(',',$row['gallery']);?>
            <li class="pic1"><img src="<?php echo $attachUrl.$images[0];?>" /></li>
			<?php if(!empty($images[1])):?>
            <li class="pic2">
				<span class="mbottom"><img src="<?php echo $attachUrl.$images[1];?>"/></span>
				<?php if(!empty($images[2])):?>
				<span><img src="<?php echo $attachUrl.$images[2];?>" /></span>
				<?php endif;?>
			</li>
			<span class="clear"></span>
			<?php endif;?>
            <li class="text"><span>特卖</span><font><?php echo $row['price'];?></font>元<i><?php echo $row['old_price'].'元';?></i></li>
        </ul>
    </div>
	</a>
	<?php endforeach;?>
	<?php endif;?>
    </div>
    <div class="copyright">
        <ul>
			<li>
				<a href="<?php echo $baseUrl;?>"><span>首页</span></a>
				<a href="<?php getUrl('group');?>"><span>圈子</span></a>
				<a href="<?php getUrl('special');?>"><span>特卖</span></a>
				<a href="<?php getUrl('user');?>"><span>我的</span></a>
				<a href="<?php getUrl('downloadApp');?>" class="none"><span>客户端</span></a>
			</li>
			<li class="cline"></li>
			<li class="ctext">© 2015 且游网 京ICP备15000312号-1</li>
            <li class="ctext">Version: <?php echo $this->config->item('version');?></li>
        </ul>
    </div>

    <footer>
        <div class="bottomnav">
            <ul>
                <li><a href="/" class="now"><img src="<?php echo $staticUrl;?>images/home-g.png"/>首页</a></li>
                <li><a href="<?php getUrl('group');?>"><img src="<?php echo $staticUrl;?>images/community-gray.png"/>圈子</a></li>
                <li>&nbsp;</li>
                <li><a href="<?php getUrl('special');?>"><img src="<?php echo $staticUrl;?>images/car-gray.png"/>特卖</a></li>
                <li><a href="<?php getUrl('user');?>" class="none"><img src="<?php echo $staticUrl;?>images/user-gray.png"/>我的</a></li>
                <span class="clear"></span>
            </ul>
        </div>
        <div id="dialog_foot_menu_btn" class="add">
            <button type="button" style="border:0;  background:rgba(0,0,0,0) none repeat scroll !important;background:#fff; filter:Alpha(opacity=0); "><img src="<?php echo $staticUrl;?>images/add.png"/></button>
        </div>
        <div id="dialog_foot_menu" class="addfoot-box" style="display:none;">
            <div class="addfoot">
				<div class="bottommenu"> 
					<a href="<?php getUrl('forumSendJian');?>"><span><img src="<?php echo $staticUrl;?>images/jianr.png"/></span>发捡人</a>
					<a href="<?php getUrl('forumSendTour');?>"><span class="youji"><img src="<?php echo $staticUrl;?>images/youji.png"/></span>发游记</a>
					<a href="<?php getUrl('forumSendWen');?>"><span class="ask"><img src="<?php echo $staticUrl;?>images/ask.png"/></span>发问答</a>
				</div>
				<div class="close"><img src="<?php echo $staticUrl;?>images/close2.png"/></div>
            </div>
        </div>
        <span class="clear"></span>
        <?php if(empty($_COOKIE['showad'])):?>
        <div id="download">
			<a id="download_close" href="javascript:void(0);" class="colse" onclick="javascript:void(0);"><img src="<?php echo $staticUrl;?>images/close.png"/></a>
			<a href="javascript:void(0);" class="text" >下载客户端，领取新人大礼包<span>立即下载</span></a>
		</div>
        <script>
        document.getElementById('download_close').addEventListener('click', function(){
            document.getElementById('download').style.display = 'none';
            var Days = 30; //此 cookie 将被保存 30 天
            var exp = new Date();
            exp.setTime(exp.getTime() + Days*24*60*60*1000);
            document.cookie = "showad=1;expires=" + exp.toGMTString();
        });
		</script>
        <?php endif;?>
    </footer>
    <?php if($dbInfo){ foreach($dbInfo as $query){ echo '<p>'.$query.'</p>';}};?>
</div>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/index'}, G = {pos: {<?php if(!empty($session['lat'])) echo 'lat:'.$session['lat'].',lon:'.$session['lon']; ?>}};</script>
<?php $this->load->view('_elements/resource_map'); ?>
<!--空白页-->
<div class="blank-bg" style="display:none"></div>
</body>
</html>