<div class="tabs" >
	<ul id="nav_tabs">
		<li class="active"><a href="#base"><span>基本信息</span><i></i></a></li>
		<li><a href="#detail"><span>商品详情</span><i></i></a></li>
		<li><a href="#koubei"><span>买家口碑</span><i></i></a></li>
	</ul>
</div>
<span class="blank3"></span>
<div id="swiper_main_container" class="swiper-container">
	<div id="swiper_main_wrapper" class="swiper-wrapper">
		<div class="swiper-slide">
			<?php $basePreview = array(); ?>
			<?php $shareImg = null; ?>
			<?php if($product['product_images']):?>
			<?php $images = explode(',',$product['product_images']); ?>
			<div class="detailpic">
				<div id="slider" class="swiper-container imgs">
					<ul class="swiper-wrapper imgBox">
					<?php foreach($images as $key => $image):?>
						<?php if($key==0){$shareImg=$image;}?>
						<?php $basePreview[] = array('content' => $attachUrl.$image); ?>
						<li class="swiper-slide"><a href="#"><img src="<?php echo $attachUrl.$image;?>" alt=""/></a></li>
					<?php endforeach;?>
					</ul>
				</div>
				<div class="extalBox">
					<ul id="btnBox" class="btnBox">
						<?php foreach($images as $key => $image):?>
							<li <?php if(!$key) echo 'class="curr"';?>></li>
						<?php endforeach;?>
					</ul>
				</div>
                <a href="javascript:history.back(-1);" class="itemback"><img src="<?php echo $staticUrl;?>images/back2.png" /></a>
			</div>
			<?php endif;?>
			<dl class="tit">
				<dt><?php echo $product['product_name'];?></dt>
				<dd><font><?php echo $product['price'];?></font><span><?php echo round($product['price']/$product['old_price'],2)*10;?>折</span></dd>
			</dl>
			<dl class="price">
				<dt>原价：<font><?php echo $product['old_price'];?></font></dt>
				<?php if(!empty($session['lat'])):?>
				<dd><span><a href="<?php echo $baseUrl.'item/map?id='.$product['product_id'];?>"><img src="<?php echo $staticUrl;?>images/pos2.png" /><?php echo echoDistance($session['lat'],$session['lon'],$product['lat'],$product['lon']);?></a></span></dd>
				<?php endif;?>
			</dl>
			<div class="promise">
				<ul>
				  <li><img src="<?php echo $staticUrl;?>images/cuser.png"  />已售<?php echo $product['bought_count'];?></li>
				  <li><img src="<?php echo $staticUrl;?>images/ctime.png"  /><i id="timer">0天0小时0分</i></li>
				</ul>
				<ul class="hook">
				  <li><img src="<?php echo $staticUrl;?>images/hook.png"  />支持随时退款</li>
				  <li><img src="<?php echo $staticUrl;?>images/hook.png"  />支持过期退款</li>
				  <li><img src="<?php echo $staticUrl;?>images/hook.png"  />实物商品线下退换货</li>
                  <li><img src="<?php echo $staticUrl;?>images/hook.png"  />发票</li>
				</ul>
			</div>
			<div id="item_comment_btn" class="comments">
				<dl>
					<dt><img src="<?php echo $staticUrl;?>images/star<?php echo round($product['score'])?round($product['score']):'5';?>.png"/></dt>
					<dd><?php echo $product['comments'];?>人评价<img src="<?php echo $staticUrl;?>images/arrow2.png" width="34" height="34" /></dd>
				</dl>
				<span class="clear"></span> 
			</div>
			<div class="shopinfo">
				<ul>
					<a href="<?php getUrl('innView'); echo '?sid='.$product['inn_id']?>">
					<li class="infoleft">
						<dl>
							<dt><span><img src="<?php if(empty($product['inn_head'])): ?><?php echo $staticUrl; ?>/images/head.jpg<?php else: ?><?php echo $attachUrl.$product['inn_head'];?><?php endif; ?>" /></span></dt>
							<dd><span><?php echo $product['local'];?></span><?php echo $product['inn_address']?></dd>
						</dl>
					</li>
					</a>
					<div class="inforight">
						<a href="tel:<?php echo $product['inner_moblie_number']?>"><img src="<?php echo $staticUrl;?>images/telephone.png" width="80" height="80" /></a>
					</div>
				</ul>
				<span class="clear"></span>
			</div>
			<div class="recom" >
				<dl>
					<dt><img src="<?php echo $staticUrl;?>images/tab.png" width="29" height="29" />本店其他特卖</dt>
					<dd>
						<ul>
						<?php foreach($product['tuan'] as $key => $val):?>
							<a href="<?php echo $baseUrl."item/".$val['product_id'];?>">
								<li  class="img"><img src="<?php echo $attachUrl.$val['thumb'];?>"  /></li>
								<li class="rectit"><?php echo $val['product_name'];?></li>
								<li><font><?php echo $val['price'];?></font>元<span><i><?php echo $val['old_price'];?></i>元</span> </li>
							</a>
						<?php endforeach;?>
						</ul>
					</dd>
				</dl>
				<span class="clear"></span>
			</div>
		</div>
	
		<!-- 详情 -->
		<div class="swiper-slide">
			<div class="detacon" >
				<div class="contit"><img src="<?php echo $staticUrl;?>images/book.png" />本单详情</div>
				<div id="detail_content" class="context"><div id="note"><?php echo $product['note'];?></div>
					<div id="detail_images">
					<?php $detailPreview = array(); ?>
					<?php if(!empty($product['detail_images'])):?>
					<?php $images = explode(',',$product['detail_images']); ?>
					<?php foreach($images as $key2 => $image):?>
					<?php $detailPreview[] = array('content' => $attachUrl.$image); ?>
					<img node-type="pic" src="<?php $image_m = explode('.',$image);  echo $attachUrl.$image_m[0].'m.'.$image_m[1];?>" alt=""/> 
					<?php endforeach;?>
					<?php endif;?>
					</div>
				</div>
			</div>
			<div class="detacon" >
				<div class="contit"><img src="<?php echo $staticUrl;?>images/exclamation.png" />购买须知</div>
				<div class="context"> <?php echo $product['booking_info']?></div>
				<br>
			</div>
		</div>
		<div class="swiper-slide">
			<div class="comments">
				<dl>
					<dt><img src="<?php echo $staticUrl;?>images/star<?php echo round($product['score'])?round($product['score']):'5';?>.png"/></dt>
					<dd><?php echo $product['comments'];?>人评价</dd>
				</dl>
				<span class="clear"></span>
			</div>
			<div id="item_comment_tabs" class="screen">
			<?php if($product['comments']):?>
				<a data-type="all" href="#all" class="now">全部</a><a data-type="good" href="#good" >好评(<?php echo $product['comment_score']['four']+$product['comment_score']['five'];?>)</a><a data-type="between" href="#between" >中评(<?php echo $product['comment_score']['three']+$product['comment_score']['two'];?>)</a><a data-type="bad" href="#bad">差评(<?php echo $product['comment_score']['one'];?>)</a><a data-type="pic" href="#pic">有图(<?php echo $product['comment_score']['picture'];?>)</a>
			<?php else:?>
				<a data-type="all" href="#all" class="now">全部</a><a data-type="good" href="#good" >好评(0)</a><a data-type="between" href="#between" >中评(0)</a><a data-type="bad" href="#bad">差评(0)</a><a data-type="pic" href="#pic">有图(0)</a>
			<?php endif;?>
			</div>
			<div id="swiper_comment_container" class="swiper-container">
				<div id="swiper_comment_wrapper" class="swiper-wrapper">
					<div class="swiper-slide">
						<div id="comment_all"></div>
						<div class="loading"></div>
					</div>
					<div class="swiper-slide">
						<div id="comment_good"></div>
						<div class="loading"></div>
					</div>
					<div class="swiper-slide">
						<div id="comment_between"></div>
						<div class="loading"></div>
					</div>
					<div class="swiper-slide">
						<div id="comment_bad"></div>
						<div class="loading"></div>
					</div>
					<div class="swiper-slide">
						<div id="comment_pic"></div>
						<div class="loading"></div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>


