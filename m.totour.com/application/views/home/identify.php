<div class="confirm-address2"> <a href="<?php getUrl('userIdcardEdit');if($type==1) echo '?type=1';?>"> <img src="<?php echo $staticUrl;?>images/add2.png" />新增证件信息<span class="right"><img src="<?php echo $staticUrl;?>images/arrow2.png" /></span> </a> </div>
<?php if($identify):?>
<div id="address_list">
    <?php foreach($identify as $key => $row):?>
    <div class="confirm-address">
		<a href="<?php getUrl('userIdcardEdit');?>?identifyId=<?php echo $row['identify_id'];?>">
        <div class="addrinfo">
            <p class="font12"><?php echo $row['real_name'];?><span class="u-phone"><?php echo $row['idcard']?></span></p>
        </div>
		<div class="idenarrow"><img src="<?php echo $staticUrl;?>images/arrow2.png" /></div>
        <span class="clear"></span>
        <div class="add-default">
            <div class="left">
                <input node-type="radio" data-id="<?php echo $row['identify_id'];?>" class="u-radio u-light" name="u-radio" id="cb<?php echo $row['identify_id'];?>" type="radio"<?php if($row['is_default'])echo ' checked flag="1"';?>>
                <label class="u-btn" for="cb<?php echo $row['identify_id'];?>"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
            </div>
            <div  class="left">
                <label for="cb<?php echo $row['identify_id'];?>">设置为默认证件</label>
            </div>
            <div class="right"><a node-type="del" data-id="<?php echo $row['identify_id'];?>" href="#">删除</a></div>
        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
<!--
<div class="confirm-address">
    <div class="addrinfo">
        <p class="font12">张琪琪<span class="u-phone">41082319860222734</span></p>
    </div>
    <div class="idenarrow"><img src="<?php echo $staticUrl;?>images/arrow2.png" /></div>
    <span class="clear"></span>
    <div class="add-default">
        <div class="left">
            <input node-type="radio" data-id="<?php echo $row['identify_id'];?>" class="u-radio u-light" name="u-radio" id="cb1" type="radio">
            <label class="u-btn" for="cb1"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
        </div>
        <div  class="left">
            <label for="cb1">设置为默认地址</label>
        </div>
        <div class="right"><a node-type="del" href="#">删除</a></div>
    </div>
</div>
-->
<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/userData', ACT: 'identify'}, IDENTIFY_TYPE = <?php echo $type; ?>;</script>