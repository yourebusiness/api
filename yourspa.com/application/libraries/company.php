<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include "../.inc/globals.inc.php";

class Company extends baseClass {
	private $mysqli;

	public function __construct() {
		global $db;

		$this->mysqli = new mysqli($db["hostname"], $db["username"], $db["password"], $db["database"]);
		if ($this->mysqli->connect_errno)
			die("Connection error: " . mysqli_connect_errno() . ": " . mysqli_connect_error());
	}

	// company phone number must be unique in company table
    private function okToAddTelNo($telNo) {
    	$returnResult = false;

		$stmt = $this->mysqli->prepare("select companyId from company where telNo=?");
		$stmt->bind_param("s", $telNo);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows < 1)
			$returnResult = true;
		else {
			error_log(parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": telNo.");
    		return false;
		}    			

		return $returnResult;
    }
	// TIN must be unique in company table
    private function okToAddTIN($tin) {
    	$returnResult = false;
		$stmt = $this->mysqli->prepare("select companyId from company where tin=?");
		$stmt->bind_param("s", $tin);
    	$stmt->execute();
    	$stmt->store_result();
    	if ($stmt->num_rows < 1)
    		$returnResult = true;
    	else {
    		error_log(parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": TIN.");
    		return false;
    	}

    	return $returnResult;
    }
    // uniqueCode is unique in company table
    private function okToAddUniqueCode($hash) {
    	$returnResult = false;
		$stmt = $this->mysqli->prepare("select companyId from company where uniqueCode=?");
		$stmt->bind_param("s", $hash);
    	$stmt->execute();
    	$stmt->store_result();
    	if ($stmt->num_rows < 1)
    		$returnResult = true;
    	else {
    		error_log(parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": uniqueCode.");
    		return false;
    	}

    	return $returnResult;
    }
	// username or email should not exist
	private function okToAddUsername($username) { // note: username = email
		if ($username == "")
			return false;

		$returnResult = false;

		$sql = "select userId from users where username=? or email=?";
		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("ss", $username, $username);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows < 1)
			$returnResult = true;
		else {
			error_log(parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": username/email.");
    		return false;
		}

		return $returnResult;
	}
	private function checkDataForAdd(array $data) {
        if (strlen($data["tin"]) < 12)
        	return false;
        if (!in_array($data["gender"], array("M", "F")))
        	return false;

        return true;
    }
    private function okToAddCompany(array $data) {
    	$returnResult = false;
    	if (isset($data["companyWebsite"]) && $data["companyWebsite"] != "") {
    		$stmt = $this->mysqli->prepare("select companyId from company where companyName=? or website=?");
    		$stmt->bind_param("ss", $data["company"], $data["companyWebsite"]);
    	} else {
            if (isset($data["company"]) && $data["company"] != "") {
                $stmt = $this->mysqli->prepare("select companyId from company where companyName=?");
                $stmt->bind_param("s", $data["company"]);
            } else {
                return false; // $data["company"] should be set and not empty
            }
    	}

    	$stmt->execute();
    	$stmt->store_result();
    	if ($stmt->num_rows < 1)
    		$returnResult = true;
    	else {
    		error_log(parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": Existing company name or website.");
    		$returnResult = false;
    	}

    	return $returnResult;
    }
    public function add(array $data) {
		$needles = array("company", "province", "city", "address", "phoneNo", "tin", "fName", "lName", "userEmail", "gender", "password", "hash");
    	if(!$this->checkArrayKeyExists($needles, $data))
    		return false;
    	if (!$this->checkDataForAdd($data))
    		return false;
    	if (!$this->okToAddUsername($data["userEmail"]))
    		return false;
    	if (!$this->okToAddCompany($data))
    		return false;
    	if (!$this->okToAddTIN($data["tin"]))
    		return false;
    	if (!$this->okToAddUniqueCode($data["hash"]))
    		return false;
    	if (!$this->okToAddTelNo($data["phoneNo"]))
    		return false;

        $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);

		$returnResult = false; // default value

		$sql1 = sprintf("insert into company(companyName, address, province, city, telNo, website, tin, uniqueCode, createDate)
			values('%s', '%s', %d, %d, '%s', '%s', '%s', '%s', now());",
			$data["company"], $data["address"], $data["province"], $data["city"], $data["phoneNo"], $data["companyWebsite"], $data["tin"], $data["hash"]);
		$sql2 = "SET @companyId = LAST_INSERT_ID();";
		$sql3 = sprintf("insert into users(companyId,userId,username, passwd, fName, lName, email, gender, createDate, role)
			values(@companyId, 1, '%s', '%s', '%s', '%s', '%s', '%s', now(), 0);",
			 $data["userEmail"], $data["password"], $data["fName"], $data["lName"], $data["userEmail"], $data["gender"]);
        $sql4 = "insert into `documents`(companyId, documentCode, documentName, lastNo)
                    values(@companyId, 'BP', 'BusinessPartners', 0),
                    (@companyId, 'CU', 'Customers', 0),
                    (@companyId, 'EM', 'Employees', 0),
                    (@companyId, 'SVS', 'Services', 0),
                    (@companyId, 'TRAN', 'Transactions', 0),
                    (@companyId, 'USR', 'Users', 1);";

		try {
			$this->mysqli->autocommit(false);
			if (!$this->mysqli->query($sql1))
				throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql2))
				throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql3))
				throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql4))
				throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
			
			$this->mysqli->commit();
			$returnResult = true;
		} catch (Exception $e) {
            error_log($e->getMessage());
			$this->mysqli->rollback();
			$returnResult = false;
		} finally {
			$this->mysqli->autocommit(true);
		}

		return $returnResult;
    }

    private function checkArrayKeyExists(array $needles, array $haystack) {
    	foreach ($needles as $needle) {
            if (!array_key_exists($needle, $haystack)) {
                error_log(parent::ERRORNO_INVALID_PARAMETER . ": " . parent::ERRORSTR_INVALID_PARAMETER);
                return false;
            }
        }

    	return true;
    }
    public function edit(array $data) {
    	$needles = array("company", "province", "city", "address", "phoneNo", "tin", "companyId");
    	if (!$this->checkArrayKeyExists($needles, $data))
    		return false;

    	$returnResult = false;

    	$sql = "update company set companyName=?, province=?, city=?, address=?, telNo=?, tin=?, website=? where companyId=?";
    	if (!$stmt = $this->mysqli->prepare($sql))
            error_log("Error preparing sql");            
    	if (!$stmt->bind_param("siissssi", $data["company"], $data["province"], $data["city"], $data["address"], $data["phoneNo"], $data["tin"], $data["companyWebsite"], $data["companyId"]))
           error_log("Error binding sql.");
    	if ($stmt->execute())
    		$returnResult = true;
        else
            error_log("Error executing sql.");

    	return $returnResult;
    }    

    public function activateRegistration($hash) {
		if ($hash == "")
			return false;

		$sql = "update company set activated = 'Y' where BINARY uniqueCode = ?";
		if (!$stmt = $this->mysqli->prepare($sql))
			return false;
		if (!$stmt->bind_param("s", $hash))
			return false;
		if (!$stmt->execute())
			return false;

		return true;
	}

    public function getCompanyInfo($companyId) {
        if ($companyId == "")
            return false;

        $arr = array();

        $sql = "select companyName, address, province, city, telNo, website, tin from company where companyId=$companyId";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
            $arr[] = $row;

        return $arr;
    }

    public function getProvinceIdByCompanyId($companyId) {
        if ($companyId == "")
            return false;

        $arr = array();

        $sql = "select province FROM company where companyId=$companyId";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
            $arr[] = $row;

        return $arr;
    }

	public function __destruct() {
		$this->mysqli->close();
	}
}