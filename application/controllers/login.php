<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("form_validation");        

        // form validation messages
        $this->form_validation->set_message('required', '%s 不能为空');
        $this->form_validation->set_message('valid_email', '%s 不是正确的邮箱格式');
        $this->form_validation->set_message('matches', '%s 和 %s 必须相同');
        $this->form_validation->set_message('min_length', '%s 最小长度为%s位');
    }

    public function index() {
        if ($this->session->userdata('is_logged_in')) {
            // already logged in, go home
            redirect(base_url('home')); 
        }
        $this->_load_login_view('login', '');
    }

    public function do_login() {
        $this->load->model("user_model");

        $email_address = $this->input->post("email_address");
        $password = $this->input->post("password");
        $result = $this->user_model->check_login($email_address, sha1($password));

        if ($result != false) {
            $userdata = Array (
                'is_logged_in' => true,
                'user_id' => $result->user_id,
                'user_nickname' => $result->nickname,
                'user_email_address' => $result->email_address,
                'user_avatar_url' => $result->avatar_url
            );
            $this->session->set_userdata($userdata);
            redirect(base_url('home'));
        }
        else {
            // return fail message with html tags
            $this->_load_login_view('login', '邮箱或者密码不正确');
        }

    }

    public function do_register() {
        $this->load->model("user_model");
        
        $config = Array(
            Array(
                'field' => 'email_address',
                'label' => '邮箱',
                'rules' => 'trim|required|valid_email|is_unique[user.email_address]'
            ),
            Array(
                'field' => 'password',
                'label' => '密码',
                'rules' => 'trim|min_length[8]|required|matches[password_confirm]'
            ),
            Array(
                'field' => 'password_confirm',
                'label' => '密码确认',
                'rules' => 'trim|required'
            )
        );

        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() != false) {
            $this->user_model->insert_new($this->input->post('email_address'), sha1($this->input->post('password')));
            // return success message with html tags
            $this->_load_login_view('login', '注册成功！');
        }
        else {
            // return fail message with html tags
            $this->_load_login_view('register', validation_errors());
        }

    }

    public function do_logout() {
        $userdata = Array (
            'is_logged_in' => '',
            'user_id' => '',
            'user_nick_name' => '',
            'user_email_address' => '',
            'user_avatar_url' => ''
        );
        $this->session->unset_userdata($userdata);
        redirect(base_url());
    }

    private function _load_login_view($content, $message) {
        if ($content == 'login') {
            $this->load->view('login_view', array('content' => 'login', 'login_message' => $message));
        }
        else if ($content == 'register'){
            $this->load->view('login_view', array('content' => 'register', 'register_message' => $message));
        }
    }
}
