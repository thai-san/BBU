<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata("user_id")) {
			header("location:/");
			exit();
		}
		$this->load->model('Category_Model');
	}

	public function index() {
		$data['menu'] = $this->Category_Model->get_list();
		$this->smarty->view('dashboard', $data);
	}

	public function logout(){
		// Remove all session
		$this->session->sess_destroy();
		header("location:/");
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */