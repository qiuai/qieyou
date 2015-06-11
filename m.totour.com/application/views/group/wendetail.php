<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<div class="d-top">
    <div class="left">来自春节旅行部落</div>
    <div class="right"><a href="/group/groupdetail">进入部落</a></div>
</div>
<div class="scon-list">
    <div class="list-tit">
        <div class="gleft dimg"><a href="#"> <img alt="" src="<?php echo $staticUrl;?>images/pic8.jpg"/></a>
            <div class="sf"><img alt="" src="<?php echo $staticUrl;?>images/dangd.png"/></div>
        </div>
        <div class="gright">
            <div class="left">
                <ul style="margin-top:-0.5rem">
                    <a href="#">
                    <li class="red">甜美邂逅~<img alt="" src="<?php echo $staticUrl;?>images/boyred.png"/><font>22岁</font> <span><img alt="" src="<?php echo $staticUrl;?>images/ask4.png"/>问答贴</span> </li>
                    <li class="fgray">丽江古城</li>
                    </a>
                </ul>
            </div>
            <div class="right">
                <ul>
                    <li><img alt="" src="<?php echo $staticUrl;?>images/time.png"/>56分钟</li>
                    <li class="pos"><a href="#"><img alt="" src="<?php echo $staticUrl;?>images/pos3.png"/>2km</a></li>
                </ul>
            </div>
        </div>
        <span class="clear"></span> </div>
    <div class="list-con2">
        <dl>
            <dt>[问答]纪念最后一次的婚前旅行</dt>
            <dd class="dkey"><a href="#">婚前旅行</a><a href="#">旅行</a></dd>
            <dd>还记得完美一起用旧衣改造的围巾吗？在
                我眼里它比什奢侈品更珍得完美一起用旧一起用旧衣贵。还有完美一起养的小狗</dd>
        </dl>
        <div class="list-img">
            <ul id="picture_list">
                <li data-pictureView="<?php echo $staticUrl;?>images/pic10.jpg"><img src="<?php echo $staticUrl;?>images/pic10.jpg"/></li>
                <li data-pictureView="<?php echo $staticUrl;?>images/pic13.jpg"><img src="<?php echo $staticUrl;?>images/pic13.jpg"/></li>
                <li data-pictureView="<?php echo $staticUrl;?>images/pic13.jpg"><img src="<?php echo $staticUrl;?>images/pic13.jpg"/></li>
            </ul>
        </div>
    </div>
    <span class="clear"></span> </div>
<div class="huif">共20条回答</div>
<div class="ping">
    <div id="comment_list"></div>
    <div class="loading"></div>
</div>

<div class="d-foot">
    <div id="wenda_action" class="dnav"> <a data-action="praise" data-id="1" data-num="10" href="#"><span><img alt="" src="<?php echo $staticUrl;?>images/praise-red.png"/></span>110</a> <a data-action="share" data-id="1" data-num="10" href="#"><span><img alt="" src="<?php echo $staticUrl;?>images/zhuan-red.png"/></span>10</a> <a data-action="comment" data-id="1" data-num="10" href="#"><span><img alt="" src="<?php echo $staticUrl;?>images/info-red.png"/></span>110</a> <a data-action="give" data-id="1" href="#"><span><img alt="" src="<?php echo $staticUrl;?>images/jifen-red.png"/></span>打赏</a> <a data-action="collect" data-id="1" href="#"><span><img alt="" src="<?php echo $staticUrl;?>images/collect-red.png"/></span>收藏</a>

    </div>
</div>

<div id="dialogMainReply" style="display:none;position:fixed;top:0;left:0;z-index:99999;width:100%;height:100%;background-color:#fff;">
    <form method="post" action="">
        <div class="replytop">
            <ul>
                <li id="dialogMainReplyCancle" class="close repleft">取消</li>
                <li class="repmiddle">回复评论<span id="comment_user_name">丽江小玫瑰</span></li>
                <li id="dialogMainReplySend" class="repright">发送</li>
            </ul>
        </div>
        <div class="reptext">
            <textarea id="mainReplyNote" cols="" rows=""></textarea>
        </div>
        <div class="picture">
            <ul id="upload_pic">
                <li id="upload_add"><a class="add" href="#"><span class="add-line"><span class="line1"></span><span class="line2"></span></span><div class="progress" style="display:none;"><div class="progress-bar"></div></div></a><input id="upload_img" class="upload-img" type="file"></li>
                <span class="clear"></span>
            </ul>
        </div>
    </form>
</div>

<div id="dialogReply" class="com-foot" style="display:none;"><input id="dialogReplyNote" name="" type="text" class="cinp" placeholder="回复 吉祥"><a id="dialogReplySend" href="#">发送</a></div>

<script id="template_comment" type="text/template">
    <%each list v%>
    <div class="ping-list">
        <div class="gleft"> <a href="#"><img alt="" src="<?php echo $staticUrl;?>images/pic8.jpg"/></a></div>
        <div class="gright">
            <ul style="margin-top:-0.5rem">
                <li class="left red">甜美邂逅~<img alt="" src="<?php echo $staticUrl;?>images/boyred.png"/><font>22岁</font> <font>涑河古镇</font></li>
                <li class="right"><img alt="" src="<?php echo $staticUrl;?>images/time.png"/>56分钟</li>
                <li class="text">美女~有联系方式吗美女~有联系方式吗美女~有联系方式吗美女~有联系方式吗</li>
            </ul>
            <div class="info"><a node-action="praise" data-cid="1" href="#"><img alt="" src="<?php echo $staticUrl;?>images/praise.png"/>20</a><a node-action="comment" data-uname="吉祥1" data-cid="1" href="#"><img alt="" src="<?php echo $staticUrl;?>images/info.png"/>20</a></div>
        </div>
        <span class="clear"></span>
        <div class="ping-con">
            <div class="jian"></div>
            <ul>
                <li><a href="#" class="red">米亚1997:</a><a href="#">哦我的要留一下吗</a></li>
                <li><a href="#" class="red">艾烈@^^ </a><a href="#">这要就要是不是太唐突了</a></li>
                <li><a class="more" href="/forum/comment">更多18条评论...</a></li>
            </ul>
        </div>
    </div>
    <%/each%>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/group/detail', DEP: 'wenda', FORUM_ID: 1}, G = {pos: {<?php if(!empty($session['lat'])) echo 'lat:'.$session['lat'].',lon:'.$session['lon']; ?>}};</script>