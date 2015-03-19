<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include "../.inc/globals.inc.php";

class Profile {
	private $mysqli;

	public function __construct() {
		global $db;

		$this->mysqli = new mysqli($db["hostname"], $db["username"], $db["password"], $db["database"]);

		if ($this->mysqli->connect_errno)
			die("Connection error: " . mysqli_connect_errno() . ": " . mysqli_connect_error());
	}

	public function getProfileByUserId($userId = 0) {
		if ($userId < 1)
			return false;

		$arr = array();
		$sql = "select username,fName,midName,lName,address,gender from `users` where userId=" . $userId;
		$result = $this->mysqli->query($sql);
		while($row = $result->fetch_array(MYSQLI_ASSOC))
			$arr[] = $row;

		$result->close();
		return $arr;
	}

	private function checkDataForUpdateProfile($data) {
		$keys = array("userId", "fName", "lName", "gender");
		foreach ($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		return true;
	}
	private function formatDataForAdd($data) {
		if ($data["midName"] == "" || empty($data["midName"]))
			$data["midName"] = null;
		if ($data["address"] == "" || empty($data["address"]))
			$data["address"] = null;

		return $data;
	}
	public function updateProfile($data) {
		if (!$this->checkDataForUpdateProfile($data))
			return false;

		$data = $this->formatDataForAdd($data);

		$returnValue = false;

		$sql = "update `users` set fName=?,midName=?,lName=?,gender=?,address=? where userId=?";
		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("sssssi", $data["fName"], $data["midName"], $data["lName"], $data["gender"], $data["address"], $data["userId"]);
		if ($stmt->execute())
    		$returnValue = true;
    	else
    		$returnValue = false;

    	$stmt->close();
    	return $returnValue;
	}

	/* return: if true then the old password is correct therefore allowed. */
	private function checkOldPassword($data) {
		var_dump($data);

		if (empty($data["oldPassword"]) || $data["oldPassword"] == "")
			return false;

		$arr = array();

		$sql = "select userId from users where userId=" . $data["userId"] . " and passwd=md5(" . $data["oldPassword"] . ")";
		$result = $this->mysqli->query($sql);
		while($row = $result->fetch_array(MYSQLI_ASSOC))
			$arr[] = $row;

		$result->close();
		if (count($arr) > 0)
			return true;
		else
			return false;
	}
	private function checkDataForChangePassword($data) {
		$keys = array("newPassword", "oldPassword", "userId");
		foreach($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		return true;
	}
	public function changePassword($data) {

		if (!$this->checkDataForChangePassword($data))
			return false;
		if (!$this->checkOldPassword($data))
			return false;

		$returnValue = false;

		$sql = "update users set passwd=md5(?) where userId=?";
		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("si", $data["newPassword"], $data["userId"]);
		if ($stmt->execute())
			$returnValue = true;

		$stmt->close();
		return $returnValue;
	}

	public function __destruct() {
        $this->mysqli->close();
    }
}