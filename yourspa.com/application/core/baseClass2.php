<?php

class baseClass2 extends CI_Model {
	
	const ERRORNO_EMPTY_VALUE = 4;
	const ERRORSTR_EMPTY_VALUE = "Data should not be empty string.";

	const ERRORNO_INVALID_PARAMETER = 8;
	const ERRORSTR_INVALID_PARAMETER = "Invalid passed parameter.";

	const ERRORNO_DB_VALUE_EXISTS = 9;
	const ERRORSTR_DB_VALUE_EXISTS = "Database value already exist.";

	public function __construct() {
		parent::__construct();
	}

	protected function checkArrayKeyExists(array $needles, array $haystack) {
    	foreach ($needles as $needle) {
            if (!array_key_exists($needle, $haystack)) {
                error_log(self::ERRORNO_INVALID_PARAMETER . ": " . self::ERRORSTR_INVALID_PARAMETER . " '$needle' ");
                return false;
            }
        }

    	return true;
    }
}