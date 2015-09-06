<?php

class Masseurs extends My_Model {
	public function __construct() {
		parent::__construct();
	}

	public function getMasseursListByCompanyId($companyId) {
		$query = "select masseurId,fName,midName,lName,gender,nickname,active from `masseurs` where companyId = ?";
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

	public function getMasseurNamesByCompanyId($companyId) {
		$query = "SELECT masseurId, (CASE WHEN nickname IS NULL THEN CONCAT(fName, ' ', lName) ELSE nickname END) AS `name` FROM masseurs WHERE companyId = ?";
		$query = $this->db->query($query, array($companyId));
		return $query->result_array();
	}

	public function add(array $data) {
		//file_put_contents("/tmp/masseus.txt", print_r($data, TRUE));

		if ($this->session->userdata["role"] > 0)
			return array("statusCode" => parent::ERRORNO_NOT_AUTHORIZED, "statusMessage" => parent::ERRORSTR_NOT_AUTHORIZED, "statusDesc" => "");

		$needles = array("fName", "lName", "gender", "active", "createdBy", "companyId");

		$status = $this->checkArrayKeyExists($needles, $data);
		if ($status["statusCode"] != 0)
			return $status;

		$sql1 = "SET @masseurId=(SELECT CAST(lastNo+1 AS char(11)) FROM documents WHERE documentCode='MS' and companyId=?);";
        $sql2 = "insert into masseurs(masseurId, companyId, nickname, fName, midName, lName, gender, active, createDate, createdBy)
                    values(@masseurId, ?, ?, ?, ?, ?, ?, ?, now(), ?);";
        $sql3 = "Update documents set lastNo=@masseurId where documentCode='MS' and companyId=?;";
        $sql4 = "select @masseurId as newMasseurId;";

		$this->db->trans_start();
		$this->db->query($sql1, array($data["companyId"]));
		$this->db->query($sql2, array($data["companyId"], $data["nickname"], $data['fName'], $data["midName"], $data['lName'], $data['gender'], $data["active"], $data['createdBy']));
		$this->db->query($sql3, array($data["companyId"]));
		$query = $this->db->query($sql4);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		$row = $query->row_array();

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK, "newMasseurId" => $row["newMasseurId"]);
	}

	public function edit(array $data) {
    	if ($this->session->userdata["role"] > 0)
			return array("statusCode" => parent::ERRORNO_NOT_AUTHORIZED, "statusMessage" => parent::ERRORSTR_NOT_AUTHORIZED, "statusDesc" => "");

		$needles = array("masseurId", "fName", "lName", "gender", "active", "updatedBy", "companyId");
		$status = $this->checkArrayKeyExists($needles, $data);
		if ($status["statusCode"] != 0) // meaning not OK = 0
			return $status;

		$query = "update masseurs set nickname = ?, fName = ?, midName = ?, lName = ?, gender = ?, active = ?, updatedBy = ?, updateDate = now() where masseurId = ? and companyId = ?";
		$query = $this->db->query($query, array($data['nickname'], $data['fName'], $data['midName'], $data['lName'], $data['gender'], $data['active'], $data['updatedBy'], $data['masseurId'], $data['companyId']));
		if (!$query) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR);
		}

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);		
	}

	private function okToDeleteRecord($masseurId, $companyId) {
		$query = "SELECT trans FROM masseurs where masseurId = ? and companyId = ?";
		$query = $this->db->query($query, array($masseurId, $companyId));

		if (!$query) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR);
		}

		if ($query->num_rows())
			return FALSE;
		else
			return TRUE;
	}

	public function delete(array $data) {
		if ($this->session->userdata["role"] > 0)
			return array("statusCode" => parent::ERRORNO_NOT_AUTHORIZED, "statusMessage" => parent::ERRORSTR_NOT_AUTHORIZED, "statusDesc" => "");

		if (!isset($data["masseurIds"]))
			return array("statusCode" => parent::ERRORNO_INVALID_PARAMETER, "statusMessage" => parent::ERRORSTR_INVALID_PARAMETER, "statusDesc" => "Missing key: masseurIds");

		$cannotBeDeleted = 0;

		foreach ($data["masseurIds"] as $masseurId)
			if (!$this->okToDeleteRecord($masseurId, $data["companyId"]))
				$cannotBeDeleted++;

		$sql1 = "delete from masseurs where masseurId in(" . implode(", ", $data["masseurIds"]) . ") and companyId = ?";

		$this->db->trans_start();
		$this->db->query($sql1, array($data["companyId"]));
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
		}

		if ($cannotBeDeleted)
			return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK, "statusDesc" => "One or more record(s) cannot be deleted.");
		else
			return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
	}

}