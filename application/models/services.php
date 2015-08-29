<?php

class Services extends MY_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->helper("utility");
	}	

	public function getServicesListByCompanyId($companyId) {
		$query = "select serviceId, serviceName, description
			,(select FORMAT(price, 2) FROM pricelist WHERE pricelist.serviceId = services.id AND pricelistCode = 0) AS regPrice
			,(select FORMAT(price, 2) FROM pricelist WHERE pricelist.serviceId = services.id AND pricelistCode = 1) AS memberPrice
			,active FROM services WHERE companyId = ?";;
		$query = $this->db->query($query, array($companyId));

		if (!$query) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		if ($query->num_rows())
			return $query->result_array();
		else
			return array();
	}

	public function getServicesByCompanyId($companyId) {
		$query = "SELECT serviceId, serviceName FROM services WHERE companyId = ?";
		$query = $this->db->query($query, array($companyId));
		return $query->result_array();
	}

	/* utilities */
	private function formatData(array $data) {
		if (!isset($data["description"]) || empty($data["description"]))
			$data["description"] = NULL;

		$data["regPrice"] = normalizeNumber($data["regPrice"]);
		$data["memberPrice"] = normalizeNumber($data["memberPrice"]);

		return $data;
	}

	private function checkValues(array $data) {
		if (!isCurrency($data["regPrice"]))
			return array("statusCode" => parent::ERRORNO_INVALID_VALUE, "statusMessage" => parent::ERRORSTR_INVALID_VALUE, "statusDesc" => "Invalid passed value for reg. price.");

		if (!isCurrency($data["memberPrice"]))
			return array("statusCode" => parent::ERRORNO_INVALID_VALUE, "statusMessage" => parent::ERRORSTR_INVALID_VALUE, "statusDesc" => "Invalid passed value for member price.");
		
		if ($data["regPrice"] < 0)
			return array("statusCode" => parent::ERRORNO_INVALID_VALUE, "statusMessage" => parent::ERRORSTR_INVALID_VALUE, "statusDesc" => "Invalid passed value for reg. price.");

		if ($data["memberPrice"] < 0)
			return array("statusCode" => parent::ERRORNO_INVALID_VALUE, "statusMessage" => parent::ERRORSTR_INVALID_VALUE, "statusDesc" => "Invalid passed value for member price.");

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
	}
	/* end for utilities */


	public function add(array $data) {
		if ($this->session->userdata["role"] > 0)
			return array("statusCode" => parent::ERRORNO_NOT_AUTHORIZED, "statusMessage" => parent::ERRORSTR_NOT_AUTHORIZED, "statusDesc" => "");

		$needles = array("companyId", "serviceName", "regPrice", "memberPrice", "createdBy");
		$status = $this->checkArrayKeyExists($needles, $data);
		if ($status["statusCode"] != 0)
			return $status;

		// we only accept Y/N for active
		if (!in_array($data["active"], array("Y", "N")))
			return array("statusCode" => parent::ERRORNO_INVALID_PARAMETER, "statusMessage" => parent::ERRORSTR_INVALID_PARAMETER, "statusDesc" => "Active value should only be Y or N.");

		$data = $this->formatData($data);

		$returnedValue = $this->checkValues($data);
		if ($returnedValue["statusCode"] != 0)
			return $returnedValue;

		$sql1 = "SET @serviceId=(SELECT CAST(lastNo+1 AS char(11)) FROM documents WHERE documentCode='SVS' and companyId = ?);";
		$sql2 = "insert into services(companyId, serviceId, serviceName, description, createdBy, createDate)
				values(?, @serviceId, ?, ?, ?, NOW());";
		$sql3 = "SET @id = LAST_INSERT_ID();";
		$sql4 = "insert into pricelist(serviceId, pricelistCode, `price`, createDate, createdBy) values(@id, 0, ?, now(), ?);";
		$sql5 = "insert into pricelist(serviceId, pricelistCode, `price`, createDate, createdBy) values(@id, 1, ?, now(), ?);";
		$sql6 = "Update documents set lastNo=@serviceId where documentCode='SVS' and companyId = ?;";
		$sql7 = "select @serviceId as newServiceId;";

		$this->db->trans_start();
		$this->db->query($sql1, array($data["companyId"]));
		$this->db->query($sql2, array($data["companyId"], $data['serviceName'], $data['description'], $data['createdBy'] ));
		$this->db->query($sql3);
		$this->db->query($sql4, array($data['regPrice'], $data['createdBy']));
		$this->db->query($sql5, array($data['memberPrice'], $data['createdBy']));
		$this->db->query($sql6, array($data['companyId']));
		$query = $this->db->query($sql7);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		$row = $query->row_array();

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK, "newId" => $row["newServiceId"]);
	}

	public function edit(array $data) {
		if ($this->session->userdata["role"] > 0)
			return array("statusCode" => parent::ERRORNO_NOT_AUTHORIZED, "statusMessage" => parent::ERRORSTR_NOT_AUTHORIZED, "statusDesc" => "");

		$needles = array("serviceId", "serviceName", "description", "regPrice", "memberPrice", "createdBy", "companyId");
		$status = $this->checkArrayKeyExists($needles, $data);
		if ($status["statusCode"] != 0)
			return $status;

		$data = $this->formatData($data);

		$returnedValue = $this->checkValues($data);
		if ($returnedValue["statusCode"] != 0)
			return $returnedValue;

		$sql1 = "SET @id = (SELECT id FROM services WHERE serviceId = ? AND companyId = ?);";
		$sql2 = "update services set serviceName = ?, description = ? where id = @id;";
		$sql3 = "insert into pricelistHistory(serviceId,priceListCode,price,cp_createDate,cp_createdBy,createDate,createdBy)
					SELECT serviceId,priceListCode,price,createDate,createdBy,now(), ? FROM pricelist WHERE serviceId = @id AND priceListCode=0;";
		$sql4 = "insert into pricelistHistory(serviceId,priceListCode,price,cp_createDate,cp_createdBy,createDate,createdBy)
					SELECT serviceId,priceListCode,price,createDate,createdBy,now(), ? FROM pricelist WHERE serviceId = @id AND priceListCode=1;";
		$sql5 = "update pricelist set price = ? where serviceId = @id and pricelistCode = 0;";
		$sql6 = "update pricelist set price = ? where serviceId = @id and pricelistCode = 1;";

		$this->db->trans_start();
		$this->db->query($sql1, array($data["serviceId"], $data["companyId"]));
		$this->db->query($sql2, array($data['serviceName'], $data['description']));
		$this->db->query($sql3, array($data["createdBy"]));
		$this->db->query($sql4, array($data["createdBy"]));
		$this->db->query($sql5, array($data['regPrice']));
		$this->db->query($sql6, array($data['memberPrice']));
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
	}

	private function okToDeleteRecord($serviceId, $companyId) {
		$query = "SELECT trans FROM services WHERE serviceId = ? AND companyId = ? AND trans='Y'";
		$query = $this->db->query($query, array($serviceId, $companyId));

		if (!$query) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		if ($query->num_rows())
			return FALSE;
		else
			return TRUE;
	}

	public function delete(array $data) {
		if ($this->session->userdata["role"] > 0)
			return array("statusCode" => parent::ERRORNO_NOT_AUTHORIZED, "statusMessage" => parent::ERRORSTR_NOT_AUTHORIZED, "statusDesc" => "");

		if (!isset($data["serviceIds"]))
			return array("statusCode" => parent::ERRORNO_INVALID_PARAMETER, "statusMessage" => parent::ERRORSTR_INVALID_PARAMETER, "statusDesc" => "Missing key: userIds");

		$cannotBeDeleted = 0;

		foreach ($data["serviceIds"] as $serviceId) {
			if (!$this->okToDeleteRecord($serviceId, $data["companyId"])) {
				$cannotBeDeleted++;
			} else {
				/* Note: services.id = pricelist.serviceId and
						 services.id = pricelistHistory.serviceId */

				$sql1 = "SET @id = (SELECT id from services WHERE serviceId = ? AND companyId = ?);";
				$sql2 = "delete from pricelistHistory WHERE serviceId = @id;";
				$sql3 = "delete from pricelist WHERE serviceId = @id;";
				$sql4 = "delete from services WHERE id = @id;";

				$this->db->trans_start();
				$this->db->query($sql1, array($serviceId, $data["companyId"]));
				$this->db->query($sql2);
				$this->db->query($sql3);
				$this->db->query($sql4);
				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
					$msg = $this->db->_error_number();
		            $num = $this->db->_error_message();
		            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
		            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
				}
			} //else
		} //foreach

		if ($cannotBeDeleted)
			return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK, "statusDesc" => "One or more record(s) cannot be deleted.");
		else
			return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);

	}
}