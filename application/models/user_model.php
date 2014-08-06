<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends MY_Model {

	public function login($user_name, $password) {
		$sql = <<<SQL
SELECT
	*,
	COUNT(*) AS result
FROM
	users
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

	public function manage() {
		$sql = <<<SQL
SELECT
	*,
	(
		SELECT
			COUNT(*) AS total_post
		FROM
			posts p
		WHERE
			p. OWNER = u.user_id
	) AS total_post
FROM
	users u
	LEFT JOIN groups g ON (g.group_id = u.group_id)
SQL;
		$res = $this->db->query(
			$sql,
			array(

			)
		);

		return $res->result_array();
	}
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */