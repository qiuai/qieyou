<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?php echo $layout_for_title;?></title>
		<link href="<?php echo $staticUrl;?>styles/manage.css?v=<?php echo $staticVer;?>" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery-1.11.2.min.js?v=<?php echo $staticVer;?>"></script>
        <script type="text/javascript" src="<?php echo $staticUrl;?>js/layer.min.js?v=<?php echo $staticVer;?>"></script>
		<script type="text/javascript" src="<?php echo $staticUrl;?>js/common.js?v=<?php echo $staticVer;?>"></script>
        <link rel="shortcut icon" href="<?php echo $staticUrl;?>favicon.ico" type="image/x-icon" />
		<script type="text/javascript">
            //定义URL全局变量
            var staticUrl = '<?php echo $staticUrl;?>';
            var baseUrl = '<?php echo $baseUrl;?>';
        </script>
	</head>

	<body>
        <div class="frame clearfix">
            <?php $this->load->view('_elements/header'); ?>
            <div class="frameDown">
				<?php $this->load->view('_elements/slider'); ?>
                <div class="frameMain">
					<?php $this->load->view($layout_for_content); ?>
                </div>
            </div>
			<?php $this->load->view('_elements/footer'); ?>
        </div>

	</body>

</html>
