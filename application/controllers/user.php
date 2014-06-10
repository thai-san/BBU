<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function login() {
		
		$user_name = trim($this->input->post('staff_user_name'));
		$password = md5(trim($this->input->post('staff_password')));
		
		if ($this->input->post('staff_remember') == "on") {
			$this->input->set_cookie('staff_user_name', $user_name, time() + (60*60*24*30)); 
		} else {
			delete_cookie('staff_user_name'); 
		}
		
		delete_cookie('student_id'); 

		$this->load->model('User_Model');
		$result = $this->User_Model->login($user_name, $password);
		
		if ($result && $result['result'] == 1 ) {
			$this->session->set_userdata("user_id", $result['user_id']);
			$this->session->set_userdata("user_name", $result['user_name']);
			$status = array('status' => "OK", 'url' => "/dashboard");
		} else {
			$status = array('status' => "FAIL", 'url' => "/login");
		}

		if ($this->input->post('ajax') && $this->input->post('ajax') == 1) {
			echo json_encode($status);
		} else {
			redirect($status['url']);
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		header("location:/");
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */