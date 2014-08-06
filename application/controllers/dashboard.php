<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata("user_id")) {
			header("location:/");
			exit();
		}
	}

	public function index() {
		$this->smarty->view('dashboard');
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */