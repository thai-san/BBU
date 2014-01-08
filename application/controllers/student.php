<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
		$this->load->model("Student_Model");
    }

	public function index($stu_id = null)
	{
		$data['student'] = $this->Student_Model->detail($stu_id);
		$data['score'] = $this->Student_Model->score($stu_id);
		$this->smarty->view("student_detail",$data);
	}

}

/* End of file student.php */
/* Location: ./application/controllers/student.php */