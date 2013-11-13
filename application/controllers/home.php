<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'tx_Config.php';
require_once 'Tencent.php';

class Home extends CI_Controller {

    private $auth_url = array();

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_logged_in')) {
            header("Location: " . base_url('login'));
        }
        header('Content-Type: text/html; charset=utf-8'); 
        $this->load->library('renren_client');
        $renren_client = $this->renren_client->build(array('client_id' => APP_KEY, 'client_secret' => APP_SECRET));
        $this->auth_url['renren'] = $renren_client->getAuthorizeURL(CALLBACK_URL, 'code', '', '', true);

        $this->load->library('weibo_oauth', array(
            'client_id' => WB_AKEY, 
            'client_secret' => WB_SKEY
        ));
        $this->auth_url['weibo'] = $this->weibo_oauth->getAuthorizeUrl(WB_CALLBACK_URL);
    }

    public function index() {
        $this->load->view('dashboard_view', $this->_generate_params(''));
    }

    public function settings() {
        $this->load->view('dashboard_view', $this->_generate_params('settings'));
    }

    public function analysis() {
        $this->load->view('dashboard_view', $this->_generate_params('analysis'));
    }

    public function contacts() {
        $this->load->view('dashboard_view', $this->_generate_params('contacts'));
    }

    // this is an ajax function!!!
    public function add_stream() {
        $account_id = $this->input->post('account_id');
        $stream_id = $this->input->post('stream_id');
        $this->load->model('account_stream_model');
        $this->account_stream_model->insert_new($account_id, $stream_id, 1); // TODO change the rank!
        var_dump($this->account_stream_model->fetch_one($account_stream_id));
    }

    private function _generate_params($page) {
    	//session_start();
    	//$accessToken = $_SESSION['weibo_access_token']['access_token'];
    	//$weibo_access_token = $this->session->userdata('weibo_access_token');
        //$access_token = $weibo_access_token['access_token'];

        $this->load->model('account_model');
        $this->load->model('account_stream_model');

        // add_stream_options init
        $add_stream_options = $this->account_model->get_add_stream_options($this->session->userdata('user_id'));
        //var_dump($add_stream_options);
        //die();

        // stream contents init
        $streams = $this->account_stream_model->fetch_asc($this->session->userdata('user_id'));
        $stream_contents = array();

        // organize stream contents, according to stream type
        foreach ($streams as $stream) {
            array_push($stream_contents, $this->_generate_stream_content($stream));
        }

        return array(
            "page" => $page,
            "auth_url" => $this->auth_url,
            "add_stream_options" => $add_stream_options,
            "stream_contents" => $stream_contents
        );
    }

    private function _generate_stream_content($stream) {
        // init content
        $content = array(
            'account_stream_id' => $stream->account_stream_id,
            'stream_id' => $stream->stream_id
        );
        
        // check sn type
        if ($stream->social_name == 'Weibo') {
            
            $this->load->library('weibo_client');
            $client = $this->weibo_client->build(array(
                'akey' => WB_AKEY,
                'skey' => WB_SKEY, 
                'access_token' => $stream->token1)
            );

            // check stream type
            if ($stream->stream_id == 1) { // home timeline
                $content['stream_items'] = $client->public_timeline()['statuses']; 
            }
            else {
            }
        }
        else if ($stream->social_name == 'Renren') {
            $this->load->library('renren_client');
            $client = $this->renren_client->build(array(
                'client_id' => APP_KEY,
                'client_secret' => APP_SECRET
            ));
            $client->debug = true;
            $client->authWithToken($stream->token1);

            // check stream type
            if ($stream->stream_id == 2) { // home
                //$content['stream_items'] = $client->getFeedService()->listFeed(array('ALL'), 323070858, 30, 1); 
            }
            else {
            }
        }
        
        
        else if ($stream->social_name == 'Douban') {
        	
        	$accesstoken = $stream->token1;
        	
        	#获取用户信息
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,'https://api.douban.com/v2/user/~me');
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . 'Bearer ' . $accesstoken)); 
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$res = curl_exec($ch);
			curl_close($ch);
			$user_data = json_decode($res,true);
			$uid = $user_data['uid'];
        	
        	if ($stream->stream_id == 3) {
        		$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL,'https://api.douban.com/v2/note/user_created/'.$uid);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				$response = curl_exec($ch);
				curl_close($ch);
				$content['stream_items'] = json_decode($response,true);
				
			} 
			else {
			
			}
		
        }
        
        else if ($stream->social_name == 'Txweibo') {
        	
        	$accesstoken = $stream->token1;
        	$openid = $stream->token2;
        	OAuth::init(TX_AKEY, TX_SKEY);
			//打开session
			session_start();
			$_SESSION['t_access_token'] = $accesstoken;
			$_SESSION['t_openid'] = $openid;
			
			if ($stream->stream_id == 4) {
				$params = array( 
    				"format" => "json", 
    				"pageflag" => "0", 
    				"pagetime" => "0", 
    				"reqnum" => "20", 
    				"type" => "0", 
    				"contenttype" => "0"
    			);
    			$r = Tencent::api('statuses/home_timeline',$params,"GET",false);
    			$content['stream_items'] = json_decode($r,true);
    		} else {
    		
    		}
        
        }
        
        
        else if (1){
            // baidu 
        }

        return $content;
    }
}
