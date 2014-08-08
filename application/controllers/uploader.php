<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uploader extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $error = array('error' => '');
        $this->load->view('uploader_view', $error);
    }

    public function do_upload() {
        $this->load->model('Uploader_Model');
    }
}

/* End of file file.php */
/* Location: ./application/controllers/file.php */