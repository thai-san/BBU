<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends CI_Controller {

	public function __construct() {
        parent::__construct();
		$this->data = array();
		if ($this->session->userdata("STUDENT_ID")) {
			$this->load->model("Student_Model");
			$this->student_id = $this->session->userdata("STUDENT_ID");
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

}

/* End of file student.php */
/* Location: ./application/controllers/student.php */