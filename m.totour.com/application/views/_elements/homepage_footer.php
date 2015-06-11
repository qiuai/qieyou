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
    </ul>
</div>
<span class="blank4a"></span>
<footer>
    <div class="morph-button morph-button-overlay morph-button-fixed" >
        <div class="bottomnav">
            <ul>
                <li><a href="<?php echo $baseUrl;?>"><img src="<?php echo $staticUrl;?>images/home-gray.png"/>首页</a></li>
                <li>
					<a href="<?php getUrl('group');?>" <?php if(isset($shouye)&&$shouye=='group') echo 'class="now"';?>>
						<img src="<?php echo $staticUrl;?>images/community-<?php if(isset($shouye)&&$shouye=='group') echo 'g';else echo 'gray';?>.png"/>圈子
					</a>
				</li>
                <li>&nbsp;</li>
                <li>
					<a href="<?php getUrl('special');?>" <?php if(isset($shouye)&&$shouye=='special') echo 'class="now"';?>>
						<img src="<?php echo $staticUrl;?>images/car-<?php if(isset($shouye)&&$shouye=='special') echo 'g';else echo 'gray';?>.png"/>特卖
					</a>
				</li>
                <li>
					<a href="<?php getUrl('user');?>" class="none <?php if(isset($shouye)&&$shouye=='home') echo 'now';?>">
						<img src="<?php echo $staticUrl;?>images/user-<?php if(isset($shouye)&&$shouye=='home') echo 'g';else echo 'gray';?>.png"/>我的
					</a>
				</li>
                <span class="clear"></span>
            </ul>
        </div>
        <div id="dialog_foot_menu_btn" class="add">
            <button type="button" style="border:0;background:rgba(0,0,0,0) none repeat scroll !important;background:#fff;filter:Alpha(opacity=0);"><img src="<?php echo $staticUrl;?>images/add.png"/></button>
        </div>

    </div>	
	<div id="dialog_foot_menu" class="addfoot-box" style="display:none;">
		<div class="addfoot dynamic">
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
		<a href="javascript:void(0);" class="colse" onclick="javascript:void(0);"><img src="<?php echo $staticUrl;?>images/close.png"/></a>
		<a href="javascript:void(0);" class="text" >下载客户端，领取新人大礼包<span>立即下载</span></a>
	</div>
	<script>
	$('.colse').click(function(){
		$('#download').hide();
		var Days = 30; //此 cookie 将被保存 30 天
		var exp = new Date();
		exp.setTime(exp.getTime() + Days*24*60*60*1000);
		document.cookie = "showad=1;expires=" + exp.toGMTString();
	});
	</script>
	<?php endif;?>
</footer>
<?php if($dbInfo){ foreach($dbInfo as $query){ echo '<p>'.$query.'</p>';}};?>