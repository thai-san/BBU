<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function index()
	{
		$this->load->model('Student_Model');	
		$data['user'] = $this->Student_Model->user();
		$this->smarty->view("user.tpl",$data);
	}

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */