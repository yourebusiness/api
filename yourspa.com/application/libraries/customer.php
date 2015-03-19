<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include "../.inc/globals.inc.php";

class Customer {
	private $mysqli;

	public function __construct() {
		global $db;

		$this->mysqli = new mysqli($db["hostname"], $db["username"], $db["password"], $db["database"]);
		if ($this->mysqli->connect_errno)
			die("Connection error: " . mysqli_connect_errno() . ": " . mysqli_connect_error());
	}

	private function checkDataForSearchCustomersDetails(array $data) {
		$keys = array("searchText", "companyId");
		foreach($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		if ($data["companyId"] == "" || $data["searchText"] == "")
			return false;

		return true;
	}

	public function searchCustomersDetails(array $data) {
		if (!$this->checkDataForSearchCustomersDetails($data))
			return false;

		$arr = array();
		$sql = "select fName, midName, lName from customer where fName like '%" . $data["searchText"] . "%' or midName like '%" . $data["searchText"] . "%' or lName like '%" . $data["searchText"] . "%' and companyId=" . $data["companyId"];
		$result = $this->mysqli->query($sql);
		while ($row = $result->fetch_array(MYSQLI_ASSOC))
			$arr[] = $row;

		return $arr;
	}

	/* we prevent running SQL without complete data */
	private function checkDataForGetCustomerDetails(array $data) {
		$keys = array("companyId", "customerId");
		foreach($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		if ($data["companyId"] == "" || $data["customerId"] == "")
			return false;

		return true;
	}

	public function getCustomerDetails(array $data) {
		if (!$this->checkDataForGetCustomerDetails($data))
			return false;

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
	private function checkDataForUpdate(array $data) {
		$keys = array("companyId", "customerId", "custType", "fName", "lName", "updatedBy");
		foreach($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		if ($data["companyId"] == "" || $data["customerId"] == "" || $data["custType"] == "")
			return false;

		return true;
	}
	public function update(array $data) {
		if (!$this->checkDataForUpdate($data))
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
		if (!isset($data["midName"]))
			$data["midName"] = null;

		return $data;
	}
	private function checkDataForAdd(array $data) {
		$keys = array("companyId", "custType", "fName", "lName", "createdBy");
		foreach($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		if ($data["companyId"] == "" || $data["custType"] == "" || $data["fName"] == "" || $data["lName"] == "" || $data["createdBy"] == "")
			return false;

		return true;
	}
	public function add(array $data) {
		if (!$this->checkDataForAdd($data))
			return false;
		$data = $this->formatDataForAdd($data);

		$sql = "insert into customer(companyId,custType,fName,midName,lName,createdBy,createDate)
			value(?,?,?,?,?,?,now())";
		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("iisssi", $data["companyId"], $data["custType"], $data["fName"], $data["midName"], $data["lName"], $data["createdBy"]);
		if ($stmt->execute())
			return false;
		else
			return true;
	}

	private function checkDataForDelete(array $data) {
		$keys = array("customerId", "companyId");
		foreach ($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

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