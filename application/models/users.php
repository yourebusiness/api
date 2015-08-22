<?php

class Users extends My_Model {

	public function __construct() {
		parent::__construct();
	}

	public function getUsersExceptCurrentByCompanyId($myUserId, $companyId) {
		$query = "select userId,username,fName,midName,lName,gender,active,role from users where userId <> ? and companyId = ?";
		$query = $this->db->query($query, array($myUserId, $companyId));
		return $query->result_array();
	}

	public function getUsersByCompanyId($includeCurrent, $myUserId, $myCompanyId) {
		if ($includeCurrent) {
			$query = "SELECT userId,username,fName,midName,lName,gender,active,role from users WHERE companyId = ?";
			$bindArray = array($myCompanyId);
		} else {
			$query = "SELECT userId,username,fName,midName,lName,gender,active,role from users WHERE userId <> ? and companyId = ?";
			$bindArray = array($myUserId, $myCompanyId);
		}

		$query = $this->db->query($query, $bindArray);
		if (!$query) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		return $query->result_array();
	}

	private function countActiveUsersByCompanyId($companyId) {
		if (intval($companyId) < 1)
			return array("statusCode" => parent::ERRORNO_INVALID_PARAMETER, "statusMessage" => parent::ERRORSTR_INVALID_PARAMETER, "statusDesc" => "");

		$query = $this->db->query("select count(*) as cnt from users where `active`='Y' and companyId = ?", array($companyId));
		if (!$query) {
    		$msg = $this->db->_error_message();
    		$num = $this->db->_error_number();
    		log_message("error", "Database error ($num) $msg");
			return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
    	}

    	$row = $query->row_array();

    	// we only allow max 4 active users per company. Static for now.
    	if ($row["cnt"] > 3)
    		return array("statusCode" => parent::ERRORNO_MAX_REACHED, "statusMessage" => parent::ERRORSTR_MAX_REACHED, "statusDesc" => 'Deactivate other users first to add new one or add this one as deactivated.');
    	else
    		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
	}

	// username or email should not exist
	private function okToAddUsername($username) { // note: username = email
		$username = trim($username);
        if ($username == "")
			return array("statusCode" => parent::ERRORNO_EMPTY_VALUE, "statusMessage" => parent::ERRORSTR_EMPTY_VALUE, "statusDesc" => "");

		$query = $this->db->query("select userId from users where username = ? or email = ?", array($username, $username));
    	if (!$query) {
    		$msg = $this->db->_error_message();
    		$num = $this->db->_error_number();
    		log_message("error", "Database error ($num) $msg");
			return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
    	}

    	$count = $query->num_rows();
    	if ($count < 1)
			return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
		else {
			log_message("error", parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": username: $username.");
    		return array("statusCode" => parent::ERRORNO_DB_VALUE_EXISTS, "statusMessage" => parent::ERRORSTR_DB_VALUE_EXISTS, "statusDesc" => "");;
		}
	}

