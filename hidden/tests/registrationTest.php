<?php

require_once(dirname(__FILE__) . "/../../.inc/globals.inc.php");
require_once(dirname(__FILE__) . "/../../.inc/shared.inc.php");
require_once(dirname(__FILE__) . "/../../.inc/functions.inc.php");

class RegistrationTest extends PHPUnit_Framework_Testcase {
	protected $url;
	private $mysqli;

	protected function setUp() {
		global $webvars;
		$this->url = $webvars["SERVER_ROOT"] . "/registration/register";

		$this->assertTrue(mysqlDump());
		$this->assertTrue(dropAndReloadDatabase());
		$this->assertTrue(insertCommonData());

		global $db;
		$this->mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
	}

	protected function tearDown() {
		$this->mysqli->close();
	}

	public function testSuccessRegistration() {
		$data = array("password" => "12345",
	        	"confirmPassword" => "12345",
	        	"company" => "ABC Company",
		        "province" => 25, // cebu
		        "city" => 48,  // cebu city
		        "address" => "Cebu City",
		        "phoneNo" => "3253333",
		        "companyWebsite" => "www.yahoo.com",
		        "tin" => "123456789012",
		        "fName" => "Jhunex",
		        "lName" => "Morcilla",
		        "gender" => "M",
		        "userEmail" => "jdream_catcher@yahoo.com");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, 1);                //0 for a get request
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ($ch);

		$this->assertEquals(200, $status_code);	// should be status code 200 OK

		$query = "select * from company";
		$result = $this->mysqli->query($query);
		$this->assertEquals(1, $result->num_rows, "Number of records are not equals to expected in company table.");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$this->assertEquals($data["company"], $row["companyName"], "Company names are not equals.");
		$this->assertEquals($data["address"], $row["address"]);
		$this->assertEquals($data["province"], $row["province"]);
		$this->assertEquals($data["city"], $row["city"]);
		$this->assertEquals($data["phoneNo"], $row["telNo"]);
		$this->assertEquals($data["companyWebsite"], $row["website"]);
		$this->assertEquals($data["tin"], $row["tin"]);
		$this->assertEquals('Y', $row['active']);
		$this->assertEquals('N', $row['activated']);
		$this->assertNotNull($row["uniqueCode"]);
		$this->assertNotNull($row["createDate"]);

		$query = "select * from users";
		$result = $this->mysqli->query($query);
		$this->assertEquals(1, $result->num_rows, "Number of records are not equals to expected in users table.");

		$row = $result->fetch_array(MYSQLI_ASSOC);
		$this->assertEquals(1, $row["companyId"]); // This is a 1st record.
		$this->assertEquals(1, $row["userId"]); // This is a 1st record. From document.documentCode = "USR"
		$this->assertEquals($data["fName"], $row["fName"]);
		$this->assertEquals(null, $row["midName"]);
		$this->assertEquals($data["lName"], $row["lName"]);
		$this->assertEquals($data["gender"], $row["gender"]);
		$this->assertEquals($data["userEmail"], $row["username"]);
		$this->assertEquals($data["userEmail"], $row["email"]);
		$this->assertNotNull($row["passwd"]);
		$this->assertEquals(60, strlen($row["passwd"])); // the password_hash always produce 60 chars
		$this->assertTrue(password_verify($data["password"], $row["passwd"]));
		$this->assertEquals(null, $row["address"]);
		$this->assertEquals(null, $row["lastLogIn"]);
		$this->assertNotNull($row["createDate"]);
		$this->assertEquals(null, $row["updateDate"]);
		$this->assertEquals(null, $row["updatedBy"]);
		$this->assertEquals(null, $row["createdBy"]);
		$this->assertEquals("Y", $row["active"]);
		$this->assertEquals(0, $row["role"]);
		$this->assertEquals("N", $row["trans"]);

		$result = $this->mysqli->query("select * from documents");
		$this->assertEquals(6, $result->num_rows, "Number of records are not equals to expected in documents table.");
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			if ($row["documentCode"] == "USR")
				$this->assertEquals(1, $row["lastNo"]);
			elseif ($row["documentCode"] == "CU")
				$this->assertEquals(1, $row["lastNo"]);
			else
				$this->assertEquals(0, $row["lastNo"]);
		}

		$result = $this->mysqli->query("SELECT * FROM customer");
		$this->assertEquals(1, $result->num_rows);
			
	}

	/* public function testEditCompanyProfile() {
		$this->testSuccessRegistration();

		global $webvars;
		$this->url = $webvars["SERVER_ROOT"] . "/registration/editCompanyProfile";

		$data = array("company" => "ABCDEF Company",
		        "province" => 82, // metro manila
		        "city" => 3,  // makati
		        "address" => "Makati",
		        "phoneNo" => "3259999",
		        "companyWebsite" => "www.google.com",
		        "tin" => "03434354535",
		        "companyId" => 1);
		
		$getData = "";
		foreach ($data as $key => $value) {
			$getData .= $key . "=" . urlencode($value) . "&";
		}
		$getData = "?" . rtrim($getData, "&");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $this->url . $getData);
		
		$output = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ($ch);

		$this->assertEquals(200, $status_code);	// should be status code 200 OK

		global $db;

		$this->mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);

		$result = $this->mysqli->query("select * from company");
		$this->assertEquals(1, $result->num_rows, "Number of records are not equals to expected in users table.");

		$row = $result->fetch_array(MYSQLI_ASSOC);
		$this->assertEquals($data["company"], $row["companyName"]);
		$this->assertEquals($data["address"], $row["address"]);
		$this->assertEquals($data["province"], $row["province"]);
		$this->assertEquals($data["city"], $row["city"]);
		$this->assertEquals($data["phoneNo"], $row["telNo"]);
		$this->assertEquals($data["companyWebsite"], $row["website"]);
		$this->assertEquals($data["tin"], $row["tin"]);
	}
	*/
	/* public function testActivateCompany() {
		$this->testSuccessRegistration();

		global $webvars;
		$this->url = $webvars["SERVER_ROOT"] . "/registration/activateRegistration";

		

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $this->url . $getData);
		
		$output = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ($ch);

		$this->assertEquals(200, $status_code);	// should be status code 200 OK

		global $db;

		$this->mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);

		$result = $this->mysqli->query("select * from company");
		$this->assertEquals(1, $result->num_rows, "Number of records are not equals to expected in users table.");

		$row = $result->fetch_array(MYSQLI_ASSOC);
		$this->assertEquals($data["company"], $row["companyName"]);
	} */
}
