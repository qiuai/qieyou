<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/group.css"/>
<div class="seting-con">
    <ul class="border-bot">
        <a node-edit="name" node-edit-name="修改部落名称" href="#" >
        <li class="sleft">部落名称：</li>
        <li id="edit_content_name" class="sright"><?php echo $group['group_name'];?> </li>
        </a>
        
    </ul>
    <ul>
        <a href="/group/editavatar?group=<?php echo $group['group_id']; ?>">
        <li class="sleft">部落头像：</li>
        <li class="sright relative"> <span><img id="avatar_preview" class="toux" src="<?php echo $attachUrl.$group['group_img'];?>" /> </span><input id="avatar" class="upload-avatar" type="file"></li>
        </a>
    </ul>
</div>
<div class="seting-con">
    <ul class="border-bot bordernone">
        <a node-edit="desc" node-edit-name="修改部落描述" href="#" >
        <li class="sleft">部落描述：</li>
        <li id="edit_content_desc" class="sright"><?php echo $group['note'];?></li>
        </a>
    </ul>
</div>
<div class="seting-con">
    <ul>
        <li class="sleft">加入方式：</li>
        <li class="sright2">
            <ul id="join_method">
                <li> <span style=" display:inline-block">
                    <input  id="cb1" class="u-radio u-light" name="join_method" value="able" type="radio"<?php if($group['join_method']=='able'): ?> checked<?php endif; ?>>
                    <label class="u-btn" for="cb1"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
                    </span> <span  style=" display:inline-block">
                    <label for="cb1">无需审核，任何人可加入</label>
                    </span> 
				</li>
                 <li> <span style=" display:inline-block">
                    <input id="cb2" class="u-radio u-light" name="join_method" value="verify" type="radio"<?php if($group['join_method']=='verify'): ?> checked<?php endif; ?>>
                    <label class="u-btn" for="cb2"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
                    </span> <span  style=" display:inline-block">
                    <label for="cb2">需要管理员审核</label>
                    </span>
				</li>  
                    <li> <span style=" display:inline-block">
                    <input id="cb3" class="u-radio u-light" name="join_method" value="noable" type="radio"<?php if($group['join_method']=='noable'): ?> checked<?php endif; ?>>
                    <label class="u-btn" for="cb3"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
                    </span> <span  style=" display:inline-block">
                    <label for="cb3">不允许任何人加入</label>
                    </span>
				</li>  
            </ul>
        </li>
        
    </ul>
</div>

<div id="dialog_edit" class="dialog-edit" style="display:none;">
    <form class="edit-form" action="">
        <div class="edit-hd">
            <div class="save">保存</div>
            <div class="cancel">取消</div>
            <div id="dialog_edit_title" class="edit-hd-title"></div>
        </div>
        <div id="edit_content">
            <div data-name="name" style="display:none;">
                <div class="edit-item">
                    <textarea id="name" class="edit-input edit-textarea" type="text" placeholder="请输入部落名称"><?php echo $group['group_name'];?></textarea>
                </div>
            </div>
            <div data-name="desc" style="display:none;">
                <div class="edit-item">
                    <textarea id="desc" class="edit-input edit-textarea" type="text" placeholder="请输入部落简介"><?php echo $group['note'];?></textarea>
                </div>
            </div>
        </div>
    </form>
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

<script type="text/javascript">var REQUIRE = {MODULE: 'page/group/settings', GROUP_ID: <?php echo $group['group_id']; ?>};</script>