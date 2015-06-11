<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<div class="d-top">
    <div class="left">来自<?php echo $forum['group_name']?>部落</div>
    <div class="right"><?php if(!empty($forum['group_id'])): ?><a href="<?php getUrl('groupDetail'); echo $forum['group_id'];?>">进入部落</a><?php endif; ?></div>
</div>
<div class="scon-list">
    <div class="list-tit overhidden">
        <div class="gleft">
            <a href="<?php echo $baseUrl.'user/'.$forum['create_user']?>">
                <img alt="" src="<?php if(empty($forum_detail['headimg'])): ?><?php echo $staticUrl; ?>images/head.jpg<?php else: ?><?php echo $attachUrl.$forum_detail['headimg'];?><?php endif; ?>"/>
            </a>
            <div class="sf"><img alt="" src="<?php echo $staticUrl;?>images/dangd.png"/></div>
        </div>
        <div class="gright">
			<ul class="left">
				<li class="red"><?php echo $forum_detail['nick_name'];?>
					<?php if($forum_detail['sex'] == 'F'):?>
						<img alt="" src="<?php echo $staticUrl;?>images/grilred.png"/>
					<?php else:?>
						<img alt="" src="<?php echo $staticUrl;?>images/boyred.png"/>
					<?php endif;?>
					<font><?php echo $forum_detail['age']?($forum_detail['age'].'岁'):''?></font>
					<span>
					<?php switch($forum['type'])
					{
						case 'tour':
							echo '<img alt="" src="'.$staticUrl.'images/youji3.png"/>游记贴';
							break;
						case 'jianren':
							echo '<img alt="" src="'.$staticUrl.'images/jianren5.png"/>捡人贴';
							break;
						case 'wenda':
							echo '<img alt="" src="'.$staticUrl.'images/ask4.png"/>问答贴';
							break;
					}
					?>
					</span>
				</li>
				<li class="fgray"><?php echo $forum['city'];?></li>
			</ul>
            <?php if($forum['is_admin']||$forum['is_owner']):?>
            <div class="right huati-r">
                <a id="opr" href="#"><img alt="" src="<?php echo $staticUrl; ?>images/info3.png"></a>
                <div class="huati-info" style="display:none;">
                    <?php if($forum['is_admin']): ?>
                    <?php if($forum['is_top']): ?>
                    <a node-type="float" data-act="unset_top" data-fid="<?php echo $forum['forum_id']; ?>" href="#">取消置顶</a>
                    <?php else: ?>
                    <a node-type="float" data-act="set_top" data-fid="<?php echo $forum['forum_id']; ?>" href="#">置顶</a>
                    <?php endif; ?>
                    <a node-type="hidden" data-act="delete" data-fid="<?php echo $forum['forum_id']; ?>" class="bordernone" href="#">屏蔽</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php else:?>
            <ul class="right">
                <li><img alt="" src="<?php echo $staticUrl;?>images/time.png"/><?php echo showTime($forum['create_time']);?></li>
                <?php if(!empty($session['lat'])&&(float)($forum['lat'])):;?>
                <li class="pos"><a href="#"><img alt="" src="<?php echo $staticUrl;?>images/pos3.png"/><?php echo echoDistance($session['lat'],$session['lon'],$forum['lat'],$forum['lon']);?></a></li>
                <?php endif;?>
            </ul>
        <?php endif;?>
        </div>
	</div>
    <div class="list-con2">
        <dl>
		<?php switch($forum['type'])
		{
			case 'tour':
				echo '<dt>[游记]'.$forum['forum_name'].'</dt>';
				break;
			case 'jianren':
				echo '<dd><font>[路线]</font> '.$forum_detail['line'].'</dd>
					  <dd><font>[时间]</font> '.date('m-d',$forum_detail['start_time']).'至'.date('m-d',($forum_detail['start_time']+$forum_detail['day']*86400)).'</dd>';
				break;
			case 'wenda':
				echo '<dt>[问答]'.$forum['forum_name'].'</dt>';
				break;
		}
		?>
			<?php if($forum_detail['tags']):?>
			<dd class="dkey">
				<?php $tags = explode(',',$forum_detail['tags']);?>
				<?php foreach($tags as $key => $row):?>
				<a href="#"><?php echo $row;?></a>
				<?php endforeach;?>
			</dd>
			<?php endif;?>
            <dd><?php echo $forum_detail['note'];?></dd>
        </dl>
		<?php if($forum_detail['pictures']):?>
        <div class="list-img">
            <ul id="picture_list">
			<?php $pictures = explode(',',$forum_detail['pictures']);?>
			<?php foreach($pictures as $key => $row):?>
				<?php if(!$row){continue;} ?>
                <li data-pictureView="<?php echo $attachUrl.$row;?>"><a href="javascript:;"><img data-src="<?php echo $attachUrl.$row;?>" src="data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMqaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAxNCA3OS4xNTE0ODEsIDIwMTMvMDMvMTMtMTI6MDk6MTUgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NzQ0Njg0QjhGNTUyMTFFNDg1Njk4QjNCODkzRjM1MTIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NzQ0Njg0QjlGNTUyMTFFNDg1Njk4QjNCODkzRjM1MTIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3NDQ2ODRCNkY1NTIxMUU0ODU2OThCM0I4OTNGMzUxMiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3NDQ2ODRCN0Y1NTIxMUU0ODU2OThCM0I4OTNGMzUxMiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv/uAA5BZG9iZQBkwAAAAAH/2wCEAAYEBAQFBAYFBQYJBgUGCQsIBgYICwwKCgsKCgwQDAwMDAwMEAwODxAPDgwTExQUExMcGxsbHB8fHx8fHx8fHx8BBwcHDQwNGBAQGBoVERUaHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fH//AABEIAAEAAQMBEQACEQEDEQH/xABKAAEAAAAAAAAAAAAAAAAAAAAIAQEAAAAAAAAAAAAAAAAAAAAAEAEAAAAAAAAAAAAAAAAAAAAAEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwBGA//Z"/></a></li>
			<?php endforeach;?>
            </ul>
        </div>
		<?php endif;?>
    </div>
