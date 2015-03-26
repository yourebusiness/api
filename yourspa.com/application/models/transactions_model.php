<?php

class Transactions_model extends baseClass2 {
	
	public function __construct() {
		parent::__construct();
	}

	public function add(array $data) {
		$needles = array("serviceId, serviceName, customerId, customerName, employeeId, price, discount, total, createdBy");		
		if ($this->checkArrayKeyExists($needles, $data))
			return false;

		$sql1 = "INSERT INTO transactions(serviceId, serviceName, customerId, customerName, employeeId, price, discount, total, createdBy, createDate, remarks)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?);";
		$sql2 = "UPDATE services SET trans='Y' WHERE id = ?;";

		$this->db->trans_start();
		$this->db->query($sql1, array($data["serviceId"], $data["serviceName"], $data["customerId"], $data["customerName"], $data["employeeId"], $data["price"], $data["discount"], $data["total"], $data["createdBy"], $data["remarks"]));
		$this->db->query($sql2, array($data["serviceId"]));
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			log_message("error", "Error running sql query in " . __METHOD__ . "()");
			return FALSE;
		}

		return TRUE;
	}
}