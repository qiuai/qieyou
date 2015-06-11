<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<div class="seting-con">
    <ul class="border-bot ">
        <li class="sleft">部落名称：</li>
        <li class="sright" style="background:none"><input id="form_name" name="" type="text" class="edit-input2" placeholder="请输入部落名称" maxlength="8" /></li>
    </ul>
    <ul>
        <a href="#">
        <li class="sleft">部落头像：</li>
        <li class="sright relative"> <span><img id="avatar_preview" alt="" src=""><input id="avatar" class="upload-avatar" type="file"><input id="form_avatar" type="hidden"></span></li>
        </a>
    </ul>
</div>
<div class="seting-con">
    <ul class="border-bot bordernone">
        <li class="sleft">部落描述：</li>
        <li id="edit_content_desc" class="sright" style="background:none"><textarea id="form_desc" class="edit-textarea2" type="text" placeholder="请输入部落简介"></textarea></li>
    </ul>
</div>
<div class="seting-con">
    <ul>
        <li class="sleft">加入方式：</li>
        <li class="sright2">
            <ul id="join_method">
                <li> <span style=" display:inline-block">
                    <input  id="cb1" class="u-radio u-light" name="join_method" value="able" type="radio" checked>
                    <label class="u-btn" for="cb1"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
                    </span> <span  style=" display:inline-block">
                    <label for="cb1">无需审核，任何人可加入</label>
                    </span> 
				</li>
                 <li> <span style=" display:inline-block">
                    <input id="cb2" class="u-radio u-light" name="join_method" value="verify" type="radio">
                    <label class="u-btn" for="cb2"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
                    </span> <span  style=" display:inline-block">
                    <label for="cb2">需要管理员审核</label>
                    </span>
				</li>  
                    <li> <span style=" display:inline-block">
                    <input id="cb3" class="u-radio u-light" name="join_method" value="noable" type="radio">
                    <label class="u-btn" for="cb3"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
                    </span> <span  style=" display:inline-block">
                    <label for="cb3">不允许任何人加入</label>
                    </span>
				</li>  
            </ul>
        </li>
        
    </ul>
</div>


<div id="crop_container" class="crop-container">
    <div id="crop_wrapper" class="crop-wrapper">
        <canvas id="crop_dark" class="crop-dark"></canvas>
        <div class="crop-cover"></div>
        <canvas id="crop_light" class="crop-light"></canvas>
    </div>
    <div id="crop_bottom" class="crop-bottom">
        <a id="crop_confirm" class="crop-bottom-item crop-bottom-confirm" href="javascript:;">选取</a>
        <a id="crop_cancel" class="crop-bottom-item crop-bottom-cancel" href="javascript:;">取消</a>
    </div>
</div>


<script type="text/javascript">var REQUIRE = {MODULE: 'page/group/newgroup'};</script>