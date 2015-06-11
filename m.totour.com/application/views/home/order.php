<div class="o-top" >
    <ul id="nav_tabs">
        <li class="active"><a href="#O">全部订单</a></li>
        <li><a href="#A">待支付</a></li>
        <li><a href="#U">已支付</a></li>
        <li><a href="#R">待退款</a></li>
        <span class="clear"></span>
    </ul>
</div>

<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <div id="content_O"></div>
            <div class="loading"></div>
        </div>
        <div class="swiper-slide">
            <div id="content_A"></div>
            <div class="loading"></div>
        </div>
        <div class="swiper-slide">
            <div id="content_U"></div>
            <div class="loading"></div>
        </div>
        <div class="swiper-slide">
            <div id="content_R"></div>
            <div class="loading"></div>
        </div>
    </div>
</div>

<!-- A S U R C -->
<script id="template_content_O" type="text/template">
    <%each list v%>
    <%switch v.state%>
        <%case 'A'%><%include template_content_AI%><%/case%>
        <%case 'S'%><%include template_content_SI%><%/case%>
        <%case 'P'%><%include template_content_UI%><%/case%>
        <%case 'U'%><%include template_content_UI%><%/case%>
        <%case 'R'%><%include template_content_RI%><%/case%>
        <%case 'N'%><%include template_content_NI%><%/case%>
        <%case 'C'%><%include template_content_CI%><%/case%>
    <%/switch%>
    <%eachElse%>
    <div class="rs-empty">暂无结果</div>
    <%/each%>
</script>
<script id="template_content_A" type="text/template">
    <%each list v%>
    <%include template_content_AI%>
    <%eachElse%>
    <div class="rs-empty">暂无结果</div>
    <%/each%>
</script>
<script id="template_content_U" type="text/template">
    <%each list v%>
    <%include template_content_UI%>
    <%eachElse%>
    <div class="rs-empty">暂无结果</div>
    <%/each%>
</script>
<script id="template_content_R" type="text/template">
    <%each list v%>
    <%include template_content_RI%>
    <%eachElse%>
    <div class="rs-empty">暂无结果</div>
    <%/each%>
</script>

<!-- 待支付 -->
<script id="template_content_AI" type="text/template">
    <div class="o-list">
        <div class="zhuang"><span class="left"><%=(new Date(parseInt(v.create_time)*1000)).format('yyyy-mm-dd')%></span><span class="right red">待支付</span><span class="clear"></span></div>
        <div class="product">
            <a href="/order/view/<%=v.order_num%>">
            <div class="left">
                <div class="pic"><img alt="" src="<?php echo $attachUrl;?><%=v.product_thumb%>"/></div>
                <div class="text"><%=v.product_name%></div>
            </div>
            <div class="right">
                <p class="font1"><%=v.price%>元</p>
                <p>x<%=v.quantity%></p>
            </div>
            </a>
            <span class="clear"></span> </div>
        <div class="price">共计：<font class="red"><%=v.total.split('.')[0]%><i>.<%=v.total.split('.')[1]%></i></font>元<!--（含10元快递费）--></div>
        <div class="btn"><a node-type="cancel" data-oid="<%=v.order_num%>" href="#" class="gbtn">取消订单</a><a href="/order/pay?order=<%=v.order_num%>" class="rbtn">去支付</a></div>
    </div>
</script>

<!-- 已支付 -->
<script id="template_content_UI" type="text/template">
    <div class="o-list">
        <div class="zhuang"><span class="left"><%=(new Date(parseInt(v.create_time)*1000)).format('yyyy-mm-dd')%></span><span class="right red">已支付</span><span class="clear"></span></div>
        <div class="product">
            <a href="/order/view/<%=v.order_num%>">
            <div class="left">
                <div class="pic"><img alt="" src="<?php echo $attachUrl;?><%=v.product_thumb%>"></div>
                <div class="text"><%=v.product_name%></div>
            </div>
            <div class="right">
                <p class="font1"><%=v.price%>元</p>
                <p>x<%=v.quantity%></p>
            </div>
            </a>
            <span class="clear"></span> </div>
        <div class="price">共计：<font class="red"><%=v.total.split('.')[0]%><i>.<%=v.total.split('.')[1]%></i></font>元</div>
        <div class="btn"><a node-type="cancel" data-oid="<%=v.order_num%>" href="#" class="gbtn">取消订单</a><a href="/order/view/<%=v.order_num%>" class="grbtn2">查看电子凭证</a></div>
    </div>
