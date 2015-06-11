<!DOCTYPE html>
<html lang="zh"><!--用于2 3级绿色头的页面 -->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/user.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/base.css"/>
        <link rel="shortcut icon" href="<?php echo $attachUrl;?>favicon.ico" type="image/x-icon" />
		<script type="text/javascript">window.QY = {domain: {base: '<?php echo $baseUrl;?>', resource: '<?php echo $staticUrl;?>', attach: '<?php echo $attachUrl;?>'}, userInfo: {id: <?php echo empty($session['user_id'])?0:$session['user_id'];?>, name: '<?php echo empty($session["nick_name"])?null:$session["nick_name"];?>'}}</script>
		<title><?php echo $layout_for_title;?></title>
	</head>
	<body>
		<?php $this->load->view('_elements/simple_header');?>
		<div class="wrap wrap4">
			<?php $this->load->view($layout_for_content);?>
		</div>
		<?php $this->load->view('_elements/default_footer');?>
		<?php $this->load->view('_elements/resource_map'); ?>
	</body>
</html>