<?php

class Api_model extends My_model {
	public function __construct() {
		parent::__construct();
	}

	public function getProvinces() {
		$query = "select id,provinceName from province order by provinceName";
		$query = $this->db->query($query);
		return $query->result_array();
	}

	public function getCity($provinceId) {
		$bind_vars = array($provinceId);
		$query = "select id, cityName from city where provinceId = ? order by cityName";
		$query = $this->db->query($query, $bind_vars);
		return $query->result_array();
	}

	public function getProvincesAndCities() {
		$query = "select province.id AS provinceId, provinceName, city.id AS cityId, city.cityName
					from province join city on province.id = city.provinceId
					order by provinceId, cityId";
		$query = $this->db->query($query);
		return $query->result_array();
	}

	public function signIn(array $data) {
		$this->load->model("Users");
		return $this->Users->login($data);
	}

	public function checkUsername($username) {
		$bind_vars = array($username, $username);
		$query = "select username from users where username = ? or email = ?";
		$query = $this->db->query($query, $bind_vars);
		// there should be no returned rows otherwise record already exists and therefore not allowed
		if ($query->num_rows())
			return false;
		else
			return true;
	}

	public function checkCompanyDetails($data) {
		$bind_vars = array($data["company"], $data["companyEmail"]);
		$query = "select companyId from company where companyName = ? or email = ?";
		$query = $this->db->query($query, $bind_vars);
		if ($query->num_rows())
			return false;
		else
			return true;
	}

	// when requesting password reset
	public function resetPassword($email, $hash) {
		// we need a result here because it returns false on not empty
		if ($this->checkUsername($email)) {
			return array("statusCode" => parent::ERRORNO_RECORD_DOES_NOT_EXISTS, "statusMessage" => parent::ERRORSTR_RECORD_DOES_NOT_EXISTS, "statusDesc" => 'Record was not found.');
		}
		if (!$hash) {
			return array("statusCode" => parent::ERRORNO_INVALID_PARAMETER, "statusMessage" => parent::ERRORSTR_INVALID_PARAMETER, "statusDesc" => 'No reset password hash found.');
		}

		$bind_vars = array($email, $hash);

    	$query = "update users set resetPassword=1, resetPasswordDate=now(), resetPasswordHash=? where username=?";
    	$query = $this->db->query($query, $bind_vars);
		if ( ! $query) {
			$msg = $this->db->_error_number();
			$num = $this->db->_error_message();
			log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
			return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR,
				"statusDesc" => 'Database error.');
		}

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK,
			"statusDesc" => "Reset password success.");
	}

	// call from the link sent to the email
	public function forgotPasswordReset($hash) {
		$bind_vars = array($hash);

    	$query = "update users set resetPasswordSuccess=1, resetPasswordSuccessDate=now() where resetPasswordHash=?";
    	$query = $this->db->query($query, $bind_vars);
		if ( ! $query) {
			$msg = $this->db->_error_number();
			$num = $this->db->_error_message();
			log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
			return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR,
				"statusDesc" => 'Database error.');
		}

		return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK,
			"statusDesc" => "Reset password success.");
	}
}