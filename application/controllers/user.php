<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct() {
		parent::__construct();

		// Load User Model First
		$this->load->model('User_Model');

		// Prepare User ID
		if ($this->session->userdata("user_id")) {
			$this->user_id = $this->session->userdata("user_id");
			$this->user = $this->User_Model->detail($this->user_id);
		} else {
			redirect("/"); exit();
		}
	}

	public function index() {
		
		// Load All user
		$data['users'] = $this->User_Model->manage();
		
		$this->load->model('Group_Model');
		$data['groups'] = $this->Group_Model->get_list();

		$this->smarty->view('user_manage', $data);
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
		
		// set default value user_id = 0
		$user_id = $this->uri->segment(3, 0);

		// Detail user want to delete
		$user = $this->User_Model->detail($user_id);
		
		// check post
		if ($user['total_post'] > 0) {
			// send back message
			$this->session->set_flashdata(
				array(
					"title" => "Remove user",
					"message" => "Can not delete user {$user['user_name']}. Because this user have have many post.",
					"status" => "warning"
				)
			);
			redirect("/dashboard"); exit();
		}
		
		// Only Admin can delete other user;
		if ($this->user['is_admin'] == 1) {
			
			// process delete user then return affect row
			$affected_rows = $this->User_Model->delete($user['user_id']);
			
			if ($affected_rows > 0) {
				// send back message
				$this->session->set_flashdata(
					array(
						"title" => "Remove user",
						"message" => "{$affected_rows} user has been deleted",
						"status" => "success"
					)
				);
			} else {
				// send back message
				$this->session->set_flashdata(
					array(
						"title" => "Remove user",
						"message" => "can not delete user ",
						"status" => "warning"
					)
				);
			}

		} else {
			// send back message
			$this->session->set_flashdata(
				array(
					"title" => "Remove user",
					"message" => "you don't have permission to delete this user",
					"status" => "warning"
				)
			);
			
		}
		// redirect user
		redirect("/dashboard"); exit();
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
		
		// Input validation rule
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('password', '<b>Password</b>', 'trim|required|matches[confirm_password]|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('confirm_password', '<b>Confirm password</b>', 'trim|required');

		if ($user_id != 0) {
			
			$data['user'] = $this->User_Model->detail($user_id);

			// process form validation
			if ($this->form_validation->run()){
				
				// process change user password in database
				$affect_row = $this->User_Model->update($user_id, array(
					'password' => md5(trim($this->input->post('password')))
				));

				// check result
				if ($affect_row > 0) {
					// send back message
					$this->session->set_flashdata(
						array(
							"title" => "Reset Password",
							"message" => "Password has been reseted for user <b>{$data['user']['user_name']}</b>",
							"status" => "success"
						)
					);
				// can't reset password
				} else {
					// send back message
					$this->session->set_flashdata(
						array(
							"title" => "Reset Password",
							"message" => "Can not reset password for user <b>{$data['user']['user_name']}</b>",
							"status" => "danger"
						)
					);
				}

				// redirect user
				redirect("user/resetpwd/{$user_id}"); exit();
			// form validate error
			} else  {
				//​ re-load form with alert error
				$this->smarty->view('user_password_reset');
			}

			$this->smarty->view('user_password_reset', $data);
		} else {
			echo "page are you looking for is not found.";
		}
	}

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */