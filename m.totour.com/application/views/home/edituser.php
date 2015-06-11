<div class="editu-h">
    <ul>
        <a href="#">
        <li class="left">头像</li>
        <li class="right relative"><span><img id="avatar_preview" src="<?php echo $user['headimg']?$attachUrl.$user['headimg']:$staticUrl.'';?>" /></span><input id="avatar" class="upload-avatar" type="file"></li>
        </a>
    </ul>
</div>
<div class="editu-con">
    <ul>
        <a href="/home/editmobile">
        <li class="left">绑定手机</li>
        <li class="right"><?php echo $user['user_mobile'];?></li>
        <span class="clear"></span> </a>
    </ul>
    <ul>
        <a node-edit="nickname" node-edit-name="修改昵称" href="#">
        <li class="left">昵称</li>
        <li id="edit_content_nickname" class="right"><?php echo $user['nick_name']?></li>
        </a>
    </ul>
    <ul >
        <a node-edit="signature" node-edit-name="修改个性签名" href="#">
        <li style="width:35%; display:inline-block; vertical-align:middle">个性签名</li>
        <li id="edit_content_signature" style="width:63%; display:inline-block; vertical-align:middle; line-height:1.5rem; color:#939393;text-align:right"><?php echo $user['signature']?$user['signature']:'<span class="red" >＋20积分</span>';?></li>
        </a>
    </ul>
    <ul>
        <a node-edit="birthday" node-edit-name="修改年龄" href="#" ref="<?php $age = getAge($user['birthday']); echo $age?$user['birthday']:'';?>">
        <li class="left">年龄</li>
        <li id="edit_content_birthday" class="right"><?php echo $age?$age:'<span class="red" style="margin-left: 3rem;">＋20积分</span>';?></li>
        </a>
    </ul>
    <ul>
        <a node-edit="sex" node-edit-name="修改性别" href="#">
        <li class="left">性别</li>
        <li id="edit_content_sex" class="right">
            <?php switch($user['sex']){case 'M':echo '<i class="right">男</i>';break;case 'F': echo '<i class="right">女</i>';break;default:echo '<span class="red" style="margin-left: 3rem">＋20积分</span>';break;}?>
        </li>
        </a>
    </ul>
    <ul class="bordernone">
        <a href="/home/password">
        <li>修改密码</li>
        </a>
    </ul>
</div>
<!--
<div class="editu-con">
    
     <ul style="display:none;">
        <a href="#">
        <li class="left">绑定微信<span></span></li>
        <li class="right red">＋50积分</li>
        </a>
    </ul>

    <ul style="display:none;">
        <a href="#">
        <li class="left">绑定新浪微博<span></span></li>
        <li class="right red">＋50积分</li>
        <li class="right">lilian@sinna.com</li>
        </a>
    </ul>
    <ul class="bordernone" style="display:none;">
        <a href="#">
        <li class="left">绑定QQ</li>
        <li class="right">2000-01-10</li>
        <li class="right">554131755</li> 
        </a>
    </ul>
</div>-->
<div class="editu-btn"><a class="redbtn" href="<?php getUrl('logout');?>">退出登录</a></div>
<div id="dialog_edit" class="dialog-edit" style="display:none;">
    <form class="edit-form" action="">
        <div class="edit-hd">
            <div class="save">保存</div>
            <div class="cancel">取消</div>
            <div id="dialog_edit_title" class="edit-hd-title"></div>
        </div>
        <div id="edit_content">
            <div data-name="nickname" style="display:none;">
                <div class="edit-item">
                    <input id="nickname" class="edit-input" type="text" value="<?php echo $user['nick_name']?>" maxlength="8">
                </div>
            </div>
            <div data-name="signature" style="display:none;">
                <div class="edit-item">
                    <textarea id="signature" class="edit-input edit-textarea" type="text" placeholder="请输入个性签名"><?php echo $user['signature'];?></textarea>
                </div>
            </div>
            <div data-name="birthday" style="display:none;">
                <div class="edit-item">
                    <input id="birthday" class="edit-select" type="date" name="birthday" value="<?php $age = getAge($user['birthday']); echo $age?$user['birthday']:'';?>">
                </div>
            </div>
            <div data-name="sex" style="display:none;">
                <div class="edit-item2" >
                    <dl>
                        <dt class="left">
                            <label for="cb1">男</label>
                        </dt>
                        <dd class="right">
                            <input class="u-radio u-light" name="sex" id="cb1" type="radio"<?php if($user['sex']=='M'): ?> checked<?php endif; ?> value="M">
                            <label class="u-btn" for="cb1"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
                        </dd>
                        <span class="clear"></span>
                    </dl>
                    <dl class="bordernone">
                        <dt class="left" >
                            <label for="cb2" >女</label>
                        </dt>
                        <dd class="right">
                            <input class="u-radio u-light" name="sex" id="cb2" type="radio"<?php if($user['sex']=='F'): ?> checked<?php endif; ?> value="F">
                            <label class="u-btn" for="cb2"><img src="<?php echo $staticUrl;?>images/radio.png" /></label>
                        </dd>
                        
                        <span class="clear"></span>
                    </dl>
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


<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/edituser'};</script>