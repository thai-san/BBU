<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post_model extends MY_Model {

	public function detail($post_id) {
		$sql = <<<SQL
SELECT
	p.*,
	u.group_id,
	g.group_name,
	c.category_name
FROM
	posts p
	LEFT JOIN users u ON (u.user_id = p.OWNER)
	LEFT JOIN groups g ON (g.group_id = u.group_id)
	LEFT JOIN categories c ON (c.category_id = p.category_id)
WHERE
	p.post_id = ?
SQL;
		$res = $this->db->query(
			$sql,
			array(
				$post_id
			)
		);

		return $res->row_array();
	}

	public function get_all_post() {
		$sql = <<<SQL
SELECT
	p.*,
	u.user_name,
	(
		SELECT
			COUNT(*) AS total_visitor
		FROM
			visitors v
		WHERE
			v.post_id = p.post_id
	) AS total_visitor
FROM
	posts p
	INNER JOIN users u ON (u.user_id = p.owner)
SQL;

		$res = $this->db->query(
			$sql,
			array(
			)
		);

		return $res->result_array();
	}

	public function manage($group_id) {
		$sql = <<<SQL
SELECT
	p.*,
	u.user_name,
	u.group_id,
	g.group_name,
	(
		SELECT
			COUNT(*) AS total_visitor
		FROM
			visitors v
		WHERE
			v.post_id = p.post_id
	) AS total_visitor
FROM
	posts p
	INNER JOIN users u on (u.user_id = p.owner)
	INNER JOIN groups g on (g.group_id = u.group_id)
WHERE
	u.group_id = ?
SQL;

		$res = $this->db->query(
			$sql,
			array(
				$group_id
			)
		);

		return $res->result_array();
	}

	public function file_list($post_id) {
		$sql = <<<SQL
SELECT
	a.*,
	f.file_name,
	f.file_upload_date,
	f.orig_name,
	f.file_size
FROM
	attachments a
	INNER JOIN files f on (f.file_id = a.file_id)
WHERE
	a.post_id = ?
SQL;

		$res = $this->db->query(
			$sql,
			array(
				$post_id
			)
		);

		return $res->result_array();
	}

	public function get_post_relate_category($group_id) {
		$sql = <<<SQL
SELECT
	p.*,
	u.user_id,
	u.user_name,
	u.is_enable AS is_user_enable,
	u.user_create_date,
	g.group_name
FROM
	posts p
	INNER JOIN users u ON (u.user_id = p. OWNER)
	INNER JOIN groups g ON (u.group_id = g.group_id)
WHERE
	u.group_id = ?
SQL;
		$res = $this->db->query(
			$sql,
			array(
				$group_id
			)
		);

		return $res->result_array();
	}
}

/* End of file post_model.php */
/* Location: ./application/models/post_model.php */