<div class="ping-list bordernone">
    <div class="gleft"><a href="/user/<?php echo $post['create_user']; ?>"><img alt="" src="<?php echo $attachUrl.$post['headimg'];?>"/></a></div>
    <div class="gright">
        <ul style="margin-top:-0.5rem">
            <li class="left red"><?php echo $post['nick_name']; ?><img alt="" src="<?php echo $staticUrl;?>images/boyred.png"/><font><?php echo $post['age']?($post['age'].'岁'):''; ?></font> <font><?php echo $post['city']; ?></font></li>
                <li class="right"><img alt="" src="<?php echo $staticUrl;?>images/time.png"/><?php echo showTime($post['create_time']); ?></li>
            <li class="text"><?php echo $post['post_detail']; ?></li>
        </ul>
        <?php if(!empty($post['pictures'])): ?>
        <?php $imgs = explode(',', $post['pictures']); ?>
        <div class="list-img">
            <ul id="picture_list">
                <?php foreach($imgs as $img): ?>
                <?php if(!$img){continue;} ?>
                <li data-pictureView="<?php echo $attachUrl.$img; ?>"><a href="javascript:;"><img data-src="<?php echo $attachUrl.$img; ?>" src="data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMqaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAxNCA3OS4xNTE0ODEsIDIwMTMvMDMvMTMtMTI6MDk6MTUgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NzQ0Njg0QjhGNTUyMTFFNDg1Njk4QjNCODkzRjM1MTIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NzQ0Njg0QjlGNTUyMTFFNDg1Njk4QjNCODkzRjM1MTIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3NDQ2ODRCNkY1NTIxMUU0ODU2OThCM0I4OTNGMzUxMiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3NDQ2ODRCN0Y1NTIxMUU0ODU2OThCM0I4OTNGMzUxMiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv/uAA5BZG9iZQBkwAAAAAH/2wCEAAYEBAQFBAYFBQYJBgUGCQsIBgYICwwKCgsKCgwQDAwMDAwMEAwODxAPDgwTExQUExMcGxsbHB8fHx8fHx8fHx8BBwcHDQwNGBAQGBoVERUaHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fH//AABEIAAEAAQMBEQACEQEDEQH/xABKAAEAAAAAAAAAAAAAAAAAAAAIAQEAAAAAAAAAAAAAAAAAAAAAEAEAAAAAAAAAAAAAAAAAAAAAEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwBGA//Z"></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <div id="action" class="info" style="margin-top:0.5rem"><a node-action="praise" data-cid="<?php echo $post['post_id']; ?>" href="#"><img alt="" src="<?php echo $staticUrl;?>images/praise.png"/><?php echo $post['post_likes']; ?></a><a node-action="comment" data-uname="<?php echo $post['nick_name']; ?>" data-cid="<?php echo $post['post_id']; ?>" href="#"><img alt="" src="<?php echo $staticUrl;?>images/info.png"/><?php echo $post['post_comments']; ?></a></div>

    </div>
    <span class="clear"></span> 
    
    </div>
    <span class="blank8"></span>



<div class="comment-list">
    <div id="comment_list"></div>
    <div class="loading"></div>
</div>


<div id="dialogReply" class="replybox">
    <form method="post" action="">
        <div class="replytop">
            <ul>
                <li id="dialogReplyCancle" class="close repleft">取消</li>
                <li class="repmiddle">回复评论<span id="comment_user_name"></span></li>
                <li id="dialogReplySend" class="repright">发送</li>
            </ul>
        </div>
        <div class="reptext">
            <textarea id="dialogReplyNote" cols="" rows=""></textarea>
        </div>
    </form>
</div>

<!-- <div id="dialogReply" class="com-foot" style="display:none;"><input id="dialogReplyNote" name="" type="text" class="cinp"><a id="dialogReplySend" href="#">发送</a></div> -->

<script id="template_comment" type="text/template">
    <%each list v%>
    <div class="comment2">
        <ul>
            <li class="comleft"><span class="outer"><span class="cover"></span><img alt="" src="<%if !!v.headimg%><?php echo $attachUrl;?><%=v.headimg%><%else%><?php echo $staticUrl;?>images/head.jpg<%/if%>"/></span></li>
            <li class="comright">
                <ul>
                    <li class="comname">
                        <dl>
                            <dt><%=v.create_name%><font><%if v.create_name%>回复</font><%=v.reply_name%><%/if%></dt>
                            <dd>
                                <?php if($post['is_admin']): ?>
                                <a node-action="hide" data-pid="<%=v.post_id%>" class="red" href="#">屏蔽</a>
                                <?php else: ?>
                                <a node-action="reply" data-rid="<%=v.post_id%>" data-uname="<%=v.create_name%>" href="#">回复</a>
                                <?php endif; ?>
                            </dd>
                        </dl>
                    </li>
                    <li class="comtime"><%=(new Date(parseInt(v.create_time)*1000)).format('yyyy-mm-dd hh:ss:ii')%></li>
                    <li class="comtext"><%=v.post_detail%></li>
                </ul>
            </li>
        </ul>
        <span class="clear"></span>
    </div>
    <%eachElse%>
    <div class="rs-empty">暂无数据</div%>
    <%/each%>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/forum/postdetail', FORUM_ID: <?php echo $post['forum_id']; ?>, POST_ID: <?php echo $post['post_id']; ?>}, G = {pos: {<?php if(!empty($session['lat'])) echo 'lat:'.$session['lat'].',lon:'.$session['lon']; ?>}};</script>