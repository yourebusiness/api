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

		return $query->result_array();
	}
}