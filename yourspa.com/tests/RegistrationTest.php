<?php

class RegistrationTest extends PHPUnit_Framework_Testcase {
	protected $url;
	protected function setUp()
	{
		global $webvars;
		$this->url = $webvars["SERVER_ROOT"] . "/index.php/signup/validation";
	}
	protected function tearDown() {
		global $webvars;
	}
	public function testSuccessRegistration() {
		$this->assertTrue(true);
	}
}
