<div class="inn-top">
    <div class="pic"><img alt="" src="<?php echo $attachUrl.$inn['inn_head'];?>"/></div>
    <div class="text">
        <dl>
            <dt><?php echo $inn['inn_name'];?></dt>
            <dd>
			<?php if(empty($inn['is_fav'])):?>
				<a id="collect_btn" data-act="like" data-id="<?php echo $inn['inn_id'];?>" href="#"><img alt="" src="<?php echo $staticUrl;?>images/collect.png"/>收藏</a>
			<?php else:?>
				<a id="collect_btn" data-act="unlike" data-id="<?php echo $inn['inn_id'];?>" href="#"><img alt="" src="<?php echo $staticUrl;?>images/collect-light.png"/>收藏</a>
			<?php endif;?>
				<a id="share_btn" href="#"><img alt="" src="<?php echo $staticUrl;?>images/share2.png"/>分享</a>
			</dd>
        </dl>
    </div>
</div>
<div class="inn-con">
    <dl>
        <dt>店铺介绍</dt>
        <dd><?php echo $inn['inn_summary'];?></dd>
    </dl>
</div>
<div class="inn-con">
    <dl>
        <dt>店铺地址</dt>
        <dd><span class="left"><img alt="" src="<?php echo $staticUrl;?>images/pos3.png"/><?php echo $inn['inn_address'];?></span><span class="right"><a href="tel:<?php echo $inn['inner_telephone']?$inn['inner_telephone']:$inn['inner_moblie_number'];?>"><img alt="" src="<?php echo $staticUrl;?>images/telephone.png"/></a></span><span class="clear"></span></dd>
        
    </dl>
</div>
<?php if($product):?>
<div class="inn-list">
    <div class="inn-tit">店铺全部商品</div>
	<?php foreach($product as $key => $row):?>
    <div class="sale-list"> 
		<a href="<?php getUrl('itemView');echo $row['product_id'];?>">
        <div class="sleft"><img alt="" src="<?php echo $attachUrl.$row['thumb'];?>"/></div>
        <div class="sright">
            <div class="sright-con">
            <dl>
                <dt><span class="left"><?php echo $row['product_name'];?></span><?php if(!empty($session['lat'])):?><span class="right"><img src="<?php echo $staticUrl;?>images/pos2.png" /><?php echo echoDistance($session['lat'],$session['lon'],$row['lat'],$row['lon']);?></span><?php endif;?></dt>
                <dd><?php echo $row['product_name'];?></dd>
            </dl>
            <div class="pleft">
                <ul>
                    <li class="price"><span class="redprice"><?php $price = explode('.',$row['price']); echo $price[0].'<i>.'.$price[1].'</i>';?></span>元 <font class="grayprice"><?php echo $row['old_price'];?></font></li>
                    <li class="pj"><img src="<?php echo $staticUrl;?>images/star<?php echo round($row['score'])?round($row['score']):5;?>.png" /><?php echo $row['comments'];?>人评价</li>
                </ul>
            </div>
            <div class="pright">
                <div class="quantity">
                    <?php $row['percentage'] = round($row['quantity']/($row['quantity']+$row['bought_count'])*100); ?>
                    <div class="inner" style="width:<?php echo $row['percentage']; ?>%"></div>
                    <div class="desc"><p>仅剩</p><p><?php echo $row['percentage']; ?>%</p></div>
                </div>
            </div>
            </div>
        </div>
		</a> 
	</div>
	<?php endforeach;?>
</div>
<?php endif;?>
</div>
<?php
    $shareData = array(
        'pic' => $attachUrl.$inn['inn_head'],
        'url' => $baseUrl.'/special/inn?sid='.$inn['inn_id'],
        'title' => $inn['inn_name'],
    );
?>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/special/inn'}, SHARE_DATA = <?php echo json_encode($shareData); ?></script>