<div class="confirm-address2"> <a href="<?php getUrl('userAddressEdit'); if($type==1) echo '?type=1';?>"> <img src="<?php echo $staticUrl;?>images/add2.png" />新增收货地址<span class="right"><img src="<?php echo $staticUrl;?>images/arrow2.png" /></span> </a> </div>
<?php if($address):?>
<div id="address_list">
    <?php foreach($address as $key => $row):?>
    <div class="confirm-address">
		<a href="<?php getUrl('userAddressEdit');?>?addressId=<?php echo $row['address_id'];if($type==1) echo '&type=1';?>">
        <div class="addrinfo">
            <p class="font12"><?php echo $row['real_name'];?><span class="u-phone"><?php echo $row['mobile']?></span></p>
            <p><?php echo $row['location'];?></p>
            <p><?php echo $row['address'];?></p>
        </div>
        <div class="addrarrow2"><img src="<?php echo $staticUrl;?>images/arrow2.png" /></div>
        <span class="clear"></span>
        <div class="add-default">
            <div class="left">
                <input node-type="radio" data-id="<?php echo $row['address_id'];?>" class="u-radio u-light" name="u-radio" id="cb<?php echo $row['address_id'];?>" type="radio"<?php if($row['is_default'])echo ' checked flag="1"';?>>
                <label class="u-btn" for="cb<?php echo $row['address_id'];?>"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
            </div>
            <div  class="left">
                <label for="cb<?php echo $row['address_id'];?>"><?php if($type==1) echo '送到这里去';else echo '设置为默认地址';?></label>
            </div>
            <div class="right"><a node-type="del" data-id="<?php echo $row['address_id'];?>" href="#">删除</a></div>
        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
<span class="blank1"></span> 
<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/userData', ACT: 'address'}, IDENTIFY_TYPE = <?php echo $type;?></script>