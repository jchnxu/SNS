<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Example Page</title>
	<link href="<?php echo base_url('css/normalize.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/bootstrap.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/flat-ui.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/smoothness/jquery-ui-1.10.3.custom.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/login.css'); ?>" rel="stylesheet">
	<script src="<?php echo base_url('js/jquery-1.9.1.js') ?>"></script>
	<script src="<?php echo base_url('js/jquery-ui-1.10.3.custom.js'); ?>"></script>
    
    <script>
        $(function(){
            $("#show-login-form").click(function() {
                $("#login-form-wrapper").show();
                $("#register-form-wrapper").hide();
            });
            $("#show-register-form").click(function() {
                $("#login-form-wrapper").hide();
                $("#register-form-wrapper").show();
            });

            content = "<?php if (isset($content)) echo $content; ?>";
            if (content == 'login' || content == '') {
                // no status, login fail or register ok, show line login form
                $("#show-login-form").trigger("click");
            }
            else {
                // reigister fail, show register form
                $("#show-register-form").trigger("click");
            }
            
        });
    </script>

</head>
<body>
    <div id="notification-popup"></div>
    <div id="container">
        <div id="forms-wrapper">
            <div id="login-form-wrapper">
                <div id="login-messages" class="messages">
                    <?php if (isset($login_message)) echo $login_message; ?>
                </div>
                <form action="<?php echo base_url('login/do_login'); ?>" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="email_address" id="email_address" value="<?php echo set_value('email_address'); ?>" placeholder="邮箱" />
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" id="password" value="" placeholder="密码" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn form-control" id="login-in" value="登陆"/>
                    </div>
                </form>
            </div>
            <div id="register-form-wrapper">
                <div id="register-messages" class="messages">
                    <?php if (isset($register_message)) echo $register_message; ?>
                </div>
                <form action="<?php echo base_url('login/do_register'); ?>" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="email_address" id="email_address" value="<?php echo set_value('email_address'); ?>" placeholder="邮箱" />
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" id="password" value="<?php echo set_value('password'); ?>" placeholder="密码" />
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password_confirm" id="password_confirm" value="<?php echo set_value('password_confirm'); ?>" placeholder="重复密码" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn form-control" value="注册"/>
                    </div>
                </form>
            </div>
        </div>
        <div id="footer">
            <div id="show-login-form" class="inline-block">
                <span class="fui-lock tab-button"></span>
            </div>
            <div id="show-register-form" class="inline-block">
                <span class="fui-user tab-button"></span>
            </div>
        </div>
    </div>

</body>
</html>


