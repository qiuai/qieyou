<div class="group" >
    <ul id="nav_tabs">
        <li class="active"><a href="#group"><span>部落消息</span></a></li>
        <li><a href="#system"><span>系统消息</span></a></li>
    </ul>
</div>

<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <div id="content_forum"></div>
            <div class="loading"></div>
        </div>
        <div class="swiper-slide">
            <div id="content_system"></div>
            <div class="loading"></div>
        </div>
    </div>
</div>

<script id="template_forum" type="text/template">
    <%each list v%>
        <%v._create_time=new Date(parseInt(v.create_time)*1000)%>
        <%switch v.type%>
            <%case 'forum'%>
                <div class="scon-list">
                    <div class="list-tit bordernone">
                        <div class="left"> <a href="/user/<%=v.note.user_id%>" class="red"> <%=v.note.user_name%> </a> </div>
                        <div class="right fgray"> <img alt="" src="<?php echo $staticUrl;?>images/time.png"/><%=v._create_time.format('m')%>月<%=v._create_time.format('d')%>日 <%=v._create_time.format('hh:ii')%> </div>
                        <span class="clear"></span> </div>
                    <div class="list-con3"> <%=v.note.content%> </div>
                    <span class="clear"></span>
                    <div class="mess-y">
                        <div class="jian2"></div>
                        <div class="mtext"><font class="fgray">
                            我发表的<%switch v.note.type%><%case 'wenda'%>问答<%/case%><%case 'jianren'%>捡人<%/case%><%case 'tour'%>游记<%/case%><%/switch%>：
                            </font><%=v.note.post_detail||v.note.forum_name%>
                        </div>
                    </div>
                    <div class="mess-del"><a node-action="del" data-mid="<%=v.id%>" href="#"><img alt="" src="<?php echo $staticUrl;?>images/delete.png"/></a></div>
                </div>
            <%/case%>
            <%case 'group'%>
            <div class="scon-list">
                <div class="list-tit bordernone">
                    <div class="left"> <a href="/user/<%=v.note.user_id%>" class="red"> <%=v.note.user_name%> </a><span class="fgray">申请加入[<%=v.note.group_name%>]</span></div>
                    <div class="right fgray"> <img alt="" src="<?php echo $staticUrl;?>images/time.png"/><%=v._create_time.format('m')%>月<%=v._create_time.format('d')%>日 <%=v._create_time.format('hh:ii')%> </div>
                    <span class="clear"></span> </div>
                    <div class="mess-b">
                        <em>
                            <%if v.note.waiting==0%>
                            <%=v.note.set_user_name%> 设为通过
                            <%elseif v.note.waiting==1%>
                            <a node-action="ctrl" data-mid="<%=v.id%>" href="#"><img alt="" src="<?php echo $staticUrl;?>images/info3.png"/></a>
                            <div class="mess-info" style="display:none;"><a node-action="agree" data-mid="<%=v.note.member_id%>" data-gid="<%=v.note.group_id%>" href="#">同意</a><a node-action="ignore" data-mid="<%=v.member_id%>" data-gid="<%=v.note.group_id%>" href="#" class="bordernone">忽略</a>
                            </div>
                            <%elseif v.note.waiting==2%>
                            <%=v.note.set_user_name%> 设为忽略
                            <%/if%>
                        </em>
                        <a node-action="del" data-mid="<%=v.id%>" href="#"><img alt="" style="width:1rem" src="<?php echo $staticUrl;?>images/delete.png"/></a>
                </div>
            </div>
            <%/case%>
        <%/switch%>
    <%eachElse%>
    <div class="rs-empty">暂无数据</div>
    <%/each%>
</script>
<script id="template_system" type="text/template">
    <%each list v%>
    <div class="mess-list">
        <dl>
            <%v._create_time=new Date(parseInt(v.create_time)*1000)%>
            <dt>[<%=v.msgtype%>] <span class="right"><img alt="" src="<?php echo $staticUrl;?>images/time.png"/><%=v._create_time.format('m')%>月<%=v._create_time.format('d')%>日 <%=v._create_time.format('hh:ii')%></span></dt>
            <dd>[<%=v.note%>]</dd>
        </dl>
        <div class="mess-del"><a node-action="del" data-mid="<%=v.id%>" href="#"><img alt="" src="<?php echo $staticUrl;?>images/delete.png"/></a></div>
    </div>
    <%eachElse%>
    <div class="rs-empty">暂无数据</div>
    <%/each%>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/user/message'};</script>