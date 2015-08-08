<?php

class Users extends My_Model {

	public function __construct() {
		parent::__construct();
	}

	public function getAllUsersExceptCurrent($currentUserId, $companyId) {
		$query = "select userId,username,fName,midName,lName,gender,active,role from users where userId <> ? and companyId = ?";
		$query = $this->db->query($query, array($currentUserId, $companyId));
		return $query->result_array();
	}

	private function countUsersByCompanyId($companyId) {
		if (intval($companyId) < 1) return FALSE;

		$query = $this->db->query("select count(*) as cnt from users where companyId = ?", array($companyId));
		if (!$query) {
    		$msg = $this->db->_error_message();
    		$num = $this->db->_error_number();
    		log_message("error", "Database error ($num) $msg");
			return FALSE;
    	}

    	$row = $query->row_array();

    	// we only allow max 3 users per company
    	if ($row["cnt"] > 3)
    		return FALSE;
    	else
    		return TRUE;
	}

	// username or email should not exist
	private function okToAddUsername($username) { // note: username = email
		$username = trim($username);
        if ($username == "")
			return FALSE;

		$query = $this->db->query("select userId from users where username = ? or email = ?", array($username, $username));
    	if (!$query) {
    		$msg = $this->db->_error_message();
    		$num = $this->db->_error_number();
    		log_message("error", "Database error ($num) $msg");
			return FALSE;
    	}

    	$count = $query->num_rows();
    	if ($count < 1)
			return TRUE;
		else {
			log_message("error", parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": username: $username.");
    		return FALSE;
		}
	}

	public function add(array $data) {
		$needles = array("username", "password", "fName", "lName", "gender", "role", "createdBy", "companyId");
		if (!$this->checkArrayKeyExists($needles, $data))
			return FALSE;

		$data["email"] = $data["username"]; // because they are the same; For future use.
		$password = password_hash($data["password"], PASSWORD_BCRYPT);

		if (!$this->okToAddUsername($data["username"]))
			return FALSE;
		if (!$this->countUsersByCompanyId($data["companyId"]))
			return FALSE;

		$sql1 = "SET @userId=(SELECT CAST(lastNo+1 AS char(11)) FROM documents WHERE documentCode='USR' and companyId = ?);";
		$sql2 = "insert into users(companyId, userId, username, passwd, fName, midName, lName, email, address, gender, createDate, createdBy, role)
				values(?, @userId, ?, ?,  ?,  ?,  ?,  ?,  ?,  ?, now(), ?, ?);";
		$sql3 = "Update documents set lastNo=@userId where documentCode='USR' and companyId = ?;";

		$this->db->trans_start();
		$this->db->query($sql1, array($data["companyId"]));
		$this->db->query($sql2, array($data["companyId"], $data['username'], $password, $data['fName'], $data['midName'], $data['lName'], $data['email'], $data['address'], $data['gender'], $data['createdBy'], $data['role']));
		$this->db->query($sql3, array($data["companyId"]));
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return FALSE;
		}

		return TRUE;
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
}