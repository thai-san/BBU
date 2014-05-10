<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata("user_id")) {
			header("location:/");
		}
	}

	public function index() {
		$this->smarty->view('admin');
	}

	public function user() {
		$this->load->model('Admin_Model');
		$data['users'] = $this->Admin_Model->user_list();
		$this->smarty->view('user_list',$data);
	}

	public function userAdd() {
		$user_name = trim($this->input->post('user_name'));
		$password = md5(trim($this->input->post('password')));
		$email = trim($this->input->post('email'));
		
		$this->load->model('User_Model');
		$user_id = $this->User_Model->userAdd($user_name, $password, $email);
		echo $user_id;
	}

	public function userDisable($user_id) {

	}

	public function resetPassword($user_id) {

	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */