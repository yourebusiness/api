<?php

class Api extends My_Controller {
	public function getProvinces() {
		$this->load->model("Api_model");
		$this->_response($this->Api_model->getProvinces());
	}

	public function getCity($provinceId) {
		$this->load->model("Api_model");
		$this->_response($this->Api_model->getCity($provinceId));
	}

	public function provincesAndCities() {
		$this->load->model("Api_model");
		$this->_response($this->Api_model->getProvincesAndCities());
	}

	public function signIn() {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("username", "Username", "trim|required|valid_email|xss_clean");
		$this->form_validation->set_rules("password", "Password", "trim|required");

		$data = array("username" => $this->input->post("username"),
					"password" => $this->input->post("password")
				);

		$data["title"] = "Login";
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("templates/header", $data);
			$this->load->view("failedLogin");
		} else {
			$this->load->model("Api_model");
			if ($this->Api_model->signIn($data)) {	// if successful

				$this->load->helper('record');
				$row = getRecordsForLoginByUsername($data["username"]);

				$sess_data = array( "username" => $data["username"], "companyId" => $row["companyId"], "uniqueCode" => $row["uniqueCode"], "userId" => $row["userId"], "id" => $row["id"], "role" => $row["role"]); // username = email
				$this->session->set_userdata($sess_data);

				redirect(site_url("admin"));
			} else {
				$this->load->view("templates/header", $data);
				$this->load->view("failedLogin");
			}
		}
	}

	public function logout() {
		$this->session->sess_destroy();
		$data["title"] = "Logged Out";
		$this->load->view("templates/header", $data);
		$this->load->view("logout");
	}
}