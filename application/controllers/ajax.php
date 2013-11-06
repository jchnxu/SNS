<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
            $this->_escape_load_view('secondary/settings_view');
        }
        else if ($page == 'analysis') {
            $this->_escape_load_view('secondary/analysis_view');
        }
        else if ($page == 'contacts') {
            $this->_escape_load_view('secondary/contacts_view');
        }
    }

    private function _escape_load_view($view) {
        $this->load->helper('url');
        ob_start();
        //include('application/views/secondary/settings_view.php');
        $this->load->view($view);
        $output = ob_get_contents();
        ob_end_clean();
        echo $output;
    }
}
