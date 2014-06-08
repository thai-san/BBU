<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
		$this->load->model("Student_Model");
    }

	public function index($stu_id = null)
	{
		$data['student'] = $this->Student_Model->infomation($stu_id);
		$data['score'] = $this->Student_Model->score($stu_id);
		$data['group'] = $this->Student_Model->group($stu_id);
		$data['payment'] = $this->Student_Model->payment($stu_id);
		$this->smarty->view("student_detail",$data);
	}

	public function login() {
		$student_id = trim($this->input->post('student_id'));
		$password = md5(trim($this->input->post('student_password')));
		
		if ($this->input->post('student_remember') == "on") {
			$this->input->set_cookie('student_id', $student_id, time() + (60*60*24*30)); 
		} else {
			delete_cookie('student_id'); 
		}

		authenticate:

		// Check is exist student in MySQL
		if ($this->Student_Model->detail($student_id)) {
			
			$result = $this->Student_Model->login($student_id, $password);
			// Check User Name and Password
			if ($result && $result['result'] == 1) {
				$this->session->set_userdata("user_id", $result['student_id']);
				$this->session->set_userdata("user_name", $result['student_name']);
				$status = array('status' => "OK", 'url' => "/dashboard");
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

}

/* End of file student.php */
/* Location: ./application/controllers/student.php */