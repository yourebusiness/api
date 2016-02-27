<?php

class Company extends My_Model {

	public function __construct() {
		parent::__construct();
	}
	
	public function edit(array $data) {
    	$needles = array("company", "province", "city", "address", "phoneNo", "tin", "companyId");
    	$status = $this->checkArrayKeyExists($needles, $data);
        if ($status["statusCode"] != 0)
            return $status;

    	$query = "update company set companyName=?, province=?, city=?, address=?, telNo=?, tin=?, website=? where companyId=?";
    	$query = $this->db->query($query, array($data["company"], $data["province"], $data["city"], $data["address"], $data["phoneNo"], $data["tin"], $data["companyWebsite"], $data["companyId"]));
		if ( ! $query) {
			$msg = $this->db->_error_number();
			$num = $this->db->_error_message();
			log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
			return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR);
		}

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
    }

    public function getCompanyInfo($companyId) {
        if ($companyId == "")
            return FALSE;

        $query = "select companyName, address, province, city, telNo, website, tin from company where companyId = ?";
        $query = $this->db->query($query, array($companyId));
        if ( ! $query) {
			$msg = $this->db->_error_number();
			$num = $this->db->_error_message();
			log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
			return FALSE;
		}

		return $query->result_array();
    }

    public function getProvinceIdByCompanyId($companyId) {
        $query = "select province FROM company where companyId = ?";
        $query = $this->db->query($query, array($companyId));
        if ( ! $query) {
			$msg = $this->db->_error_number();
			$num = $this->db->_error_message();
			log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
			return FALSE;
		}

		return $query->result_array();
    }

    public function activateRegistration($hash) {
		if ($hash == "")
			return FALSE;

		$query = "UPDATE company set activated = 'Y' where BINARY uniqueCode = ?";
		$query = $this->db->query($query, array($hash));
        if ( ! $query) {
			$msg = $this->db->_error_number();
			$num = $this->db->_error_message();
			log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
			return FALSE;
		}

		return TRUE;
	}
}