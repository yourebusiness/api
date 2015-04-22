<?php

class Api extends CI_Controller {
	public function getCity($provinceId) {
		$this->load->model("Api_model");
		echo json_encode($this->Api_model->getCity($provinceId));
	}

	public function signIn() {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("username", "Username", "trim|required");
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
				$companyId = getCompanyIdByUsername($data["username"]);
				$userId = getUserIdByUsername($data["username"]);
				$userRights = checkUserRightsByUserId($userId);
				
				$sess_data = array( "username" => $data["username"], "companyId" => $companyId, "userId" => $userId, "role" => $userRights); // username = email
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