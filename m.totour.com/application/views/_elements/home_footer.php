<span class="clear"></span>
<span class="blank2"></span>
<div class="copyright">
  <ul>
    <li>
		<a href="<?php echo $baseUrl;?>"><span>首页</span></a>
		<a href="<?php getUrl('group');?>"><span>圈子</span></a>
		<a href="<?php getUrl('special');?>"><span>特卖</span></a>
		<a href="<?php getUrl('user');?>"><span>我的</span></a>
		<a href="<?php getUrl('downloadApp');?>" class="none"><span>客户端</span></a>
	</li>
    <li class="cline"></li>
    <li class="ctext">@ 2015 且游网 京ICP备15000312号-1</li>
  </ul>
</div>
<?php if($dbInfo){ foreach($dbInfo as $query){ echo '<p>'.$query.'</p>';}};?>