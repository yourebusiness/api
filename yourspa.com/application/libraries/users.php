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

	public function getAllUsersExceptCurrent($currentUserId) {
		$sql = "select userId,username,fName,midName,lName,address,gender,active,role,trans from users where userId<>$currentUserId";
		if (!$result = $this->mysqli->query($sql))
			die ("There's an error running the query [" . $this->mysqli->error() . "]");

		return $result;
	}

	public function add(array $data) {
		$needles = array("username", "password", "fName", "lName", "gender", "role", "createdBy", "companyId");
		if (!$this->checkArrayKeyExists($needles, $data))
			return false;

		$returnValue = false;

		$data["email"] = $data["username"]; // because they are the same; For future use.
		$password = password_hash($data["password"], PASSWORD_BCRYPT);

		$sql1 = sprintf("SET @userId=(SELECT CAST(lastNo+1 AS char(11)) FROM documents WHERE documentCode='USR' and companyId=%d);", $data["companyId"]);
		$sql2 = sprintf("insert into users(companyId, userId, username, passwd, fName, midName, lName, email, address, gender, createDate, createdBy, role)
				values(%d, @userId, '%s', '%s',  '%s',  '%s',  '%s',  '%s',  '%s',  '%s', now(), %d, %d);"
				, $data["companyId"], $data['username'], $password, $data['fName'], $data['midName'], $data['lName'], $data['email'], $data['address'], $data['gender']
					, $data['createdBy'], $data['role']);
		$sql3 = sprintf("Update documents set lastNo=@userId where documentCode='USR' and companyId=%d;", $data["companyId"]);

		try {
			$this->mysqli->autocommit(false);
			if (!$this->mysqli->query($sql1))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql2))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql3))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);

			$this->mysqli->commit();
			$returnValue = true;
		} catch (exception $e) {
			$this->mysqli->rollback();			
			$returnValue = false;
		} finally {
			$this->mysqli->autocommit(true);
			$this->mysqli->close();
		}

		return $returnValue;
	}

	public function edit(array $data) {
		$needles = array("username", "fName", "lName", "updatedBy");
		if (!$this->checkArrayKeyExists($needles, $data))
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

	public function changeStatus(array $data) {
		$needles = array("status", "id", "updatedBy");
    	if (!$this->checkArrayKeyExists($needles, $data))
    		return false;

		$query = "update users set `active` = ?, updateDate = now(), updatedBy = ? where userId = ?";
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param("sii", $data["status"], $data["updatedBy"], $data["id"]);
		if ($stmt->execute())
			return true;
		else
			return false;
    }

    public function changeRights(array $data) {
    	$needles = array("role", "id", "updatedBy");
    	if (!$this->checkArrayKeyExists($needles, $data))
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
			if (password_verify($data["password"], $row["passwd"])) {
				$stmt = $this->mysqli->prepare("update users set lastLogin=now() where username=?");
				$stmt->bind_param("s", $data["username"]);
				if ($stmt->execute())
					$returnValue = true;
				else
					$returnValue = false;

				$stmt->close();
			}
				
		}

		$result->close();		    	

    	return $returnValue;
    }

    public function __destruct() {
        $this->mysqli->close();
    }
}

/* End of file Users.php */