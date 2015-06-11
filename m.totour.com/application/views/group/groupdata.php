<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<div class="gdata">
    <div class="gdata-list">
        <div class="gleft">部落名称</div>
        <div class="gright"><?php echo $group['group_name'];?></div>
    </div>
    <div class="gdata-list">
        <div class="gleft">部落简介</div>
        <div class="gright">
            <div><?php echo $group['note'];?></div>
        </div>
    </div>
	<a href="<?php echo $baseUrl.'group/member?group='.$group['group_id'];?>">
    <div class="gdata-list bordernone">
        <div class="gleft">部落成员</div>
        <div class="gright"><?php echo $group['members'];?>人<img src="<?php echo $staticUrl;?>images/arrow2.png" /></div>
    </div>
	</a>
    <div class="gdata-cy">
			<?php foreach($group_member as $key => $row):?>
			<a href="<?php echo $baseUrl.'user/'.$row['user_id'];?>">
           <img src="<?php if(empty($row['headimg'])): ?><?php echo $staticUrl; ?>images/head.jpg<?php else: ?><?php echo $attachUrl.$row['headimg'];?><?php endif; ?>"/>
			</a>
			<?php endforeach;?>

    </div>
</div>
<?php if($group['is_join']):?>
<?php if($group['waiting_verify']):?>
<div class="fbtn"><a id="gate_btn" data-attention="quit" data-gid="<?php echo $group['group_id']; ?>" href="#" class="redbtn btnDisable">审核中</a></div>
<?php else:?>
<div class="fbtn"><a id="gate_btn" data-attention="quit" data-gid="<?php echo $group['group_id']; ?>" href="#" class="redbtn btnDisable">已加入</a></div>
<?php endif; ?>
<?php else:?>
<div class="fbtn"><a id="gate_btn" data-attention="join" data-joinable="<?php echo $group['join_method']; ?>" data-gid="<?php echo $group['group_id']; ?>" href="#" class="redbtn">加入部落</a></div>
<?php endif;?>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/group/groupData'};</script>