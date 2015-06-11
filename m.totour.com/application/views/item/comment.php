<div class="comtit">评价回复 </div>
	<div class="comment">
		<ul>
			<li class="comleft">
				<span class="outer">
					<span class="cover"></span>
					<img src="<?php echo $staticUrl;?>images/pic8.jpg"  />
				</span>
			</li>
			<li class="comright">
				<ul>
					<li class="comname"><?php echo $comment['nick_name'];?></li>
					<li class="comtime">
						<span><img src="<?php echo $staticUrl;?>images/star<?php echo $comment['points'];?>.jpg"/></span>
						<?php echo date('Y-m-d H:i',$comment['create_time']);?>
					</li>
					<li class="comtext"><?php echo $comment['note'];?></li>
					<li class="compic">
					<?php if($comment['picture']):?>
						<?php $images = explode(',',$comment['picture']);$img = array(); ?>
						<?php foreach($images as $image):?>
						<?php $img[] = array('content' => $attachUrl.$image); ?>
						<a node-type="pictureView" href="#"><img src="<?php echo $attachUrl.$image;?>" alt=""/></a>
						<?php endforeach;?>
					<?php endif;?>
					</li>
					<span class="clear"></span>
					<li class="operate"><!--<?php echo $comment['is_like']; //已点赞为 1;?>-->
						<a id="vote_btn" data-id="<?php echo $comment['comment_id']; ?>" data-num="<?php echo $comment['likeNum']; ?>" href="#"><img src="<?php echo $staticUrl;?>images/praise<?php if($comment['is_like']):?>-light<?php endif; ?>.png"/><?php echo $comment['likeNum'];?></a>
						<button id="comment_btn" data-uid="<?php echo $comment['user_id']; ?>" data-name="<?php echo $comment['nick_name']; ?>" type="button" style="border:0; background:#fff"><img src="<?php echo $staticUrl;?>images/info.png"/></button>
						<?php echo $comment['replyNum'];?>
					</li>
				</ul>
			</li>
		</ul>
		<span class="clear"></span>
	</div>
	<div id="subcomment_list">
		<?php if(isset($comment_reply)):?>
		<?php foreach($comment_reply as $key => $val):?>
		<div class="comment2">
			<ul>
				<li class="comleft"><span class="outer"><span class="cover"></span><img src="<?php echo $attachUrl.$user_info[$val['create_user_id']]['headimg'];?>"/></span></li>
				<li class="comright">
					<ul>
						<li class="comname">
							<dl>
								<dt><?php echo $val['create_nick_name'];?><font>回复</font><?php echo $val['reply_nick_name'];?></dt>
								<dd><a href="#" ref=<?php echo $val['reply_user_id'];?>>回复</a></dd>
							</dl>
						</li>
						<li class="comtime"><?php echo date('Y-m-d H:i',$comment['create_time']);?></li>
						<li class="comtext"><?php echo $val['note']?></li>
					</ul>
				</li>
			</ul>
			<span class="clear"></span>
		</div>
		<?php endforeach;?>
		<?php endif;?>
	</div>
	<div id="load_more_icon" class="loading-icon" style="display: none;"><img src="<?php echo $staticUrl; ?>images/loading.gif" alt="Loading..."></div>
	<a id="load_more_btn" data-type="item" data-page="1" href="javascript:;" class="load-more">加载更多...</a>
	<span class="blank1"></span>

	<!-- <div id="dialog_comment" style="display:none;position:fixed;top:0;left:0;z-index:999;width:100%;height:100%;background-color:#fff;">
		<form method="post" action="">
			<div style="max-width:640px; margin:0 auto;">
				<div class="replytop">
					<ul>
						<li id="dialog_close" class="close repleft">取消</li>
						<li class="repmiddle">回复评论<span id="comment_user_name">丽江小玫瑰</span></li>
						<li id="dialog_send" class="repright">发送</li>
					</ul>
				</div>
				<div class="reptext">
					<textarea id="note" cols="" rows=""></textarea>
				</div>
			</div>
		</form>
	</div>
	</div> -->
<div id="dialog_comment" class="com-foot" style="display:none;"><input id="note" name="" type="text" class="cinp" placeholder="回复&nbsp吉祥"/><a id="dialog_send" href="#">发送</a></div>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/item/comment'}, COMMENT_ID = <?php echo $comment['comment_id']; ?>, COMMENT_IMG = <?php echo json_encode($img); ?>;</script>