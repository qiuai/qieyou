<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?php echo $layout_for_title;?></title>
		<link href="<?php echo $staticUrl;?>styles/manage.css?v=<?php echo $staticVer;?>" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery-1.11.2.min.js?v=<?php echo $staticVer;?>"></script>
		<script type="text/javascript" src="<?php echo $staticUrl;?>js/common.js?v=<?php echo $staticVer;?>"></script>
		<script type="text/javascript" src="<?php echo $staticUrl;?>js/layer.min.js?v=<?php echo $staticVer;?>"></script>
		<link rel="shortcut icon" href="<?php echo $staticUrl;?>favicon.ico" type="image/x-icon" />
	</head>
	<body>
		<?php $this->load->view($layout_for_content); ?>
	</body>
</html>