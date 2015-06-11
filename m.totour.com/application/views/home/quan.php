<div class="quan">
  <ul id="nav_tabs">
    <li class="active">有效抵用券（<?php echo count($able);?>）</li>
    <li>全部抵用券（<?php echo count($quan);?>）</li>
  </ul>
</div>

<div class="swiper-container">
	<div class="swiper-wrapper">
		<div class="swiper-slide">
			<div class="quancon">
			<?php if($able):?>
			<?php foreach($able as $key => $row):?>
				<ul>
					<li class="qleft">￥<span><?php echo $row['amount']?></span><br />代金券</li>
					<li class="qright"><?php echo $row['type']==1?'全场通用':'指定商品';?><span>有效期：<?php echo date('Y-m-d',$row['start_time']);?>至<?php echo date('Y-m-d',$row['end_time']);?></span><a href="<?php getUrl('special');?>">立即使用</a></li>
				</ul>
			<?php endforeach;?>
			<?php else: ?>
				<div class="rs-empty">暂无数据</div>
			<?php endif;?>
			</div>
		</div>
		<div class="swiper-slide">
			<div class="quancon">
			<?php if($quan):?>
			<?php foreach($quan as $key => $row):?>
				<ul<?php if(isset($row['user_time'])): ?> graybg<?php endif; ?>>
					<li class="qleft">￥<span><?php echo $row['amount']?></span><br />代金券</li>
					<li class="qright"><?php echo $row['type']==1?'全场通用':'指定商品';?><span>有效期：<?php echo date('Y-m-d',$row['start_time']);?>至<?php echo date('Y-m-d',$row['end_time']);?></span>
					<a href="<?php getUrl('special');?>" <?php if($row['overdue']) echo 'class="qgray"';?>><?php if(!$row['overdue']) echo '立即使用'; else if($row['user_time']) echo '已过期'; else echo '已使用';?></a></li>
				</ul>
			<?php endforeach;?>
			<?php else: ?>
				<div class="rs-empty">暂无数据</div>
			<?php endif;?>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/quan'};</script>