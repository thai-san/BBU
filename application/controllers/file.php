<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('File_model');
	}


}

/* End of file file.php */
/* Location: ./application/controllers/file.php */