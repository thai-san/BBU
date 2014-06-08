<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller {

	public function detail() {
		$this->smarty->view("post_detail");
	}

}

/* End of file post.php */
/* Location: ./application/controllers/post.php */