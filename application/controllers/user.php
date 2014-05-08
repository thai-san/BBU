<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function login() {
		$user_name = trim($this->input->post('user_name'));
		$password = trim($this->input->post('password'));
		$this->load->model('User_Model');
		$result = $this->User_Model->login($user_name, $password);
		
		if ($result && $result['result'] == 1 ) {
			$this->session->set_userdata("user_id",$result['user_id']);
			$this->session->set_userdata("user_name",$result['user_name']);
			echo "login successfull.";
		}
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */