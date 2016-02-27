<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once ".inc/globals.inc.php";

class Services extends baseClass {
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
    	}

    	return true;
    }

	public function getAllServices() {
		$sql = "select id,serviceName,description
			, (select price FROM pricelist WHERE pricelist.serviceId = services.id AND pricelistCode = 0) AS regPrice
			, (select price FROM pricelist WHERE pricelist.serviceId = services.id AND pricelistCode = 1) AS memberPrice
			,trans FROM services";

		$result = $this->mysqli->query($sql);

		$arr = array();

		while($row = $result->fetch_array(MYSQLI_ASSOC))
			$arr[] = $row;

		$result->close();
		return $arr;
	}

	private function okToDeleteRecord($id) {
		$returnResult = false;
		$arr = array();
		$sql = "select trans from services where id = ? and trans='Y'";
		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		// this can be compressed.
		$stmt->store_result();
		$stmt->bind_result($row["trans"]);
		while ($stmt->fetch())
			$arr[] = $row;

		if (count($arr) < 1)
			$returnResult = true;

		$stmt->close();

		return $returnResult;
	}

	public function delete($id) {
		$returnResult = false; // default value

		if (!$this->okToDeleteRecord($id))
			return $returnResult;

		$sql1 = sprintf("delete from pricelistHistory where serviceId = %d;", $id);
		$sql2 = sprintf("delete from pricelist where serviceId = %d;", $id);
		$sql3 = sprintf("delete from services where id = %d;", $id);

		try {
			$this->mysqli->autocommit(false);
			if (!$this->mysqli->query($sql1))
				throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql2))
				throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql3))
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

	public function getServiceDetailById($id) {
		$arr = array();

		$sql = "select id,serviceName,description
			, (select price FROM pricelist WHERE pricelist.serviceId = services.id AND pricelistCode = 0) AS regPrice
			, (select price FROM pricelist WHERE pricelist.serviceId = services.id AND pricelistCode = 1) AS memberPrice
			,trans FROM services where id = ?";

		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($row["id"], $row["serviceName"], $row["description"], $row["regPrice"], $row["memberPrice"], $row["trans"]);
		while ($stmt->fetch())
			$arr[] = $row;

		$stmt->close();

		return $arr;
	}

	private function formatDataForAdd(array $data) {
		if (!isset($data["description"]) || empty($data["description"]))
			$data["description"] = "NULL";
		else
			$data["description"] = "'{$data["description"]}'";

		return $data;
	}

	public function add(array $data) {
		$needles = array("companyId", "serviceName", "regPrice", "memberPrice", "createdBy");
		if (!$this->checkArrayKeyExists($needles, $data))
			return false;

		$data = $this->formatDataForAdd($data);

		$returnResult = false;

		$sql1 = sprintf("SET @serviceId=(SELECT CAST(lastNo+1 AS char(11)) FROM documents WHERE documentCode='SVS' and companyId=%d);", $data["companyId"]);
		$sql2 = "insert into services(companyId, serviceId, serviceName, description, createdBy, createDate)
				values({$data["companyId"]}, @serviceId, '{$data["serviceName"]}', {$data["description"]}, {$data["createdBy"]}, NOW());";
		$sql3 = "SET @id = LAST_INSERT_ID();";
		$sql4 = sprintf("insert into pricelist(serviceId, pricelistCode, `price`, createDate, createdBy) values(@id, 0, %g, now(), %d);"
			, $data["regPrice"], $data["createdBy"]);
		$sql5 = sprintf("insert into pricelist(serviceId, pricelistCode, `price`, createDate, createdBy) values(@serviceId, 1, %g, now(), %d);"
			, $data["memberPrice"], $data["createdBy"]);
		$sql6 = sprintf("Update documents set lastNo=@serviceId where documentCode='SVS' and companyId=%d;", $data["companyId"]);

		try {
			$this->mysqli->autocommit(false);
			if (!$this->mysqli->query($sql1))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql2))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql3))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql4))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql5))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql6))
				throw new exception ('Something went wrong on sql.' . "Error: " . $this->mysqli->error);

			$this->mysqli->commit();
			$returnResult = true;
		} catch (exception $e) {
			error_log($e->getMessage());
			$this->mysqli->rollback();
			$returnResult = false;
		} finally {
			$this->mysqli->autocommit(true);
		}

		return $returnResult;
	}

	private function checkDataForEdit(array $data) {
		$keys = array("serviceId", "serviceName", "description", "regPrice", "memberPrice", "createdBy");
		foreach ($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		return true;
	}

	private function escapeCharacters(string $str) {
		return str_replace("'", "''", $str);
	}

	public function edit(array $data) {
		if (!$this->checkDataForEdit($data))
			return false;

		$returnResult = false;

		$data["serviceName"] = $this->escapeCharacters($data["serviceName"]);
		$data["description"] = $this->escapeCharacters($data["description"]);

		$sql1 = sprintf("update services set serviceName='%s', description='%s' where id=%d;", $data['serviceName'], $data['description'], $data['serviceId']);
		$sql2 = sprintf("insert into pricelistHistory(serviceId,priceListCode,price,cp_createDate,cp_createdBy,createDate,createdBy)
					SELECT serviceId,priceListCode,price,createDate,createdBy,now(), %d FROM pricelist WHERE serviceId = %d AND priceListCode=0;"
					,$data["createdBy"], $data["serviceId"]);
		$sql3 = sprintf("insert into pricelistHistory(serviceId,priceListCode,price,cp_createDate,cp_createdBy,createDate,createdBy)
					SELECT serviceId,priceListCode,price,createDate,createdBy,now(), %d FROM pricelist WHERE serviceId = %d AND priceListCode=1;"
					,$data["createdBy"], $data["serviceId"]);
		$sql4 = sprintf("update pricelist set price = %g where serviceId = %d and pricelistCode = 0;", $data['regPrice'], $data['serviceId']);
		$sql5 = sprintf("update pricelist set price = %g where serviceId = %d and pricelistCode = 1;", $data['memberPrice'], $data['serviceId']);

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
			if (!$this->mysqli->query($sql5))
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

	public function __destruct() {
        $this->mysqli->close();
    }

}