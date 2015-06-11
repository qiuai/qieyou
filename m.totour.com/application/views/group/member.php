<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<div class="gm">
    <div id="container"></div>
    <div class="loading"></div>
</div>

<script id="template_item" type="text/template">
    <%each list v%>
    <ul>
        <li class="gmleft"><span><a href="/user/<%=v.user_id%>"><img alt="" src="<%if !!v.headimg%><?php echo $attachUrl;?><%=v.headimg%><%else%><?php echo $staticUrl;?>images/head.jpg<%/if%>"/></a></span> </li>
        <li class="gmright">
            <a href="/user/<%=v.user_id%>"><p class="tit"><%=v.nick_name%><img alt="" src="<?php echo $staticUrl;?>images/<%if v.sex.toUpperCase()=='F'%>grilred<%else%>boyred<%/if%>.png"/><%if !!v.age%><font><%=v.age%>岁</font><%/if%></p></a>
            <i></i>
        </li>
    </ul>
    <%eachElse%>
    <div class="rs-empty">暂无数据</div>
    <%/each%>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/group/member'};</script>