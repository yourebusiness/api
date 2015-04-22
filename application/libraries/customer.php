<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once ".inc/globals.inc.php";

class Customer extends baseClass {
	private $mysqli;

	public function __construct() {
		global $db;

		$this->mysqli = new mysqli($db["hostname"], $db["username"], $db["password"], $db["database"]);
		if ($this->mysqli->connect_errno)
			die("Connection error: " . mysqli_connect_errno() . ": " . mysqli_connect_error());
	}

	private function checkArrayKeyExists(array $needles, array $haystack) {
    	foreach ($needles as $needle) {
    		if (!array_key_exists($needle, $haystack)) {
    			error_log(parent::ERRORNO_INVALID_PARAMETER . ": " . parent::ERRORSTR_INVALID_PARAMETER);
    			return false;
    		}
    		if ($haystack[$needle] == "") {
    			error_log(parent::ERRORNO_EMPTY_VALUE . ": " . parent::ERRORSTR_EMPTY_VALUE);
    			return false;
    		}
    	}

    	return true;
    }

    public function getAllCustomersDetails($companyId) {
		if ($companyId == "" || $companyId < 0)
			return array();

		$sql = "select customerId, custType, fName, midName, lName from customer where companyId=?;";
		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("i", $companyId);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($row["customerId"], $row["custType"], $row["fName"], $row["midName"], $row["lName"]);
		$arr = array();
		while ($stmt->fetch())
			$arr[] = $row;

		return $arr;
	}

	public function searchCustomersDetails(array $data) {
		$needles = array("searchText", "companyId");
		if (!$this->checkArrayKeyExists($needles, $data))
			return array();

		if ($data["companyId"] == "" || $data["searchText"] == "")
			return array();

		$fName = "%{$data['searchText']}%";		$midName = "%{$data['searchText']}%";		$lName = "%{$data['searchText']}%";

		$sql = "select fName, midName, lName from customer where fName like ? or midName like ? or lName like ? and companyId=?";
		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("sssi", $fName, $midName, $lName, $data["companyId"]);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($row["fName"], $row["midName"], $row["lName"]);
		$arr = array();
		while ($stmt->fetch())
			$arr[] = $row;

		return $arr;
	}

	public function getCustomerDetails(array $data) {
		$needles = array("companyId", "customerId");
		if (!$this->checkArrayKeyExists($needles, $data))
			return false;
		if ($data["companyId"] == "" || $data["customerId"] == "")
			return array();

		$arr = array();

		$sql = "select fName, midName, lName from customer where id=" . $data["customerId"] . " and companyId=" . $data["companyId"];
		$result = $this->mysqli->query($sql);
		while ($row = $result->fetch_array(MYSQLI_ASSOC))
			$arr[] = $row;

		return $arr;
	}

	private function formatDataForUpdate(array $data) {
		if (!isset($data["midName"]))
			$data["midName"] = null;
		if ($data["midName"] == "")
			$data["midName"] = null;

		return $data;
	}

	public function update(array $data) {
		$needles = array("companyId", "customerId", "custType", "fName", "lName", "updatedBy");
		if (!$this->checkArrayKeyExists($needles, $data))
			return false;
		if ($data["companyId"] == "" || $data["customerId"] == "" || $data["custType"] == "")
			return false;
		
		$data = $this->formatDataForUpdate($data);

		$sql = "update customer set custType=?,fName=?,midName=?lName=? where companyId=? and id=?";
		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("isssii", $data["custType"], $data["fName"], $data["midName"], $data["lName"], $data["companyId"], $data["customerId"]);
		if($stmt->execute())
			return true;
		else
			return false;
	}

	private function formatDataForAdd(array $data) {
		if (!isset($data["midName"]) || $data["midName"] == "")
			$data["midName"] = "NULL";
		else
			$data["midName"] = "'{$data["midName"]}'";

		return $data;
	}

	public function add(array $data) {
		$needles = array("companyId", "custType", "fName", "lName", "createdBy");
		if (!$this->checkArrayKeyExists($needles, $data))
			return false;
		if (empty($data["companyId"]) || $data["custType"] == "" || empty($data["fName"]) || empty($data["lName"]) || empty($data["createdBy"]))
			return false;

		$data = $this->formatDataForAdd($data);

		$returnValue = false;

		$sql1 = sprintf("SET @customerId=(SELECT CAST(lastNo+1 AS char(11)) FROM documents WHERE documentCode='CU' and companyId=%d);", $data["companyId"]);
		$sql2 = "insert into customer(companyId,customerId,custType,fName,midName,lName,createdBy,createDate)
			value({$data["companyId"]}, @customerId, {$data["custType"]}, '{$data["fName"]}', {$data["midName"]}, '{$data["lName"]}', {$data["createdBy"]}, NOW());";
		$sql3 = sprintf("Update documents set lastNo=@customerId where documentCode='CU' and companyId=%d;", $data["companyId"]);

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
			error_log($e->getMessage());
			$this->mysqli->rollback();
			$returnValue = false;
		} finally {
			$this->mysqli->autocommit(true);
		}

		return $returnValue;
	}

	private function checkDataForDelete(array $data) {
		if (!is_numeric($data["customerId"]))
			return false;
		if ($data["customerId"] < 1)
			return false;
		if (!is_numeric($data["companyId"]))
			return false;
		if ($data["companyId"] < 1)
			return false;

		return true;
	}
	public function delete(array $data) {
		$needles = array("customerId", "companyId");
		if (!$this->checkArrayKeyExists($needles, $data))
			return false;
		if (!$this->checkDataForDelete($data))
			return false;

		$sql = "delete from customer where id=? and companyId=?";
		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("ii", $data["customerId"], $data["companyId"]);
		if ($stmt->execute())
			return true;
		else
			return false;
	}

	public function __destruct() {
		$this->mysqli->close();
	}

}