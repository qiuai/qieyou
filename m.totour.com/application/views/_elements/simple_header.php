<div class="base-top">
	<a href="<?php echo isset($backUrl)?$backUrl:'javascript:history.back(-1);'?>" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"/></a>
	<p class="simpleTag"><?php echo $moduleTag;?></p>
	<?php if(isset($shoucang)):?>
	<a id="item_edit_btn" href="javascript:void(0);" class="right">编缉</a>
	<?php elseif($moduleTag=='创建部落'): ?>
	<a id="submit_btn" class="right" href="#">完成</a>
	<?php elseif($moduleTag=='我的部落'): ?>
	<?php if(!empty($session['inn_id'])):?>
	<a class="right" href="/group/newgroup">创建</a>
	<?php endif;?>
	<?php endif;?>
</div>