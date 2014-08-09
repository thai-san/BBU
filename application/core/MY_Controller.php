<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		if ($this->user_id = $this->session->userdata("user_id")) {
			$this->load->model('User_Model');
			$this->user = $this->User_Model->detail($this->user_id);
		} else {
			redirect("/"); exit();
		}
	}

	public function redirect($url) {
		redirect($url); exit();
	}

	public function show_404() {
		show_404(); exit();
	}

	public function send_message($title, $message, $status) {
		return $this->session->set_flashdata(
			array(
				"title" => $title,
				"message" => $message,
				"status" => $status
			)
		);
	}

	// detect admin user
	public function is_admin() {
		return ($this->user['is_admin'] == 1);
	}
}