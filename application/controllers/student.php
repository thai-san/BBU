<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends CI_Controller {

	public function __construct() {

        parent::__construct();

		if (!$this->session->userdata("is_student")) {
			redirect("/"); exit();
		}else {
			$this->load->model("Student_Model");
			$this->student_id = $this->session->userdata("user_id");
		}

    }

	public function score($stu_id = null) {
		$data['score'] = $this->Student_Model->score($this->student_id);
		$this->smarty->view("student_score",$data);
	}

	public function info() {
		$data['student'] = $this->Student_Model->infomation($this->student_id);
		$data['group'] = $this->Student_Model->group($this->student_id);
		$this->smarty->view("student_info",$data);
	}

	public function payment() {
		$data['payment'] = $this->Student_Model->payment($this->student_id);
		$this->smarty->view("student_payment",$data);
	}
}

/* End of file student.php */
/* Location: ./application/controllers/student.php */