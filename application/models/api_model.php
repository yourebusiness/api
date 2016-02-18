<?php

class Api_model extends CI_Model {
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
}