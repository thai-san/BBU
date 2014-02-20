<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_model extends CI_Model {

	function detail($student_id)
	{
		$sql = <<<SQL
SELECT

	std.*, 
	
	pob.PROVINCE 				AS POB_PROVINCE,
	pob.PROVINCE_IN_KHMER 		AS POB_IN_KHMER,
	pob.IS_CITY 				AS POB_IS_CITY,
	
	rce.RACE 					AS RACE,
	rce.RACE_IN_KHMER 			AS RACE_IN_KHMER,
	
	nnl.NATIONALITY 			AS NATIONALITY,
	nnl.NATIONALITY_IN_KHMER 	AS NATIONALITY_IN_KHMER,
	
	prv.PROVINCE 				AS FROM_PROVINCE,
	prv.PROVINCE_IN_KHMER 		AS FROM_PROVINCE_IN_KHMER,
	prv.IS_CITY 				AS FROM_PROVINCE_IS_CITY,
	
	ctp.CONTACT_PERSON_NAME 	AS CONTACT_PERSON_NAME,
	ctp.ADDRESS 				AS CONTACT_ADDRESS,
	ctp.JOB 					AS CONTACT_PERSON_JOB,
	ctp.PHONE 					AS CONTACT_PERSON_PHONE,

	std_jb.JOB 					AS STUDENT_JOB,
	std_jb.JOB_IN_KHMER 		AS STUDENT_JOB_IN_KHMER

FROM

	STUDENT std	
	LEFT JOIN PROVINCE pob 		 ON (pob.PROVINCE_ID = std.PLACE_OF_BIRTH_ID)
	LEFT JOIN RACE rce 			 ON (rce.RACE_ID = std.RACE_ID)
	LEFT JOIN NATIONALITY nnl 	 ON (nnl.NATIONALITY_ID = std.NATIONALITY_ID)
	LEFT JOIN PROVINCE prv 		 ON (prv.PROVINCE_ID = std.PLACE_OF_BIRTH_ID)
	LEFT JOIN CONTACT_PERSON ctp ON (ctp.CONTACT_PERSON_ID = std.CONTACT_PERSON_ID)
	LEFT JOIN STUDENT_JOB std_jb ON (std_jb.JOB_ID = std.JOB_ID)
WHERE
	STUDENT_ID = ?
SQL;

		$sqlsrv = $this->load->database('BBU', TRUE);

		$res = $sqlsrv->query(
			$sql,
			array(
				$student_id
			)
		);

		return $res->row_array();
	}
	
	function score($student_id)
	{
		$sql = <<<SQL
SELECT
	sc.*,
	sc.FINAL_SCORE + sc.MID_TERM_SCORE 	AS TOTAL_SCORE,
	cur.COURSE_FULL_NAME 				AS COURSE_FULL_NAME,
	cur.COURSE_FULL_NAME_IN_KHMER 		AS COURSE_FULL_NAME_IN_KHMER,
	cur.COURSE_SHORT_NAME  				AS COURSE_SHORT_NAME,
	cur.COURSE_SHORT_NAME_IN_KHMER 		AS COURSE_SHORT_NAME_IN_KHMER,
	cur.CREDIT 							AS CREDIT,
	cur.IS_GENERAL_COURSE 				AS IS_GENERAL_COURSE,
	cur.IS_STATE_EXAM_COURSE 			AS IS_STATE_EXAM_COURSE,
	cur.NUMBER_OF_HOURS 				AS NUMBER_OF_HOURS,
	stu_grp.STUDENT_ID 					AS STUDENT_ID,
	stu_grp.TERM_NO 					AS TERM_NO,
	stu_grp.GROUP_ID 					AS GROUP_ID
FROM
	SCORE sc
	INNER JOIN COURSE cur 				ON (cur.COURSE_ID = sc.COURSE_ID)
	INNER JOIN STUDENT_GROUP stu_grp 	ON (stu_grp.STUDENT_GROUP_ID = sc.STUDENT_GROUP_ID)
WHERE
	stu_grp.STUDENT_ID = ?
ORDER BY
	stu_grp.TERM_NO DESC
SQL;

		$sqlsrv = $this->load->database('BBU', TRUE);

		$res = $sqlsrv->query(
			$sql,
			array(
				$student_id
			)
		);

		return $res->result_array();

	}

	public function group($student_id)
	{
		$sql = <<<SQL
SELECT
	*
FROM
	V_THAISAN_STUDENT_GROUP_HISTORY g
WHERE
	g.STUDENT_ID = ?
SQL;
		$sqlsrv = $this->load->database('BBU', TRUE);

		$res = $sqlsrv->query(
			$sql,
			array(
				$student_id
			)
		);

		return $res->result_array();
	}

	public function payment($student_id)
	{
		$sql = <<<SQL
SELECT
	*
FROM
	V_THAISAN_STUDENT_PAYMENT p
WHERE
	p.STUDENT_ID = ?
SQL;
		$sqlsrv = $this->load->database('BBU', TRUE);

		$res = $sqlsrv->query(
			$sql,
			array(
				$student_id
			)
		);

		return $res->result_array();
	}

}

/* End of file student_model.php */
/* Location: ./application/models/student_model.php */