</div>
<div class="huif">共<?php echo $forum['comments'];?>条回复</div>
<div class="ping">
    <div id="comment_list"></div>
    <div class="loading"></div>
</div>

<div class="d-foot">
    <div id="wenda_action" class="dnav">
		<?php if(empty($forum['is_like'])):?>
		<a data-action="praise" data-act="like" data-id="<?php echo $forum['forum_id'];?>" data-num="<?php echo $forum['likes'];?>" href="#like"><span><img alt="" src="<?php echo $staticUrl;?>images/praise-light.png"/></span><?php echo $forum['likes'];?></a>
		<?php else:?>
		<a data-action="praise" data-act="like" data-id="<?php echo $forum['forum_id'];?>" data-num="<?php echo $forum['likes'];?>" href="#like"><span><img alt="" src="<?php echo $staticUrl;?>images/praise-rlight.png"/></span><?php echo $forum['likes'];?></a>
		<?php endif;?>
		<a data-action="share" data-act="share" data-id="<?php echo $forum['forum_id'];?>" data-num="<?php echo $forum['shares'];?>" href="#share"><span><img alt="" src="<?php echo $staticUrl;?>images/zhuan-red.png"/></span><?php echo $forum['shares'];?></a>
		<a data-action="comment" data-act="comment" data-id="<?php echo $forum['forum_id'];?>" href="#comment"><span><img alt="" src="<?php echo $staticUrl;?>images/info-red.png"/></span><?php echo $forum['comments'];?></a>
		<?php if($forum['type']=='tour'): ?>
		<a data-action="give" data-act="give" data-id="<?php echo $forum['forum_id'];?>" href="#given"><span><img alt="" src="<?php echo $staticUrl;?>images/jifen-red.png"/></span>打赏</a>
		<?php endif; ?>
		<?php if(empty($forum['is_fav'])):?>
		<a data-action="collect" data-act="fav" data-id="<?php echo $forum['forum_id'];?>" href="#favorite"><span><img alt="" src="<?php echo $staticUrl;?>images/collect-red.png"/></span>收藏</a>
		<?php else:?>
		<a data-action="collect" data-act="unfav" data-id="<?php echo $forum['forum_id'];?>" href="#favorite"><span><img alt="" src="<?php echo $staticUrl;?>images/collect-rlight.png"/></span>收藏</a>
		<?php endif;?>
	</div>
</div>

