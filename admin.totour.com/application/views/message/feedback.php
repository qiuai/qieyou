<h3 class="headline">意见反馈
<span class="more"></h3>
<div class="filter mb20">
    <form method="get">
    <table class="form table-form">
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
        <tbody>
        <tr> 
            <td>     
             创建时间：<label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick

('st'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $starttime?date('Y-m-d',

$starttime):'';?>" name="starttime" title="请选择开始日期" id="startTime" class="Wdate" 

readOnly></label>
               <span class="mr10">至</span>
               <label><input type="text" onFocus="WdatePicker({onpicked:WdataOnPick

('ed'),doubleCalendar:true,maxDate:'%y-%M-%d'})" value="<?php echo $endtime?date('Y-m-d',

$endtime):'';?>" name="endtime" title="请选择结束日期" id="endTime" class="Wdate" readOnly></label>
               <label><input type="submit" value="查询" id="setTimeButton" 

class="buttonG"/></label>
                <div class="tips tips-ok" style="display: none;" id="timeTips"><i class="tips-

ico"></i><p></p></div>
            </td>
        </tr>
        </tbody>
    </table>
    </form>
</div>
<table class="orderList table table-border table-odd userLog">
    <colgroup>
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">
        <col class="wp5">   
    </colgroup>
    <thead>
    <tr>
        <th>用户ID</th>
        <th>反馈时间</th>
        <th>反馈内容</th>
        <th>图片</th>
        <th>客户端</th>
        <th>版本</th>
        <th>操作</th>  
    </tr>
    </thead>
    <tbody>
	<?php if($data):?>
	<?php foreach($data as $key => $val):?>
	<tr>	
		<td><?php echo $val['user_name']?></td>
		<td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td> 
		<td><?php echo $val['note']?></td>
		<td> <?php if($val['imgs']){
			$imgs=explode(',',$val['imgs']);
			foreach($imgs as $k=>$v){
			?>
            <a href="<?php echo $staticUrl.$v;?>" data-lightbox="roadtrip">
            <img height="80" width="80" class="img_small"  src="<?php echo $staticUrl.$v;?>" />
            </a>
			<?php }
			}?>
        </td>
        <td><?php echo $val['client']?></td>
		<td><?php echo $val['version']?></td>
        <td><a href="javascript:void(0);" onclick="is_del(<?php echo $val['feed_id'];?>)">删除</a>
</td>	
	</tr>
	<?php endforeach;?>
	<?php endif;?>
    </tbody>
</table>

<!--分页样式开始-->
<div class="pageBar clearfix">
    <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo

['perpage']?></em>条</p>
    <div class="pages fr" id="page">
    </div>
</div>
<!--分页样式结束-->

<script type="text/javascript" src="<?php echo $staticUrl;?>js/DatePicker/WdatePicker.js"></script>    
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/lightbox.min.js"></script>
<link href="<?php echo $staticUrl;?>css/lightbox.css" rel="stylesheet" />
<script type="text/javascript">
    $(function(){	
        var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo

['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
        $('#page').html(page.createPage());
    })
	function is_del(feed_id){
		var msg = "确定要删除吗？"; 
		if (confirm(msg)==true){ 
			$.ajax({
				type:"POST",
				dataType: "json",
				url: "<?php echo $baseUrl.'message/is_del_feedback';?>",
				data: {feed_id:feed_id},
				success: function(data){
					if(data.code == 1){
						setTimeout(function(){
						 window.location.href='<?php echo 

$baseUrl.'message/feedback';?>';
						},500);
					}else{
						
					}
				}
			})
		}
	}
	/*
	function showBigImg(self){
		var conf = {};
		$.getJSON('ajax地址', {}, function(json){
			conf.photoJSON = json; //保存json，以便下次直接读取内存数据
			layer.photos({
				html: '这里传入自定义的html，也可以不用传入（这意味着不会输出右侧区域）。相册支持左右方向键、Esc关闭',
				json: json
			});
		});
		$.getJSON('/jquery/layer/test/photos.json', function(json){
			layer.photos({
				photos: json
			});
		}); 
	}
	*/
	function showBigImg(self){
		$img=$(self).attr('src');
		$("#tong").find('img').attr('src',$img);
		var i = $.layer({
			type : 1,
			title : false,
			fix : false,
			offset:['150px' , ''],
			area : ['800px','600px'],
			page : {dom : '#tong'}
		});
	}
</script>
