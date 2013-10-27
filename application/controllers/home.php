<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_logged_in')) {
            header("Location: " . base_url('login'));
        }
    }

    public function index() {
        $this->load->view('dashboard', array("content" => ""));
    }

    public function settings() {
        $this->load->view('dashboard', array("content" => "settings"));
    }

    public function analysis() {
        $this->load->view('dashboard', array("content" => "analysis"));
    }

    public function contacts() {
        $this->load->view('dashboard', array("content" => "contacts"));
    }
}
