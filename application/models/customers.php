<?php

class Customers extends MY_Model {
	
	public function __construct() {
		parent::__construct();
	}

	/* for download */
	public function getCustomersByCompanyId($companyId) {
		$query = "SELECT customerId, custType, fName, midName, lName, gender, active FROM customers WHERE companyId = ?";
		$query = $this->db->query($query, array($companyId));

		if (!$query) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		if ($query->num_rows())
			return $query->result_array();
		else
			return array();
	}

	public function getCustomersBasicInfoByCompanyId($companyId) {
		$query = "SELECT customerId, CONCAT(fName, ' ', midName, ' ', lName) AS customerFullName FROM customers WHERE companyId = ? AND active='Y'";
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
		$query2 = "INSERT INTO customers(companyId,customerId,custType,fName,midName,lName,gender,active,defaultCustomer,createdBy,createDate)
			value( ?, @customerId, ?, ?, ?, ?, ?, ?, 'N', ?, NOW());";
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

	public function edit(array $data) {
		$needles = array("custType", "customerId", "fName", "lName", "gender", "active", "updatedBy", "companyId");
    	$status = $this->checkArrayKeyExists($needles, $data);
        if ($status["statusCode"] != 0)
            return $status;

    	$query = "UPDATE customers SET custType = ?, fName = ?, midName = ?, lName = ?, gender = ?, active = ?, updatedBy = ? WHERE companyId = ? AND customerId = ?";
    	$query = $this->db->query($query, array($data["custType"], $data["fName"], $data["midName"], $data["lName"], $data["gender"], $data["active"], $data["updatedBy"], $data["companyId"], $data["customerId"]));
		if ( ! $query) {
			$msg = $this->db->_error_number();
			$num = $this->db->_error_message();
			log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
			return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR);
		}

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
	}

	private function _okToDeleteRecord($customerId, $companyId) {
		$query = "SELECT trans FROM customers WHERE customerId = ? AND companyId = ? AND (trans='Y' or defaultCustomer = 'Y')";
		$query = $this->db->query($query, array($customerId, $companyId));

		if (!$query) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		if ($query->num_rows())
			return FALSE;
		else
			return TRUE;
	}

	public function delete(array $data) {
		if ($this->session->userdata["role"] > 0)
			return array("statusCode" => parent::ERRORNO_NOT_AUTHORIZED, "statusMessage" => parent::ERRORSTR_NOT_AUTHORIZED, "statusDesc" => "");

		if (!isset($data["customerIds"]))
			return array("statusCode" => parent::ERRORNO_INVALID_PARAMETER, "statusMessage" => parent::ERRORSTR_INVALID_PARAMETER, "statusDesc" => "Missing key: customerIds");

		$cannotBeDeleted = 0;

		foreach ($data["customerIds"] as $customerId) {
			if (!$this->_okToDeleteRecord($customerId, $data["companyId"])) {
				$cannotBeDeleted++;
			} else {
				$sql1 = "SET @id = (SELECT id FROM `customers` WHERE customerId = ? AND companyId = ?);";
				$sql2 = "DELETE FROM `customers` WHERE id = @id;";

				$this->db->trans_start();
				$this->db->query($sql1, array($customerId, $data["companyId"]));
				$this->db->query($sql2);
				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
					$msg = $this->db->_error_number();
		            $num = $this->db->_error_message();
		            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
		            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
				}
			} //else
		} //foreach

		if ($cannotBeDeleted)
			return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK, "statusDesc" => "One or more record(s) cannot be deleted.");
		else
			return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
	}

}	/* end for Customer.php */
