<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Example Page</title>
	<link href="<?php echo base_url(); ?>css/normalize.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/flat-ui.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/dashboard.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/secondary.css" rel="stylesheet">
	<script src="<?php echo base_url(); ?>js/jquery-1.9.1.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery-ui-1.10.3.custom.js"></script>
    
    <script>
        
        // controls
        $(function(){
            //$("#global-menu").menu();
            $("#streams-list").sortable({
                scroll: true,
                revert: true,
                placeholder: "stream-placeholder",
                axis: "x",
                tolerance: "pointer"
            });

            $("#add-sn-modal").dialog({
                autoOpen: false    
            });
            $("#add-sn-button").click(function(){
                $("#add-sn-modal").dialog("open");
            });

        });

        // content management
        $(function(){
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
        function loadSecondary(content) {
            window.history.pushState("", "", "<?php echo base_url('home'); ?>/" + content);
            $("#primary-content").hide();
            $("#secondary-content").show();
            
            $.post(
                "<?php echo base_url('ajax/load_secondary'); ?>",
                {"content": content},
                function(data, status){
                    $("#secondary-content").html(data);
                }
            );
        }
    </script>

</head>
<body>
    <div id="notification-popup"></div>
    <div id="modals">
        <div id="add-sn-modal" title="添加社交网站">
            <button>人人</button>
            <button>微博</button>
        </div>
    </div>
    <div id="container">
        <div id="global-nav">
            <ul id="global-menu" class="nav nav-tabs nav-stacked">
                <li><a onclick="loadSecondary('settings');" class="fui-gear"></a></li>
                <li><a onclick="loadPrimary();" class="fui-list"></a></li>
                <li><a onclick="loadSecondary('analysis');" class="fui-eye"></a></li>
                <li><a onclick="loadSecondary('contacts');" class="fui-user"></a></li>
                <li><a href="<?php echo base_url('login/do_logout'); ?>" class="fui-arrow-left"></a></li>
            </ul>
        </div>
        <div id="global-main">
            <div id="primary-content" class="content">
                <div id="streams-outer" class="">
                   <div id="stream-add">
                        <div class="">
                        </div>
                    </div>
                    <ul id="streams-list" class="">
                        <li class="stream">content1</li>
                        <li class="stream">content2</li>
                        <li class="stream">content3</li>
                    </ul>
                    <div id="streams-panel">
                        <div class="left">
                            <input type="text" class="form-control" placeholder="发布状态">
                        </div>
                        <div class="right">
                            <div id="search-bar" class="inline-block">
                                搜索
                            </div>
                            <div id="add-sn-button" class="btn">添加社交网站</div>
                            <div id="add-stream-button" class="btn">添加流</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="secondary-content" class="content">
            </div>
        </div>

    </div>

</body>
</html>

