<?php
	function genImage($src, $href) {
	return '<a href="'.$href.'"><img src="'.$src.'" width=50,height=50 /></a>';
}?>


<div>我的腾讯微博粉丝<br><br>
	<?php $friends = $Txweibo_friends['data']['info'];
	foreach($friends as $friend):?>
                                <?php 
                            			echo '姓名:'.$friend['name'].'<br>';
                            			$contentImage = genImage($friend['head'],'#');
                            			echo '状态:'.$friend['tweet']['0']['text'].'<br>';
                                    	
                                    	?>

                                <br><br>
    <?php endforeach;?>
</div>
    
    
<div>我的新浪微博互粉<br><br>
    <?php $friends = $Weibo_friends['users'];
    foreach($friends as $friend):?>
                                <?php 
                                	    $contentImage = genImage($friend['profile_image_url'],'#');
                            			echo $contentImage;
                                    	echo '姓名:'.$friend['name'].'<br>';
                                    	echo '位置:'.$friend['location'].'<br>';
                                		echo '状态:'.$friend['description'].'<br>';
                                		
                                    	?>
								<br><br>
    <?php endforeach;?>				
</div>

<div>我的人人好友<br><br>
    <?php 
    foreach($Renren_friends as $friend):?>
                                <?php 
                                		$imageurl = $friend['avatar'][0]['url'];
                                		$contentImage = genImage($imageurl,'#');
                            			echo $contentImage;
                                	    echo '姓名:'.$friend['name'].'<br>';
                                	    $basicInformation = $friend['basicInformation'];
                                		echo '生日:'.$basicInformation['birthday'].'<br>';
                                		$province = $basicInformation['homeTown']['province'];
                                		$city = $basicInformation['homeTown']['city'];
                                		echo '位置:'.$province.' '.$city.'<br>';
                                    	?>
								<br><br>
    <?php endforeach;?>				
</div>