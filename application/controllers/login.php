<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('is_logged_in')) {
            redirect(base_url('home')); 
        }
    }

    public function index() {
        $this->load->view("login");
    }

    public function do_login() {
        $this->session->set_userdata('is_logged_in', true);
        redirect(base_url('home'));
    }

    public function do_register() {
    }

    public function do_logout() {
        $this->session->unset_userdata('is_logged_in');    
    }
}
