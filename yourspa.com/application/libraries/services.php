<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include "../.inc/globals.inc.php";

class Services {
	private $mysqli;

	public function __construct() {
		global $db;

		$this->mysqli = new mysqli($db["hostname"], $db["username"], $db["password"], $db["database"]);
		if ($this->mysqli->connect_errno)
			die("Connection error: " . mysqli_connect_errno() . ": " . mysqli_connect_error());
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
			$this->mysqli->rollback();
		}

		$this->mysqli->autocommit(true);
		$this->mysqli->close();
		
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

	private function checkDataForAdd(array $data) {
		$keys = array("serviceName", "description", "regPrice", "memberPrice", "createdBy");
		foreach ($keys as $key)
			if (!array_key_exists($key, $data))
				return false;

		return true;
	}

	public function add(array $data) {
		$sql1 = sprintf("insert into services(serviceName, description) values('%s', '%s');", 
				$data["serviceName"], $data["description"]);
		$sql2 = "set @serviceId = LAST_INSERT_ID();";
		$sql3 = sprintf("insert into pricelist(serviceId, pricelistCode, `price`, createDate, createdBy) values(@serviceId, 0, %g, now(), %d);"
			, $data["regPrice"], $data["createdBy"]);
		$sql4 = sprintf("insert into pricelist(serviceId, pricelistCode, price, createDate, createdBy) values(@serviceId, 1, %g, now(), %d);"
			, $data["memberPrice"], $data["createdBy"]);

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
			$this->mysqli->autocommit(true);
			$this->mysqli->close();

			return true;
		} catch (Exception $e) {
			$this->mysqli->rollback();
			$this->mysqli->autocommit(true);

			$this->mysqli->close();
			return false;
		}
	}

	public function __destruct() {
        $this->mysqli->close();
    }

}