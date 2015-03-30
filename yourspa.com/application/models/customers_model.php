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

	public function getAllCustomersByCompanyId($companyId = 0) {
		if ($companyId < 1)
			return array();

		$sql = "SELECT customerId, custType, fName, midName, lName FROM customer WHERE companyId = ?";
		$query = $this->db->query($sql, array($companyId));
		return $query->result_array();
	}

	private function formatDataForAdd(array $data) {
		if ( ! isset($data["midName"]) || $data["midName"] == "")
			$data["midName"] = NULL;

		return $data;
	}

	public function add(array $data) {
		$needles = array("companyId", "custType", "fName", "lName", "createdBy");
		if ( ! $this->checkArrayKeyExists($needles, $data))
			return FALSE;
		if (empty($data["companyId"]) || $data["custType"] == "" || empty($data["fName"]) || empty($data["lName"]) || empty($data["createdBy"]))
			return FALSE;

		$data = $this->formatDataForAdd($data);

		$returnValue = FALSE;

		$query1 = "SET @customerId=(SELECT CAST(lastNo+1 AS char(11)) FROM documents WHERE documentCode='CU' and companyId = ?);";
		$query2 = "insert into customer(companyId,customerId,custType,fName,midName,lName,createdBy,createDate)
			value( ?, @customerId, ?, ?, ?, ?, ?, NOW());";
		$query3 = "Update documents set lastNo=@customerId where documentCode='CU' and companyId = ?;";

		$this->db->trans_start();
		$this->db->query($query1, array($data["companyId"]));
		$this->db->query($query2, array($data["companyId"], $data["custType"], $data["fName"], $data["midName"], $data["lName"], $data["createdBy"]));
		$this->db->query($query3, array($data["companyId"]));
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
			$num = $this->db->_error_message();
			log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
			return FALSE;
		}

		return TRUE;
	}

}
