<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata("user_id")) {
			header("location:/");
			exit();
		}
	}

	public function index() {
		$this->smarty->view('admin');
	}

	public function user() {
		$this->load->model('User_Model');

		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('user_name', '<b>User name</b>', 'trim|required|is_unique[users.user_name]|min_length[6]|max_length[20]|callback_username_check');
		$this->form_validation->set_rules('password', '<b>Password</b>', 'trim|required|matches[confirm_password]|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('confirm_password', '<b>Confirm password</b>', 'trim');
		$this->form_validation->set_rules('email', '<b>Email</b>', 'trim|valid_email|max_length[40]');
		$this->form_validation->set_message('is_unique', 'The %s already exist');
		
		if ($this->form_validation->run()){
			$data = array(
				"user_name" => strtolower(trim($this->input->post('user_name'))),
				"password" => md5(trim($this->input->post('password'))),
				"email" => $this->input->post('email') ? strtolower(trim($this->input->post('email'))) : null
			);
			
			if ($user_id = $this->User_Model->insert($data)) {
				$this->session->set_flashdata("message", "an user added");
				redirect("/dashboard/user");
				exit();
			};

		} else {
			$data['users'] = $this->User_Model->get_list();
			$this->smarty->view('user', $data);
		}
	}

	public function username_check($user_name) {
		if (preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $user_name)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('username_check', 'The %s is invalid');
			return FALSE;
		}
	}

	public function newpost() {
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('post_title', '<b>Title</b>', 'required');
		$this->form_validation->set_rules('post_content', '<b>Content</b>', 'required');

		if ($this->form_validation->run()){
			
			$data = array(
				"post_title" => trim($this->input->post('post_title')),
				"post_content" => $this->input->post('post_content'),
				"post_owner" => $this->session->userdata("user_id")
			);

			$this->load->model('Post_Model');

			if ($post_id = $this->Post_Model->insert($data)) {
				$this->session->set_flashdata("message", "a post added");
				redirect("/dashboard/editpost/{$post_id}");
				exit();
			}

		} else {
			$this->smarty->view('add_post.html');
		}
	}

	public function editpost($post_id) {
		$this->load->model('Post_Model');
		$data['post'] = $this->Post_Model->detail($post_id);
		$this->smarty->view('edit_post.html', $data);
	}

}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */