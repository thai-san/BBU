<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct() {
		parent::__construct();

		// Load User Model First
		$this->load->model('User_Model');

		// Prepare User ID
		if ($this->session->userdata("user_id")) {
			$this->user_id = $this->session->userdata("user_id");
		}
	}

	public function login() {
			
		// Prepare Data before insert
		$user_name = trim($this->input->post('staff_user_name'));
		$password = md5(trim($this->input->post('staff_password')));
		
		// SetCookie if user remember account
		if ($this->input->post('staff_remember') == "on") {
			// set Cookie for 1 Month
			$this->input->set_cookie('staff_user_name', $user_name, time() + (60*60*24*30)); 
		} else {
			// Remove user Cookie
			delete_cookie('staff_user_name'); 
		}
		
		// Remove Student Cookie
		delete_cookie('student_id'); 

		// Process User Login
		$result = $this->User_Model->login($user_name, $password);
		
		// Check User Account
		if ($result && $result['result'] == 1 ) {

			// Store user data in session
			$this->session->set_userdata("user_id", $result['user_id']);
			$this->session->set_userdata("user_name", $result['user_name']);

			// return Ajax status SUCCESS
			$status = array('status' => "OK", 'url' => "/dashboard");
		} else {
			// return Ajax status FAIL
			$status = array('status' => "FAIL", 'url' => "/login");
		}

		// Check if Javascript enable
		if ($this->input->post('ajax') && $this->input->post('ajax') == 1) {
			// Return json message
			echo json_encode($status);
		} else {
			// go to login without javascript
			redirect($status['url']);
		}
	}

	public function index() {
		
		// Load All user
		$data['users'] = $this->User_Model->manage();
		
		$this->load->model('Group_Model');
		$data['groups'] = $this->Group_Model->get_list();

		$this->smarty->view('user_manage', $data);
	}

	public function logout(){

		// Remove all session
		$this->session->sess_destroy();
		header("location:/");
	}



	public function addnew() {

		// Input validation rule
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('user_name', '<b>User name</b>', 'trim|required|is_unique[users.user_name]|min_length[3]|max_length[20]|callback_username_check');
		$this->form_validation->set_rules('password', '<b>Password</b>', 'trim|required|matches[confirm_password]|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('confirm_password', '<b>Confirm password</b>', 'trim');
		$this->form_validation->set_rules('group_id', '<b>Group Id</b>', 'required');
		$this->form_validation->set_message('is_unique', 'The %s already exist');
		
		if ($this->form_validation->run()){
			
			// Prepare Date before insert
			$data = array(
				"user_name" => strtolower(trim($this->input->post('user_name'))),
				"password" => md5(trim($this->input->post('password'))),
			);
			
			// return user_id after insert
			if ($user_id = $this->User_Model->insert($data)) {
				$this->session->set_flashdata(
					array(
						"title" => "Create user",
						"message" => "user <b>{$data['user_name']}</b> has been created",
						"status" => "success"
					)
				);
				// Go back to user page
				redirect("/user/addnew");
				exit();
			};
		// form validate error
		} else {
			$this->load->model('Group_Model');
			$data['groups'] = $this->Group_Model->get_list();
			$this->smarty->view("user_add", $data);
		}
	}

	public function delete() {
		
		// Detail current logon user
		$user = $this->User_Model->detail($this->user_id);

		// set default value user_id = 0
		$user_id = $this->uri->segment(3, 0);

		// Detail user want to delete
		$del_user = $this->User_Model->detail($user_id);
		
		// User Type admin can't delete
		if ($del_user['is_admin'] == 1) {
			
			// send back message
			$this->session->set_flashdata(
				array(
					"title" => "Remove user",
					"message" => "admin user can't be delete",
					"status" => "warning"
				)
			);

			// go back to user page
			redirect("/dashboard/user");
			exit();
		}

		// Only Admin can delete other user;
		if ($user['is_admin'] == 1) {
			
			// process delete user then return affect row
			$affected_rows = $this->User_Model->delete($user_id);
			
			// send back message
			$this->session->set_flashdata(
				array(
					"title" => "Remove user",
					"message" => "{$affected_rows} user has been deleted",
					"status" => "success"
				)
			);

			// go back to user page
			redirect("/dashboard/user");
			exit();

		} else {

			// go back to dashboard
			redirect("/dashboard");
			exit();
		}
	}

	public function username_check($user_name) {
		if (preg_match('/^[A-Za-z][A-Za-z0-9]{2,20}$/', $user_name)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('username_check', 'The %s is invalid');
			return FALSE;
		}
	}

	public function groupchange() {

		$user_id = $this->uri->segment(3,0);

		if ($user_id != 0) {

			$data = array(
				'group_id' => $this->input->post('group_id')
			);

			$affect_row = $this->User_Model->update($user_id, $data);
		}

		redirect("/dashboard/user");
	}

	public function resetpwd() {
		$user_id = $this->uri->segment(3, 0);
		
		if ($user_id != 0) {
			$this->load->view('user_password_reset', $data);
		} else {
			
		}
	}

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */