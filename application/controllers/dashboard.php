<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Category_Model');
		$this->load->model('Post_Model');
	}

	public function index() {
		$data['menu'] = $this->Category_Model->get_list();
		if ($this->is_admin()) {
			$data['post_today'] = $this->Post_Model->get_all_post_today();
		} else {
			$data['post_today'] = $this->Post_Model->get_post_today($this->user_id['group_id']);
		}
		$this->smarty->view('dashboard', $data);
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */