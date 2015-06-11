<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/user.css"/>
<div class="base-top">
    <div class="left2"><a href="<?php echo isset($backUrl)?$backUrl:'javascript:history.back(-1);'?>" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"/></a></div>
    <div class="simpleTag2"><span><img alt="" src="<?php echo $staticUrl;?>images/youji3.png"/></span><?php echo $moduleTag;?></div>
    <div class="right3"><a id="submit_btn" href="#" >完成</a></div>
</div>
<div class="sgroup">
    <ul>
        <a id="select_group" href="#">
        <li class="left">发表到 <span id="group_name" class="red">虚拟</span> 部落</li>
        <li class="right"><img id="group_img" alt="" src=""/></li>
        <span class="clear"></span> </a>
        <input id="form_group" type="hidden" value="0">
    </ul>
</div>
<div class="stext">
    <form method="post" action="/forum/sendtour">
    <dl>
        <dt><span class="left"><img alt="" src="<?php echo $staticUrl;?>images/tit.png"/>游记标题：</span><span class="right">
            <input id="form_title" value="" autocomplete="off" placeholder="填写游记标题" maxlength="80">
            </span><span class="clear"></span></dt>
        <dt><span class="left"><img alt="" src="<?php echo $staticUrl;?>images/label.png"/>设置标签：</span><span class="right">
            <input id="form_tag" value="" autocomplete="off" placeholder="多个请以空格隔开，最多三个标签">
            </span><span class="clear"></span></dt>
        <dd>
            <textarea id="form_note" name="" cols="" rows="" placeholder="请写下你的旅行故事（至少要发一张图片）"></textarea>
        </dd>
    </dl>
    </form>
</div>
<div class="picture">
    <ul id="upload_pic">
        <li id="upload_add"><a class="add" href="#"><span class="add-line"><span class="line1"></span><span class="line2"></span></span><div class="progress" style="display:none;"><div class="progress-bar"></div><span class="progress-percentage">上传中...</span></div></a><input id="upload_img" class="upload-img" type="file"></li>
        <span class="clear"></span>
    </ul>
</div>
<span class="blank2"></span>
<div class="sfoot">
    <div class="pos"><a id="select_pos" href="/group/position"><img alt="" src="<?php echo $staticUrl;?>images/pos.jpg"/><em>显示你的位置</em></a></div>
    <!-- <div class="photo">
        <ul>
            <li><a href="#"><img alt="" src="<?php echo $staticUrl;?>images/photo.png"/></a></li>
            <li><a href="#"><img alt="" src="<?php echo $staticUrl;?>images/picture.png"/></a></li>
        </ul>
    </div> -->
</div>


<div style="display:none;position:fixed;top:0;left:0;z-index:99995;width:100%;height:100%;background-color:rgba(0,0,0,0.75)"></div>
<div id="dialog_tip" class="layer" style="display:none;z-index:99996;">
<div class="layer-con">您还没有关注的部落，帖子将发到虚拟部落！</div>
<div class="layer-btn"><a node-type="confirm" href="#" >确定</a><a node-type="recommend" href="/group/recommend" class="bordernone">推荐部落</a></div>
</div>

<div id="dialog_position" class="dialog-position" style="display:none;">
    <div class="base-top">
        <a id="pos_close" href="javascript:;" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"></a>
        <p class="simpleTag">所在位置</p>
    </div>
    <!-- <div class="search-top stop-bg">
        <div class="left">
            <span><img alt="" src="<?php echo $staticUrl;?>images/search2.png"/></span>
            <input name="" type="text" />
        </div>
        <div class="right"><a href="#">取消</a></div>
        <span class="clear"></span>
    </div> -->
    <div id="position_list" class="plist"></div>
</div>

<!-- 选择部落 -->
<div id="dialog_group" class="user-nav" style="display:none;position:fixed;top:0;left:0;z-index:99994;width:100%;height:100%;overflow-x:hidden;overflow-y:auto;">
    <div class="base-top">
        <a id="dialog_group_close" href="javascript:;" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"></a>
        <p class="simpleTag">选择部落</p>
    </div>
    <div class="user-nav-list" >
        <a href="<?php getUrl('userGroup');?>">
            <ul style="height:3rem">
                <li class="uleft" style="width:15%"><img alt="" src="<?php echo $staticUrl;?>images/group.png"/></li>
                <li class="uright" style="width:85%;"><span class="left">我的部落</span><span id="dialog_group_num" class="right fgray">0</span> </li>
            </ul>
        </a>
        <div>
            <div id="content_group"></div>
            <div class="loading"></div>
        </div>
    </div>
