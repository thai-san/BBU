<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post_model extends MY_Model {
	public function manage($user_id) {
		$sql = <<<SQL
SELECT
	*, (
		SELECT
			COUNT(*) AS total_visitor
		FROM
			visiters v
		WHERE
			v.post_id = p.post_id
	) AS total_visitor
FROM
	posts p
WHERE
	p.owner = ?
SQL;

		$res = $this->db->query(
			$sql,
			array(
				$user_id
			)
		);

		return $res->result_array();
	}
}

/* End of file post_model.php */
/* Location: ./application/models/post_model.php */