<div class="detafoot">
	<a id="share_btn" class="share" href="javascript:void(0);"><img src="<?php echo $staticUrl;?>images/share.png" />分享</a>
	<?php if($product['is_fav']):?>
	<a data-id="<?php echo $product['product_id']; ?>" id="collect" class="share is_fav" href="javascript:void(0);"><img src="<?php echo $staticUrl;?>images/collect-light.png"/>取消</a>
	<?php else:?>
	<a data-id="<?php echo $product['product_id']; ?>" id="collect" class="share" href="javascript:void(0);"><img src="<?php echo $staticUrl;?>images/collect.png"/>收藏</a>
	<?php endif;?>
	<?php if($product['quantity']):?>
	<a id="submit_btn" class="btnbuy" href="<?php echo $baseUrl.'order/confirm?pid='.$product['product_id'];?>">立即抢购</a>
	<?php else:?>
	<a id="submit_btn" class="btnbuy btnDisable" href="javascript:void(0);">已售罄</a>
	<?php endif;?>
</div>
<script type="text/javascript">
	var tuan_end_time = <?php echo $product['tuan_end_time'];?>;
	function replaceEnterToBr(str){
	    var reg=new RegExp("\n","g");
	    var reg1=new RegExp("\r\n","g");

	    str = str.replace(reg,"<br/>");
	    str = str.replace(reg1,"<br/>");

	    return str;
	}
	var note = document.getElementById('note');
	note.innerHTML = replaceEnterToBr(note.innerHTML);
	function ShowCountDown(endtime,htmlid) 
	{
		var leftTime=endtime - new Date(), btn = document.getElementById('submit_btn');
		if(leftTime<=0&&btn)
		{
			btn.innerHTML = '已结束';
			btn.href = 'javascript:;';
			btn.classList.add('btnDisable');
		}
		var leftsecond = parseInt(leftTime/1000);
		var day1=Math.floor(leftsecond/(60*60*24));
		var hour=Math.floor((leftsecond-day1*24*60*60)/3600);
		var minute=Math.floor((leftsecond-day1*24*60*60-hour*3600)/60);
		var second=Math.floor(leftsecond-day1*24*60*60-hour*3600-minute*60);
		document.getElementById(htmlid).innerHTML = checkTime(day1) + "天" + checkTime(hour) + "时" + checkTime(minute) + "分" + checkTime(second) +"秒";  
	}
	
	function checkTime(i)    
	{
		if (i < 10) {
			i = "0" + i;    
		}
		return i;
	}
	var timer = window.tuan_end_time && window.setInterval(function(){ShowCountDown(tuan_end_time*1000,'timer');}, 1000);
</script>
<?php
	$shareData = array(
		'title' => $product['product_name'],
		'url' => $baseUrl.'item/'.$product['product_id'],
		'pic' => $attachUrl.$product['thumb']
	);
?>
<script id="template_comment_item" type="text/template">
	<%each list v%>
	<div class="comment">
		<ul>
			<li class="comleft"><span class="outer"><span class="cover"></span><img src="<%if !!v.headimg%><?php echo $attachUrl;?><%=v.headimg%><%else%><?php echo $staticUrl;?>images/head.jpg<%/if%>" /></span></li>
			<li class="comright">
				<ul>
					<li class="comname"><%=v.user_name%></li>
					<li class="comtime"><span><img src="<?php echo $staticUrl;?>images/star<%=v.points%>.jpg" /></span><%=new Date(parseInt(v.create_time)*1000).format('yyyy-mm-dd')%></li>
					<li class="comtext"><%=v.note%></li>
					<%if v.pictures%>
		            <div class="compic">
	                    <%each v.pictures p%>
	                    <a><img data-pictureview="<%=p.id%>:<%=p.index%>" src="<%=p.src%>"/></a>
	                    <%/each%>
		            </div>
		            <%/if%>
					<span class="clear"></span>
					<li class="operate">
						<a node-type="vote" data-id="<%=v.comment_id%>" data-num="<%=v.likeNum%>" href="#"><img src="<?php echo $staticUrl;?>images/praise.png"/><%=v.likeNum%></a>
						<a node-type="comm" data-id="<%=v.comment_id%>" data-num="<%=v.replyNum%>" href="/item/comment/<%=v.comment_id%>"><img src="<?php echo $staticUrl;?>images/info.png"/><%=v.replyNum%></a>
					</li>
				</ul>
			</li>
		</ul>
		<span class="clear"></span>
	</div>
	<%eachElse%>
	<div class="rs-empty">暂无评论</div>
	<%/each%>
</script>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/item/index'}, DOMAIN = {like: '<?php getUrl("itemLike"); ?>'}, ITEM_ID = <?php echo $product['product_id'];?>, SHARE_DATA = <?php echo json_encode($shareData); ?>, BASEPREVIEW = <?php echo json_encode($basePreview); ?>, DETAILPREVIEW = <?php echo json_encode($detailPreview); ?>;</script>
<span class="blank2"></span>
