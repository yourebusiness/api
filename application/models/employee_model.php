<?php

class Employee_model extends CI_Model_2 {
	
	public function __construct() {
		parent::__construct();
	}

	public function getMasseurNamesByCompanyId($companyId) {
		$query = "SELECT id, nickname FROM employee where companyId = ?";
		$query = $this->db->query($query, array($companyId));
		return $query->result_array();
	}
}