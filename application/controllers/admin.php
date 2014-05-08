<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function index() {
		$this->smarty->view('admin');
	}

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */