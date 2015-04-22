<?php

class Aboutus extends CI_Controller {
	public function view($page = "aboutus_view") {

		$data['title'] = "About Us";
		$this->load->view('templates/header', $data);
		$this->load->view($page);
	}
}