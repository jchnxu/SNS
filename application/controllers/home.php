<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	require_once 'weibo_config.php';
	require_once 'saetv2.ex.class.php';

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_logged_in')) {
            header("Location: " . base_url('login'));
        }
    }

    public function index() {
    	session_start();
    	$accessToken = $_SESSION['weibo_access_token']['access_token'];
    	$client = new SaeTClientV2( WB_AKEY , WB_SKEY , $accessToken);
		$ms  = $client->public_timeline(1,10,0,0,0,3);
		$res['statuses'] = $ms['statuses'];
		$res['content'] = "";
		#print_r($res['statuses']);
        $this->load->view('dashboard_view', $res);
    }

    public function settings() {
        $this->load->view('dashboard_view', array("content" => "settings"));
    }

    public function analysis() {
        $this->load->view('dashboard_view', array("content" => "analysis"));
    }

    public function contacts() {
        $this->load->view('dashboard_view', array("content" => "contacts"));
    }
}
