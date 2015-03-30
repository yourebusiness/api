<?php

class Company_model extends baseClass2 {

	public function __construct() {
		parent::__construct();
	}

	// company phone number must be unique in company table
    private function okToAddTelNo($telNo) {
    	$query = $this->db->query("select companyId from company where telNo = ?", array($telNo));
    	if ( ! $query) {
    		$msg = $this->db->_error_message();
    		$num = $this->db->_error_number();
    		log_message("error", "Database error ($num) $msg");
			return FALSE;
    	}

    	$count = $query->num_rows();
    	if ($count < 1)
			return TRUE;
		else {
			error_log(parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": Tel no: $telNo");
    		return FALSE;
		}    			
    }
	// TIN must be unique in company table
    private function okToAddTIN($tin) {
    	$query = $this->db->query("select companyId from company where tin = ?", array($tin));
    	if ( ! $query) {
    		$msg = $this->db->_error_message();
    		$num = $this->db->_error_number();
    		log_message("error", "Database error ($num) $msg");
			return FALSE;
    	}

    	$count = $query->num_rows();
    	if ($count < 1)
			return TRUE;
		else {
			error_log(parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": TIN: $tin.");
    		return FALSE;
		}
    }
    // uniqueCode is unique in company table
    private function okToAddUniqueCode($hash) {
    	$query = $this->db->query("select companyId from company where uniqueCode = ?", array($hash));
    	if ( ! $query) {
    		$msg = $this->db->_error_message();
    		$num = $this->db->_error_number();
    		log_message("error", "Database error ($num) $msg");
			return FALSE;
    	}

    	$count = $query->num_rows();
    	if ($count < 1)
			return TRUE;
		else {
			error_log(parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": Hash: $hash.");
    		return FALSE;
		}
    }
	// username or email should not exist
	private function okToAddUsername($username) { // note: username = email
		if ($username == "")
			return FALSE;

		$query = $this->db->query("select userId from users where username = ? or email = ?", array($username, $username));
    	if ( ! $query) {
    		$msg = $this->db->_error_message();
    		$num = $this->db->_error_number();
    		log_message("error", "Database error ($num) $msg");
			return FALSE;
    	}

    	$count = $query->num_rows();
    	if ($count < 1)
			return TRUE;
		else {
			error_log(parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": username: $username.");
    		return FALSE;
		}
	}
	private function checkDataForAdd(array $data) {
        if (strlen($data["tin"]) < 12)
        	return false;
        if (!in_array($data["gender"], array("M", "F")))
        	return false;

        return true;
    }
    private function okToAddCompany(array $data) {
		if (isset($data["companyWebsite"]) && $data["companyWebsite"] != "") {
			$query = "select companyId from company where companyName=? or website=?";
    		$bind_param = array($data["company"], $data["companyWebsite"]);
    	} else {
            if (isset($data["company"]) && $data["company"] != "") {
                $query = "select companyId from company where companyName=?";
                $bind_param = array($data["company"]);
            } else {
                return FALSE; // $data["company"] should be set and not empty
            }
    	}

		$query = $this->db->query($query, $bind_param);
    	if ( ! $query) {
    		$msg = $this->db->_error_message();
    		$num = $this->db->_error_number();
    		log_message("error", "Database error ($num) $msg");
			return FALSE;
    	}

    	$count = $query->num_rows();

    	if ($count < 1)
			return TRUE;
		else {
			error_log(parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": username: $username.");
    		return FALSE;
		}
    }
    public function add(array $data) {
		$needles = array("company", "province", "city", "address", "phoneNo", "tin", "fName", "lName", "userEmail", "gender", "password", "hash");
		if( ! $this->checkArrayKeyExists($needles, $data))
    		return FALSE;
    	if ( ! $this->checkDataForAdd($data))
    		return FALSE;
    	if ( ! $this->okToAddUsername($data["userEmail"]))
    		return FALSE;
    	if ( ! $this->okToAddCompany($data))
    		return FALSE;
    	if ( ! $this->okToAddTIN($data["tin"]))
    		return FALSE;
    	if ( ! $this->okToAddUniqueCode($data["hash"]))
    		return FALSE;
    	if ( ! $this->okToAddTelNo($data["phoneNo"]))
    		return FALSE;

        $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);

		$sql1 = "insert into company(companyName, address, province, city, telNo, website, tin, uniqueCode, createDate, captcha)
			values( ?,  ?, ?, ?,  ?,  ?,  ?,  ?, now(), ?);";
		$sql2 = "SET @companyId = LAST_INSERT_ID();";
		$sql3 = "insert into users(companyId,userId,username, passwd, fName, lName, email, gender, createDate, role)
			values(@companyId, 1,  ?,  ?,  ?,  ?,  ?,  ?, now(), 0);";
        $sql4 = "insert into `documents`(companyId, documentCode, documentName, lastNo)
                    values(@companyId, 'BP', 'BusinessPartners', 0),
                    (@companyId, 'CU', 'Customers', 1),
                    (@companyId, 'EM', 'Employees', 0),
                    (@companyId, 'SVS', 'Services', 0),
                    (@companyId, 'TRAN', 'Transactions', 0),
                    (@companyId, 'USR', 'Users', 1);";
        $sql5 = "insert into customer(companyId, customerId, custType, fName, midName, lName, active, createdBy, createDate)
                values(@companyId, 1, 0, 'Guest', 'Guest', 'Guest', 'Y', 1, now());";

		$this->db->trans_start();
		$this->db->query($sql1, array($data["company"], $data["address"], $data["province"], $data["city"], $data["phoneNo"], $data["companyWebsite"], $data["tin"], $data["hash"], $data["captcha"]));
		$this->db->query($sql2);
		$this->db->query($sql3, array($data["userEmail"], $data["password"], $data["fName"], $data["lName"], $data["userEmail"], $data["gender"]));
		$this->db->query($sql4);
		$this->db->query($sql5);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return FALSE;
		}

		return TRUE;
    }
	
	public function edit(array $data) {
    	$needles = array("company", "province", "city", "address", "phoneNo", "tin", "companyId");
    	if ( ! $this->checkArrayKeyExists($needles, $data))
    		return false;

    	$query = "update company set companyName=?, province=?, city=?, address=?, telNo=?, tin=?, website=? where companyId=?";
    	$query = $this->db->query($query, array($data["company"], $data["province"], $data["city"], $data["address"], $data["phoneNo"], $data["tin"], $data["companyWebsite"], $data["companyId"]));
		if ( ! $query) {
			$msg = $this->db->_error_number();
			$num = $this->db->_error_message();
			log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
			return FALSE;
		}

		return TRUE;
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