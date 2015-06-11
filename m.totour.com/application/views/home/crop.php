<style type="text/css">
	.crop-container{display:none;position:fixed;top:0;left:0;width:100%;height:100%;z-index:8;background-color:#000;}
	.crop-wrapper{position:relative;width:100%;height:100%;}
	.crop-dark{position:absolute;top:0;left:0;}
	.crop-cover{position:absolute;top:0;left:0;width:100%;height:100%;background-color:#000;opacity:0.75;}
	.crop-light{position:absolute;top:0;right:0;bottom:0;left:0;margin:auto;}
	.crop-bottom{height:30px;line-height:30px;background-color:#0F0F0F;}
	.crop-bottom-item{display:inline-block;padding:0 16px;color:#D7D7D7;}
	.crop-bottom-confirm{float:right;}
	.crop-bottom-cancel{}
</style>

<input type="file">

<div id="crop_container" class="crop-container">
	<div id="crop_wrapper" data-image="<?php echo $baseUrl;?>static/demo/avatar3.jpg" class="crop-wrapper">
		<canvas id="crop_dark" class="crop-dark"></canvas>
		<div class="crop-cover"></div>
		<canvas id="crop_light" class="crop-light"></canvas>
	</div>
	<div id="crop_bottom" class="crop-bottom">
		<a id="crop_confirm" class="crop-bottom-item crop-bottom-confirm" href="javascript:;">选取</a>
		<a id="crop_cancel" class="crop-bottom-item crop-bottom-cancel" href="javascript:;">取消</a>
	</div>
</div>

<script type="text/javascript">var REQUIRE = {MODULE: 'widget/crop'};</script>