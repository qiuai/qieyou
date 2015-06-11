<h3 class="headline">订单中心</h3>
<div class="filter mb20">
    <div class="form p10-20">
        <label><input type="radio" name="search" value="1" />交易时间</label>：
        <label><input type="text" onfocus="WdatePicker({doubleCalendar:true,minDate:'%y-%M-%d'})" name="startTime" title="请选择开始日期" class="Wdate"></label>
        <span class="mr10">至</span>
        <label><input type="text" onfocus="WdatePicker({doubleCalendar:true,minDate:'%y-%M-%d'})" name="startTime" title="请选择结束日期" class="Wdate"></label>
        <label><input type="radio" name="search" value="2" />订单号</label>：
        <label><input type="text" value="" class="w200" name="name" placeholder="订单号码" /></label>
        <label><input type="radio" name="search" value="3" />手机号</label>：
        <label><input type="text" value="" class="w200" name="name" placeholder="联系人手机号码" /></label>
        <label><input class="buttonG" type="submit" value="查找" /></label>
    </div>

</div>
<div class="tab">
	<ul class="clearfix">
		<li class="current"><a href="javascript:void(0)">全部订单</a></li>
		<li><a href="javascript:void(0)">已支付订单</a></li>
		<li><a href="javascript:void(0)">已完成订单</a></li>
		<li><a href="javascript:void(0)">待退款订单</a></li>
		<li><a href="javascript:void(0)">已退款订单</a></li>
	</ul>
</div>
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp4"/>
        <col class="wp10"/>
        <col class="wp10"/>
        <col class="wp20"/>
        <col class="wp16"/>
        <col class="wp8"/>
        <col class="wp8"/>
        <col class="wp8"/>
        <col class="wp8"/>
        <col class="wp8"/>
    </colgroup>
    <thead>
    <tr>
        <th>序号</th>
        <th>订单号</th>
        <th>交易时间</th>
        <th>商品名称</th>
        <th>入住/出行时间</th>
        <th>单价</th>
        <th>数量</th>
        <th>订单总额</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td rowspan="3">1</td>
        <td rowspan="3">D000001</td>
        <td rowspan="3">2013-05-23 11:35:50</td>
        <td class="tl">
            <a href="#">丽江八十八号驿栈订房</a>
            <span class="c999">大床房</span>

        </td>
        <td>04-14入住 04-15日离店</td>
        <td><cite>¥99.00</cite></td>
        <td>2</td>
        <td rowspan="3"><em>¥198.00</em></td>
        <td rowspan="3"><span class="finish">已支付</span></td>
        <td rowspan="3"><a href="orderDetail.html" target="_blank">查看详情</a></td>
    </tr>
    <tr>
        <td class="tl">
            <a href="#">昆明机场-古城大巴</a>
            <span class="c999">往返</span>
        </td>
        <td>04-14 14:00出发</td>
        <td><cite>¥99.00</cite></td>
        <td>2</td>
    </tr>
    <tr>
        <td class="tl">
            <a href="#">游洱海</a>
            <span class="c999">单车套餐</span>
        </td>
        <td>04-14 14:00出发</td>
        <td><cite>¥99.00</cite></td>
        <td>2</td>
    </tr>
    <tr>
        <td>2</td>
        <td>D000004</td>
        <td>2013-05-23 11:35:50</td>
        <td class="tl">
            <a href="#">丽江八十八号驿栈订房</a>
            <span class="c999">大床房</span>
        </td>
        <td>04-14 09：00 发车</td>
        <td><cite>¥99.00</cite></td>
        <td>2</td>
        <td><em>¥198.00</em></td>
        <td><span class="error">已完成</span></td>
        <td><a href="orderDetail.html" target="_blank">查看详情</a></td>
    </tr>
    <tr>
        <td>3</td>
        <td>D000005</td>
        <td>2013-05-23 11:35:50</td>
        <td class="tl">
            <a href="#">丽江八十八号驿栈订房</a>
            <span class="c999">大床房</span>
        </td>
        <td>2013-07-23 11:35:50</td>
        <td><cite>¥99.00</cite></td>
        <td>2</td>
        <td><em>¥198.00</em></td>
        <td><span class="wait">未结算</span></td>
        <td><a href="orderDetail.html" target="_blank">查看详情</a></td>
    </tr>
    <tr>
        <td>4</td>
        <td>D000006</td>
        <td>2013-05-23 11:35:50</td>
        <td class="tl">
            <a href="#">丽江八十八号驿栈订房</a>
            <span class="c999">大床房</span>
        </td>
        <td>2013-07-23 11:35:50</td>
        <td><cite>¥99.00</cite></td>
        <td>2</td>
        <td><em>¥198.00</em></td>
        <td><span class="finish">已结算</span></td>
        <td><a href="orderDetail.html" target="_blank">查看详情</a></td>
    </tr>
    </tbody>
</table>
<!--分页样式开始-->
<div class="pageBar clearfix">
	<p>共<em>90</em>条 记录， 每页显示<em>5</em>条</p>
	<div class="pages fr">
		<span>« Prev</span>
		<span class="current">1</span>
		<a href="#">2</a>
		<a href="#">3</a>
		<span>...</span>
		<a href="#">14</a>
		<a href="#">15</a>
		<a href="#">16</a>
		<a href="#">Next »</a>
	</div>
</div>
<!--分页样式结束-->
<!--Date Picker-->
<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js" charset="utf-8"></script>
