<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<div class="huati-top" id="content_top">
</div>
<div class="group-container">
    <div id="content_forum"></div>
    <div class="loading"></div>
</div>


<script id="template_top" type="text/template">
    <%each list v%>
        <dl>
            <dt class="hleft"><a href="/forum/<%=v.forum_id%>?admin=yes"><i>顶</i><%=v.forum_name%></a></dt>
            <dd class="hright">
                <a node-type="opr" href="#"><img alt="" src="<?php echo $staticUrl; ?>images/info3.png"></a>
                <div class="huati-info" style="display:none;"><a node-type="float" data-act="unset_top" data-fid="<%=v.forum_id%>" href="#">取消置顶</a><a node-type="hidden" data-act="delete" data-fid="<%=v.forum_id%>" class="bordernone" href="#">屏蔽</a></div>
            </dd>
			<span class="clear"></span>
        </dl>
    <%/each%>
</script>
<script id="template_forum" type="text/template">
    <%each list v%>
    <div class="scon-list">
        <div class="list-tit">
            <div class="gleft"> <a href="/user/<%=v.create_user%>"><img alt="" src="<%if !!v.headimg%><?php echo $attachUrl;?><%=v.headimg%><%else%><?php echo $staticUrl;?>images/head.jpg<%/if%>"/></a>  <%if !!v.local%><div class="sf"><img alt="" src="<?php echo $staticUrl;?>images/dangd.png"/><!--<img alt="" src="<?php echo $staticUrl;?>images/admin.png"/>--></div><%/if%></div>
            <div class="gright">
                <div class="left">
                    <ul style="margin-top:-0.6rem">
                        <a href="/user/<%=v.create_user%>">
                        <li class="red"><%=v.nick_name%><img alt="" src="<?php echo $staticUrl;?>images/<%if v.sex.toUpperCase()=='F'%>grilred<%else%>boyred<%/if%>.png"/><%if !!v.age%><font><%=v.age%>岁</font><%/if%>
                        <span>
                        <%switch v.type%>
                            <%case 'tour'%><img alt="" src="<?php echo $staticUrl;?>images/youji3.png"/>游记贴<%/case%>
                            <%case 'wenda'%><img alt="" src="<?php echo $staticUrl;?>images/ask4.png"/>问答贴<%/case%>
                            <%case 'jianren'%><img alt="" src="<?php echo $staticUrl;?>images/jianren5.png"/>捡人贴<%/case%>
                        <%/switch%>
                        </span>
                        </li>
                        <li class="fgray"><%if v.group_name%>来自<%=v.group_name%><%/if%></li>
                        </a>
                    </ul>
                </div>
                <div class="right huati-r">
                    <a node-type="opr" href="#"><img alt="" src="<?php echo $staticUrl; ?>images/info3.png"></a>
                    <div class="huati-info" style="display:none;"><a node-type="float" data-act="set_top" data-fid="<%=v.forum_id%>" href="#">置顶</a><a node-type="hidden" data-act="delete" data-fid="<%=v.forum_id%>" class="bordernone" href="#">屏蔽</a></div>
                </div>
            </div>
            <span class="clear"></span>
        </div>
        <div class="list-con">
            <dl>
                <a href="/forum/<%=v.forum_id%>?admin=yes">
                <%switch v.type%>
                    <%case 'tour'%>
                    <dt>[游记]<%=v.forum_name%></dt>
                    <dd><%=v.note%></dd>
                    <%/case%>
                    <%case 'wenda'%>
                    <dt><%=v.forum_name%></dt>
                    <dd><%=v.note%></dd>
                    <%/case%>
                    <%case 'jianren'%>
                    <dd><%=v.forum_name%></dd>
                    <dd><font>[路线] <%=v.line%></font></dd>
                    <dd><font>[时间] <%=(new Date(parseInt(v.start_time)*1000)).format('mm-dd')%>至<%=(new Date((parseInt(v.start_time)+parseInt(v.day)*86400)*1000)).format('mm-dd')%></font></dd>
                    <dd class="note"><%=v.note%></dd>
                    <%/case%>
                <%/switch%>
                </a>
            </dl>
            <%if v.pictures%>
            <div class="list-img">
                <ul>
                    <%each v.pictures p%>
                    <li><a href="javascript:;"><img data-pictureview="<%=p.id%>:<%=p.index%>" data-src="<%=p.src%>" src="data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMqaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAxNCA3OS4xNTE0ODEsIDIwMTMvMDMvMTMtMTI6MDk6MTUgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NzQ0Njg0QjhGNTUyMTFFNDg1Njk4QjNCODkzRjM1MTIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NzQ0Njg0QjlGNTUyMTFFNDg1Njk4QjNCODkzRjM1MTIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3NDQ2ODRCNkY1NTIxMUU0ODU2OThCM0I4OTNGMzUxMiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3NDQ2ODRCN0Y1NTIxMUU0ODU2OThCM0I4OTNGMzUxMiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv/uAA5BZG9iZQBkwAAAAAH/2wCEAAYEBAQFBAYFBQYJBgUGCQsIBgYICwwKCgsKCgwQDAwMDAwMEAwODxAPDgwTExQUExMcGxsbHB8fHx8fHx8fHx8BBwcHDQwNGBAQGBoVERUaHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fH//AABEIAAEAAQMBEQACEQEDEQH/xABKAAEAAAAAAAAAAAAAAAAAAAAIAQEAAAAAAAAAAAAAAAAAAAAAEAEAAAAAAAAAAAAAAAAAAAAAEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwBGA//Z"/></a></li>
                    <%/each%>
                </ul>
            </div>
            <%/if%>
        </div>
        <span class="clear"></span>
        <div class="info">
            <ul>
                <li><a data-fid="<%=v.forum_id%>" data-num="<%=v.likes%>" data-action="like" href="#"><img src="<?php echo $staticUrl;?>images/praise.png" /><%=v.likes%></a></li>
                <li><a href="/forum/<%=v.forum_id%>#comment"><img src="<?php echo $staticUrl;?>images/info.png" /><%=v.comments%></a></li>
                <li><a data-fid="<%=v.forum_id%>" data-num="<%=v.shares%>" data-action="share" data-share="<%=v.share%>" href="#"><img src="<?php echo $staticUrl;?>images/zhuan.png" /><%=v.shares%></a></li>
                <li><a data-fid="<%=v.forum_id%>" data-num="<%=v.favorites%>" data-action="favorite" href="#"><img src="<?php echo $staticUrl;?>images/collect3.png" /><%=v.favorites%></a></li>
            </ul>
            <span class="clear"></span>
        </div>
    </div>
    <%eachElse%>
    <div class="rs-empty">暂无数据</div>
    <%/each%>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/group/adminTopic', GROUP_ID: <?php echo $group['group_id']; ?>}, G = {pos: {<?php if(!empty($session['lat'])) echo 'lat:'.$session['lat'].',lon:'.$session['lon']; ?>}};</script>