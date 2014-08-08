<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Post_Model');
		$this->load->model('File_Model');
		$this->load->model('Category_Model');
		$this->load->model('Attachment_Model');
	}

	public function index() {
		$data['menu'] = $this->Category_Model->get_list();

		// initailize data
		$data['posts'] = array();
		// if user admin can see all post
		if ($this->user['is_admin'] == 1) {
			$data['posts'] = $this->Post_Model->get_all_post();
		// if normal user can see post in group
		} else {
			$data['posts'] = $this->Post_Model->manage($this->user['group_id']);
		}
		$this->smarty->view('post_manage', $data);
	}

	public function addnew() {
		
		$post_type = $this->uri->segment(3, "image");
		
		switch ($post_type) {
			case 'pdf':
				$this->post_type_pdf(); // post_type pdf
				break;
			case 'text':
				$this->post_type_text(); // post_type text
				break;
			default:
				$this->post_type_image(); // post_type image
		}
	}

	public function update() {
		
		$post_id = $this->uri->segment(3, 0);
		
		if ($post_id != 0) {
			
			$post = $this->Post_Model->detail($post_id);
			
			switch ($post['post_type']) {
				case 'TEXT':
					$this->update_post_type_text($post_id); // post_type text
					break;
				case 'PDF':
					$this->update_post_type_pdf($post_id); // post_type pdf
					break;
				default:
					$this->update_post_type_image($post_id); // post_type image
			}
		// No form submit just load add new post normal
		} else {
			$this->redirect("/dashboard/editpost/{$post_id}");
		}
	}

	public function post_type_image(){
		$data['menu'] = $this->Category_Model->get_list();

		// setup rule for differance post_type
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('post_title', '<b>Title</b>', 'required'); 		// rule for title
		$this->form_validation->set_rules('expire_date', '<b>Expire Date</b>', 'required');  // rule for title
		// process form validation
		if ($this->form_validation->run()) {
			// if file was upload
			if ($this->process_file_upload('input_type_image', 'IMAGE')) {
				// prepare data before input to database
				$data = array(
					"post_title" => trim($this->input->post('post_title')),
					"post_type" => "IMAGE",
					"expire_date" => $this->input->post('expire_date'),
					"owner" => $this->session->userdata("user_id")
				);
				// insert post data to database return post_id
				$post_id = $this->Post_Model->insert($data);
				// if have post_id it mean new post is success input
				if ($post_id) {
					// send messsage back to user
					$this->send_message("Add new post", "a post added", "success");
					// load image manipulation library
					$this->load->library('image_lib');
					// process image one by one
					foreach ($this->upload->get_multi_upload_data() as $file) {
						// create image thumbnail
						$this->process_create_image_thumbnail($file['full_path'], THUMBNAIL);
						// attach file_owner to file array
						$file['file_owner'] = $this->user["user_id"];
						// insert to Files Table
						$file_id = $this->File_Model->insert($file);
						// insert to Attachement Table
						$this->Attachment_Model->insert(
							array(
								"post_id" => $post_id,
								"file_id" => $file_id
							)
						);
					}
					// redirect user to edit post/post_id
					$this->redirect("/dashboard/editpost/{$post_id}"); 
				}
			//image upload error
			} else { 
				$error = array('error' => $this->upload->display_errors());
				$this->smarty->view("post_add", $error);
			}
		// form validate error
		} else {
			$data["categories"] = $this->Category_Model->get_list();
			$this->smarty->view("post_add", $data);
		}
	}

	public function post_type_pdf() {
		$data['menu'] = $this->Category_Model->get_list();

		// setting up form validation rule
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('post_title', '<b>Title</b>', 'required'); 		// rule for title
		
		if ($this->form_validation->run()) {
			
			if ($this->process_file_upload('input_type_pdf', 'PDF')) {
				
				// prepare data before input to database
				$data = array(
					"post_title" => $this->input->post('post_title'),
					"post_type" => "PDF",
					"owner" => $this->session->userdata("user_id")
				);
				
				// insert post data to database return post_id
				$post_id = $this->Post_Model->insert($data);

				// if have post_id it mean new post is success input
				if ($post_id) {
					
					// send messsage back to user
					$this->send_message("Add new post", "a post added", "success");
			
					foreach ($this->upload->get_multi_upload_data() as $file) {

						// Attach file_owner
						$file['file_owner'] = $this->user["user_id"];
						// insert to Files Table
						$file_id = $this->File_Model->insert($file);
						// insert to Attachement Table
						$this->Attachment_Model->insert(
							array(
								"post_id" => $post_id,
								"file_id" => $file_id
							)
						);
					}

					// redirect user to edit post/post_id
					$this->redirect("/dashboard/editpost/{$post_id}"); 
				}

			//image upload error
			} else { 
				$error = array('error' => $this->upload->display_errors());
				$this->smarty->view("post_add", $error);
			}
		// form validate error
		} else {
			$this->smarty->view("post_add");
		}
	}

	public function post_type_text() {
		$data['menu'] = $this->Category_Model->get_list();
		
		// setup rule for differance post_type
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('post_title', '<b>Title</b>', 'required'); 		// rule for title
		$this->form_validation->set_rules('post_description', '<b>Description</b>', 'required'); 	// rule for title
		
		// Process for validation
		if ($this->form_validation->run()) {
			// prepare data before insert to database
			$data = array(
				'post_title' => $this->input->post('post_title'),
				'post_type' => "TEXT",
				'post_description' => $this->input->post('post_description'),
				'owner' => $this->session->userdata("user_id")
			);
			
			$post_id = $this->Post_Model->insert($data);

			if ($post_id) {
				// send messsage back to user
				$this->send_message("Add new post", "a post added", "success");
				// redirect user to edit post/post_id
				$this->redirect("/dashboard/editpost/{$post_id}"); 
			} else {
				// send messsage back to user
				$this->send_message("add new post", "can't create new post", "danger");
				// redirect user
				$this->redirect('/dashboard/add_post'); 
			}
		// form validation error
		} else {
			$this->smarty->view('post_add'); 
		}
	}

	private function update_post_type_image() {
		$data['menu'] = $this->Category_Model->get_list();
		
		$post_id = $this->uri->segment(3, 0);
		// setting form validation rule
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('post_title', '<b>Title</b>', 'trim|required');
		$this->form_validation->set_rules('expire_date', '<b>Expire Date</b>', 'trim|required');
		
		// process form validation
		if ($this->form_validation->run()) {
			// prepare data before input to database
			$data = array(
				'post_title' => $this->input->post('post_title'),
				'expire_date' => $this->input->post('expire_date')
			);

			$affect_row = $this->Post_Model->update($post_id, $data);
			
			// check if user add new file
			if ($_FILES['input_type_image']) {
				// check if file upload or not
				if ($this->process_file_upload('input_type_image', 'IMAGE')) {
					// load image resize library
					$this->load->library('image_lib');
					// process all image
					foreach ($this->upload->get_multi_upload_data() as $file) {
						// create image thumbnail
						$this->process_create_image_thumbnail($file['full_path'], THUMBNAIL);
						// attache file owner
						$file['file_owner'] = $this->user["user_id"];
						// insert to Files Table
						$file_id = $this->File_Model->insert($file);
						// insert to Attachement Table
						$this->Attachment_Model->insert(
							array(
								"post_id" => $post_id,
								"file_id" => $file_id
							)
						);
					}
				}
			}
			
			if ($affect_row > 0) {
				// send messsage back to user
				$this->send_message("Edit Post", "{$affect_row} post updated", "success");
			}

			// redirect user to post_edit
			$this->redirect("/dashboard/editpost/{$post_id}"); 
		// form validate error
		} else {
			// load edit post with alert error
			$data["post"] = $this->Post_Model->detail($post_id);
			$data["files"] = $this->get_post_file($post_id, $data["post"]['post_type']);
			$this->smarty->view("post_edit", $data);
		}
	}
	
	public function update_post_type_pdf() {
		$data['menu'] = $this->Category_Model->get_list();
		
		$post_id = $this->uri->segment(3, 0);
		// setting form validation rule
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('post_title', '<b>Title</b>', 'trim|required');
		
		// process form validation
		if ($this->form_validation->run()) {
			// prepare data before input to database
			$data = array(
				'post_title' => $this->input->post('post_title')
			);

			$affect_row = $this->Post_Model->update($post_id, $data);
			
			// check if user add new file
			if ($_FILES['input_type_pdf']) {
				// check if file upload or not
				if ($this->process_file_upload('input_type_pdf', 'pdf')) {
			
					// manage image location
					$directory = UPLOAD_PATH . $post_id;
					// manage thumnail location
					// process all pdf
					foreach ($this->upload->get_multi_upload_data() as $file) {
						// prepare location
						$file_path = UPLOAD_PATH . $file['file_name'];
						$new_path = $directory . "/" . $file['file_name'];

						// move to into post_id/
						$this->move_file($file_path, $new_path);
					}
				}
			}
			
			if ($affect_row > 0) {
				// send messsage back to user
				$this->send_message("Edit Post", "{$affect_row} post updated", "success");
			} else {
				$this->send_message("Edit Post", "{$affect_row} post updated", "warning");
			}

			// redirect user to post_edit
			$this->redirect("/dashboard/editpost/{$post_id}"); 
		// form validate error
		} else {
			// load edit post with alert error
			$data["post"] = $this->Post_Model->detail($post_id);
			$data["files"] = $this->get_post_file($post_id, $data["post"]['post_type']);
			$this->smarty->view("post_edit", $data);
		}
	}
	
	public function update_post_type_text() {
		$data['menu'] = $this->Category_Model->get_list();
		
		// setup rule for differance post_type
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('post_title', '<b>Title</b>', 'required'); 		// rule for title
		$this->form_validation->set_rules('post_description', '<b>Description</b>', 'required'); 	// rule for title
		
		// Process for validation
		if ($this->form_validation->run()) {
			
			// prepare data before insert to database
			$data = array(
				'post_title' => $this->input->post('post_title'),
				'post_type' => "TEXT",
				'post_description' => $this->input->post('post_description'),
				'owner' => $this->session->userdata("user_id")
			);
			
			$affect_row = $this->Post_Model->update($post_id, $data);

			if ($affect_row > 0) {
				// send messsage back to user
				$this->send_message("Edit Post", "{$affect_row} post updated", "success");
				// redirect user to edit post/post_id
				$this->redirect("/dashboard/editpost/{$post_id}"); 
			} else {
				// send messsage back to user
				$this->send_message("Edit Post", "No Post update", "danger");
				// redirect user
				$this->redirect('/dashboard/add_post'); 
			}
		// form validation error
		} else {
			$this->smarty->view('post_edit', $data); 
		}
	}

	public function get_post_file($post_id, $post_type) {
		$files = array();
		switch ($post_type) {
			case "IMAGE":
					$files = directory_map("./uploads/{$post_id}/thumbnail/");
				break;
			case "PDF":
					$files = directory_map("./uploads/{$post_id}/");
				break;
		}
		return $files;
	}

	public function deletepost() {

		$post_id = $this->uri->segment(3, 0);

		if($post_id != 0) {
			// if user in the same group then can delete post
			$this->load->model('User_Model');
			$user = $this->User_Model->detail($this->user_id);
			$post = $this->Post_Model->detail($post_id);

			// check is post & user in same group or admin
			if ($user['group_id'] == $post['group_id'] || $user['is_admin'] == 1)  {
				
				// process delete post
				$affect_row = $this->Post_Model->delete($post_id);

				// send message
				$this->send_message("Delete Post", "{$affect_row} post deleted", "success");
			// no permission to delete
			} else {
				$this->send_message("Delete Post", "U don't have permission to delete this post", "success");
			}

			$this->redirect("/post"); 

		} else {
			show_error('Sorry! Page are you looking for is not found.', 404);
		}
	}

	public function files($post_id, $type) {
		// load file name from post_id
		$data["files"] = $this->Post_Model->file_list($post_id);
		// check type
		switch ($type) {
			case 'IMAGE':
				echo $this->smarty->view('post_file_image', $data, TRUE);
				break;
			case 'PDF':
				echo $this->smarty->view('post_file_pdf', $data, TRUE);
				break;
		}
	}

	public function deletefile($post_id, $file_id, $attachment_id) {
		// detail attachment
		$attachment = $this->Attachment_Model->detail($attachment_id);
		// assign file_id
		$file_id = $attachment['file_id'];
		// deital file
		$file = $this->File_Model->detail($file_id);
		// locate file on disk
		$file_on_disk = UPLOAD_PATH . $file['file_name'];
		$file_thumbnail_on_disk = THUMBNAIL . $file['file_name'];
		
		// start transaction
		$this->db->trans_begin();
		// Test delete attachment
		$this->Attachment_Model->delete($attachment_id);
		// Test delete file information
		$this->File_Model->delete($file_id);

		// Check result
		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    $this->send_message("Delete file", "Can not delete <b>{$file['orig_name']}</b>","warning");
		} else {
		    $this->db->trans_commit();
			
			if (file_exists($file_on_disk)) {
				unlink($file_on_disk);
			}
			
			if (file_exists($file_thumbnail_on_disk)) {
				unlink($file_thumbnail_on_disk);
			}

		    $this->send_message("Delete file", "file <b>{$file['orig_name']}</b> has been deleted","success");
		}

		redirect("/post/update/{$post_id}");
	}

	private function move_file($file_target, $file_distination) {
		return rename($file_target, $file_distination);
	}

	private function process_create_image_thumbnail($original_image, $thumbnail_distination) {
		
		$this->image_lib->clear();
		// confilg location for image thumbnail
		$resize['image_library'] = 'gd2';
		$resize['source_image']	= $original_image;
		$resize['new_image'] = $thumbnail_distination;
		$resize['create_thumb'] = TRUE;
		$resize['thumb_marker'] = "";
		$resize['maintain_ratio'] = FALSE;
		$resize['width']	= 50;
		$resize['height']	= 50;

		$this->image_lib->initialize($resize);
		return $this->image_lib->resize();
	}

	private function process_file_upload($field, $type) {
		
		$config['upload_path'] = UPLOAD_PATH;
		$config['encrypt_name'] = TRUE;

		switch ($type) {
			case 'IMAGE':
				// config upload option
				$config['allowed_types'] = 'gif|jpg|png|bmp';
				break;
			case 'PDF':
				// config upload option
				$config['allowed_types'] = 'pdf';
				break;
		}
		$this->load->library('upload', $config);
		return $this->upload->do_multi_upload($field);
	}
}

/* End of file post.php */
/* Location: ./application/controllers/post.php */