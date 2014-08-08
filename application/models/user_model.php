<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends MY_Model {

	public function login($user_name, $password) {
		$sql = <<<SQL
SELECT
	u.*,
	g.group_name,
	COUNT(*) AS result
FROM
	users u
	LEFT JOIN groups g ON (g.group_id = u.group_id)
WHERE
	u.user_name = ? && u.password = ?
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

	public function detail($user_id) {
		$sql = <<<SQL
SELECT
	u.*,
	g.group_id,
	g.group_name,
	COUNT(*) AS result
FROM
	users u
	INNER JOIN groups g on (g.group_id = u.group_id)
WHERE
	u.user_id = ?
SQL;
		$res = $this->db->query(
			$sql,
			array(
				$user_id
			)
		);

		return $res->row_array();
	}

}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */