<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include "../.inc/globals.inc.php";

class Employee {
	private $mysqli;

	public function __construct() {
		global $db;

		$this->mysqli = new mysqli($db["hostname"], $db["username"], $db["password"], $db["database"]);

		if ($this->mysqli->connect_errno)
			die("Connection error: " . mysqli_connect_errno() . ": " . mysqli_connect_error());
	}

    public function showAll(array $data) {
    	if (!array_key_exists("companyId", $data))
        	return false;

        $arr = array();

		$sql = "select employeeId,fName,midName,lName,nickname,active,trans from `employee` where companyId=" . $data['companyId'];
        $result = $this->mysqli->query($sql);

		while($row = $result->fetch_array(MYSQLI_ASSOC))
			$arr[] = $row;

		$result->close();
		return $arr;		
    }

    private function checkDataForAdd(array $data) {
		$keys = array("companyId", "nickname", "fName", "lName", "createdBy");

		foreach ($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		return true;
	}

    public function add(array $data) {
    	if (!$this->checkDataForAdd($data))
    		return false;
        if (!isset($data["midName"])) {
            $data["midName"] = null;
            $midName_column = null;
        }
        else
            if ($data["midName"] == "")
                $data["midName"] = null;

    	$returnValue = false;

        $sql1 = sprintf("SET @employeeId=(SELECT CAST(lastNo+1 AS char(11)) FROM documents WHERE documentCode='EM' and companyId=%d);", $data["companyId"]);
        $sql2 = sprintf("insert into employee(employeeId, companyId, nickname, fName, midName, lName, createDate, createdBy)
                    values(@employeeId, %d, '%s', '%s', '%s', '%s', now(), %d);",
                    $data["companyId"], $data["nickname"], $data["fName"], $data["midName"], $data["lName"], $data["createdBy"]);
        $sql3 = sprintf("Update documents set lastNo=@employeeId where documentCode='EM' and companyId=%d;", $data["companyId"]);

        try {
            $this->mysqli->autocommit(false);
            if (!$this->mysqli->query($sql1))
                throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
            if (!$this->mysqli->query($sql2))
                throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
            if (!$this->mysqli->query($sql3))
                throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
            
            $this->mysqli->commit();
            $returnValue = true;
        } catch (Exception $e) {
            $this->mysqli->rollback();
            error_log($e->getMessage());
            $returnValue = false;
        } finally {
            $this->mysqli->autocommit(true);
        }

        return $returnValue;
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

    	$returnValue = false;

		$query = "update employee set active = ?, updateDate = now(), updatedBy = ? where id = ?";
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param("sii", $data["status"], $data["updatedBy"], $data["id"]);
		if ($stmt->execute())
    		$returnValue = true;
    	else
    		$returnValue = false;

    	$stmt->close();
    	return $returnValue;
    }

    public function delete($id) {
    	$returnValue = false;

		$query = "delete from employee where id = ?";
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param("i", $id);
		if ($stmt->execute())
    		$returnValue = true;
    	else
    		$returnValue = false;

    	$stmt->close();
    	return $returnValue;
    }

    private function checkDataForEdit(array $data) {
		$keys = array("fName", "lName", "nickname", "updatedBy", "id");
		foreach($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		return true;
	}

    public function edit(array $data) {
    	if (!$this->checkDataForEdit($data))
    		return false;

    	$returnValue = false;

    	$query = "update employee set nickname = ?, fName = ?, midName = ?, lName = ?, updatedBy = ?, updateDate = now() where id = ?";
    	$stmt = $this->mysqli->prepare($query);
    	$stmt->bind_param("ssssii", $data["nickname"], $data["fName"], $data["midName"], $data["lName"], $data["updatedBy"], $data["id"]);
    	if ($stmt->execute())
    		$returnValue = true;
    	else
    		$returnValue = false;

    	$stmt->close();
    	return $returnValue;
    }

    public function __destruct() {
        $this->mysqli->close();
    }
}

/* End of file Employee.php */