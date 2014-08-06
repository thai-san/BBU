<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Post_Model');
		if (!$this->session->userdata("user_id")) {
			header("location:/");
			exit();
		} else {
			$this->user_id = $this->session->userdata("user_id");
		}
	}

	public function addnew() {
		
		$post_type = $this->input->post('post_type');
		
		// If have post judy_type(array)
		if ($post_type) {
			switch ($post_type) {
				case 'TEXT':
					$this->post_type_text(); // post_type text
					break;
				case 'PDF':
					$this->post_type_pdf(); // post_type pdf
					break;
				default:
					$this->post_type_image(); // post_type image
			}
		// No form submit just load add new post normal
		} else {
			$this->smarty->view('post_add'); 
		}
	}

	public function post_type_image(){
		
		// setup rule for differance post_type
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('post_title', '<b>Title</b>', 'required'); 		// rule for title
		
		if ($this->form_validation->run()) {
			
			if ($this->process_file_upload('input_type_image', 'IMAGE')) {
				
				// prepare data before input to database
				$data = array(
					"post_title" => $this->input->post('post_title'),
					"post_type" => "IMAGE",
					"owner" => $this->session->userdata("user_id")
				);
				
				// load post model
				$this->load->model('Post_Model');
				
				// insert post data to database return post_id
				$post_id = $this->Post_Model->insert($data);

				// if have post_id it mean new post is success input
				if ($post_id) {
					
					// send messsage back to user
					$this->session->set_flashdata(
						array(
							"title" => "Add new post",
							"message" => "a post added",
							"status" => "success"
						)
					);

					// manage image location
					$directory = UPLOAD_PATH . $post_id;
					// manage thumnail location
					$thumbnail = $directory . "/thumbnail/";
					// load image library
					$this->load->library('image_lib');

					// check folder is success create or not
					if(mkdir($directory)) {
						// create thumbnail folder inside post_id/ folder
						mkdir($thumbnail);
						
						// process all image
						foreach ($this->upload->get_multi_upload_data() as $file) {
							
							$file_path = UPLOAD_PATH . $file['file_name'];
							$new_path = $directory . "/" . $file['file_name'];

							$this->process_create_image_thumbnail($file_path, $thumbnail);
							$this->move_file($file_path, $new_path);
						}
					// create folder error
					} else {
						// send message error can't create folder
						$this->session->set_flashdata(
							array(
								"title" => "Image Upload",
								"message" => "can't create new directory for image upload",
								"status" => "warning"
							)
						);
					}

					// redirect user to edit post/post_id
					redirect("/dashboard/editpost/{$post_id}"); exit();
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

	public function post_type_pdf() {
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
					$this->session->set_flashdata(
						array(
							"title" => "Add new post",
							"message" => "a post added",
							"status" => "success"
						)
					);

					$directory = UPLOAD_PATH . $post_id;
					// check folder is success create or not
					if(mkdir($directory)) {						
						// process all image
						foreach ($this->upload->get_multi_upload_data() as $file) {
							// prepare location
							$file_path = UPLOAD_PATH . $file['file_name'];
							$new_path = $directory . "/" . $file['file_name'];
							// move file to post_id/
							$this->move_file($file_path, $new_path);
						}
					// create folder error
					} else {
						// send message error can't create folder
						$this->session->set_flashdata(
							array(
								"title" => "PDF Upload",
								"message" => "can't create new directory for image upload",
								"status" => "warning"
							)
						);
					}

					// redirect user to edit post/post_id
					redirect("/dashboard/editpost/{$post_id}"); exit();
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
		
		// setup rule for differance post_type
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('post_title', '<b>Title</b>', 'required'); 		// rule for title
		$this->form_validation->set_rules('post_description', '<b>Description</b>', 'required'); 	// rule for title
		
		if ($this->form_validation->run()) {
			
			$data = array(
				'post_title' => $this->input->post('post_title'),
				'post_type' => "TEXT",
				'post_description' => $this->input->post('post_description'),
				'owner' => $this->session->userdata("user_id")
			);
			
			$this->load->model('Post_Model');

			$post_id = $this->Post_Model->insert($data);

			if ($post_id) {
				// send messsage back to user
				$this->session->set_flashdata(
					array(
						"title" => "Add new post",
						"message" => "a post added",
						"status" => "success"
					)
				);

				// redirect user to edit post/post_id
				redirect("/dashboard/editpost/{$post_id}"); exit();
			} else {
				// send messsage back to user
				$this->session->set_flashdata(
					array(
						"title" => "Add new post",
						"message" => "can't create new post",
						"status" => "danger"
					)
				);
				// redirect user
				redirect('/dashboard/add_post'); exit;
			}
		// form validation error
		} else {
			$this->smarty->view('post_add'); 
		}
	}

	public function index() {
		$data['posts'] = $this->Post_Model->manage($this->user_id);
		$this->smarty->view('post_manage', $data);
	}

	public function detail() {
		$this->smarty->view("post_detail");
	}

	public function lists() {
		$this->smarty->view("post_list");
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
			redirect("/dashboard/editpost/{$post_id}"); exit();
		}
	}

	private function update_post_type_image($post_id) {
		
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
			if ($_FILES['input_type_image']) {
				// check if file upload or not
				if ($this->process_file_upload('input_type_image', 'IMAGE')) {
			
					// manage image location
					$directory = UPLOAD_PATH . $post_id;
					// manage thumnail location
					$thumbnail = $directory . "/thumbnail/";
					// load image resize library
					$this->load->library('image_lib');
					// process all image
					foreach ($this->upload->get_multi_upload_data() as $file) {
						// prepare location
						$file_path = UPLOAD_PATH . $file['file_name'];
						$new_path = $directory . "/" . $file['file_name'];
						// create thumbnail
						$this->process_create_image_thumbnail($file_path, $thumbnail);
						// move to into post_id/
						$this->move_file($file_path, $new_path);
					}
				}
			}
			
			if ($affect_row > 0) {
				// send messsage back to user
				$this->session->set_flashdata(
					array(
						"title" => "Edit Post",
						"message" => "{$affect_row} post updated",
						"status" => "success"
					)
				);
			}

			// redirect user to post_edit
			redirect("/dashboard/editpost/{$post_id}"); exit();
		// form validate error
		} else {
			// load edit post with alert error
			$data["post"] = $this->Post_Model->detail($post_id);
			$data["files"] = $this->get_post_file($post_id, $data["post"]['post_type']);
			$this->smarty->view("edit_post", $data);
		}
	}
	
	public function update_post_type_pdf() {
		
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
				$this->session->set_flashdata(
					array(
						"title" => "Edit Post",
						"message" => "{$affect_row} post updated",
						"status" => "success"
					)
				);
			}

			// redirect user to post_edit
			redirect("/dashboard/editpost/{$post_id}"); exit();
		// form validate error
		} else {
			// load edit post with alert error
			$data["post"] = $this->Post_Model->detail($post_id);
			$data["files"] = $this->get_post_file($post_id, $data["post"]['post_type']);
			$this->smarty->view("edit_post", $data);
		}
	}
	
	public function update_post_type_text() {
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

	public function editpost() {
	
		$post_id = $this->uri->segment(3, 0);
		
		if ($post_id != 0 ) {
		
			$this->load->model('Post_Model');
			$data['post'] = $this->Post_Model->detail($post_id);
			$data['files'] = $this->get_post_file($post_id, $data['post']['post_type']);
			$this->smarty->view('edit_post.html', $data);
		} else {
			die("post are you looking for dose not exists or deleted.");
		}
	}

	public function deletefile() {
		// get post_id from url
		$post_id = $this->uri->segment(3, 0);
		// get file_name from url
		$file_name = $this->uri->segment(4, "");

		if ($post_id != 0 && $file_name != "") {
			
			$this->load->model('Post_Model');
			$post = $this->Post_Model->detail($post_id);

			$file_path = UPLOAD_PATH . $post_id . '/' . $file_name;
			$thumbnail = UPLOAD_PATH . $post_id . '/thumbnail/' . $file_name;

			if(file_exists($file_path)) {
				switch ($post['post_type']) {
					case 'IMAGE':
						unlink($file_path);
						unlink($thumbnail);
						break;
					case 'PDF':
						unlink($file_path);
						break;
				}
			} else {
				die('file not exists.');
			}
			redirect("dashboard/editpost/{$post_id}"); exit();
		}
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