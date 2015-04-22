<?php

class Subscription_model extends baseClass2 {
	public function __construct() {
		parent::__construct();
	}

	public function add(array $data) {
		$needles = array("companyId", "paymentId", "stripeToken", "createdBy");
		if ( ! $this->checkArrayKeyExists($needles, $data))
			return FALSE;

		$query1 = "SET @intervalInMonths = (SELECT intervalInMonths FROM payment where id = ?);";
		$query2 = "SET @expiry = (SELECT DATE_ADD(NOW(), INTERVAL @intervalInMonths MONTH));";
		$query3 = "INSERT INTO company_payment(companyId, paymentId, stripeToken, expiry, createDate, createdBy)
			VALUES(?, ?, ?, @expiry, NOW(), ?);";

		$this->db->trans_start();
		$this->db->query($query1, array($data["paymentId"]));
		$this->db->query($query2);
		$this->db->query($query3, array($data["companyId"], $data["paymentId"], $data["stripeToken"], $data["createdBy"]));
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			log_message("error", "Something happened running sql query in " . __METHOD__ . "()");
			return FALSE;
		}

		return TRUE;
	}
}