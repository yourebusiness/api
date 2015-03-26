<?php

class Subscription_model extends baseClass2 {
	const WITH_SUBSCRIPTION = TRUE;
	const NO_SUBSCRIPTION = FALSE;

	public function __construct() {
		parent::__construct();
	}

	public function withActiveSubscription($companyId) {
		$query = $this->db->query("SELECT id FROM company_payment WHERE expiry > now()");
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

	public function addSubscription(array $data) {
		$needles = array("companyId", "paymentId", "stripeToken", "createdBy");
		if ($this->checkArrayKeyExists($needles, $data))
			return false;

		$query1 = "SET @daysToAdd = (SELECT numOfDays FROM payment where id = ?);";
		$query2 = "INSERT INTO company_payment(companyId, paymentId, stripeToken, expiry, createDate, createdBy)
			VALUES(?, ?, ?, ?, NOW(), ?);";

		$this->db->trans_start();
		$this->db->query($query1, array($data["paymentId"]));
		$this->db->query($query2, array($data["companyId"], $data["paymentId"], $data["stripeToken"], $expiry, $data["createdBy"]));
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			log_message("error", "Something happened running sql query in " . __METHOD__ . "()");
			return FALSE;
		}

		return TRUE;
	}
}