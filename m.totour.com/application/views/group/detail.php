<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>

<div class="base-top">
    <div class="left2"><a href="<?php echo isset($backUrl)?$backUrl:'javascript:history.back(-1);'?>" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"/></a><a href="/group/search" class="left"><img alt="" src="<?php echo $staticUrl;?>images/serach.png"/></a></div>
    <div class="simpleTag2"><?php echo $moduleTag;?></div>
    <div class="right2"><a id="item_edit_btn" href="<?php getUrl('userMsg');?>" ><img alt="" src="<?php echo $staticUrl;?>images/mail.png"/><?php if($session['unreadmsg']){ ?><span></span><?php }?></a><a id="item_edit_btn" href="<?php getUrl('my');?>" ><img alt="" src="<?php echo $staticUrl;?>images/user2.png"/></a></div>
</div>
<div class="d-banner">
    <div class="top">
        <div class="left"><a href="<?php echo $baseUrl.'group/groupdata?group='.$group['group_id'];?>"><span><img alt="" src="<?php echo $attachUrl.$group['group_img'];?>"/></span></a></div>
        <div class="right">
            <div class="right-con">
                <a href="<?php echo $baseUrl.'group/groupdata?group='.$group['group_id'];?>">
                <dl>
                    <dt><?php echo $group['group_name'];?></dt>
                    <dd><img alt="" src="<?php echo $staticUrl;?>images/admin.png"/>管理员：<?php echo $group['admin']?></dd>
                    <dd><?php echo $group['note'];?></dd>
                </dl>
                </a>
                <?php if(!$group['join_time']):?>
                <?php if($group['join_method'] != 'noable'):?>
                <span><a data-attention="join" data-gid="<?php echo $group['group_id'];?>" href="#">加入部落</a></span>
                <?php endif;?>
                <?php else:?>
                <?php if($group['waiting']):?>
                <span><a data-attention="quit" data-gid="<?php echo $group['group_id'];?>" href="#">审核中</a></span>
                <?php else:?>
                <span><a data-attention="quit" data-gid="<?php echo $group['group_id'];?>" href="#">已加入</a></span>
                <?php endif;?>
                <?php endif;?>
            </div>
        </div>
    </div>
    <div class="d-info"> 贴子<span><?php echo $group['group_topics'];?></span>关注<span><?php echo $group['members'];?></span> </div>
    <?php if(!empty($group['is_admin'])):?>
    <div class="edit"><a href="<?php echo $baseUrl.'group/groupadmin?group='.$group['group_id'];?>"><img alt="" src="<?php echo $staticUrl;?>images/edit.png"/></a></div>
    <?php endif;?>
</div>
<div class="d-nav" >
    <ul id="nav_tabs">
        <li class="active"><a href="#hot"><span>热门</span></a></li>
        <li><a href="#near"><span>周边</span></a></li>
        <li><a href="#live"><span>最新</span></a></li>
        <span class="clear"></span>
    </ul>
</div>
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <div id="content_hot"></div>
            <div class="loading"></div>
        </div>
        <div class="swiper-slide">
            <div id="content_near"></div>
            <div class="loading"></div>
        </div>
        <div class="swiper-slide">
            <div id="content_live"></div>
            <div class="loading"></div>
        </div>
    </div>
</div>
<a id="scroll_top" class="backtop" href="javascript:;" style="display:none;"></a>

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
                <div class="right">
                    <ul>
                        <li><img alt="" src="<?php echo $staticUrl;?>images/time.png"/><%=v.create_time%></li>
                        <%if v.dist%><li class="pos"><a href="#"><img alt="" src="<?php echo $staticUrl;?>images/pos3.png"/><%=v.dist%></a></li><%/if%>
                    </ul>
                </div>
            </div>
            <span class="clear"></span>
        </div>
        <div class="list-con">
            <dl>
                <a href="/forum/<%=v.forum_id%>">
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
<script type="text/javascript">var REQUIRE = {MODULE: 'page/group/groupDetail', GROUP_ID: <?php echo $group['group_id']; ?>}, G = {pos: {<?php if(!empty($session['lat'])) echo 'lat:'.$session['lat'].',lon:'.$session['lon']; ?>}};</script>