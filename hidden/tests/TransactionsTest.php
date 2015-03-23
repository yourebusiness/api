<?php

require_once(dirname(__FILE__) . "/../../.inc/globals.inc.php");
require_once(dirname(__FILE__) . "/../../.inc/shared.inc.php");
require_once(dirname(__FILE__) . "/../../.inc/functions.inc.php");

class TransactionsTest extends PHPUnit_Framework_Testcase {
	protected $url;
	private $mysqli;

	protected function setUp() {
		global $webvars;
		$this->assertTrue(dropAndReloadDatabase());
		$this->url = $webvars["SERVER_ROOT"] . "/admin/addTransaction";

		$this->assertTrue(mysqlDump());
		$this->assertTrue(insertCommonData());

		global $db;
		$this->mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
	}

	protected function tearDown() {
		$this->mysqli->close();
	}

	public function testLogin() {
		$uniqueCode = generateRandomString();
		$password = password_hash("pass", PASSWORD_BCRYPT);

		$sql1 = sprintf("insert into company(companyName, address, province, city, telNo, website, tin, uniqueCode, createDate)
			values('%s', '%s', %d, %d, '%s', '%s', '%s', '%s', now());",
			"ABC Company", "Brgy. Talamban", 25, 48, "3251234", "www.yahoo.com", "01234567891235", $uniqueCode);
		$sql2 = "SET @companyId = LAST_INSERT_ID();";
		$sql3 = sprintf("insert into users(companyId,userId,username, passwd, fName, lName, email, gender, createDate, role)
			values(@companyId, 1, '%s', '%s', '%s', '%s', '%s', '%s', now(), 0);",
			"abc123@yahoo.com", $password, "Justin", "Cruz", "abc123@yahoo.com", "M");
		$sql4 = "insert into `documents`(companyId, documentCode, documentName, lastNo)
                    values(@companyId, 'BP', 'BusinessPartners', 0),
                    (@companyId, 'CU', 'Customers', 0),
                    (@companyId, 'EM', 'Employees', 0),
                    (@companyId, 'SVS', 'Services', 0),
                    (@companyId, 'TRAN', 'Transactions', 0),
                    (@companyId, 'USR', 'Users', 1);";

		try {
			$this->mysqli->autocommit(false);
			if (!$this->mysqli->query($sql1))
				throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql2))
				throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql3))
				throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
			if (!$this->mysqli->query($sql4))
				throw new Exception("Something went wrong on sql." . "Error: " . $this->mysqli->error);
			
			$this->mysqli->commit();
			$returnResult = true;
		} catch (Exception $e) {
			$this->mysqli->rollback();			
			$returnResult = false;
		} finally {
			$this->mysqli->autocommit(true);
		}

		$data = array("username" => "abc123@yahoo.com", "password" => "pass");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ($ch);

		$this->assertEquals(302, $status_code);	// 302 is a redirection code

		$result = $this->mysqli->query("select * from users");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$this->assertNotNull($row["lastLogIn"]);

		$result = $this->mysqli->query("select * from ci_sessions");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$this->assertNotEmpty($row["user_data"]);
	}
}
