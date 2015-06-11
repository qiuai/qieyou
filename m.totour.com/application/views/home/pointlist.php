<div class="pointtop">
	<dl>
		<dt><span><img alt="" src="<?php echo $attachUrl.$user['headimg'];?>"/></span><?php echo $user['nick_name'];?></dt>
		<dd><span><?php echo $user['point'];?></span>积分</dd>
	</dl>
</div>
<div class="pointlist" style="display:none;">
<?php if($pointlist):?>
	<?php foreach($pointlist as $key => $row):?>
	<?php $pointlist[$key]['strday'] = date('Y-m-d',$row['create_time']);?>
	<?php if($key == 0):?>
		<dl>
			<dt><?php echo $pointlist[$key]['strday'];?></dt>
	        <dd><span class="left"><?php echo $row['content'];?></span><span class="right <?php echo $row['point']>0?'red':'green';?>"><?php echo $row['point']>0?('+'.$row['point']):$row['point']; ?></span><span class="clear"></span></dd>
	<?php elseif($pointlist[($key-1)]['strday'] == $pointlist[$key]['strday']):?>
	        <dd><span class="left"><?php echo $row['content'];?></span><span class="right <?php echo $row['point']>0?'red':'green';?>"><?php echo $row['point']>0?('+'.$row['point']):$row['point']; ?></span><span class="clear"></span></dd>
	<?php else:?>
		</dl>
		<dl>
			<dt><?php echo $pointlist[$key]['strday'];?></dt>
	        <dd><span class="left"><?php echo $row['content'];?></span><span class="right <?php echo $row['point']>0?'red':'green';?>"><?php echo $row['point']>0?('+'.$row['point']):$row['point']; ?></span><span class="clear"></span></dd>
	<?php endif;?>
	<?php endforeach;?>
	</dl>
<?php endif;?>
</div>


<div class="pointlist">
	<div id="container"></div>
	<div class="loading"></div>
</div>

<script id="template_item" type="text/template">
	<%each list k v%>
	<%var date=new Date(parseInt(v.create_time)*1000).format('yyyy-mm-dd')%>
	<dl>
	<%if !window._STRDAY&&k==0%>
		<%window._STRDAY=date%>
		<dt><%=window._STRDAY%></dt>
        <dd><span class="left"><%=v.content%></span><span class="right<%=v.point>0?' red':' green'%>"><%=v.point>0?('+'+v.point):v.point%></span><span class="clear"></span></dd>
    <%else%>
    	<%list[k].strday=date%>
    	<%if window._STRDAY==list[k].strday%>
    	<dd><span class="left"><%=v.content%></span><span class="right<%=v.point>0?' red':' green'%>"><%=v.point>0?('+'+v.point):v.point%></span><span class="clear"></span></dd>
    	<%else%>
		<dt><%=list[k].strday%></dt>
        <dd><span class="left"><%=v.content%></span><span class="right<%=v.point>0?' red':' green'%>"><%=v.point>0?('+'+v.point):v.point%></span><span class="clear"></span></dd>
    	<%/if%>
    	<%window._STRDAY=date%>
	<%/if%>
	</dl>
	<%eachElse%>
	<div class="rs-empty">暂无数据</div>
	<%/each%>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/pointlist'};</script>