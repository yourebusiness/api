<?php

class Api_model extends CI_Model {
	public function getProvince() {
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

	public function signIn($data) {
		$bind_vars = array($data["username"], $data["password"]);
		$query = "select username, passwd from users where username = ? and passwd = md5(?) and active = 'Y'";
		$query = $this->db->query($query, $bind_vars);
		if($query->num_rows())
			return true;
		else
			return false;
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
}