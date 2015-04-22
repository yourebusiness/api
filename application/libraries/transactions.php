<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once ".inc/globals.inc.php";

class transactions {
	private $mysqli;

	public function __construct() {
		global $db;

		$this->mysqli = new mysqli($db["hostname"], $db["username"], $db["password"], $db["database"]);

		if ($this->mysqli->connect_errno)
			die("Connection error: " . mysqli_connect_errno() . ": " . mysqli_connect_error());
	}

	

	public function __destruct() {
        $this->mysqli->close();
    }
}