<div id="dialogMainReply" class="replybox">
    <form method="post" action="">
        <div id="reply_hd">
            <div class="replytop">
                <ul>
                    <li id="dialogMainReplyCancle" class="close repleft">取消</li>
                    <li class="repmiddle">回复评论<span></span></li>
                    <li id="dialogMainReplySend" class="repright">发送</li>
                </ul>
            </div>
            <div class="reptext">
                <textarea id="dialogMainReplyNote" cols="" rows=""></textarea>
            </div>
        </div>
        <div class="picture">
            <ul id="upload_pic">
                <li id="upload_add"><a class="add" href="#"><span class="add-line"><span class="line1"></span><span class="line2"></span></span><div class="progress" style="display:none;"><div class="progress-bar"></div><span class="progress-percentage">上传中...</span></div></a><input id="upload_img" class="upload-img" type="file"></li>
                <span class="clear"></span>
            </ul>
        </div>
    </form>
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
    <div class="ping-list">
        <div class="gleft"><a href="#"><img alt="" src="<%if !!v.headimg%><?php echo $attachUrl;?><%=v.headimg%><%else%><?php echo $staticUrl;?>images/head.jpg<%/if%>"/></a></div>
        <div class="gright">
            <ul style="margin-top:-0.5rem">
                <li class="left red"><%=v.nick_name%><img alt="" src="<?php echo $staticUrl;?>images/<%if v.sex.toUpperCase()=='F'%>grilred<%else%>boyred<%/if%>.png"/><%if !!v.age%><font><%=v.age%>岁</font><%/if%> <%if !!v.city||v.city!=undefined%><font><%=v.city%></font><%/if%></li>
                <?php if($forum['is_admin']): ?>
                <li class="right"><a node-action="hide" data-pid="<%=v.post_id%>" href="#" class="red">屏蔽</a></li>
                <?php else: ?>
                <li class="right"><img alt="" src="<?php echo $staticUrl;?>images/time.png"/><%=v.create_time%></li>
                <?php endif; ?>
                <a class="text" href="/forum/postdetail/<%=v.post_id%>"><%=v.post_detail%></a>
            </ul>
            <%if v.pictures%>
            <div class="list-img">
                <ul>
                    <%each v.pictures p%>
                    <li><a href="javascript:;"><img data-pictureview="<%=p.id%>:<%=p.index%>" data-src="<%=p.src%>" src="data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMqaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAxNCA3OS4xNTE0ODEsIDIwMTMvMDMvMTMtMTI6MDk6MTUgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NzQ0Njg0QjhGNTUyMTFFNDg1Njk4QjNCODkzRjM1MTIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NzQ0Njg0QjlGNTUyMTFFNDg1Njk4QjNCODkzRjM1MTIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3NDQ2ODRCNkY1NTIxMUU0ODU2OThCM0I4OTNGMzUxMiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3NDQ2ODRCN0Y1NTIxMUU0ODU2OThCM0I4OTNGMzUxMiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv/uAA5BZG9iZQBkwAAAAAH/2wCEAAYEBAQFBAYFBQYJBgUGCQsIBgYICwwKCgsKCgwQDAwMDAwMEAwODxAPDgwTExQUExMcGxsbHB8fHx8fHx8fHx8BBwcHDQwNGBAQGBoVERUaHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fH//AABEIAAEAAQMBEQACEQEDEQH/xABKAAEAAAAAAAAAAAAAAAAAAAAIAQEAAAAAAAAAAAAAAAAAAAAAEAEAAAAAAAAAAAAAAAAAAAAAEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwBGA//Z"/></a></li>
                    <%/each%>
                </ul>
            </div>
            <%/if%>
			<span class="blank8 "></span>
            <div class="info">
            	<a node-action="praise" data-cid="<%=v.post_id%>" data-num="<%=v.post_likes%>" href="#"><img alt="" src="<?php echo $staticUrl;?>images/praise.png"/><em><%=v.post_likes%></em></a>
            	<?php if($forum['type']=='wenda'): ?>
            	<a node-action="given" data-cid="<%=v.post_id%>" data-num="<%=v.post_points%>" href="#"><img alt="" src="<?php echo $staticUrl;?>images/jifen.png"/><em><%=v.post_points%></em></a>
            	<?php endif; ?>
            	<a node-action="comment" data-uname="<%=v.nick_name%>" data-cid="<%=v.post_id%>" data-num="<%=v.post_comments%>" href="#"><img alt="" src="<?php echo $staticUrl;?>images/info.png"/><em><%=v.post_comments%></em></a>
            </div>
        </div>
        <span class="clear"></span>
        <%v.post_comments=parseInt(v.post_comments)||0%>
        <div node-wrap="subcom" class="ping-con"<%if v.post_comments<=0%> style="display:none;"<%/if%>>
            <div class="jian"></div>
            <ul id="comment_<%=v.post_id%>">
                <%if v.reply%>
            	<%each v.reply r%>
                <%include template_subcomment%>
                <%/each%>
                <%/if%>
                <%if v.post_comments>2%>
                <li><a class="more" href="/forum/postdetail/<%=v.post_id%><?php if($admin): ?>?admin=yes<?php endif; ?>">更多<%=v.post_comments-2%>条评论...</a></li>
                <%/if%>
            </ul>
        </div>
		<span class="clear"></span>
    </div>
    <%/each%>
</script>
<script id="template_subcomment" type="text/template">
    <li><a href="/user/<%=r.create_user%>" class="red"><%=r.create_name%>:</a><a href="/forum/postdetail/<%=v.post_id%><?php if($admin): ?>?admin=yes<?php endif; ?>"><%=r.post_detail%></a></li>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/forum/detail', FORUM_ID: <?php echo $forum['forum_id'];?>}, G = {pos: {<?php if(!empty($session['lat'])) echo 'lat:'.$session['lat'].',lon:'.$session['lon']; ?>}}, SHARE = {title: "<?php echo $forum['forum_name']; ?>", pic: "<?php echo $attachUrl.$pictures[0]; ?>", url: "<?php echo $baseUrl; ?>/fourm/<?php echo $forum['forum_id']; ?>"};</script>
