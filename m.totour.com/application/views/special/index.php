<header class="sale-top upside">
    <?php if(empty($keyword)): ?>
    <div id="adress">
        <ul>
            <li>
                <div id="select_city" class="adress_li">
                    <div><?php if(empty($_COOKIE['city'])): ?>丽江<?php else: ?><?php echo $_COOKIE['city']; ?><?php endif; ?></div>
                    <div><img src="<?php echo $staticUrl;?>images/arrow.png" /></div>
                </div>
                <span id="city_list" style="display:none;"> <!-- span包div 不推荐  -->
                <div class="arrow"></div>
                <div class="alist">
                    <?php foreach($citys as $key => $row):?>
                        <a data-cid="<?php echo $row['city'];?>" data-cname="<?php echo $row['name'];?>" href="#"><?php echo $row['name'];?></a>
                    <?php endforeach;?>
                </div>
                </span> </li>
        </ul>
        <span class="clear"></span> </div>
    <?php else: ?>
        <a href="javascript:history.back(-1);" class="sleft"><img alt="" src="<?php echo $staticUrl; ?>images/back2.png"></a>
    <?php endif; ?>
    <div class="middle">
        <?php if(empty($keyword)):?>
        <div id="nav_tabs" class="middlenav"><a href="#new" class="now">今日上新</a><a href="#all">全部特卖</a></div>
        <?php else: ?>
        <div id="nav_tabs" class="middlenav"><a href="#item" class="now">商品</a><a href="#inn">商家</a></div>
        <?php  endif;?>
    </div>
    <a href="<?php getUrl('search');?>" id="search"><img src="<?php echo $staticUrl?>images/serach.png" />&nbsp;</a> </header>
<?php if(empty($keyword)):?>
<div id="choose_container" class="choose-container">
    <div class="choose upside">
        <ul id="choose_tabs">
            <li data-target="all"><a href="#"><em>全部分类</em><span></span></a></li>
            <li data-target="city"><a href="#"><em>全城</em><span></span></a></li>
            <li data-target="inte" class="bordernone"><a href="#"><em>智能排序</em><span></span></a></li>
        </ul>
    </div>
    <div id="choose_con" class="choose-wrap">
        <!--全部分类-->
        <div data-name="all" class="choose-con" style="display:none;">
            <div node-type="cate" class="left">
                <ul data-type="all">
                <li data-id="0" data-name="全部分类" class="now"><a href="#">全部</a></li>
            <?php foreach($category['title'] as $key => $row):?>
                <li data-id="<?php echo $row['id']?>" data-name="<?php echo $row['name']?>"><a href="#"><?php echo $row['name']?><!--<span>23472 ></span>--></a></li>
			<?php endforeach;?>
                </ul>
            </div>
            <div node-type="list" class="right">
                <ul data-pid="0"></ul>
			<?php foreach($category['list'] as $key => $row):?>
                <ul data-pid="<?php echo $key;?>" style="display:none;">
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
                    <li data-id="0" data-name="全城" class="now"><a href="#">全部</a></li>
				<?php foreach($local['title'] as $key => $row):?>
                    <li data-id="<?php echo $row['dest_id']?>" data-name="<?php echo $row['dest_name']?>"><a href="#"><?php echo $row['dest_name']?><!--<span>23472 ></span>--></a></li>
				<?php endforeach;?>
                </ul>
            </div>
            <div node-type="list" class="right">
                <ul data-pid="0"></ul>
			<?php foreach($local['list'] as $key => $row):?>
				<ul data-pid="<?php echo $key;?>" style="display:none;">
                <li data-id="0" data-cname="全城" class="now"><a href="#">全部<!--<span>23472 ></span>--></a></li>
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
                    <li data-id="time" data-cname="最新上线" class="now"><a href="#">最新上线</a></li>
                    <li data-id="local" data-cname="离我最近"><a href="#">离我最近</a></li>
                    <li data-id="highp" data-cname="人气最旺"><a href="#">人气最旺</a></li>
                    <li data-id="lowp" data-cname="人均最低"><a href="#">人均最低</a></li>
                </ul>
            </div>
            <div class="choose-close"><img src="<?php echo $staticUrl;?>images/c-close.jpg" /></div>
        </div>
    </div>
    <div id="choose_cover" class="choose-cover" style="display:none;"></div>
</div>
<div id="sales_time" class="sale-tips"><span>且游团购 每周三10:00上新</span></div>
<?php else:?>
<div id="sales_time" class="sale-tips">当前搜索：<font class="orange"><?php echo $keyword;?></font></div>
<?php endif;?>


<div class="container">
    <div id="content_list"></div>
    <div class="loading"></div>
</div>


<script id="page_item" type="text/template">
    <div data-page="<%=data.page%>">
        <%each data.list v%>
            <%if data.type=='inn'%>
            <div class="sale-list">
                <a href="/special/inn?sid=<%=v.inn_id%>">
                <div class="sleft"><img src="<?php echo $attachUrl;?><%=v.inn_head%>" /></div>
                <div class="sright">
                    <div class="sright-con">
                    <dl>
                        <dt><span class="left"><%=v.inn_name%></span><%if v.pos%><span class="right"><img src="<?php echo $staticUrl;?>images/pos2.png" /><%=v.pos%></span><%/if%><span class="clear"></span></dt>
                        <dd class="sm"><%=v.inn_summary%></dd>
                    </dl>
                <div class="sadd"><%=v.inn_address%></div>
                </div>
                </div>
                <span class="clear"></span> </a>
            </div>
            <%else%>
            <div class="sale-list">
                <a href="/item/<%=v.product_id%>">
                    <div class="sleft"><img src="<?php echo $attachUrl;?><%=v.thumb%>" /></div>
                    <div class="sright">
                        <div class="sright-con">
    					<dl>
                            <dt><span class="left"><%=v.product_name%></span><%if v.pos%><span class="right"><img src="<?php echo $staticUrl;?>images/pos2.png" /><%=v.pos%></span><%/if%></dt>
                            <dd><%=v.content%></dd>
                        </dl>
                        <div class="pleft">
                            <ul>
                                <li class="price"><span class="redprice"><%v.price=v.price.split('.')%><%=v.price[0]%>.<i><%=v.price[1]%></i></span> <font class="grayprice"><%=v.old_price%></font></li>
                                <li class="pj"><img src="<?php echo $staticUrl;?>images/star<%=v.score%>.png" /><span><%=v.comments%>人评价</span></li>
                            </ul>
                        </div>
                        <div class="pright">
                            <div class="quantity">
                                <%v.quantity=parseInt(v.quantity)%>
                                <%v.com_count=v.quantity+parseInt(v.bought_count)%>
                                <%v.percentage=v.quantity==0&&v.com_count==0?0:Math.round(parseInt(v.quantity)/v.com_count*100)%>
                                <div class="inner" style="width:<%=v.percentage%>%;"></div>
                                <div class="desc"><p>仅剩</p><p><%=v.percentage%>%</p></div>
                            </div>
                        </div>
    					</div>
                    </div>
                    <span class="clear"></span>
                </a>
            </div>
            <%/if%>
        <%eachElse%>
        <div class="rs-empty">暂无结果</div>
        <%/each%>
		
    </div>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/special'}, POSITION = {lat: <?php echo empty($session['lat']) ? 'undefined' : $session['lat']; ?>, lon: <?php echo empty($session['lon']) ? 'undefined' : $session['lon']; ?>};</script>
