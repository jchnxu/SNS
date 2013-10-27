<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Example Page</title>
	<link href="<?php echo base_url('css/normalize.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/register_ok.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/smoothness/jquery-ui-1.10.3.custom.css'); ?>" rel="stylesheet">
	<script src="<?php echo base_url('js/jquery-1.9.1.js') ?>"></script>
	<script src="<?php echo base_url('js/jquery-ui-1.10.3.custom.js'); ?>"></script>
    
    <script>
        $(function(){
        });
    </script>

</head>
<body>
    <div id="notification-popup"></div>
    <div id="container">
        <?php echo "注册成功 " . $email; ?>
        <div id="footer">
        </div>
    </div>

</body>
</html>


