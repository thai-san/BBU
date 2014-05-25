<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
	private $table;
	private $id;

	public function __construct() {
		parent::__construct();
		$class_name = strtolower(substr(get_class($this), 0, strpos(get_class($this), '_')));
		$this->table = plural($class_name);
		$this->id = $class_name . '_id';
	}

	public function insert($data = array()) {

		return ($this->db->insert($this->table, $data) ? $this->db->insert_id() : false);
	}

	public function update($id, $data = array()) {

		return ($this->db->update($this->table, $data, array($this->id => $id)) ? $this->db->affected_rows() : false);
	}

	public function delete($id) {

		return ($this->db->delete($this->table, array($this->id => $id)) ? $this->db->affected_rows() : false);
	}

	public function detail($id) {

		return (($res = $this->db->get_where($this->table, array($this->id => $id))) ? $res->row_array() : false);
	}

	public function get_list() {

		return (($res = $this->db->get($this->table)) ? $res->result_array() : false);
	}
}
