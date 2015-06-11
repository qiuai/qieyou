<!DOCTYPE html>
<html lang="zh-CN">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no" />
<head>
<meta charset="UTF-8">
<title>订单管理</title>
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/customer.css">
</head>
<body>
<header>
    <div class="fl"><a href="#customer.html"><img src="images/back.png"></a></div>
    <div class="fm">订单管理</div>
    <form id="searchForm" class="fr">
        <div class="search2"><span><img id="searchBtn" src="images/search.png"></span><span>
            <input id="searchInput" name="" type="text" placeholder="请输入商品名称/订单号/手机号/姓名">
            </span></div>
    </form>
</header>
<div class="wrap">
    <div class="order">
        <div class="sidenav">
            <ul id="filterList">
                <li data-type="O" class="now"><a href="#">全部订单</a></li>
                <!-- <li data-type="R"><a href="javascript:void(0);">待发货</a></li> -->
                <li data-type="A"><a href="javascript:void(0);">待支付</a></li>
                <li data-type="P"><a href="javascript:void(0);">已支付</a></li>
                <li data-type="U"><a href="javascript:void(0);">待消费</a></li>
                <li data-type="R"><a href="javascript:void(0);">退款中</a></li>
                <li data-type="C"><a href="javascript:void(0);">已退款</a></li>
                <li data-type="S"><a href="javascript:void(0);">已完成</a></li>
                <li data-type="N"><a href="javascript:void(0);">已取消</a></li>
                <!-- <li data-type="C"><a href="javascript:void(0);">已消费</a></li> -->
            </ul>
        </div>
        <div id="orderBox">
            <div class="orderItem">
                <div id="order_O" class="order-list"></div>
                <div class="loading" style="margin-left:12rem;"></div>
            </div>
            <div class="orderItem" style="display:none;">
                <div id="order_A" class="order-list"></div>
                <div class="loading" style="margin-left:12rem;"></div>
            </div>
            <div class="orderItem" style="display:none;">
                <div id="order_P" class="order-list"></div>
                <div class="loading" style="margin-left:12rem;"></div>
            </div>
            <div class="orderItem" style="display:none;">
                <div id="order_S" class="order-list"></div>
                <div class="loading" style="margin-left:12rem;"></div>
            </div>
            <div class="orderItem" style="display:none;">
                <div id="order_R" class="order-list"></div>
                <div class="loading" style="margin-left:12rem;"></div>
            </div>
            <div class="orderItem" style="display:none;">
                <div id="order_C" class="order-list"></div>
                <div class="loading" style="margin-left:12rem;"></div>
            </div>
            <div class="orderItem" style="display:none;">
                <div id="order_N" class="order-list"></div>
                <div class="loading" style="margin-left:12rem;"></div>
            </div>
            <div class="orderItem" style="display:none;">
                <div id="order_U" class="order-list"></div>
                <div class="loading" style="margin-left:12rem;"></div>
            </div>
        </div>
    </div>
</div>

<div id="cover" class="global-cover" style="display:none;z-index:99;"></div>
<div id="orderDetail" class="orderdeta" style="display:none;">
    <div class="loading"></div>
</div>


<script id="template_item" type="text/template">
    <%each list v%>
    <div data-oid="<%=v.order_num%>" class="list-con">
        <div class="l-tit">
            <div class="left"><%=(new Date(parseInt(v.create_time)*1000)).format('yyyy-mm-dd')%></div>
            <div class="right">待支付</div>
            <span class="clear"></span>
        </div>
        <div class="l-pro">
            <a href="javascript:void(0);">
            <div class="pic"><img src="images/test/173421459272.jpg"></div>
            <div class="text">
                <dl>
                    <dt><%=v.product_name%></dt>
                    <dd><font class="red"><%=parseInt(v.price)||0%></font><span class="gray">元</span></dd>
                    <dd>X&nbsp;<%=v.quantity%></dd>
                </dl>
            </div>
            </a>
        </div>
        <div class="l-operate">
            <div class="left">总价：<font class="red"><%=parseInt(v.total)||0%></font><span class="gray">元</span></div>
            <div class="right"><a href="#" class="graybtn">取消订单</a><a href="#" class="redbtn2">去支付</a></div>
            <span class="clear"></span>
        </div>
    </div>
    <%/each%>
</script>
<script id="template_detail" type="text/template">
    <div class="tit">订单详情</div>
    <div class="zt">
        <ul>
            <li>已付款</li>
            <li>订单金额：<font class="red">234.00</font>元</li>
        </ul>
    </div>
    <div class="info">
        <div class="itit">订单信息</div>
        <dl>
            <dt>订单号</dt>
            <dd><%=data.order_num%></dd>
        </dl>
        <dl>
            <dt>联系人</dt>
            <dd>李小萌</dd>
        </dl>
        <dl>
            <dt>手机号码</dt>
            <dd>18612340330</dd>
        </dl>
        <dl>
            <dt>付款时间</dt>
            <dd>2015-05-02  12:23</dd>
        </dl>
    </div>
    <div class="info">
        <div class="itit">电子凭证</div>
        <dl>
            <dt>密码1 </dt>
            <dd>13478＊＊＊＊3467</dd>
            <span class="green right">未使用</span>
        </dl>
        <dl>
            <dt></dt>
            <dd>（有效期至2016-04-05） 12:23</dd>
        </dl>
    </div>
    <div class="pro">
        <div class="ptit">商品信息</div>
        <dl>
            <dt><img src="images/test/173421459272.jpg"></dt>
            <dd>
                <ul>
                    <li>云南大理现烤鲜花饼200g</li>
                    <li><font class="red">230.00</font>元</li>
                    <li>数量：2</li>
                </ul>
            </dd>
        </dl>
    </div>
    <div class="inner">
        <dl>
            <dt><img src="images/test/pic1.jpg"></dt>
            <dd>
                <ul>
                    <li>如意鲜花饼店</li>
                    <li class="gray">丽江市古城区七一街兴文巷14号</li>
                    <li class="green"><img class="icon-phone" src="images/phone.png" alt=""> 18654546776</li>
                </ul>
            </dd>
        </dl>
    </div>
    <div class="info">
        <div class="itit">订单金额</div>
        <dl>
            <dt>商品总额</dt>
            <dd class="r">460.00</dd>
        </dl>
        <dl>
            <dt>运费</dt>
            <dd class="r">0.00</dd>
        </dl>
        <dl>
            <dt>优惠券</dt>
            <dd class="r">10.00</dd>
        </dl>
        <dl>
            <dt>余额</dt>
            <dd class="r">0.00</dd>
        </dl>
        <dl class="tb">
            <dt>实际付款</dt>
            <dd class="r">450.00</dd>
        </dl>
    </div>
    <div class="btnBox">
        <button class="btn submit">去支付</button>
        <button class="btn cancel">取消订单</button>
    </div>
</script>

<script type="text/javascript">var REQUIRE = {MODULE: 'page/order'};</script>
<?php include "./resourceMap.php"; ?>
</body>
</html>