</div>

<!-- 推荐部落 -->
<div id="dialog_recommend" class="user-nav" style="display:none;position:fixed;top:0;left:0;z-index:99994;width:100%;height:100%;overflow-x:hidden;overflow-y:auto;">
    <div class="base-top">
        <a id="dialog_recommend_close" href="javascript:;" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"></a>
        <p class="simpleTag">推荐部落</p>
        <a id="dialog_recommend_ok" href="javascript:void(0);" class="right">完成</a>
    </div>
    <div>
        <div id="content_recommend"></div>
        <div class="loading"></div>
    </div>
    <div class="margin1">
        <input id="attention_all" class="redbtn" name="" type="submit" value="一键关注" />
    </div>
</div>


<script id="template_position" type="text/template">
    <div class="plist-con">
        <label for="cb1">
        不显示位置
        <div>
            <input data-name="显示你的位置" data-pos class="u-radio u-light" name="pos" id="cb1" type="radio"/>
            <label class="u-btn" for="cb1"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
        </div>
        </label>
    </div>
    <%each list v%>
    <div class="plist-con">
        <label for="<%=v.uid%>">
        <%=v.name%><font><%=v.addr%></font>
        <div>
            <input data-name="<%=v.name%>" data-pos="<%=v.point.x%>:<%=v.point.y%>" class="u-radio u-light" name="pos" id="<%=v.uid%>" type="radio"/>
            <label class="u-btn" for="<%=v.uid%>"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
        </div>
        </label>
    </div>
    <%/each%>
</script>
<script id="template_group" type="text/template">
    <%each list v%>
    <div class="groupcon bordernone" style="padding-bottom:0">
        <label for="cb<%=v.group_id%>">
            <div class="gleft" style="margin-left:0"><span><img alt="" src="<?php echo $attachUrl;?><%=v.group_img%>"/></span></div>
            <div class="gright" style="padding-right:0">
                <div class="gright-con border-b" style="padding-right:1rem">
					<div class="cleft">
					<p class="tit"><%=v.group_name%></p>
					<p class="con">帖子<span><%=v.group_topics%></span>关注<span><%=v.members%></span></p>
					</div> 
					<div class="right" style="padding-top:1rem">
					<input data-gname="<%=v.group_name%>" data-gimg="<%=v.group_img%>" class="u-radio u-light" name="group" id="cb<%=v.group_id%>" type="radio" value="<%=v.group_id%>">
					<label class="u-btn" for="cb<%=v.group_id%>"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
					</div>
					<span class="clear"></span>
				</div>
            </div>
        </label>
    </div>
    <%eachElse%>
    <div class="rs-empty">暂无数据</div>
    <%/each%>
</script>
<script id="template_recommend" type="text/template">
    <%each list v%>
    <div class="groupcon">
        <div class="gleft"><span><img alt="" src="<?php echo $attachUrl;?><%=v.group_img%>"/></span></div>
        <div class="gright "> 
		<div class="gright-con">
		<span class="cleft">
            <p class="tit"><%=v.group_name%></p>
            <p class="con">帖子<span><%=v.topics%></span>关注<span><%=v.members%></span></p>
            </span>
            <%if !v.join_time%>
                <%if v.join_method!='noable'%>
               <span class="cright"> <a data-attention="join" data-gid="<%=v.group_id%>" data-joinable="<%=v.join_method%>" href="#" >加入部落</a></span>
                <%/if%>
            <%else%>
                <%if !!+v.waiting%>
                <span class="cright"><a href="javascript:;">审核中</a></span>
                <%else%>
                <span class="cright"><a href="javascript:;" >已加入</a></span>
                <%/if%>
            <%/if%>
			</div>
        </div>
    </div>
    <%eachElse%>
    <div class="rs-empty">暂无数据</div>
    <%/each%>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/forum/post', TYPE: 'youji'}, G = {pos: {<?php if(!empty($session['lat'])) echo 'lat:'.$session['lat'].',lon:'.$session['lon']; ?>}};</script>