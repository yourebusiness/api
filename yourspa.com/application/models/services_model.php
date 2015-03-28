<?php

class Services_model extends baseClass2 {
	
	public function __construct() {
		parent::__construct();
	}

	public function getServicesByCompanyId($companyId) {
		$query = "SELECT id, serviceName FROM services WHERE companyId = ?";
		$query = $this->db->query($query, array($companyId));
		return $query->result_array();
	}
}