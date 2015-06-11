<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<div class="group" >
    <ul id="nav_tabs">
        <li class="active"><span>部落成员（<em id="verified_num" data-num="<?php echo $group['members'];?>"><?php echo $group['members'];?></em>）</span></li>
        <li><span>待确认成员（<em id="waiting_num" data-num="<?php echo $group['waiting_verify'];?>"><?php echo $group['waiting_verify'];?></em>）</span></li>
    </ul>
</div>
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide membercon">
            <div id="content_verified"></div>
            <div class="loading"></div>
        </div>
        <div class="swiper-slide membercon">
            <div id="content_waiting"></div>
            <div class="loading"></div>
        </div>
    </div>
</div>
<script id="template_item" type="text/template">
    <%each list v%>
    <ul>
        <li class="gleft"><span><img alt="<%=v.nick_name%>" src="<%if !!v.headimg%><?php echo $attachUrl;?><%=v.headimg%><%else%><?php echo $staticUrl;?>images/head.jpg<%/if%>"></span></li>
        <li class="gright">
            <div class="tit"><%=v.nick_name%><img alt="" src="<?php echo $staticUrl;?>images/<%if v.sex.toUpperCase()=='F'%>grilred<%else%>boyred<%/if%>.png"></div>
            <div class="con"><font><%=(new Date(parseInt(v.last_visited)*1000)).format('yyyy-mm-dd')%>加入</font><%=v.topics%>贴</div>
            <%if v.is_admin==1&&v.user_id!=<?php echo $group['create_by']; ?>%>
                <i class="mar">创建者</i>
            <%else%>
                <%if v.is_admin==1%>
                <i class="mar">管理员</i>
                <%/if%>
                <%if tab=='verified'%>
                <!--<a data-action="delmember" data-mid="<%=v.member_id%>" href="#" class="red">删除</a>-->
                <div data-action="opr" class="info"><img src="<?php echo $staticUrl;?>images/info3.png" /></div>
                <div class="huati-info info-po" style="display:none;"><a data-action="admin" data-act="<%if v.is_admin==1%>unsetadmin<%else%>setadmin<%/if%>" data-mid="<%=v.member_id%>" href="#"><%if v.is_admin==1%>取消管理员<%else%>设为管理员<%/if%></a><a data-action="delmember" data-act="delete" data-mid="<%=v.member_id%>" class="bordernone" href="#">删除成员</a></div>
                <%else%>
                <i><a data-action="allow" data-mid="<%=v.member_id%>" href="#" class="red">接受</a><a data-action="ignore" data-mid="<%=v.member_id%>" href="#" class="red">忽略</a></i>
                <%/if%>
            <%/if%>
        </li>
        <span class="clear"></span>
    </ul>
    <%eachElse%>
    <div class="rs-empty">暂无数据</div>
    <%/each%>
</script> 

<script type="text/javascript">var REQUIRE = {MODULE: 'page/group/adminMember', GROUP_ID: <?php echo $group['group_id']; ?>};</script>
