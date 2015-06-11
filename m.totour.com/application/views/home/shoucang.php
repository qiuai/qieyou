<div class="quan" >
  <ul id="nav_tabs">
    <li style="border:0" class="active">收藏的商品</li>
    <li>收藏的店铺</li>
  </ul>
</div>


<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide shou">
            <div id="content_item" style="overflow:hidden;"></div>
            <div class="loading"></div>
        </div>
        <div class="swiper-slide shou2">
            <div id="content_inn" style="overflow:hidden;"></div>
            <div class="loading"></div>
        </div>
    </div>
</div>


<script id="template_item" type="text/template">
    <%each list v%>
    <dl data-id="<%=v.product_id%>" data-name="<%=v.product_name%>">
        <a href="/item/<%=v.product_id%>">
            <dt><img alt="" src="<?php echo $attachUrl;?><%=v.thumb%>"/><span><img alt="" src="<?php echo $staticUrl;?>images/close3.png"/></span><span node-type="edit"><img alt="编辑" src="<?php echo $staticUrl;?>images/close3.png"></span></dt>
            <dd class="tit"><%=v.product_name%></dd>
            <dd class="price"><span class="left"><font><%=v.price%></font></span><span class="right"><%=v.old_price%></span></dd>
        </a>
    </dl>
    <%eachElse%>
    <div class="rs-empty">暂无数据</div>
    <%/each%>
</script>
<script id="template_inn" type="text/template">
    <%each list v%>
    <ul data-id="<%=v.inn_id%>" data-name="<%=v.inn_name%>">
        <span node-type="edit" class="delete-btn"><img alt="编辑" src="<?php echo $staticUrl;?>images/close3.png"></span>
        <li class="left">
            <a href="/special/inn?sid=<%=v.inn_id%>">
            <span class="pic"><img alt="" src="<?php echo $attachUrl;?><%=v.inn_head%>"/></span>
            <span class="text">
                <dl>
                    <dt><%=v.inn_name%></dt>
                    <dd>地址：<%=v.inn_address%></dd>
                    <%if v.dist%><dd><img alt="" src="<?php echo $staticUrl;?>images/pos.jpg"/><%=v.dist%></dd><%/if%>
                </dl>
            </span>
            </a>
        </li>
        <li class="right"><a href="tel:<%=v.inner_moblie_number%>"><img alt="" src="<?php echo $staticUrl;?>images/telephone.png"/></a></li>
    </ul>
    <%eachElse%>
    <div class="rs-empty">暂无数据</div>
    <%/each%>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/shoucang'}, G = {pos: {<?php if(!empty($session['lat'])) echo 'lat:'.$session['lat'].',lon:'.$session['lon']; ?>}}</script>