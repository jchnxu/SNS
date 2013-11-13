<?php if(! defined('BASEPATH')) exit('No direct script access allowed');
	#require_once 'weibo_config.php';
	#require_once 'saetv2.ex.class.php';
	#require_once 'renren_config.php';
	require_once 'RenrenOAuthApiService.class.php';
	require_once 'RenrenRestApiService.class.php';	
	require_once 'douban_config.php';
	require_once 'tx_Config.php';
	require_once 'Tencent.php';


class Sns_authorize extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function txweibo_authorize(){
		
		OAuth::init(TX_AKEY, TX_SKEY);
		//打开session
		session_start();
		
		if ($_GET['code']) {//已获得code
        	$code = $_GET['code'];
        	$openid = $_GET['openid'];
        	$openkey = $_GET['openkey'];
        	//获取授权token
        	$url = OAuth::getAccessToken($code, TX_CALLBACK_URL);
        	$r = Http::request($url);
        	parse_str($r, $out);
        	//存储授权数据
        	if ($out['access_token']) {
            	$_SESSION['t_access_token'] = $out['access_token'];
            	$_SESSION['t_refresh_token'] = $out['refresh_token'];
            	$_SESSION['t_expire_in'] = $out['expires_in'];
            	$_SESSION['t_code'] = $code;
            	$_SESSION['t_openid'] = $openid;
            	$_SESSION['t_openkey'] = $openkey;
            
            	//验证授权
            	$r = OAuth::checkOAuthValid(); 
            	
            	if($r){
        	
        			$r = Tencent::api('user/info');
    				$info = json_decode($r, true);
    				#echo '<pre>';
    				#print_r ($info);
    				#echo '</pre>';
    				$uid = $info['data']['openid'];

					
    				$message = $this->_update_db(
                		$this->session->userdata('user_id'), // user_id
                		'Txweibo', // social_name
               			 $uid, // sn_user_id
               			 $_SESSION['t_access_token'], // token1
              			 $_SESSION['t_openid'], // token2
               			 4, // default_stream_id, here douban diary
                		'腾讯微博添加成功！', // ok message
                		'该微博帐号已经添加过了' // already added message
            		);
        		}else {
            	// authorize failed
            	$message = '微博认证失败';
        		}
        		
         	}
         	
         }
         
