<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index() {
		$this->smarty->view("home");
	}

	public function login() {
		$this->input->post('type') == "staff" ? $this->staff_login() : $this->student_login();
	}

	public function student_login() {
		$this->load->model('Student_Model');
		$student_id = trim($this->input->post('student_id'));
		$password = md5(trim($this->input->post('student_password')));
		
		if ($this->input->post('student_remember') == "on") {
			$this->input->set_cookie('student_id', $student_id, time() + (60*60*24*30)); 
		} else {
			delete_cookie('student_id'); 
		}
		
		delete_cookie('staff_user_name'); 

		authenticate:

		// Check is exist student in MySQL
		if ($this->Student_Model->detail($student_id)) {
			
			$result = $this->Student_Model->login($student_id, $password);
			// Check User Name and Password
			if ($result && $result['result'] == 1) {
				$this->session->set_userdata($this->Student_Model->group($result['student_id']));
				$status = array('status' => "OK", 'url' => "/student/score");
			}else{
				$status = array('status' => "FAIL", 'url' => "/login");
			}
		} else {
			// Check first login 
			if (strcmp(md5(DEFAULT_PASSWORD), $password) == 0) {
				// Retrive student informatoin from SQLServer
				if($student = $this->Student_Model->getStudent($student_id)) {
					// Prepire Data
					$data = array(
						'student_id' => $student['STUDENT_ID'],
						'student_name' => $student['STUDENT_NAME'],
						'email' => $student['EMAIL'] ? $student['EMAIL'] : null,
						'password' => md5(DEFAULT_PASSWORD)
					);
					// Insert Into MySQL 
					$this->Student_Model->insert($data);
					goto authenticate;
				} else {
					$status = array('status' => "FAIL", 'url' => "/login");
				}
			} else {
				$status = array('status' => "FAIL", 'url' => "/login");
			}
		}
		
		if ($this->input->post('ajax') && $this->input->post('ajax') == 1) {
			echo json_encode($status);
		} else {
			redirect($status['url']);
		}
	}

	public function staff_login() {
		
		$this->load->model('User_Model');

		$user_name = trim($this->input->post('staff_user_name'));
		$password = md5(trim($this->input->post('staff_password')));
		
		if ($this->input->post('staff_remember') == "on") {
			$this->input->set_cookie('staff_user_name', $user_name, time() + (60*60*24*30)); 
		} else {
			delete_cookie('staff_user_name'); 
		}
		
		delete_cookie('student_id'); 

		$result = $this->User_Model->login($user_name, $password);
		
		if ($result && $result['result'] == 1 ) {
			$this->session->set_userdata("user_id", $result['user_id']);
			$this->session->set_userdata("user_name", $result['user_name']);
			$status = array('status' => "OK", 'url' => "/dashboard");
		} else {
			$status = array('status' => "FAIL", 'url' => "/login", "user" => $user_name , "pwd" => $password);
		}

		if ($this->input->post('ajax') && $this->input->post('ajax') == 1) {
			echo json_encode($status);
		} else {
			redirect($status['url']);
		}
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */