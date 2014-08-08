
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Category extends CI_Controller {
	public function __construct() {
		parent::__construct();
		if ($this->session->userdata("user_id")) {
			$this->user_id = $this->session->userdata("user_id");
			$this->load->model('Category_Model');
		} else {
			header("location:/"); exit();
		}
	}

	public function index() {
		$data['categories'] = $this->Category_Model->manage();
		$this->smarty->view('category_manage', $data);
	}

	public function addnew() {
		// setting up validation rule
		$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
		$this->form_validation->set_rules('category_name', '<b>Category Name</b>', 'trim|required|is_unique[categories.category_name]|min_length[3]|max_length[40]|callback_category_name_check');
		$this->form_validation->set_message('is_unique', 'The %s already exist');
		
		// process form validation
		if ($this->form_validation->run()) {
			// prepare data before insert to database
			$data = array(
				'category_name' => trim($this->input->post('category_name'))
			);
			// process insert data to database 
			$category_id = $this->Category_Model->insert($data);
			// check insert data result 
			if ($category_id) {
				// send messsage back to user
				$this->session->set_flashdata(
					array(
						"title" => "Create Category",
						"message" => "Category <b>{$data['category_name']}</b> has been created",
						"status" => "success"
					)
				);
			// insert fail
			} else {
				// send messsage back to user
				$this->session->set_flashdata(
					array(
						"title" => "Create Category",
						"message" => "Can't create category <b>{$data['category_name']}</b>",
						"status" => "danger"
					)
				);
			}

			redirect("/category/addnew"); exit();
		// form validate error
		} else {
			$this->smarty->view('category_add');
		}
	}

	public function edit() {
		
		$category_id = $this->uri->segment(3, 0);

		if ($category_id != 0) {
			// setting up validation rule
			$this->form_validation->set_error_delimiters('<li><p>', '</p></li>');
			$this->form_validation->set_rules('category_name', '<b>Category Name</b>', 'trim|required|is_unique[categories.category_name]|min_length[3]|max_length[40]|callback_category_name_check');
			$this->form_validation->set_message('is_unique', 'The %s already exist');
			$data['category'] = $this->Category_Model->detail($category_id);
			
			// process form validation
			if ($this->form_validation->run()) {
				
				// prepaire data before update
				$category  = array(
					'category_name' => trim($this->input->post('category_name'))
				);
				
				// process update
				$affect_row = $this->Category_Model->update($category_id, $category);
				
				// check update result
				if ($affect_row > 0) {
					// send messsage back to user
					$this->session->set_flashdata(
						array(
							"title" => "Update Category",
							"message" => "Category name change to {$category['category_name']}",
							"status" => "success"
						)
					);
				} else {
					// send messsage back to user
					$this->session->set_flashdata(
						array(
							"title" => "Update Category",
							"message" => "Can't change ",
							"status" => "danger"
						)
					);
				}

				redirect("/category/edit/{$category_id}"); exit();

			// page validate error
			} else {
				$this->smarty->view('category_edit', $data);
			}
		// show page not found
		} else {
			show_error('Sorry! Page are you looking for is not found.', 404);
		}

	}

	public function delete() {
		
		$category_id = $this->uri->segment(3,0);
		
		if ($category_id != 0) {
			
			// detail categroy
			$category = $this->Category_Model->detail($category_id);
			
			// check if category contain number of post
			if ($category['total_post'] > 0) {
				$this->session->set_flashdata(
					array(
						"title" => "Delete Category",
						"message" => "Can not delete category {$category['category_name']}. Beacause still have post inside",
						"status" => "warning"
					)
				);
			// Process delete category
			} else {
				// process delete category
				$affect_row = $this->Category_Model->delete($category_id);
				// check result
				if ($affect_row > 0) {
					$this->session->set_flashdata(
						array(
							"title" => "Delete Category",
							"message" => "{$affect_row} post updated",
							"status" => "success"
						)
					);
				// delete fail
				} else {
					$this->session->set_flashdata(
						array(
							"title" => "Delete Category",
							"message" => "Can't not delete category {$category['category_name']}",
							"status" => "success"
						)
					);
				}
			}

			// redirect user
			redirect("/dashboard/category"); exit();

		// show page not found
		} else {
			show_error('Sorry! Page are you looking for is not found.', 404);
		}
	}

 	public function category_name_check($name) {
		if (preg_match('/^[A-Za-z_ ][A-Za-z0-9_ ]{2,40}$/', $name)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('category_name_check', 'The %s is invalid');
			return FALSE;
		}
	}

}
 
/* End of file category.php */
/* Location: ./application/controllers/category.php */