<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Group_model extends MY_Model {
	public function manage() {
		$sql = <<<SQL
SELECT
	*,
	(
		SELECT
			COUNT(*) as total_user 
		FROM
			users u
		WHERE
			u.group_id = g.group_id
	 ) as total_user
FROM
	groups g
SQL;
		$res = $this->db->query(
			$sql,
			array(

			)
		);

		return $res->result_array();
	}

	public function update_name_check($old_name, $new_name) {
		$sql = <<<SQL
SELECT
	COUNT(*) AS result
FROM
	groups g
WHERE
	g.group_name != ? && g.group_name = ?
SQL;
		$res = $this->db->query(
			$sql,
			array(
				$old_name,
				$new_name
			)
		);

		return $res->row_array();
	}

	public function detail($group_id) {
		$sql =<<<SQL
SELECT
	*,
	(
		SELECT
			count(*) as total_user
		FROM
			users u
		WHERE
			u.group_id = g.group_id
	) as total_user
FROM
	groups g
WHERE
	g.group_id = ?
SQL;
		$res = $this->db->query(
			$sql,
			array(
				$group_id
			)
		);

		return $res->row_array();
	}

}
 
/* End of file group_model.php */
/* Location: ./application/models/group_model.php */