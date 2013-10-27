<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Example Page</title>
	<link href="<?php echo base_url(); ?>css/normalize.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/all.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<script src="<?php echo base_url(); ?>js/jquery-1.9.1.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery-ui-1.10.3.custom.js"></script>
    
    <script>
        $(function(){
            $("#global-menu").menu();
            $("#streams-list").sortable({
                scroll: true,
                revert: true,
                placeholder: "stream-placeholder",
                axis: "x",
                tolerance: "pointer"
            });

            var content = "<?php echo $content; ?>";
            if (content != "") {
                loadSecondary(content);
            }
        });

        function loadPrimary() {
            window.history.pushState("", "", "<?php echo base_url('home'); ?>");
            $("#secondary-content").hide();
            $("#primary-content").show();
        }
        function loadSecondary(name) {
            window.history.pushState("", "", "<?php echo base_url('home'); ?>/" + name);
            $("#primary-content").hide();
            $("#secondary-content").show();
        }
    </script>

</head>
<body>
    <div id="notification-popup"></div>
    <div id="container">
        <div id="global-nav">
            <ul id="global-menu">
                <li><a onclick="loadSecondary('settings');">设置</a></li>
                <li><a onclick="loadPrimary();">社交</a></li>
                <li><a onclick="loadSecondary('analysis');">分析</a></li>
                <li><a onclick="loadSecondary('contacts');">联系人</a></li>
                <li><a href="<?php echo base_url('login/do_logout'); ?>">登出</a></li>
            </ul>
        </div>
        <div id="global-main">
            <div id="primary-content" class="content">
                <div id="streams-outer" class="">
                    <div id="streams-panel">
                    </div>
                    <ul id="streams-list" class="">
                        <li class="stream">content1</li>
                        <li class="stream">content2</li>
                        <li class="stream">content3</li>
                        <li class="stream">content4</li>
                        <li class="stream">content5</li>
                    </ul>
                </div>
            </div>
            <div id="secondary-content" class="content">
            </div>
        </div>
        <div id="global-footer">
            <div id="footer-left">
                <input type="text" placeholder="发布状态">
            </div>
            <div id="footer-right">
                <div id="search-bar">
                    搜索
                </div>
            </div>
        </div>

    </div>

</body>
</html>
