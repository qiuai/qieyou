<div class="ta-container">
	<?php if($class=='group'): ?>
	<div class="padleft">
		<div id="container"></div>
		<div class="loading"></div>
	</div>
	<?php else: ?>
	<div id="container"></div>
	<div class="loading"></div>
	<?php endif; ?>
</div>

<script id="template_item" type="text/template">
	<%each list v%>
	<?php if($class=='group'): ?>
		<div class="groupcon">
			<a href="<?php getUrl('groupDetail');?><%=v.group_id%>">
			<div class="gleft"><span><img alt="" src="<?php echo $attachUrl;?><%=v.group_img%>"/></span></div>
			<div class="gright">
             <div class="gright-con">
				<span class="cleft">
					<p class="tit"><%=v.group_name%></p>
					<p style="color:#939393">帖子<span><%=v.group_topics%></span>关注<span><%=v.members%></span></p>
				</span>
				<%if !v.join_time%>
				<a data-attention="join" data-gid="<%=v.group_id%>" href="#" class="right gz">加入部落</a>
				<%else%>
				<a data-attention="quit" data-gid="<%=v.group_id%>" href="#" class="right gz">已加入</a>
				<%/if%>
                </div>
			</div>
			</a>
		</div>
	<?php elseif($class=='wenda'): ?>
		<div class="scon-list">
	        <div class="list-tit">
	            <div class="gleft"> <a href="#"><img alt="" src="<%if !!v.headimg%><?php echo $attachUrl;?><%=v.headimg%><%else%><?php echo $staticUrl;?>images/head.jpg<%/if%>"/></a> </div>
	            <div class="gright">
	                <div class="left">
	                    <ul style="margin-top:-0.5rem">
	                        <a href="/forum/<%=v.forum_id%>">
	                        <li class="red"><%=v.nick_name%><img alt="" src="<?php echo $staticUrl;?>images/boyred.png"/><font>23岁</font><span><img alt="" src="<?php echo $staticUrl;?>images/ask4.png"/>问答贴</span> </li>
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
	                <dt>[问答]<%=v.forum_name%></dt>
	                <dd><font>[答]</font><%=v.note%></dd>
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
	<?php elseif($class =='jianren'): ?>
		<div class="scon-list">
	        <div class="list-tit">
	            <div class="gleft"> <a href="#"><img alt="" src="<%if !!v.headimg%><?php echo $attachUrl;?><%=v.headimg%><%else%><?php echo $staticUrl;?>images/head.jpg<%/if%>"/></a> </div>
	            <div class="gright">
	                <div class="left">
	                    <ul style="margin-top:-0.5rem">
	                        <a href="/forum/<%=v.forum_id%>">
	                        <li class="red"><%=v.nick_name%><img alt="" src="<?php echo $staticUrl;?>images/grilred.png"/><font>22岁</font><span><img alt="" src="<?php echo $staticUrl;?>images/jianren5.png"/>捡人贴</span> </li>
	                        <li class="fgray"><%if v.group_name%>来自<%=v.group_name%><%/if%></li>
	                        </a>
	                    </ul>
	                </div>
	                <div class="right">
	                    <ul>
	                        <li><img alt="" src="<?php echo $staticUrl;?>images/time.png"/><%=v.create_time%></li>
	                        <li class="pos"><a href="#"><img alt="" src="<?php echo $staticUrl;?>images/pos3.png"/>2km</a></li>
	                    </ul>
	                </div>
	            </div>
	            <span class="clear"></span>
	        </div>
	        <div class="list-con">
	            <dl>
	                <a href="/forum/<%=v.forum_id%>">
                    <dd><%=v.forum_name%></dd>
	                <dd><font>[路线] <%=v.line%></font></dd>
	                <dd><font>[时间] <%=(new Date(parseInt(v.start_time)*1000)).format('mm-dd')%>至<%=(new Date((parseInt(v.start_time)+parseInt(v.day)*86400)*1000)).format('mm-dd')%></font></dd>
	                <dd class="note"><%=v.note%></dd>
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
	<?php elseif($class =='tour'): ?>
		<div class="scon-list">
	        <div class="list-tit">
	            <div class="gleft"> <a href="#"><img alt="" src="<%if !!v.headimg%><?php echo $attachUrl;?><%=v.headimg%><%else%><?php echo $staticUrl;?>images/head.jpg<%/if%>"/></a> </div>
	            <div class="gright">
	                <div class="left">
	                    <ul style="margin-top:-0.5rem">
	                        <a href="#">
	                        <li class="red"><%=v.nick_name%><img alt="" src="<?php echo $staticUrl;?>images/boyred.png"/><font>22岁</font><span><img alt="" src="<?php echo $staticUrl;?>images/youji3.png"/>游记贴</span> </li>
	                        <li class="fgray"><%if v.group_name%>来自<%=v.group_name%><%/if%></li>
	                        </a>
	                    </ul>
	                </div>
	                <div class="right">
	                    <ul>
	                        <li><img alt="" src="<?php echo $staticUrl;?>images/time.png"/><%=v.create_time%></li>
	                        <li class="pos"><a href="#"><img alt="" src="<?php echo $staticUrl;?>images/pos3.png"/>2km</a></li>
	                    </ul>
	                </div>
	            </div>
	            <span class="clear"></span>
	        </div>
	        <div class="list-con">
	        <a href="/forum/<%=v.forum_id%>">
	            <dl>
	                <dt>[游记]<%=v.forum_name%></dt>
	                <dd><!--<font>＃和你在一起</font>--><%=v.note%></dd>
	            </dl>
            </a>
	            <%if v.pictures%>
	            <div class="list-img">
	                <ul>
	                    <%each v.pictures p%>
	                    <li><a href="javascript:;"><img data-pictureview="<%=p.id%>:<%=p.index%>" src="<%=p.src%>"/></a></li>
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
	<?php endif; ?>
	<%eachElse%>
	<div class="rs-empty">暂无数据</div>
	<%/each%>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/user/taPost', ACT: '<?php echo $class;?>', USER_ID: <?php echo $user_id;?>}, G = {pos: {<?php if(!empty($session['lat'])) echo 'lat:'.$session['lat'].',lon:'.$session['lon']; ?>}};</script>