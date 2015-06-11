<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<div class="search-top">
    <div class="left">
        <form action="/special" id="form_search">
            <span id="search_btn"><img alt="" src="<?php echo $staticUrl;?>images/search2.png"/></span>
            <input id="keywords" name="keyword" type="search" value="" />
        </form>
    </div>
    <div class="right"><a href="javascript:history.go(-1);">取消</a></div>
</div>

<div id="keywords_recom" class="search-key">
    <a data-keyword="泸沽湖" href="/special?keyword=<?php echo urlencode('泸沽湖');?>">泸沽湖</a>
    <a data-keyword="玉龙雪山" href="/special?keyword=<?php echo urlencode('玉龙雪山');?>">玉龙雪山</a>
    <a data-keyword="丽江" href="/special?keyword=<?php echo urlencode('丽江');?>">丽江</a>
    <a data-keyword="客栈" href="/special?keyword=<?php echo urlencode('客栈');?>">客栈</a>
    <a data-keyword="酒吧" href="/special?keyword=<?php echo urlencode('酒吧');?>">酒吧</a>
    <a data-keyword="鲜花饼" href="/special?keyword=<?php echo urlencode('鲜花饼');?>">鲜花饼</a>
</div>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/special/search'};</script>