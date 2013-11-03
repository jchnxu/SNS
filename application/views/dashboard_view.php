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
            <a href="https://graph.renren.com/oauth/authorize?client_id=205536&amp;response_type=code&amp;scope=publish_feed,status_update,photo_upload,read_user_feed,read_user_feed,read_user_status&amp;state=a%3d1%26b%3d2&amp;redirect_uri=http://127.0.0.1/SNS/sns_authorize/renren_authorize&amp;x_renew=true"><input type="button" name="renren" value="人人"/></a>
            <a href="https://api.weibo.com/oauth2/authorize?client_id=1401769607&amp;redirect_uri=http%3A%2F%2F127.0.0.1%2FSNS%2Fsns_authorize%2Fweibo_authorize&amp;response_type=code"><input type="button" name="weibo" value="微博"/></a>
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
                        <li class="stream">
                        	<?php foreach($statuses as $item):?>
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