         // generate html, for cross-page sending $message 
        echo 
            '<!DOCTYPE html>
            <html>
            <head>
            <meta charset="utf-8">
            <script type="text/javascript" src="js/jquery-1.9.1.js">
            </script>
            <script>
                function send_back() {
                    window.opener.txweibo_auth_callback("' . $message . '");
                    window.close();
                }
            </script>
            </head>
            <body onload="send_back();">
            </body>
            </html>';
            
	}
	
	public function douban_authorize(){
		
		if(isset($_GET['code'])) {
			$code = $_GET['code'];
		
			$key0 = 'useless';
			$val0 = '0';
			$key1 = 'client_id';
			$val1 = DB_AKEY;
			$key2 = 'client_secret';
			$val2 = DB_SKEY;
			$key3 = 'redirect_uri';
			$val3 = DB_CALLBACK_URL;
			$key4 = 'grant_type';
			$val4 = 'authorization_code';
			$key5 = 'code';
			$val5 = $code;
			
			#获取accesstoken
			$post_string = $key0."=".$val0."&".$key5."=".$val5."&".$key1."=".$val1."&".$key3."=".$val3."&".$key4."=".$val4."&".$key2."=".$val2;
			
			$ch = curl_init();
			$remote_server = 'https://www.douban.com/service/auth2/token?';
			curl_setopt($ch,CURLOPT_URL,$remote_server);
			curl_setopt($ch,CURLOPT_POSTFIELDS,'mypost='.$post_string);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$content = curl_exec($ch);
			curl_close($ch);
			$response = json_decode($content, true);
			$accesstoken = $response['access_token'];
			
		}
		
		if($accesstoken) {
			//authorize ok
			
			#获取用户信息
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,'https://api.douban.com/v2/user/~me');
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . 'Bearer ' . $accesstoken)); 
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$res = curl_exec($ch);
			curl_close($ch);
			$user_data = json_decode($res,true);
			$uid = $user_data['uid'];
			
			$message = $this->_update_db(
                $this->session->userdata('user_id'), // user_id
                'Douban', // social_name
                $uid, // sn_user_id
                $accesstoken, // token1
                '', // token2
                3, // default_stream_id, here douban diary
                '豆瓣添加成功！', // ok message
                '该豆瓣帐号已经添加过了' // already added message
            );
        } else {
            // authorize failed
            $message = '微博认证失败';
        }
        
        // generate html, for cross-page sending $message 
        echo 
            '<!DOCTYPE html>
            <html>
            <head>
            <meta charset="utf-8">
            <script type="text/javascript" src="js/jquery-1.9.1.js">
            </script>
            <script>
                function send_back() {
                    window.opener.douban_auth_callback("' . $message . '");
                    window.close();
                }
            </script>
            </head>
            <body onload="send_back();">
            </body>
            </html>';
        
    }


	public function weibo_authorize(){

        $this->load->library('weibo_oauth', array("client_id" => WB_AKEY, 'client_secret' => WB_SKEY));
		
        if (isset($_REQUEST['code'])) {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = WB_CALLBACK_URL;
            try {
                $token = $this->weibo_oauth->getAccessToken( 'code', $keys ) ;
            } catch (OAuthException $e) {
            }
		}
        else {
            // get code failed, will generate error in later code
            $token = false;
        }
		
        // check authorize ok, update db
		if ($token) {
            // authorize ok

            // set session and cookie
			$cookieString = 'weibojs_'.$this->weibo_oauth->client_id;
            /*
            $this->session->set_userdata(array(
                'weibo_access_token' => $token,
                'weibo_cookie_string' => $cookieString
            ));
            */
			setcookie('weibojs_'.$this->weibo_oauth->client_id, http_build_query($token));
            
            $message = $this->_update_db(
                $this->session->userdata('user_id'), // user_id
                'Weibo', // social_name
                $token['uid'], // sn_user_id
                $token['access_token'], // token1
                '', // token2
                1, // default_stream_id, here weibo home_timeline
                '微博添加成功！', // ok message
                '该微博帐号已经添加过了' // already added message
            );
			
        }
        else {
            // authorize failed
            $message = '微博认证失败';
        }
        
        // generate html, for cross-page sending $message 
        echo 
            '<!DOCTYPE html>
            <html>
            <head>
            <meta charset="utf-8">
            <script type="text/javascript" src="js/jquery-1.9.1.js">
            </script>
            <script>
                function send_back() {
                    window.opener.weibo_auth_callback("' . $message . '");
                    window.close();
                }
            </script>
            </head>
            <body onload="send_back();">
            </body>
            </html>';
        
	}
	
	public	function renren_authorize(){

        $this->load->library('renren_client');
        $client = $this->renren_client->build(array('client_id' => APP_KEY, 'client_secret' => APP_SECRET));

        // 处理code -- 根据code来获得token
        if (isset ( $_REQUEST ['code'] )) {
            $keys = array ();
            
            // 验证state，防止伪造请求跨站攻击
            /*
            $state = $_REQUEST ['state'];
            if (empty ( $state ) || $state !== $_SESSION ['renren_state']) {
                echo '非法请求！';
                exit ();
            }
            unset ( $_SESSION ['renren_state'] );
            */
            
            // 获得code
            $keys ['code'] = $_REQUEST ['code'];
            $keys ['redirect_uri'] = CALLBACK_URL;
            try {
                // 根据code来获得token
                $token = $client->getTokenFromTokenEndpoint ( 'code', $keys );
            } catch ( RennException $e ) {
                //var_dump ( $e );
            }
        }

        $t = $token;
        $access_token = new stdClass;
        $access_token->type = $t->type; 
        $access_token->accessToken = $t->accessToken;
        $access_token->refreshToken = isset ( $t->refreshToken ) ? $t->refreshToken : null;
        $access_token->macKey = isset ( $t->macKey ) ? $t->macKey : null;
        $access_token->macAlgorithm = isset ( $t->macAlgorithm ) ? $t->macAlgorithm : null;
        $this->session->set_userdata('renren_token', $access_token);

        // check authorize ok, update db
        if ($token) {
            // ok
            $message = $this->_update_db(
                $this->session->userdata('user_id'), // user_id
                'Renren', // social_name
                $client->getUserService()->getUserLogin()['id'], // sn_user_id
                serialize($access_token), // token1
                '', // token2
                2, // default_stream_id, here renren home
                '人人添加成功！', // ok message
                '该人人帐号已经添加过了' // already added message
            );
        }
        else {
            // fail
            $message = '人人认证失败';
        }

        // generate html, for cross-page sending $message 
        echo 
            '<!DOCTYPE html>
            <html>
            <head>
            <meta charset="utf-8">
            <script type="text/javascript" src="js/jquery-1.9.1.js">
            </script>
            <script>
                function send_back() {
                    window.opener.renren_auth_callback("' . $message . '");
                    window.close();
                }
            </script>
            </head>
            <body onload="send_back();">
            </body>
            </html>';

    }

    private function _update_db($user_id, $social_name, $sn_user_id, $token1, $token2, $default_stream_id, $msg_ok, $msg_already) {
        
        $this->load->model('account_model');
        $this->load->model('account_stream_model');

        // update db
        $account_id = $this->account_model->get_id($user_id, $social_name, $sn_user_id);
        if ($account_id === false) {
            // this account is new here
            $account_id = $this->account_model->insert_new($user_id, $social_name, $sn_user_id, $token1, $token2);
            $this->account_stream_model->insert_new($account_id, $default_stream_id, 1); // insert a default stream, (account_id, stream_id, rank) 
            return $msg_ok;
        }
        else {
            // this account already exists 
            $this->account_model->update($user_id, $social_name, $sn_user_id, $token1, $token2);
            return $msg_already;
        }
    }

	
}
