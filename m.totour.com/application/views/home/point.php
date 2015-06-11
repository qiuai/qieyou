<div class="point">积分<span><?php echo $user['point'];?></span></div>
<div class="linklist"><a href="<?php getUrl('userJifenList');?>"><img alt="" src="<?php echo $staticUrl;?>images/list.png"/>积分明细</a><a href="/help/jifen"><img alt="" src="<?php echo $staticUrl;?>images/wen.png"/>积分规则</a></div>
<div class="change">
    <div class="pointtit"><img alt="" src="<?php echo $staticUrl;?>images/point.png"/>积分兑换</div>
    <?php if($quan):?>
    <?php foreach($quan as $key => $row):?>
    <ul<?php if(!empty($row['is_get'])):?> class="graybg"<?php elseif($row['total']-$row['quantity']<1): ?> class="graybg"<?php endif; ?>>
        <li class="qleft">￥<span><?php echo $row['amount'];?></span><br/>
            代金券</li>
        <li class="qright">全场通用<span>有效期：<?php echo date('Y-m-d',$row['start_time']);?>至<?php echo date('Y-m-d',$row['end_time']);?></span>
            <?php if(!empty($row['is_get'])):?>
            <a href="javascript:void(0)">已领取</a>
            <?php else:?>
            <a href="javascript:void(0)" <?php if($row['total']-$row['quantity']>0) echo 'node-type="get" data-qid="'.$row['quan_id'].'"'?>> <img alt="" src="<?php echo $staticUrl;?>images/point<?php if(!empty($row['is_get'])):?>4<?php elseif($row['total']-$row['quantity']<1): ?>4<?php else: ?>3<?php endif; ?>.png"/><?php echo $row['require'];?>积分兑换</a>
            <?php endif;?>
        </li>
    </ul>
    <?php endforeach;?>
    <?php endif;?>
   <!-- <ul class="graybg">
        <li class="qleft">￥<span>1.00</span><br/>
            代金券</li>
        <li class="qright">全场通用<span>有效期2015-02-03：至2015-02-03</span> <a href="javascript:void(0)" > <img alt="" src="<?php echo $staticUrl;?>images/point4.png"/>积分兑换</a> </li>
    </ul>
    <ul>
        <li class="qleft">￥<span>100.00</span><br/>
            代金券</li>
        <li class="qright">全场通用<span>有效期：至</span> <a href="javascript:void(0)" > <img alt="" src="<?php echo $staticUrl;?>images/point3.png"/>积分兑换</a> </li>
    </ul>-->
</div>

<script type="text/javascript">var REQUIRE = {MODULE: 'page/home/coupon'};</script>