<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

    public function load_secondary() {
        $content = $this->input->post('content');
        if ($content == 'settings') {
            $this->load->view('secondary/settings_view');
        }
        else if ($content == 'analysis') {
            $this->load->view('secondary/analysis_view');
        }
        else if ($content == 'contacts') {
            $this->load->view('secondary/contacts_view');
        }
    }
}
