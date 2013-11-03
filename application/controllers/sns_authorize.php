<?php if(! defined('BASEPATH')) exit('No direct script access allowed');
	require_once 'weibo_config.php';
	require_once 'saetv2.ex.class.php';
	#require_once 'renren_config.php';
	require_once 'RenrenOAuthApiService.class.php';
	require_once 'RenrenRestApiService.class.php';	


class Sns_authorize extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
	}

	public function weibo_authorize(){
		$o = new SaeTOAuthV2(WB_AKEY,WB_SKEY);
		
		if (isset($_REQUEST['code'])) {
		$keys = array();
		$keys['code'] = $_REQUEST['code'];
		$keys['redirect_uri'] = WB_CALLBACK_URL;
			try {
				$token = $o->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}
		
		if ($token) {
			$cookieString = 'weibojs_'.$o->client_id;
			session_start();
			$_SESSION['weibo_access_token'] = $token;
			$_SESSION['weibo_cookie_string'] = $cookieString;
			setcookie('weibojs_'.$o->client_id, http_build_query($token));
			
			$accessToken = $_SESSION['weibo_access_token']['access_token'];
			
			
			$this->load->model('authorization_model','OP');
			//$user_id = $_SESSION['user_id'];
			$user_id = 1;
			$this->OP->insert_account($user_id,$accessToken,"Weibo");
			
			
			header("Location: " . base_url('home'));
		}
	}
	
	public	function renren_authorize(){

		$code = $_GET['code'];
		#echo $code;
		
		$config                         = new stdClass;

		$config->APIURL         = 'http://api.renren.com/restserver.do'; //RenRenÕ¯µƒAPIµ˜”√µÿ÷∑£¨≤ª–Ë“™–ﬁ∏ƒ
		$config->APPID          = '205536';      //ƒ„µƒAPI Key£¨«Î◊‘––…Í«Î
		$config->APIKey         = '554f0b79093642918f322e31ced90960';   //ƒ„µƒAPI Key£¨«Î◊‘––…Í«Î
		$config->SecretKey      = '7d8492648f044ec6a7eb1141be77dd27';   //ƒ„µƒAPI √‹‘ø
		$config->APIVersion     = '1.0';        //µ±«∞APIµƒ∞Ê±æ∫≈£¨≤ª–Ë“™–ﬁ∏ƒ
		$config->decodeFormat   = 'json';       //ƒ¨»œµƒ∑µªÿ∏Ò Ω£¨∏˘æ› µº «Èøˆ–ﬁ∏ƒ£¨÷ß≥÷£∫json,xml

		$config->redirecturi= 'http://127.0.0.1/SNS/sns_authorize/renren_authorize';//ƒ„µƒªÒ»°codeµƒªÿµ˜µÿ÷∑£¨“≤ «accesstokenµƒªÿµ˜µÿ÷∑
		$config->scope='publish_feed,status_update,photo_upload,read_user_feed,read_user_feed,read_user_status';
	
		
		if($code){
			// get new access_token
			$oauthApi = new RenrenOAuthApiService;
			$postParams = array(
						'client_id' => $config->APIKey,
						'client_secret' => $config->SecretKey,
						'redirect_uri' => $config->redirecturi,
						'grant_type' => 'authorization_code',
						'code' => $code
					);
			$tokenUrl = 'http://graph.renren.com/oauth/token';
			$accessInfo = $oauthApi->rr_post_curl($tokenUrl, $postParams);
			$accessToken = $accessInfo['access_token'];
			$expiresIn = $accessInfo['expires_in'];
			$refreshToken = $accessInfo['refresh_token'];
		
			// store infos
			session_start();
			$_SESSION['renren_access_token'] = $accessToken;
			$_SESSION['renren_refresh_token'] = $refreshToken;
			
			$user_id = 1;
			$this->load->model('Authorization_model','OP');
			//$user_id = $_SESSION['user_id'];
			$this->OP->insert_account($user_id,$accessToken,"Renren");
			
		
			// redirect
			header("Location: " . base_url('home'));
		}else {
		echo 'error: parameter code is null, here is the query string<br>';
		echo $_SERVER['QUERY_STRING'];
		}
	}

}
