<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Example Page</title>
	<link href="css/normalize.css" rel="stylesheet">
	<link href="css/login.css" rel="stylesheet">
	<link href="css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.js"></script>
    
    <script>
        $(function(){
        });
    </script>

</head>
<body>
    <div id="notification-popup"></div>
    <div id="container">
        <div id="forms-wrapper">
            <div id="login-form-wrapper">
                <form action="<?php echo base_url('login/do_login'); ?>" method="post">
                    <input type="text" name="email_address" id="email_address" placeholder="用户名" />
                    <input type="password" name="password" id="password" placeholder="密码" />
                    <input type="submit" id="login-in" value="登陆"/>
                    <button id="reigister" >注册</button>
                </form>
            </div>
            <div id="register-form-wrapper">
                <form action="<?php echo base_url('login/do_register'); ?>" method="post">
                    <input type="text" name="username" id="username" placeholder="用户名" />
                    <input type="password" name="username" id="username" placeholder="密码" />
                    <input type="password" name="username" id="username" placeholder="重复密码" />
                    <input type="submit" value="注册"/>
                </form>
            </div>
        </div>
        <div id="footer">
        </div>
    </div>

</body>
</html>


