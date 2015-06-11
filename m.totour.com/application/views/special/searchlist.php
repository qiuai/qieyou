<style type="text/css">
#choose_container {
	position: relative;
}
#choose_con {
	position: absolute;
	left: 0;
	z-index: 10;
	width: 100%;
}
</style>
<header class="sale-top">
    <div id="adress">
        <ul>
            <li>
                <div class="adress_li">
                    <div>丽江</div>
                    <div><img src="<?php echo $staticUrl;?>images/arrow.png" /></div>
                </div>
                <span> <!-- span包div 不推荐  -->
                <div class="arrow"></div>
                <div class="alist"> <a href="#">大研古镇</a> <a href="#">丽江古镇</a> </div>
                </span> </li>
        </ul>
        <span class="clear"></span> </div>
    <div class="middle">
        <div id="nav_tabs" class="middlenav"><a href="#" class="now">商品</a><a href="#">商家</a></div>
    </div>
    <a href="<?php getUrl('search');?>" id="search"><img src="<?php echo $staticUrl?>images/serach.png" />&nbsp;</a> </header>
<div id="choose_container">
    <div class="choose">
        <ul id="choose_tabs">
            <li data-target="all"><a href="#"><em>全部分类</em><span></span></a></li>
            <li data-target="city"><a href="#"><em>全城</em><span></span></a></li>
            <li data-target="inte" class="bordernone"><a href="#"><em>智能排序</em><span></span></a></li>
        </ul>
    </div>
    <div id="choose_con"> 
        <!--全部分类-->
        <div data-name="all" class="choose-con" style="display:none;">
            <div node-type="cate" class="left">
                <ul data-type="all">
                    <?php foreach($category['title'] as $key => $row):?>
                    <li data-id="<?php echo $row['id']?>"<?php if($key==0): ?> class="now"<?php endif; ?>><a href="#"><?php echo $row['name']?><!--<span>23472 ></span>--></a></li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div node-type="list" class="right">
                <?php foreach($category['list'] as $key => $row):?>
                <ul data-pid="<?php echo $key;?>"<?php if($key>1): ?> style="display:none;"<?php endif; ?>>
                    <li data-id="0" data-cname="全部分类"><a href="#">全部<!--<span>23472 ></span>--></a></li>
                    <?php foreach($row as $k => $r):?>
                    <li data-id="<?php echo $r['category_id']?>" data-cname="<?php echo $r['name']; ?>"><a href="#"><?php echo $r['name']?></a></li>
                    <?php endforeach;?>
                </ul>
                <?php endforeach;?>
            </div>
            <span class="clear"></span>
            <div class="choose-close"><img src="<?php echo $staticUrl;?>images/c-close.jpg" /></div>
        </div>
        <!--全城-->
        <div data-name="city" class="choose-con" style="display:none;">
            <div node-type="cate" class="left">
                <ul data-type="city">
                    <li data-id="0" style="display:none;"><a href="#">全城<!--<span>23472 ></span>--></a></li>
                    <?php foreach($local['title'] as $key => $row):?>
                    <li data-id="<?php echo $row['dest_id']?>"<?php if($key==0): ?> class="now"<?php endif; ?>><a href="#"><?php echo $row['dest_name']?><!--<span>23472 ></span>--></a></li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div node-type="list" class="right">
                <?php foreach($local['list'] as $key => $row):?>
                <ul data-pid="<?php echo $key;?>"<?php if($key>1): ?> style="display:none;"<?php endif; ?>>
                    <li data-id="0" data-cname="全部分类" class="now"><a href="#">全部<!--<span>23472 ></span>--></a></li>
                    <?php foreach($row as $k => $r):?>
                    <li data-id="<?php echo $r['local_id']?>" data-cname="<?php echo $r['local_name']; ?>"><a href="#"><?php echo $r['local_name']?></a></li>
                    <?php endforeach;?>
                </ul>
                <?php endforeach;?>
            </div>
            <span class="clear"></span>
            <div class="choose-close"><img src="<?php echo $staticUrl;?>images/c-close.jpg" /></div>
        </div>
        <!--智能排序-->
        <div data-name="inte" class="choose-con2" style="display:none;">
            <div node-type="list">
                <ul data-type="inte">
                    <li data-id="0" data-cname="智能排序" style="display:none;"><a href="#"><em>智能排序</em></a></li>
                    <li data-id="1" data-cname="最新上线" class="now"><a href="#">最新上线</a></li>
                    <li data-id="2" data-cname="离我最近"><a href="#">离我最近</a></li>
                    <li data-id="3" data-cname="人气最旺"><a href="#">人气最旺</a></li>
                    <li data-id="4" data-cname="人均最低"><a href="#">人均最低</a></li>
                </ul>
            </div>
            <div class="choose-close"><img src="<?php echo $staticUrl;?>images/c-close.jpg" /></div>
        </div>
    </div>
</div>
<div id="sales_time" class="sale-tips">当前搜索：<font class="orange">鲜花饼</font></div>
<div class="sale-list">
<a href="#">
<div class="sleft"><span><img src="<?php echo $staticUrl;?>images/pic12.jpg" /></span></div>
<div class="sright">
<div class="sright-con">
    <dl>
        <dt><span class="left">玉龙雪山自主套票一张</span><span class="right"><img src="<?php echo $staticUrl;?>images/pos2.png" />2km</span></dt>
        <dd>玉龙雪山门票自助门票+大索道门玉龙雪山门玉龙雪山门票自助门票+大索道门玉龙雪山门玉龙雪山门票自助门票+大索道门玉龙雪山门</dd>
    </dl>
    <div class="pleft">
        <ul>
            <li class="price"><span class="redprice">230<i>.00</i></span>元 <font class="grayprice">689.00元</font></li>
            <li class="pj"><img src="<?php echo $staticUrl;?>images/star1.png" />2人评价</li>
        </ul>
    </div>
    <div class="pright"><img src="<?php echo $staticUrl;?>images/temp/round.jpg" /></div>
    </div>
</div>
<span class="clear"></span> </a></div>

<div class="sale-list">
<a href="#">
<div class="sleft"><span><img src="<?php echo $staticUrl;?>images/pic12.jpg" /></span></div>
<div class="sright">
    <div class="sright-con">
    <dl>
        <dt><span class="left">嘉华鲜花饼专卖店</span><span class="right"><img src="<?php echo $staticUrl;?>images/pos2.png" />2km</span><span class="clear"></span></dt>
        <dd class="sm">新购茶业旗下品牌“新益号”，品牌创于
2009年，目前新益号普洱茶在众多网新购茶业旗下品牌“新益号”，品牌创于
2009年，目前新益号普洱茶在众多网</dd>
    </dl>
<div class="sadd">云南 昆明市 雄达茶城唐城2幢16-17号</div>
</div>
</div>
<span class="clear"></span> </a></div>


<script type="text/javascript">var REQUIRE = {MODULE: 'page/special'}, POSITION = {lat: <?php echo empty($session['lat']) ? 'undefined' : $session['lat']; ?>, lon: <?php echo empty($session['lnt']) ? 'undefined' : $session['lnt']; ?>};</script>