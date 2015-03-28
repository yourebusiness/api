<?php

class Customers_model extends baseClass2 {
	
	public function __construct() {
		parent::__construct();
	}

	public function getCustomersByCompanyId($companyId) {
		$query = "SELECT id, CONCAT(fName, ' ', midName, ' ', lName) AS customerName FROM customer WHERE companyId = ?";
		$query = $this->db->query($query, array($companyId));
		return $query->result_array();
	}
}