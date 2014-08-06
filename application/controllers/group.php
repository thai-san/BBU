<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Group extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata("user_id")) {
			header("location:/");
			exit();
		}
		$this->load->model('Group_Model');
	}

 	public function index() {
 		$data['groups'] = $this->Group_Model->manage();
 		$this->smarty->view('group_manage', $data);
 	}
 	
 	public function addnew() {
 		// setting up validation rule
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('group_name', '<b>Group name</b>', 'trim|required|is_unique[groups.group_name]|min_length[3]|max_length[20]|callback_groupname_check');
		$this->form_validation->set_message('is_unique', 'The %s already exist');
		// Process form validation
		if ($this->form_validation->run()){

			// Prepare Date before insert
			$data = array(
				"group_name" => trim($this->input->post('group_name'))
			);
			
			// return user_id after insert
			$group_id = $this->Group_Model->insert($data);
			
			// check group can create or not
			if ($group_id) {
				// send message to user group has been create
				$this->session->set_flashdata(
					array(
						"title" => "Create Group",
						"message" => "Group <b>{$data['group_name']}</b> has been created",
						"status" => "success"
					)
				);
			} else {
				// send message to user group can not create
				$this->session->set_flashdata(
					array(
						"title" => "Create Group",
						"message" => "Group <b>{$data['group_name']}</b> can not create",
						"status" => "warning"
					)
				);
			}

			// Go back to user page
			redirect("/group/addnew");
			exit();

		// form validate error
		} else {
 			$this->smarty->view('group_add');
		}
 	}

 	public function groupname_check($group_name) {
		if (preg_match('/^[A-Za-z][A-Za-z0-9]{2,20}$/', $group_name)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('groupname_check', 'The %s is invalid');
			return FALSE;
		}
	}

	public function edit() {
		$group_id = $this->uri->segment(3, 0);

		if ($group_id != 0 ) {
	 		// setting up validation rule
			$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
			$this->form_validation->set_rules('group_name', '<b>Group name</b>', 'trim|required|is_unique[groups.group_name]|min_length[3]|max_length[20]|callback_groupname_check');
			$this->form_validation->set_message('is_unique', 'The %s already exist');

			// get group detail
			$data['group'] = $this->Group_Model->detail($group_id);
			
			if ($this->form_validation->run()){
					$group_name = trim($this->input->post('group_name'));
					// process update database
					$affect_row = $this->Group_Model->update($group_id, 
						array(
							'group_name' => $group_name
						)
					);
					
					// if database was update it will return > 0
					if ($affect_row > 0) {
						// send message to user group has been create
						$this->session->set_flashdata(
							array(
								"title" => "Edit Group name",
								"message" => "Group name changed to <b>{$group_name}</b>.",
								"status" => "success"
							)
						);
					// no database update return error message
					} else {
						// send message to user group has been create
						$this->session->set_flashdata(
							array(
								"title" => "Edit Group name",
								"message" => "Group name can not update.",
								"status" => "danger"
							)
						);
					}
					// redirect user
					redirect("group/edit/{$group_id}"); exit();

			// form validate error
			} else {
				$this->smarty->view('group_edit', $data);
			}
		// group not exist
		} else {
			echo "Page are you looking for not found.";
		}
	}



	public function delete() {
		$group_id = $this->uri->segment(3, 0);
		
		if ($group_id != 0) {
			$group = $this->Group_Model->detail($group_id);
			// if have user can't delete group
			if ($group['total_user'] > 0) {
				// send message to user group has been create
				$this->session->set_flashdata(
					array(
						"title" => "Delete Group",
						"message" => "Group <b>{$group['group_name']}</b> can not delete. Because still have user in this group.",
						"status" => "danger"
					)
				);
			// if don't have user start delete
			} else {
				$affect_row = $this->Group_Model->delete($group_id);
				if ($affect_row > 0) {
					$this->session->set_flashdata(
						array(
							"title" => "Delete Group",
							"message" => "Group <b>{$group['group_name']}</b> has been deleted",
							"status" => "success"
						)
					);
				}
			}
		}
		// redirect user
		redirect("/dashboard/group"); exit();
	}

 }
 
 /* End of file group.php */
 /* Location: ./application/controllers/group.php */