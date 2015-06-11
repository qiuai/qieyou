<!DOCTYPE html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<link rel="shortcut icon" href="<?php echo $attachUrl;?>favicon.ico" type="image/x-icon" />
<title>且游旅行 APP下载</title>
<style>
body{ margin:0; padding:0; font: 14px/1.5 arial, Microsoft YaHei;background:#f3efec}
img{width: 100%;height: auto; margin:0; padding:0; border:0}
@media screen and (min-width:240px) {html, body, button, input, select, textarea {font-size: 9px}}
@media screen and (min-width:300px) {html, body, button, input, select, textarea {font-size: 11px}}
@media screen and (min-width:320px) {html, body, button, input, select, textarea {font-size: 12px}}
@media screen and (min-width:380px) {html, body, button, input, select, textarea {font-size: 13px}}
@media screen and (min-width:420px) {html, body, button, input, select, textarea {font-size: 14px}}
@media screen and (min-width:450px) {html, body, button, input, select, textarea {font-size: 15px}}
@media screen and (min-width:480px) {html, body, button, input, select, textarea {font-size: 16px}}
@media screen and (min-width:540px) {html, body, button, input, select, textarea {font-size: 17px}}
@media screen and (min-width:600px) {html, body, button, input, select, textarea {font-size: 18px}}
@media screen and (min-width:640px) {html, body, button, input, select, textarea {font-size: 18px}}
header{height:12rem;  box-sizing:border-box; background:url(<?php echo $staticUrl;?>images/download/down-top.jpg) no-repeat; background-size:100% 12rem; text-align:center; overflow:hidden; position:relative}
.back{ position:absolute; top:1rem; left:1rem;}
.back img{ width:1.5rem}
.down-logo{ margin:0 auto; height:5.5rem;margin-top:1rem;}
.down-logo img{ height:5rem;width:auto}
.down-btn{margin:1rem auto 0 auto;width:24rem}
.down-btn a{margin:0 0.5rem; font-size:1.4rem}
.black-btn,.white-btn{height:3rem; width:45%; background:url(<?php echo $staticUrl;?>images/download/down-icon.png) -2px 0 no-repeat #000; background-size:3rem auto; text-indent:1.5rem; border-radius:2rem; line-height:3rem; display:inline-block; color:#fff; text-decoration:none}
.white-btn{background:url(<?php echo $staticUrl;?>images/download/down-icon.png) 0 -3.2rem no-repeat #fff;background-size:2.8rem auto; color:#64bb28;text-indent:1.8rem;}
.down-jian{background:url(<?php echo $staticUrl;?>images/download/down-icon.png) -8px -10rem no-repeat;background-size:4rem auto; height:3rem; width:4rem; margin:0 auto;}
.down-con{ text-align:center;}
.down-con img{width:25rem;}
.down-con p{margin:3rem 0 5rem 0}
.down-con .dtext img{width:auto; height:3.8rem; display:block; margin:1rem auto 0 auto}
.nav_fixed{position: fixed;top: 0px;}
#J_m_nav{background:url(<?php echo $staticUrl;?>images/download/down-top.jpg) 0 -7.2rem no-repeat; background-size:100% 12rem; height:5.5rem; width:100%}
</style>
</head>
<body>


<header>
	<div class="down-logo"><img src="<?php echo $staticUrl;?>images/download/down-logo.png" ></div>
	<div id="J_m_nav">
    <div class="down-btn"><a href="http://fir.im/qieyoumerchant" class="black-btn">iPhone下载</a><a href="https://fir.im/qieyoustore" class="white-btn"> Android下载</a></div>
<!--	<div class="down-jian"></div>-->    </div>
	<a href="javascript:history.back(-1)" class="back"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"/></a>
</header>
<div class="down-con">
	<p><img src="<?php echo $staticUrl;?>images/download/down-p1.jpg" ><i class="dtext"><img src="<?php echo $staticUrl;?>images/download/down-p1a.jpg" ></i></p>
	<p><img src="<?php echo $staticUrl;?>images/download/down-p2.jpg" ><i class="dtext"><img src="<?php echo $staticUrl;?>images/download/down-p2a.jpg" ></i></p>
	<p><img src="<?php echo $staticUrl;?>images/download/down-p3.jpg" ><i class="dtext"><img src="<?php echo $staticUrl;?>images/download/down-p3a.jpg" ></i></p>
	<p><img src="<?php echo $staticUrl;?>images/download/down-p4.jpg" ><i class="dtext"><img src="<?php echo $staticUrl;?>images/download/down-p4a.jpg" ></i></p>
</div>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery-1.11.2.min.js"></script>		<script type='text/javascript' >
			var nt = !1;
			$(window).bind("scroll",
				function() {
				var st = $(document).scrollTop();//往下滚的高度
				nt = nt ? nt: $("#J_m_nav").offset().top;
				// document.title=st;
				var sel=$("#J_m_nav");
				if (nt < st) {
					sel.addClass("nav_fixed");
				} else {
					sel.removeClass("nav_fixed");
				}
			});
		</script>
        </body>
</html>