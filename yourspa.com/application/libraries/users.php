<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include "../.inc/globals.inc.php";

class Users extends baseClass {
	private $mysqli;

	public function __construct() {
		global $db;

		$this->mysqli = new mysqli($db["hostname"], $db["username"], $db["password"], $db["database"]);
		if ($this->mysqli->connect_errno)
			die("Connection error: " . mysqli_connect_errno() . ": " . mysqli_connect_error());
	}

	private function checkArrayKeyExists(array $needles, array $haystack) {
    	foreach ($needles as $needle)
    		if (!array_key_exists($needle, $haystack)) {
    			throw new Exception(parent::ERRORNO_INVALID_PARAMETER . ": " . parent::ERRORSTR_INVALID_PARAMETER);
    			return false;
    		}    			

    	return true;
    }

	public function getAllUsers() {
		$sql = "select userId,username,fName,midName,lName,address,gender,active,role,trans from users";
		if (!$result = $this->mysqli->query($sql))
			die ("There's an error running the query [" . $this->mysqli->error() . "]");

		return $result;
	}

	private function checkDataForAdd(array $data) {
		$keys = array("username", "password", "fName", "lName", "gender", "role", "createdBy", "companyId");

		foreach ($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		return true;
	}
	public function add(array $data) {
		if (!$this->checkDataForAdd($data))
			return false;

		$data["email"] = $data["username"]; // because they are the same; For future use.

		$sql1 = sprintf("insert into users(username, passwd, fName, midName, lName, email, address, gender, createDate, createdBy, role)
				values('%s', md5(%s),  '%s',  '%s',  '%s',  '%s',  '%s',  '%s', now(), %d, %d);"
				, $data['username'], $data['password'], $data['fName'], $data['midName'], $data['lName'], $data['email'], $data['address'], $data['gender']
					, $data['createdBy'], $data['role']);
		$sql2 = "set @userId = LAST_INSERT_ID();";
		$sql3 = sprintf("insert into company_users(companyId, userId, createDate) values(%d, @userId, now());", $data["companyId"]);

		try {
			$this->mysqli->autocommit(false);
			if (!$this->mysqli->query($sql1))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql2))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql3))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);

			$this->mysqli->commit();
			$this->mysqli->autocommit(true);
			$this->mysqli->close();
			return true;
		} catch (exception $e) {
			$this->mysqli->autocommit(true);
			$this->mysqli->rollback();
			$this->mysqli->close();
			return false;
		}		
	}

	private function checkDataForEdit(array $data) {
		$keys = array("username", "fName", "lName", "updatedBy");

		foreach($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		return true;
	}
	public function edit(array $data) {
		if (!$this->checkDataForEdit($data))
			return false;

		$data["email"] = $data["username"]; // they are the same.

		$query = "update users set username=?, fName=?, midName=?, lName=?, email=?, address=?, gender=?, updateDate=now(), updatedBy=?, active=?, role=? where userId = ?";
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param("sssssssisii", $data['username'], $data['fName'], $data['midName'], $data['lName'], $data['email'], $data['address'], $data['gender'], $data['updatedBy'], $data['active'], $data['role'], $data['userId']);
		if ($stmt->execute())
			return true;
		else
			return false;
	}

	public function delete($id) {
		
		$sql1 = sprintf("delete from company_users where userId = %d", $id);
		$sql2 = sprintf("delete from users where userId = %d", $id);

		try {
			$this->mysqli->autocommit(false);
			if (!$this->mysqli->query($sql1))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql2))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);

			$this->mysqli->commit();
			$this->mysqli->autocommit(true);
			return true;
		} catch (exception $e) {
			$this->mysqli->autocommit(true);
			$this->mysqli->rollback();
			return false;
		}	
	}

	private function checkDataForChangeStatus(array $data) {
		$keys = array("status", "id", "updatedBy");

		foreach ($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		return true;
	}
	public function changeStatus(array $data) {
    	if (!$this->checkDataForChangeStatus($data))
    		return false;

		$query = "update users set `active` = ?, updateDate = now(), updatedBy = ? where userId = ?";
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param("sii", $data["status"], $data["updatedBy"], $data["id"]);
		if ($stmt->execute())
			return true;
		else
			return false;
    }

    private function checkDataForChangedRights(array $data) {
		$keys = array("role", "id", "updatedBy");

		foreach ($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		return true;
	}
    public function changeRights(array $data) {
    	if (!$this->checkDataForChangedRights($data))
    		return false;

    	$query = "update users set role = ? where userId = ?";
    	$stmt = $this->mysqli->prepare($query);
    	$stmt->bind_param("ii", $data["role"], $data["id"]);
    	if ($stmt->execute())
    		return true;
    	else
    		return false;
    }

    public function login(array $data) {
    	$needles = array("username", "password");
    	if (!$this->checkArrayKeyExists($needles, $data))
    		return false;

    	$returnValue = false;

		$sql = "select passwd from `users` where username='" . $data["username"] . "'";
		$result = $this->mysqli->query($sql);
		if ($result->num_rows == 1) {
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if (password_verify($data["password"], $row["passwd"]))
				$returnValue = true;
		}

		$result->close();		    	

    	return $returnValue;
    }

    public function __destruct() {
        $this->mysqli->close();
    }
}

/* End of file Users.php */