<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends CI_Controller {

	public function __construct() {
        parent::__construct();
		$this->data = array();
		if ($this->session->userdata("STUDENT_ID")) {
			$this->load->model("Student_Model");
			$this->student_id = $this->session->userdata("STUDENT_ID");
			$this->data['student'] = $this->Student_Model->detail($this->student_id);
		}else {
			redirect("/"); exit();
		}
		$this->load->model('Category_Model');
    }

	public function score($stu_id = null) {
		$this->data['menu'] = $this->Category_Model->get_list();
		$this->data['score'] = $this->Student_Model->score($this->student_id);
		$this->smarty->view("student_score",$this->data);
	}

	public function info() {
		$this->data['menu'] = $this->Category_Model->get_list();
		$this->data['student'] = $this->Student_Model->infomation($this->student_id);
		$this->data['payment'] = $this->Student_Model->payment($this->student_id);
		$this->data['group'] = $this->Student_Model->group($this->student_id);

		$this->smarty->view("student_info",$this->data);
	}

	public function changepwd() {
		$this->data['menu'] = $this->Category_Model->get_list();
		// check user from database
		if (!$this->Student_Model->row_exists($this->student_id)) $this->show_404();
		// setting form validation rule
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('old_password', '<b>Old Password</b>', 'trim|required');
		$this->form_validation->set_rules('password', '<b>New Password</b>', 'trim|required|matches[confirm_password]|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('confirm_password', '<b>Confirm New password</b>', 'trim|required');
		// Process form validation
		if ($this->form_validation->run()){
			$student = $this->data['student'];
			// Check old password
			if ($student['password'] != md5(trim($this->input->post('old_password')))) {
				$this->send_message("Change Password", "Incorect <b>Old Password</b>", "danger");
				$this->redirect("/student/changepwd");
			}
			// prepare data before update to database
			$data = array(
				"password" => md5(trim($this->input->post('password')))
			);
			// process update
			$affect_row = $this->Student_Model->update($this->student_id, $data);
			// check result 
			if ($affect_row > 0) {
				$this->send_message("Change Password", "You password has been changed", "success");
			} else {
				$this->send_message("Change Password", "You password has been changed", "danger");
			}
			// redirect user
			$this->redirect("/student/changepwd");
		}
		$this->smarty->view("student_change_password", $this->data);
	}

	public function redirect($url) {
		redirect($url); exit();
	}

	public function show_404() {
		show_404(); exit();
	}

	public function send_message($title, $message, $status) {
		return $this->session->set_flashdata(
			array(
				"title" => $title,
				"message" => $message,
				"status" => $status
			)
		);
	}

}

/* End of file student.php */
/* Location: ./application/controllers/student.php */