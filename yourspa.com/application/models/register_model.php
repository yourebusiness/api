<?php

class Register_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library("company");
	}
	
	public function add(array $data) {
		return $this->company->add($data);
	}

	public function activateRegistration($hash) {
		return $this->company->activateRegistration($hash);
	}

	public function edit(array $data) {
		return $this->company->edit($data);
	}
}