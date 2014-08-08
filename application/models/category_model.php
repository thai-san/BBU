<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends MY_Model {

	public function detail($category_id) {
		$sql = <<<SQL
SELECT
	*,
	(
		SELECT
			COUNT(*) total_post
		FROM
			posts p
		WHERE
			p.category_id = c.category_id
	) total_post
FROM
	categories c	
WHERE
	c.category_id = ?	
SQL;

		$res = $this->db->query($sql,
			array(
				$category_id
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
			COUNT(*) total_post
		FROM
			posts p
		WHERE
			p.category_id = c.category_id
	) total_post
FROM
	categories c	
SQL;

		$res = $this->db->query($sql,
			array(
				
			)
		);

		return $res->result_array();
	}

	public function post_list($category_id) {
		$sql = <<<SQL
SELECT
	*
FROM
	posts p
WHERE
	p.category_id = ?
ORDER BY
	p.post_date DESC
LIMIT 10
SQL;

		$res = $this->db->query($sql,
			array(
				$category_id
			)
		);

		return $res->result_array();	
	}

	public function get_all_post($category_id) {
		$sql = <<<SQL
SELECT
	*
FROM
	posts p
WHERE
	p.category_id = ?
ORDER BY
	p.post_date DESC
SQL;

		$res = $this->db->query($sql,
			array(
				$category_id
			)
		);

		return $res->result_array();
	}
}

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */