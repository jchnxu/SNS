<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'tx_Config.php';
require_once 'Tencent.php';

class Ajax extends CI_Controller {

    public function update_nickname() {
        $this->load->model('user_model');

        $user_id = $this->input->post('user_id');
        $new_nickname = $this->input->post('new_nickname');

        if (strlen($new_nickname) > 20) {
            echo '{"errno":1, "errmsg":"昵称必须在20位字符以内(10个汉字)", "original_nickname":"' . $this->user_model->get_nickname($user_id) . '"}';
            return;
        }
        
        if (!$this->user_model->update_nickname($user_id, $new_nickname)) {
            echo '{"errno":2, "errmsg":"更改昵称失败：数据库错误", "original_nickname":"' . $this->user_model->get_nickname($user_id) . '"}';
            return;
        }
        echo '{"errno":0, "msg":"更改昵称成功!"}';
    }

    public function update_password() {
        $user_id = $this->input->post('user_id');
        $password = $this->input->post('password');
        $new_password = $this->input->post('new_password');
        $new_password_confirm = $this->input->post('new_password_confirm');
        
        $this->load->model('user_model');
        if (!$this->user_model->check_password($user_id, $password)) {
            echo '{"errno":1, "errmsg":"原密码不正确"}';
            return;
        }
        if ($new_password !== $new_password_confirm) {
            echo '{"errno":2, "errmsg":"新密码和新密码确认不相同"}';
            return;
        }
        if (strlen($new_password) < 8) {
            echo '{"errno":3, "errmsg":"新密码长度必须不小于8位"}';
            return;
        }

        if (!$this->user_model->update_password($user_id, $new_password)) {
            echo '{"errno":4, "errmsg":"修改密码失败：数据库错误"}';
            return;
        }
        echo '{"errno":0, "msg":"修改密码成功！"}';
    }

    public function do_upload_avatar() {
        // file name to be changed in the library itself as user_{user_id}.jpg
        $this->load->library('uploadhandler', 
        	array(
        		'accept_file_types' => '/\.(gif|jpe?g|png)$/i',
        		'script_url' => base_url().'ajax/do_upload_image', // equals to this class, this method
        		'upload_url' => 'upload/avatar/', // both of upload_url, upload_dir equals to the upload destination
        		'upload_dir' => 'upload/avatar/',
        		'max_file_size' => 1024*1024*2 // in byte
            )
        );

        $this->load->model('user_model');
        $file_real_path = base_url($this->uploadhandler->file_path);
        $this->user_model->update_avatar_url($this->uploadhandler->user_id, $file_real_path);
        $this->session->set_userdata('user_avatar_url', $file_real_path);
 
    }
 

    public function load_secondary() {
        $page = $this->input->post('page');
        if ($page == 'settings') {
            $this->_escape_load_view('secondary/settings_view',array());
        }
        else if ($page == 'analysis') {
            $this->_escape_load_view('secondary/analysis_view',array());
        }
        else if ($page == 'contacts') {
        
        	OAuth::init(TX_AKEY, TX_SKEY);
        	session_start();
        	$params = array( 
    				"format" => "json", 
    				"reqnum" => "3", 
    				"startindex" => "0", 
    				"mode" => "0", 
    				"install" => "0", 
    				"sex" => "0"
    			);
    		$r = Tencent::api('friends/fanslist',$params,"GET",false);
    		$content['Txweibo_friends'] = json_decode($r,true);
    		
    		
    		$this->load->library('weibo_client');
            $client = $this->weibo_client->build(array(
                'akey' => WB_AKEY,
                'skey' => WB_SKEY, 
                'access_token' => $_SESSION['s_access_token'])
            );
            $uid = $_SESSION['s_uid'];
            $content['Weibo_friends'] = $client->bilateral( $uid, $page = 1, $count = 50, $sort = 0 ); 
            
            
            $this->load->library('renren_client');
            $client = $this->renren_client->build(array(
                'client_id' => APP_KEY,
                'client_secret' => APP_SECRET
            ));
            $t = unserialize($_SESSION['r_access_token']);
            $client->authWithToken($t);
            $content['Renren_friends_num'] = $client->getFriendService()->listFriend($_SESSION['r_uid'],5,1); 
            $content['Renren_friends'] = array();
            
            
            foreach($content['Renren_friends_num'] as $userId){
            	$info = $client->getUserService()->getUser($userId);
            	array_push($content['Renren_friends'],$info);
            }
            
            $this->_escape_load_view('secondary/contacts_view',array());
        }
    }

    private function _escape_load_view($view,$data) {
        $this->load->helper('url');
        ob_start();
        //include('application/views/secondary/settings_view.php');
        
        
        $this->load->view($view,$data);
        $output = ob_get_contents();
        ob_end_clean();
        echo $output;
    }
}
