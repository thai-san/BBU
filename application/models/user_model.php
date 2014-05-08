<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model {

	public function login($user_name, $password) {
		$sql = <<<SQL
SELECT
	*,
	COUNT(*) AS result
FROM
	user
WHERE
	user_name = ? && password = ?
SQL;
		$res = $this->db->query(
			$sql,
			array(
				$user_name,
				$password
			)
		);

		return $res->row_array();
	}

}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */