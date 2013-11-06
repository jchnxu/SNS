<?php
	function genImage($src, $href) {
	return '<a href="'.$href.'"><img src="'.$src.'" width=100,height=100 /></a>';
}

?>

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
         // page management
        $(function(){
            var page = "<?php echo $page; ?>";
            if (page != "") {
                loadSecondary(page);
            }    
        });
     
        function loadPrimary() {
            window.history.pushState("", "", "<?php echo base_url('home'); ?>");
            $("#secondary-page").hide();
            $("#primary-page").show();
        }
        function loadSecondary(page) {
            window.history.pushState("", "", "<?php echo base_url('home'); ?>/" + page);
            $("#primary-page").hide();
            $("#secondary-page").show();
            
            $.post(
                "<?php echo base_url('ajax/load_secondary'); ?>",
                {"page": page},
                function(data, status){
                    $("#secondary-page").html(data);
                }
            );
        }

       
        // controls
        $(function(){
            //$("#global-menu").menu();
            $("#streams-list").sortable({
                scroll: true,
                revert: true,
                placeholder: "stream-placeholder",
                axis: "x",
                tolerance: "pointer",
                handle: ".stream-title"
            });

            $("#add-sn-modal").dialog({
                autoOpen: false    
            });
            $("#add-sn-button").click(function(){
                $("#add-sn-modal").dialog("open");
            });

        });

        // authorization callbacks
        function weibo_auth_callback(msg) {
            alert(msg);
            // TODO refresh the default stream here
        }

        function renren_auth_callback(msg) {
            alert(msg);
            // TODO refresh the default stream here
        }

        // streams
        $(function(){
            change_stream_list_width(<?php echo count($stream_contents); ?>*400);
            $("#stream-add").hide();

        });
        function add_stream(account_id, stream_id) {
            
            $.post(
                "<?php echo base_url('home/add_stream'); ?>",
                {
                    "account_id": account_id,
                    "stream_id": stream_id
                },
                function(data, status){
                    change_stream_list_width(parseInt($("#streams-list").width) + 400);
                    $("#streams-list").prepend('<div class="stream">' + data + "</div>");
                }
            );
        }
        function remove_stream() {
        }
        function change_stream_list_width(width) {
            $("#streams-list").width(width);
        }

    </script>

</head>
<body>
    <div id="notification-popup"></div>
    <div id="modals">
        <div id="add-sn-modal" title="添加社交网站">
            <a target="_blank" onclick="window.open('<?php echo $auth_url['renren']; ?>');"><input type="button" name="renren" value="人人"/></a>
            <a target="_blank" onclick="window.open('https://api.weibo.com/oauth2/authorize?client_id=1401769607&amp;redirect_uri=http%3A%2F%2F127.0.0.1%2FSNS%2Fsns_authorize%2Fweibo_authorize&amp;response_type=code&amp;forcelogin=true','newwindow');"><input type="button" name="weibo" value="微博"/></a>
        </div>
    </div>
    <div id="container">
        <div id="global-nav">
            <ul id="global-menu" class="nav nav-tabs nav-stacked">
                <li><a onclick="$('#stream-add').toggle();" class="fui-plus"></a></li>
                <li><a onclick="loadSecondary('settings');" class="fui-gear"></a></li>
                <li><a onclick="loadPrimary();" class="fui-list"></a></li>
                <li><a onclick="loadSecondary('analysis');" class="fui-eye"></a></li>
                <li><a onclick="loadSecondary('contacts');" class="fui-user"></a></li>
                <li><a href="<?php echo base_url('login/do_logout'); ?>" class="fui-arrow-left"></a></li>
            </ul>
        </div>
        <div id="global-main">
            <div id="primary-page" class="page">
                <div id="streams-outer" class="">
                   <div id="stream-add">
                        <div class="">
                            <?php foreach ($add_stream_options as $add_stream_option): ?>
                            <div class="account-title">
                                <?php echo $add_stream_option['account']->account_id; ?>
                            </div>
                            <div class="account-stream-options">
                                <?php foreach ($add_stream_option['stream_options'] as $stream_option): ?>
                                <div class="stream-option">
                                    <a onclick="add_stream(<?php echo $add_stream_option['account']->account_id; ?>, <?php echo $stream_option->stream_id;?> );">
                                        <?php echo $stream_option->stream_name ?>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div id="streams-list-outer">
                        <ul id="streams-list" class="">
                            <?php foreach( $stream_contents as $stream_content ) : ?>
                            <li class="stream">
                                <div class="stream-title">
                                title
                                </div>
                                <?php foreach($stream_content['stream_items'] as $item):?>
                                <div><?php 
                            
                                        $updateTime = $item['created_at'];
                                        $actorName = $item['user']['screen_name'];
                                        $actorPhotoUrl = $item['user']['profile_image_url'];
                                        $imagesrc = '<img src =' . $actorPhotoUrl . ' />';
                                        $fowardPath = $item['text'];
                                        
                                        if(isset($item['retweeted_status'])){
                                            $item = $item['retweeted_status'];
                                        }
                                        $contenttitle = '<a href="http://www.weibo.com/u/'.$item['user']['idstr'].'">@'.$item['user']['name'].'</a>';
                                        $content = $item['text'];
                                        
                                        if(isset($item['bmiddle_pic'])){
                                            $contentImage = genImage($item['bmiddle_pic'],'#');
                                        }else if(isset($item['original_pic'])){
                                            $contentImage = genImage($f['original_pic'],'#');
                                        }else if(isset($item['thumbnail_pic'])) {
                                            $contentImage = genImage($item['thumbnail_pic'],'#');
                                        }else {
                                            $contentImage = '';
                                        }
                                        
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                        echo $imagesrc;
                                        echo $contenttitle;
                                        echo "<br>";
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                        echo $fowardPath;
                                        echo $content;
                                        echo "<br>";
                                        echo $contentImage;
                                        echo "<br>";
                                        echo $updateTime;
                                        echo "<br>";echo "<br>";?></div>
                                <br>
                                <?php endforeach;?>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
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
            <div id="secondary-page" class="page">
            </div>
        </div>

    </div>

</body>
</html>

