<!DOCTYPE html>
<html lang="zh">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/homepage.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/home.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $staticUrl;?>css/base.css"/>
	<link rel="shortcut icon" href="<?php echo $attachUrl;?>favicon.ico" type="image/x-icon" />
	<script type="text/javascript">window.QY = {domain: {base: '<?php echo $baseUrl;?>', resource: '<?php echo $staticUrl;?>', attach: '<?php echo $attachUrl;?>'}, userInfo: {id: <?php echo empty($session['user_id'])?0:$session['user_id'];?>, name: '<?php echo empty($session["nick_name"])?null:$session["nick_name"];?>'}}</script>
	<title><?php echo $layout_for_title;?></title>
	</head>
	<body>
		<div class="frameMain wrap3">
			<?php $this->load->view($layout_for_content); ?>
		</div>

		<?php $this->load->view('_elements/resource_map'); ?>
		<?php $this->load->view('_elements/homepage_footer'); ?>
	</body>
</html>