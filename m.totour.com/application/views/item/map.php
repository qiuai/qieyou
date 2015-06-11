<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
		body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;}
		#golist {display: none;}
		@media (max-device-width: 780px){#golist{display: block !important;}}
	</style>
	<script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&ak=zxC49c0iAk2XD1FlRGonHqKZ&v=1.0"></script>
	<title><?php echo $product['inn_name'];?></title>
</head>
<body>
	<div id="allmap"></div>
</body>
</html>
<script type="text/javascript">
	// 百度地图API功能		
	var map = new BMap.Map("allmap");        
	map.centerAndZoom(new BMap.Point(<?php echo $product['bdgps'];?>),15);

	map.addControl(new BMap.ZoomControl());  //添加地图缩放控件
	var marker1 = new BMap.Marker(new BMap.Point(<?php echo $product['bdgps'];?>));  //创建标注
	map.addOverlay(marker1);                 // 将标注添加到地图中
	//创建信息窗口
	var infoWindow1 = new BMap.InfoWindow("<?php echo $product['product_name'];?>");
	marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});
	marker1.openInfoWindow(infoWindow1); //开启信息窗口
</script>