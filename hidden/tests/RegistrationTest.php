<?php

require_once(dirname(__FILE__) . "/../../.inc/globals.inc.php");
require_once(dirname(__FILE__) . "/../../.inc/shared.inc.php");
require_once(dirname(__FILE__) . "/../../.inc/functions.inc.php");

class RegistrationTest extends PHPUnit_Framework_Testcase {
	protected $url;

	protected function setUp() {
		global $webvars;
		$this->assertTrue(dropAndReloadDatabase());
		$this->url = $webvars["SERVER_ROOT"] . "/registration/register";

		$this->assertTrue(mysqlDump());

		$this->assertTrue(insertCommonData());
	}

	protected function tearDown() {
		global $db;
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

		global $db;

		$mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);

		$query = "select * from company";
		$result = $mysqli->query($query);
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
		$result = $mysqli->query($query);
		$this->assertEquals(1, $result->num_rows, "Number of records are not equals to expected in users table.");

		$row = $result->fetch_array(MYSQLI_ASSOC);
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

		$query = "select * from company_users";
		$result = $mysqli->query($query);
		$this->assertEquals(1, $result->num_rows, "Number of records are not equals to expected in users table.");

		$row = $result->fetch_array(MYSQLI_ASSOC);
		// we know that there's only 1 record so we expect company id and user id are both 1.
		$this->assertEquals(1, $row['id']);
		$this->assertEquals(1, $row['companyId']);
		$this->assertEquals(1, $row['userId']);
		$this->assertNotNull($row['createDate']);
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

		$mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);

		$result = $mysqli->query("select * from company");
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

		$mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);

		$result = $mysqli->query("select * from company");
		$this->assertEquals(1, $result->num_rows, "Number of records are not equals to expected in users table.");

		$row = $result->fetch_array(MYSQLI_ASSOC);
		$this->assertEquals($data["company"], $row["companyName"]);
	} */
}
