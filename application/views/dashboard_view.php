<?php
	function genImage($src, $href) {
    	return '<a href="'.$href.'"><img src="'.$src.'" width=100,height=100 /></a>';
    }
    
    function stream_items($raw, $social_name) {
        $result = '';
        if ($social_name = 'Renren') {
            foreach ($raw as $item) {
                $result .= '<div class="stream-item row-fluid">';
                
                // item left
                $result .= '<div class="item-left span2">';
                $result .= '<div class="owner-avatar row-fluid"><img class="owner-avatar-img" src="' . $item['sourceUser']['avatar'][0]['url'] . '"/></div>';
                $result .= '<div class="owner-name row-fluid">' . $item['sourceUser']['name'] . '</div>';
                $result .= '</div>';
                
                // item main
                $result .= '<div class="item-main span10">';
                $result .= '<div class="item-time">' . $item['time'] . '</div>';
                $is_foward = $item['type'][0] == 'S'; // type begins with "SHARE"
                if ($is_foward) {
                    $result .= '<div class="item-message">';
                    $result .= $item['message'];
                    $result .= '</div>';
                }
                
                if ($is_foward) {
                    $result .= '<div class="item-content foward">';
                }
                else {
                    $result .= '<div class="item-content">';
                }
                if (!empty($item['thumbnailUrl']))
                    $result .= '<div class="item-thumb"><img src="' . $item['thumbnailUrl'] . '"/></div>'; 
                $result .= '<div class="item-title">' . $item['resource']['title'] . '</div>';
                $result .= '<div class="item-message">' . $item['resource']['content'] . '</div>';
                $result .= '<div class="item-attachment"></div>';
                $result .= '<div class="item-operations">
                                <div class="typcn typcn-thumbs-up operation-btn"></div>
                                <div class="typcn typcn-flow-children operation-btn"></div>
                                <div class="typcn typcn-arrow-back-outline operation-btn"></div>
                            </div>';
                $result .= '<div class="item-comments"></div>';
                $result .= '</div>'; // end of item-content
                
                $result .= '</div>'; // end of item-main
                
                $result .= '</div>'; // end of stream-item
            }    
        }
        return $result;
    }
?>

<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Example Page</title>
	<link href="<?php echo base_url(); ?>css/normalize.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/flat-ui.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/typicons.css" rel="stylesheet">
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
            //$("#sn-list").menu();
            $("#sn-list>li").click(function(){
                $(this).find("ul").slideToggle(300);
            });
            $("#sn-list>li ul").hide();
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
        
        function douban_auth_callback(msg) {
            alert(msg);
            // TODO refresh the default stream here
        }
        
        function txweibo_auth_callback(msg) {
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
            $("#streams-list").outerWidth(width);
        }

    </script>

</head>
<body>
    <div id="notification-popup"></div>
    <div id="modals">
        <div id="add-sn-modal" title="添加社交网站">
            <a target="_blank" onclick="window.open('<?php echo $auth_url['renren']; ?>');"><input type="button" name="renren" value="人人"/></a>
            <a target="_blank" onclick="window.open('https://api.weibo.com/oauth2/authorize?client_id=1401769607&amp;redirect_uri=http%3A%2F%2F127.0.0.1%2FSNS%2Fsns_authorize%2Fweibo_authorize&amp;response_type=code&amp;forcelogin=true','newwindow');"><input type="button" name="weibo" value="新浪微博"/></a>
            <a target="_blank" onclick="window.open('https://www.douban.com/service/auth2/auth?client_id=0b412ba5398070c021c382efac815240&amp;redirect_uri=http%3A%2F%2F127.0.0.1%2FSNS%2Fsns_authorize%2Fdouban_authorize&amp;response_type=code&amp;scope=shuo_basic_r,shuo_basic_w,douban_basic_common,douban_basic_note&amp;forcelogin=true','newwindow');"><input type="button" name="weibo" value="豆瓣"/></a>
            <a target="_blank" onclick="window.open('https://open.t.qq.com/cgi-bin/oauth2/authorize?client_id=801440907&redirect_uri=http%3A%2F%2F127.0.0.1%2FSNS%2Fsns_authorize%2Ftxweibo_authorize&response_type=code&amp;forcelogin=true','newwindow');"><input type="button" name="weibo" value="腾讯微博"/></a>
        </div>
    </div>
    <div id="container">
        <div id="global-nav">
            <ul id="global-menu" class="nav nav-tabs nav-stacked">
                <li><a onclick="loadSecondary('settings');" class="typcn typcn-spanner-outline"></a></li>
                <li><a onclick="loadPrimary();" class="typcn typcn-home-outline"></a></li>
                <li><a onclick="loadSecondary('analysis');" class="typcn typcn-eye-outline"></a></li>
                <li><a onclick="loadSecondary('contacts');" class="typcn typcn-contacts"></a></li>
                <li><a href="<?php echo base_url('login/do_logout'); ?>" class="typcn typcn-power-outline"></a></li>
            </ul>
        </div>
        <div id="global-line">
        </div>
        <div id="global-main">
            <div id="primary-page" class="page">
                <div id="streams-outer" class="">
                
                    <div id="streams-panel">
                        <div id="publish-panel">
                            <input id="status-input" class="form-control" type="text" placeholder="我想说。。。"/>
                            <div id="public-status-button" class="typcn typcn-media-play-outline icon-btn"></div>
                        </div>
                    
                        <div id="platforms-panel">
                            <div id="add-sn-button" class="my-btn my-btn-info">
                                添加社交网络
                            </div>
                            <ul id="sn-list" class="">
                                <?php foreach ($add_stream_options as $add_stream_option): ?>
                                <li>
                                    <a href="#" class="account-title my-btn my-btn-outline">
                                        <img src="<?php echo $add_stream_option['account']->avatar_url?>" />
                                        <?php echo $add_stream_option['account']->sn_name; ?>
                                    </a>
                                    <ul class="account-stream-options">
                                        <?php foreach ($add_stream_option['stream_options'] as $stream_option): ?>
                                        <li class="stream-option">
                                            <a class="my-btn my-btn-outline" onclick="add_stream(<?php echo $add_stream_option['account']->account_id; ?>, <?php echo $stream_option->stream_id;?> );">
                                                <span class="typcn typcn-plus"></span>
                                                <?php echo $stream_option->stream_name ?>
                                            </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div id="streams-list-outer">
                        <ul id="streams-list" class="">
                            <?php foreach( $stream_contents as $stream_content ) : ?>
                            <li class="">
                                <div class="stream">
                                    <div class="stream-title row-fluid">
                                        <div class="span2">
                                            <div class="stream-avatar">
                                                <img src="<?php echo $stream_content['avatar_url'];?>" />
                                            </div>
                                        </div>
                                        <div class="span8">
                                            <div class="stream-name"><?php echo $stream_content['stream_name']; ?></div>
                                        </div>
                                        <div class="span2">
                                            <div class="stream-logo">
                                                <img src="<?php echo base_url($stream_content['stream_logo']);?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stream-items">
                                        <?php echo stream_items($stream_content['stream_items'],$stream_content['social_name']);?>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    
                </div>
            </div>
            <div id="secondary-page" class="page">
            </div>
        </div>

    </div>

</body>
</html>

