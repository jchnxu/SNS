<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Example Page</title>
	<link href="css/normalize.css" rel="stylesheet">
	<link href="css/all.css" rel="stylesheet">
	<link href="css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.js"></script>
    
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
        });
    </script>

</head>
<body>
    <div id="notification-popup"></div>
    <div id="container">
        <div id="global-header">
            <div id="header-left">
                <input type="text" placeholder="发布状态">
            </div>
            <div id="header-right">
                <div id="search-bar">
                    搜索
                </div>
            </div>
        </div>
        <div id="global-nav">
            <ul id="global-menu">
                <li><a href="#">设置</a></li>
                <li><a href="#">社交</a></li>
                <li><a href="#">分析</a></li>
                <li><a href="#">联系人</a></li>
            </ul>
        </div>
        <div id="global-main">
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
    </div>

</body>
</html>

