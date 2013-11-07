<?php if(! defined('BASEPATH')) exit('No direct script access allowed');
	#require_once 'weibo_config.php';
	#require_once 'saetv2.ex.class.php';
	#require_once 'renren_config.php';
	require_once 'RenrenOAuthApiService.class.php';
	require_once 'RenrenRestApiService.class.php';	


class Sns_authorize extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
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
