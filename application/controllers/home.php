<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index() {
		$this->smarty->view("home");
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */