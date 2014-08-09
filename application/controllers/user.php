<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Category_Model');
		$this->load->model('Group_Model');
	}

	public function index() {
		$data['menu'] = $this->Category_Model->get_list();
		// Load All user
		$data['users'] = $this->User_Model->manage();
		
		$this->load->model('Group_Model');
		$data['groups'] = $this->Group_Model->get_list();

		$this->smarty->view('user_manage', $data);
	}

	public function addnew() {
		$data['menu'] = $this->Category_Model->get_list();
		$data['groups'] = $this->Group_Model->get_list();
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
				"group_id" => $this->input->post('group_id')
			);
			// return user_id after insert
			$user_id = $this->User_Model->insert($data);
			if ($user_id) {
				$this->send_message("Create user", "user <b>{$data['user_name']}</b> has been created", "success");
			
			}else {
				$this->send_message("Create user", "Can not create user <b>{$data['user_name']}</b> at this time", "danger");
			}
			// redirect user
			$this->redirect("/user/addnew");
		// form validate error
		} else {
			$this->smarty->view("user_add", $data);
		}
	}

	public function delete() {
		// set default value user_id = 0
		$user_id = $this->uri->segment(3, 0);
		// check user id exist or not;
		if (!$this->User_Model->row_exists($user_id)) $this->show_404();
		// Detail user want to delete
		$user = $this->User_Model->detail($user_id);
		// Only Admin can delete other user;
		if ($this->is_admin()) {
			// check post
			if ($this->is_has_post($user_id)) {
				$this->send_message("Delete User", "Can not delete user <b>{$user['user_name']}</b>. Because this user still have post.You should <b>disable</b> user instead of delete user", "warning");
				$this->redirect("/user");
			}
			// process delete user
			$affected_rows = $this->User_Model->delete($user['user_id']);
			// check result
			if ($affected_rows > 0) {
				// send back message
				$this->send_message("Delete User", "User <b>{$user['user_name']}</b> has been deleted", "success");
			} else {
				// send back message
				$this->send_message("Delete User", "Can not delete user <b>{$user['user_name']}</b>", "warning");
			}
			// redirect user
			$this->redirect("/user");
		} else {
			$this->send_message("Delete User", "You don't have permission to delete this user", "warning");
		}
		// redirect user
		$this->redirect("/dashboard");
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
		// get user_id from url
		$user_id = $this->uri->segment(3,0);
		// check user id
		if (!$this->User_Model->row_exists($user_id)) $this->show_404();
		// detail user
		$user = $this->User_Model->detail($user_id);
		// prepare data before update
		$data = array(
			'group_id' => $this->input->post('group_id')
		);
		// process group change
		$affect_row = $this->User_Model->update($user_id, $data);
		// check result
		if ($affect_row > 0) {
			// send back message
			$this->send_message("Chnage User Group", "Group changed for user <b>{$user['user_name']}</b>", "success");
		} else {
			// send back message
			$this->send_message("Chnage User Group", "Can not change group for user <b>{$user['user_name']}</b>", "danger");
		}
		// redirect user
		$this->redirect("/dashboard/user");
	}

	public function enable() {
		// get user_id from url
		$user_id = $this->uri->segment(3, 0);
		$status = $this->uri->segment(4, 1);
		// check user from database
		if (!$this->User_Model->row_exists($user_id)) $this->show_404();
		// // is admin check
		if (!$this->is_admin()) {
			$this->send_message("Change User Status", "Permission denied", "danger");
			$this->redirect("/dashboard");
		}
		// prepare data before input
		$data = array('is_enable' => $status);
		// process update
		$affect_row = $this->User_Model->update($user_id, $data);
		// check result
		if ($affect_row > 0) {
			$user = $this->User_Model->detail($user_id);
			if ($user['is_enable'] == 1) {
				// send back message
				$this->send_message("Change User Status", "User <b>{$user['user_name']}</b> status change to <b>enable</b>", "success");
			} else {
				// send back message
				$this->send_message("Change User Status", "User <b>{$user['user_name']}</b> status change to <b>disable</b>", "success");
			}
		} else {
			// send back message
			$this->send_message("Change User Status", "Can not change status for user <b>{$user['user_name']}</b>", "danger");
		}
		$this->redirect("/user");
	}

	public function changepwd() {
		$data['menu'] = $this->Category_Model->get_list();
		// check user from database
		if (!$this->User_Model->row_exists($this->user_id)) $this->show_404();
		$data['user'] = $this->user;
		// setting form validation rule
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('old_password', '<b>Password</b>', 'trim|required');
		$this->form_validation->set_rules('password', '<b>New Password</b>', 'trim|required|matches[confirm_password]|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('confirm_password', '<b>Confirm New password</b>', 'trim|required');
		// Process form validation
		if ($this->form_validation->run()){
			// Check old password
			if ($this->user['password'] != md5(trim($this->input->post('old_password')))) {
				$this->send_message("Change Password", "Incorect <b>Old Password</b>", "danger");
				$this->redirect("/user/changepwd");
			}
			// prepare data before update to database
			$data = array(
				"password" => md5(trim($this->input->post('password')))
			);
			// process update
			$affect_row = $this->User_Model->update($this->user_id, $data);
			// check result 
			if ($affect_row > 0) {
				$this->send_message("Change Password", "You password has been changed", "success");
			} else {
				$this->send_message("Change Password", "You password has been changed", "danger");
			}
			// redirect user
			$this->redirect("/user/changepwd");
		}
		$this->smarty->view("user_change_password", $data);
	}

	public function resetpwd() {
		$data['menu'] = $this->Category_Model->get_list();
		// get user_id from url
		$user_id = $this->uri->segment(3, 0);
		// check user from database
		if (!$this->User_Model->row_exists($user_id)) $this->show_404();
		// detail user
		$data['user'] = $this->User_Model->detail($user_id);
		// setting form validation rule
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('password', '<b>Password</b>', 'trim|required|matches[confirm_password]|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('confirm_password', '<b>Confirm password</b>', 'trim|required');
		// process form validation
		if ($this->form_validation->run()){
			// process change user password in database
			$affect_row = $this->User_Model->update($user_id,
				array(
					'password' => md5(trim($this->input->post('password')))
				)
			);
			// check result
			if ($affect_row > 0) {
				// send back message
				$this->send_message("Reset Password", "Password has been reseted for user <b>{$data['user']['user_name']}</b>", "success");
			} else {
				// send back message
				$this->send_message("Reset Password", "Can not reset password for user <b>{$data['user']['user_name']}</b>", "danger");
			}
			// redirect user
			$this->redirect("user/resetpwd/{$user_id}");
		// form validate error
		} else {
			//​ re-load form with alert error
			$this->smarty->view('user_password_reset', $data);
		}
	}
	// detect admin user
	private function is_admin() {
		return ($this->user['is_admin'] == 1);
	}
	// detect post of user
	private function is_has_post($user_id) {
		$user = $this->User_Model->detail($user_id);
		return ($user['total_post'] > 0);
	}

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */