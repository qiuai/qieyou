<div class="base-top">
    <div class="left2"><a href="javascript:history.back();" class="left"><img alt="" src="<?php echo $staticUrl;?>images/back2.png"/></a></div>
    <div class="simpleTag2 wentit">商品评价</div>
    <?php if($commented){?><div class="right3"><a id="submit_btn" href="#" >完成</a></div><?php }?>
</div>
<div class="com-edit">
    <input id="com_star_value" type="hidden" value="0">
    <div class="left">商品满意度：</div>
    <div id="com_star" class="left">
        <img alt="" src="<?php echo $staticUrl;?>images/star_single_dark.png"/>
        <img alt="" src="<?php echo $staticUrl;?>images/star_single_dark.png"/>
        <img alt="" src="<?php echo $staticUrl;?>images/star_single_dark.png"/>
        <img alt="" src="<?php echo $staticUrl;?>images/star_single_dark.png"/>
        <img alt="" src="<?php echo $staticUrl;?>images/star_single_dark.png"/>
    </div>
    <div id="rating_text" class="right"></div>
    <span class="clear"></span> </div>
<div class="stext">
            <textarea id="note" name="" cols="" rows="" placeholder="写下你的评论（您的评论将是其他用户的重要参考 ）"></textarea>

</div>
<div class="picture">
    <ul id="upload_pic">
        <li id="upload_add"><a class="add" href="#"><span class="add-line"><span class="line1"></span><span class="line2"></span></span><div class="progress" style="display:none;"><div class="progress-bar"></div><span class="progress-percentage">上传中...</span></div></a><input id="upload_img" class="upload-img" type="file"></li>
        <span class="clear"></span>
    </ul>
</div>
<!-- <span class="blank6"></span>
<div class="sfoot">
    <div class="photo">
        <ul>
            <li><a href="#"><img alt="" src="<?php echo $staticUrl;?>images/photo.png"/></a></li>
            <li><a href="#"><img alt="" src="<?php echo $staticUrl;?>images/picture.png"/></a></li>
        </ul>
    </div>
</div> -->
<script type="text/javascript">var REQUIRE = {MODULE: 'page/order/comment'};</script>