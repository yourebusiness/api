<?php

class Customers extends MY_Model {
	
	public function __construct() {
		parent::__construct();
	}

	public function getCustomersBasicInfoByCompanyId($companyId) {
		$query = "SELECT customerId, CONCAT(fName, ' ', midName, ' ', lName) AS customerFullName FROM customers WHERE companyId = ?";
		$query = $this->db->query($query, array($companyId));
		return $query->result_array();
	}

	public function getCustomersListByCompanyId($companyId) {
		$sql = "SELECT customerId, custType, fName, midName, lName, gender, active FROM customers WHERE companyId = ?";
		$query = $this->db->query($sql, array($companyId));
		return $query->result_array();
	}

	private function formatDataForAdd(array $data) {
		if ( ! isset($data["midName"]) || $data["midName"] == "")
			$data["midName"] = NULL;

		return $data;
	}

	private function okToAdd(array $data) {
		if (empty($data["companyId"]) || $data["custType"] == "" || empty($data["fName"]) || empty($data["lName"]) || empty($data["createdBy"]))
			return array("statusCode" => parent::ERRORNO_EMPTY_VALUE, "statusMessage" => parent::ERRORSTR_EMPTY_VALUE, "statusDesc" => "");
		
		// we only accept Y/N for active
		if (!in_array($data["active"], array("Y", "N")))
			return array("statusCode" => parent::ERRORNO_INVALID_VALUE, "statusMessage" => parent::ERRORSTR_INVALID_VALUE, "statusDesc" => "Active value should only be Y or N.");

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
	}

	public function add(array $data) {
		if (!$this->checkCompanySubscription($data["companyId"]))
			return array("statusCode" => parent::ERRORNO_NO_SUBSCRIPTION, "statusMessage" => parent::ERRORSTR_NO_SUBSCRIPTION, "statusDesc" => "");

		$needles = array("companyId", "custType", "fName", "lName", "gender", "active", "createdBy");
		$status = $this->checkArrayKeyExists($needles, $data);
		if ($status["statusCode"] != 0)
			return $status;

		$data = $this->formatDataForAdd($data);

		$status = $this->okToAdd($data);
		if ($status["statusCode"] != 0)
			return $status;

		$query1 = "SET @customerId=(SELECT CAST(lastNo+1 AS char(11)) FROM documents WHERE documentCode='CU' and companyId = ?);";
		$query2 = "INSERT INTO customer(companyId,customerId,custType,fName,midName,lName,gender,active,createdBy,createDate)
			value( ?, @customerId, ?, ?, ?, ?, ?, NOW());";
		$query3 = "UPDATE documents set lastNo=@customerId WHERE documentCode='CU' AND companyId = ?;";
		$query4 = "SELECT @customerId as newId;";

		$this->db->trans_start();
		$this->db->query($query1, array($data["companyId"]));
		$this->db->query($query2, array($data["companyId"], $data["custType"], $data["fName"], $data["midName"], $data["lName"], $data["gender"], $data["active"], $data["createdBy"]));
		$this->db->query($query3, array($data["companyId"]));
		$query = $this->db->query($query4);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		$row = $query->row_array();

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK, "newId" => $row["newId"]);
	}

}
