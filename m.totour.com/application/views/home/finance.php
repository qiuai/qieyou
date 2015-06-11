<div class="money">
    <dl>
        <dt>总资产：</dt>
        <dd>¥<?php echo $inn['account'];?></dd>
    </dl>
    <dl class="bordernone">
        <dt>可提取金额：</dt>
        <dd class="green">¥<?php echo $inn['account']-$inn['withdrawing'];?></dd>
    </dl>
</div>
<div id="finance_list">
    <!-- <div class="month">
        <div class="month-tit">
            <div class="left">
                <dl>
                    <dt><span>09</span>月</dt>
                    <dd>09.01－09.30</dd>
                </dl>
            </div>
            <div class="right">
                <dl>
                    <dt>1238.00</dt>
                    <dd>收入</dd>
                </dl>
            </div>
            <div class="right">
                <dl>
                    <dt>1238.00</dt>
                    <dd>支出</dd>
                </dl>
            </div>
        </div>
        <div class="month-list">
            <dl>
                <dt><font>25</font>周三<span class="icon"><img src="<?php echo $staticUrl;?>images/zhi.png" /></span></dt>
                <dd class="right">
                    <ul>
                        <li class="left"><font>玫瑰花茶玫茶</font><span>订单编号</span></li>
                        <li class="right green">135.00</li>
                    </ul>
                </dd>
            </dl>
            <dl>
                <dt><font>25</font>周三<span class="icon"><img src="<?php echo $staticUrl;?>images/shou.png" /></span></dt>
                <dd class="right">
                    <ul>
                        <li class="left"><font>玫瑰花茶</font><span>订单编号</span></li>
                        <li class="right red">135.00</li>
                    </ul>
                </dd>
            </dl>
            <dl>
                <dt><font>25</font>周三<span class="icon"><img src="<?php echo $staticUrl;?>images/zhi.png" /></span></dt>
                <dd class="right">
                    <ul>
                        <li class="left"><font>玫瑰花茶玫瑰花茶</font><span>订单编号</span></li>
                        <li class="right green">135.00</li>
                    </ul>
                </dd>
            </dl>
            <dl>
                <dt><font>25</font>周三<span class="icon"><img src="<?php echo $staticUrl;?>images/zhi.png" /></span></dt>
                <dd class="right">
                    <ul>
                        <li class="left"><font>玫瑰花茶玫瑰花茶玫瑰花茶玫瑰花茶玫瑰花茶</font><span>订单编号</span></li>
                        <li class="right green">135.00</li>
                    </ul>
                </dd>
            </dl>
        </div>
    </div>
    <div class="month">
        <div class="month-tit">
            <div class="left">
                <dl>
                    <dt><span>08</span>月</dt>
                    <dd>09.01－09.30</dd>
                </dl>
            </div>
            <div class="right">
                <dl>
                    <dt>1238.00</dt>
                    <dd>收入</dd>
                </dl>
            </div>
            <div class="right">
                <dl>
                    <dt>1238.00</dt>
                    <dd>支出</dd>
                </dl>
            </div>
        </div>
        <div class="month-list">
            <dl>
                <dt><font>25</font>周三<span class="icon"><img src="<?php echo $staticUrl;?>images/shou.png" /></span></dt>
                <dd class="right">
                    <ul>
                        <li class="left"><font>玫瑰花茶花茶</font><span>订单编号</span></li>
                        <li class="right red">135.00</li>
                    </ul>
                </dd>
            </dl>
            <dl>
                <dt><font>25</font>周三<span class="icon"><img src="<?php echo $staticUrl;?>images/shou.png" /></span></dt>
                <dd class="right">
                    <ul>
                        <li class="left"><font>玫瑰花茶玫瑰花茶玫花茶</font><span>订单编号</span></li>
                        <li class="right red">135.00</li>
                    </ul>
                </dd>
            </dl>
            <dl>
                <dt><font>25</font>周三<span class="icon"><img src="<?php echo $staticUrl;?>images/zhi.png" /></span></dt>
                <dd class="right">
                    <ul>
                        <li class="left"><font>玫瑰花茶玫瑰花玫瑰花茶</font><span>订单编号</span></li>
                        <li class="right green">135.00</li>
                    </ul>
                </dd>
            </dl>
            <dl>
                <dt><font>25</font>周三<span class="icon"><img src="<?php echo $staticUrl;?>images/zhi.png" /></span></dt>
                <dd class="right">
                    <ul>
                        <li class="left"><font>玫瑰花茶玫瑰花茶玫瑰花茶玫瑰花茶玫瑰花茶</font><span>订单编号</span></li>
                        <li class="right green">135.00</li>
                    </ul>
                </dd>
            </dl>
        </div>
    </div> -->
</div>
<div id="load_more_icon" class="loading-icon"><img src="<?php echo $staticUrl; ?>images/loading.gif" alt="Loading..."></div>
<a id="load_more_btn" data-type="" data-page="" href="javascript:;" class="load-more" style="display: none;">加载更多...</a>
<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/finance'}</script>