	// add new record
	public function add(array $data) {
		$needles = array("username", "password", "fName", "lName", "gender", "active", "role", "createdBy", "companyId");

		$status = $this->checkArrayKeyExists($needles, $data);
		if ($status["statusCode"] != 0)
			return $status;

		// we only accept Y/N for active
		if (!in_array($data["active"], array("Y", "N")))
			return array("statusCode" => parent::ERRORNO_INVALID_PARAMETER, "statusMessage" => parent::ERRORSTR_INVALID_PARAMETER, "statusDesc" => "Active value should only be Y or N.");

		$data["email"] = $data["username"]; // because they are the same; For future use.
		$password = password_hash($data["password"], PASSWORD_BCRYPT);

		$status = $this->okToAddUsername($data["username"]);
		if ($status["statusCode"] != 0)
			return $status;

		$status = $this->countActiveUsersByCompanyId($data["companyId"]);
		if (($status["statusCode"] != 0) && ($data["active"] == "Y"))
			return $status;

		$sql1 = "SET @userId=(SELECT CAST(lastNo+1 AS char(11)) FROM documents WHERE documentCode='USR' and companyId = ?);";
		$sql2 = "insert into users(companyId, userId, username, passwd, fName, midName, lName, email, address, gender, createDate, createdBy, active, role)
				values(?, @userId, ?, ?,  ?,  ?,  ?,  ?,  ?,  ?, now(), ?, ?, ?);";
		$sql3 = "Update documents set lastNo=@userId where documentCode='USR' and companyId = ?;";
		$sql4 = "select @userId as newUserId;";

		$this->db->trans_start();
		$this->db->query($sql1, array($data["companyId"]));
		$this->db->query($sql2, array($data["companyId"], $data['username'], $password, $data['fName'], $data['midName'], $data['lName'], $data['email'], $data['address'], $data['gender'], $data['createdBy'], $data["active"], $data['role']));
		$this->db->query($sql3, array($data["companyId"]));
		$query = $this->db->query($sql4);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		$row = $query->row_array();

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK, "newUserId" => $row["newUserId"]);
	}

	public function login(array $data) {
    	$needles = array("username", "password");
    	if (!$this->checkArrayKeyExists($needles, $data))
    		return FALSE;

		$query = "select passwd from `users` where username=?";
		$query = $this->db->query($query, array($data["username"]));

		if (!$query) {
    		$msg = $this->db->_error_message();
    		$num = $this->db->_error_number();
    		log_message("error", "Database error ($num) $msg");
			return FALSE;
    	}

		if ($query->num_rows() != 1)
			return FALSE;

		$row = $query->row_array();

		if (!password_verify($data["password"], $row["passwd"]))
			return FALSE;
		
		$query = $this->db->query("update users set lastLogin=now() where username=?", array($data["username"]));
		if (!$query) {
			$msg = $this->db->_error_number();
			$num = $this->db->_error_message();
			log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
			return FALSE;
		}

		return TRUE;
    }

    private function _getUserStatus($userId, $companyId) {
    	$query = $this->db->query("select `active` from users where userId = ? and companyId = ?", array($userId, $companyId));
    	if (!$query) {
    		$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR);
    	}

    	return $query->row_array();
    }

    // edit existing record
    public function edit(array $data) {
		$needles = array("userId", "username", "fName", "lName", "gender", "active", "role", "updatedBy", "companyId");
		$status = $this->checkArrayKeyExists($needles, $data);
		if ($status["statusCode"] != 0) // meaning not OK = 0
			return $status;

		// we do not allow more than 3 active users.
		$status = $this->countActiveUsersByCompanyId($data["companyId"]);
		$userStatus = $this->_getUserStatus($data["userId"], $data["companyId"]);
		if (($status["statusCode"] != 0) && ($userStatus["active"] == "N") && ($data["active"] == "Y"))
			return array("statusCode" => parent::ERRORNO_MAX_REACHED, "statusMessage" => parent::ERRORSTR_MAX_REACHED, "statusDesc" => 'Deactivate other user(s) first to activate this user.');

		$data["email"] = $data["username"]; // they are the same.

		$query = "update users set username=?, fName=?, midName=?, lName=?, email=?, address=?, gender=?, updateDate=now(), updatedBy=?, active=?, role=? where userId = ? and companyId = ?";
		$query = $this->db->query($query, array($data['username'], $data['fName'], $data['midName'], $data['lName'], $data['email'], $data['address'], $data['gender'], $data['updatedBy'], $data['active'], $data['role'], $data['userId'], $data["companyId"]));
		if (!$query) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR);
		}

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);		
	}

	public function delete(array $data) {
		if (!isset($data["userIds"]))
			return array("statusCode" => parent::ERRORNO_INVALID_PARAMETER, "statusMessage" => parent::ERRORSTR_INVALID_PARAMETER, "statusDesc" => "Missing key: userIds");

		// I think want the company_users in the future.
		//$sql1 = "delete from company_users where userId in(" . implode(", ", $data["userIds"]) . ")";
		$sql2 = "delete from users where userId in(" . implode(", ", $data["userIds"]) . ")";

		$this->db->trans_start();
		//$this->db->query($sql1);
		$this->db->query($sql2);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
	}
}