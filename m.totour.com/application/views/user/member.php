<div class="group" >
    <ul id="nav_tabs">
        <li class="active"><span>部落成员（0）</span></li>
        <li><span>待确认成员（0）</span></li>
    </ul>
</div>
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide membercon">
            <div id="content_staff">
                <ul>
                    <li class="gleft"><img alt="" src="<?php echo $staticUrl;?>images/pic8.jpg"></li>
                    <li class="gright">
                        <p class="tit">丽江小美神<img alt="" src="<?php echo $staticUrl;?>images/grilred.png"></p>
                        <p><font>2015-02-13加入</font>22490贴</p>
                        <i><a href="#" class="red">删除</a></i>
                    </li>
                    <span class="clear"></span>
                </ul>
            </div>
            <div class="loading"></div>
        </div>
        <div class="swiper-slide membercon">
            <div id="content_confirm">
                <ul>
                    <li class="gleft"><img alt="" src="<?php echo $staticUrl;?>images/pic8.jpg"></li>
                    <li class="gright">
                        <p class="tit">丽江小美神<img alt="" src="<?php echo $staticUrl;?>images/grilred.png"></p>
                        <p><font>2015-02-13加入</font></p>
                        <i><a href="#" class="red">接受</a><a href="#" class="red">忽略</a></i>
                        <!--<span></span>--> 
                    </li>
                    <span class="clear"></span>
                </ul>
            </div>
            <div class="loading"></div>
        </div>
    </div>
</div>
<script id="template_item" type="text/template">
    <%each list v%>
    <ul>
        <li class="gleft"><img alt="" src="<?php echo $staticUrl;?>images/pic8.jpg"></li>
         <li class="gright">
            <p class="tit">丽江小美神<img alt="" src="<?php echo $staticUrl;?>images/grilred.png"></p>
            <p><font>2015-02-13加入</font>22490贴</p>
            <select name="">
                <option>普通会员</option>
                <option>管理员</option>
            </select>
            <!--<span></span>-->
        </li>
        <span class="clear"></span>
    </ul>
    <ul>
        <li class="gleft"><span><img alt="" src="<?php echo $staticUrl;?>images/pic8.jpg"></span></li>
         <li class="gright bordernone">
            <p class="tit">丽江小美神<img alt="" src="<?php echo $staticUrl;?>images/grilred.png"></p>
            <p><font>2015-02-13请求加入</font>22490贴</p>
            <i>待确认</i>
        </li>
        <span class="clear"></span>
    </ul>
    <%/each%>
</script> 
<script type="text/javascript">var REQUIRE = {MODULE: 'page/user/member'};</script>
