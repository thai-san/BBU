<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Visitor_Model extends MY_Model {
	
	public function insert($data) {

		$sql = <<<SQL
INSERT INTO visitors (
	ip_address,
	student_id,
	post_id,
	visite_date
)
SELECT
	?,
	?,
	p.post_id,
	CURRENT_TIMESTAMP
FROM
	posts p
WHERE
	p.post_id = post_id
ON DUPLICATE KEY UPDATE visite_date = CURRENT_TIMESTAMP
SQL;
		$res = $this->db->query($sql,
			array(
				$data['ip_address'],
				$data['student_id'],
				$data['post_id']
			)
		);
		
		return ($res ? true : false);
	}	
}
 
/* End of file visitor_model.php */
/* Location: ./application/models/visitor_model.php */