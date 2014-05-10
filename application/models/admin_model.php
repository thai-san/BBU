<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Model extends CI_Model {
	public function user_list() {
		$sql = <<<SQL
SELECT
	*
FROM
	user
SQL;
		$res = $this->db->query(
			$sql,
			array(

			)
		);
		return $res->result_array();
	}
}

/* End of file admin.php */
/* Location: ./application/models/admin.php */