</script>

<!-- 待退款 -->
<script id="template_content_RI" type="text/template">
    <div class="o-list">
        <div class="zhuang"><span class="left"><%=(new Date(parseInt(v.create_time)*1000)).format('yyyy-mm-dd')%></span><span class="right red">待退款</span><span class="clear"></span></div>
        <div class="product">
            <a href="/order/view/<%=v.order_num%>">
            <div class="left">
                <div class="pic"><img alt="" src="<?php echo $attachUrl;?><%=v.product_thumb%>"></div>
                <div class="text"><%=v.product_name%></div>
            </div>
            <div class="right">
                <p class="font1"><%=v.price%>元</p>
                <p>x<%=v.quantity%></p>
            </div>
            </a>
            <span class="clear"></span> </div>
        <div class="price bordernone">共计：<font class="red"><%=v.total.split('.')[0]%><i>.<%=v.total.split('.')[1]%></i></font>元</div>
    </div>
</script>

<!-- 已完成 -->
<script id="template_content_SI" type="text/template">
    <div class="o-list">
        <div class="zhuang"><span class="left"><%=(new Date(parseInt(v.create_time)*1000)).format('yyyy-mm-dd')%></span><span class="right red">已完成</span><span class="clear"></span></div>
        <div class="product">
           <a href="/order/view/<%=v.order_num%>">
            <div class="left">
                <div class="pic"><img alt="" src="<?php echo $attachUrl;?><%=v.product_thumb%>"></div>
                <div class="text"><%=v.product_name%></div>
            </div>
            <div class="right">
                <p class="font1"><%=v.price%>元</p>
                <p>x<%=v.quantity%></p>
            </div>
            </a>
            <span class="clear"></span> </div>
        <div class="price">共计：<font class="red"><%=v.total.split('.')[0]%><i>.<%=v.total.split('.')[1]%></i></font>元</div>
        <div class="btn"><a href="/order/comment/<%=v.order_num%>" class="grbtn">去评价</a></div>
    </div>
</script>

<!-- 已取消 -->
<script id="template_content_NI" type="text/template">
    <div class="o-list">
        <div class="zhuang"><span class="left"><%=(new Date(parseInt(v.create_time)*1000)).format('yyyy-mm-dd')%></span><span class="right red">已取消</span><span class="clear"></span></div>
        <div class="product">
            <a href="/order/view/<%=v.order_num%>">
            <div class="left">
                <div class="pic"><img alt="" src="<?php echo $attachUrl;?><%=v.product_thumb%>"></div>
                <div class="text"><%=v.product_name%></div>
            </div>
            <div class="right">
                <p class="font1"><%=v.price%>元</p>
                <p>x<%=v.quantity%></p>
            </div>
            </a>
            <span class="clear"></span> </div>
        <div class="price bordernone">共计：<font class="red"><%=v.total.split('.')[0]%><i>.<%=v.total.split('.')[1]%></i></font>元</div>
    </div>
</script>

<!-- 已退款 -->
<script id="template_content_CI" type="text/template">
    <div class="o-list">
        <div class="zhuang"><span class="left"><%=(new Date(parseInt(v.create_time)*1000)).format('yyyy-mm-dd')%></span><span class="right red">已退款</span><span class="clear"></span></div>
        <div class="product">
            <a href="/order/view/<%=v.order_num%>">
            <div class="left">
                <div class="pic"><img alt="" src="<?php echo $attachUrl;?><%=v.product_thumb%>"></div>
                <div class="text"><%=v.product_name%></div>
            </div>
            <div class="right">
                <p class="font1"><%=v.price%>元</p>
                <p>x<%=v.quantity%></p>
            </div>
            </a>
            <span class="clear"></span> </div>
        <div class="price bordernone">共计：<font class="red"><%=v.total.split('.')[0]%><i>.<%=v.total.split('.')[1]%></i></font>元</div>
    </div>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/order', ACTION: '<?php echo empty($_GET["type"])?"O":$_GET["type"];?>'};</script>