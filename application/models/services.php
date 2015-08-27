<?php

class Services extends MY_Model {
	
	public function __construct() {
		parent::__construct();
	}

	private function formatData(array $data) {
		if (!isset($data["description"]) || empty($data["description"]))
			$data["description"] = NULL;

		return $data;
	}

	public function getServicesListByCompanyId($companyId) {
		$query = "select serviceId, serviceName, description
			,(select price FROM pricelist WHERE pricelist.serviceId = services.id AND pricelistCode = 0) AS regPrice
			,(select price FROM pricelist WHERE pricelist.serviceId = services.id AND pricelistCode = 1) AS memberPrice
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

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK, "newId" => $row["newId"]);
	}

	public function edit(array $data) {
		if ($this->session->userdata["role"] > 0)
			return array("statusCode" => parent::ERRORNO_NOT_AUTHORIZED, "statusMessage" => parent::ERRORSTR_NOT_AUTHORIZED, "statusDesc" => "");

		$needles = array("serviceId", "serviceName", "description", "regPrice", "memberPrice", "createdBy", "companyId");
		$status = $this->checkArrayKeyExists($needles, $data);
		if ($status["statusCode"] != 0)
			return $status;

		$data = $this->formatData($data);		

		$sql1 = "update services set serviceName = ?, description = ? where id = ?;";
		$sql2 = "insert into pricelistHistory(serviceId,priceListCode,price,cp_createDate,cp_createdBy,createDate,createdBy)
					SELECT serviceId,priceListCode,price,createDate,createdBy,now(), ? FROM pricelist WHERE serviceId = ? AND priceListCode=0;";
		$sql3 = "insert into pricelistHistory(serviceId,priceListCode,price,cp_createDate,cp_createdBy,createDate,createdBy)
					SELECT serviceId,priceListCode,price,createDate,createdBy,now(), ? FROM pricelist WHERE serviceId = ? AND priceListCode=1;";
		$sql4 = "update pricelist set price = ? where serviceId = ? and pricelistCode = 0;";
		$sql5 = "update pricelist set price = ? where serviceId = ? and pricelistCode = 1;";

		$this->db->trans_start();
		$this->db->query($sql1, array($data['serviceName'], $data['description'], $data['serviceId']));
		$this->db->query($sql2, array($data["createdBy"], $data["serviceId"]));
		$this->db->query($sql3, array($data["createdBy"], $data["serviceId"]));
		$this->db->query($sql4, array($data['regPrice'], $data['serviceId']));
		$this->db->query($sql5, array($data['memberPrice'], $data['serviceId']));
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
	}
}