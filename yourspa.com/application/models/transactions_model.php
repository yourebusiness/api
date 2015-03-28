<?php

class Transactions_model extends baseClass2 {
	const WITH_SUBSCRIPTION = TRUE;
	const NO_SUBSCRIPTION = FALSE;
	
	public function __construct() {
		parent::__construct();
	}

	public function withActiveSubscription($companyId) {
		$query = $this->db->query("SELECT id FROM company_payment WHERE expiry > now() AND companyId=?", array($companyId));
		if (!$query) {
			$msg = $this->db->_error_message();
			$num = $this->db->_error_number();
			log_message("error", "Database error ($num) $msg");
			return false;
		}

		$count = $query->num_rows();
		if ($count == 1)
			return self::WITH_SUBSCRIPTION;
		elseif ($count > 1) {
			log_message("info", "There are more than two results in the query.");
			return self::WITH_SUBSCRIPTION;
		} else {
			return self::NO_SUBSCRIPTION;
		}
	}

	private function formatDataForAdd(array $data) {
		if ( ! isset($data["remarks"]) || $data["remarks"] == "")
			$data["remarks"] = NULL;

		return $data;
	}

	private function computeTotal($price, $discount) {
		$percentInDecimal = ($discount / 100);
		$x = (1 - $percentInDecimal);
		return (double)($price * $x);
	}

	private function checkDataForAdd(array $data) {
		if (is_nan($data["price"])) {
			log_message("error", "Price is not a number.");
			return FALSE;
		}

		return TRUE;
	}

	public function add(array $data) {
		$needles = array("serviceId", "serviceName", "customerId", "customerName", "employeeId", "price", "discount", "total", "createdBy");
		if ( ! $this->checkArrayKeyExists($needles, $data))
			return FALSE;
		if ( ! $this->checkDataForAdd($data))
			return FALSE;

		$total = $this->computeTotal($data["price"], $data["discount"]);
		if ($total !== (double)$data["total"]) {
			log_message("error", "Price discrepancy found with discount and total in " . __METHOD__ . "()");
			return FALSE;
		}

		if ( ! $this->withActiveSubscription($data["companyId"]))
			return FALSE;

		$data = $this->formatDataForAdd($data);

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