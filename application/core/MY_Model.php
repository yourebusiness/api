<?php
/* My_model will now be the extended model not the CI_model to make error centralized. */

class My_model extends CI_Model {
	const ERRORNO_OK = 0;
	const ERRORSTR_OK = "OK";

	const ERRORNO_NOT_AUTHORIZED = 1;
	const ERRORSTR_NOT_AUTHORIZED = "User not authorized.";

	const ERRORNO_UNEXPECTED_VALUE = 2;
	const ERRORSTR_UNEXPECTED_VALUE = "Unexpected returned value.";
	
	const ERRORNO_EMPTY_VALUE = 4;
	const ERRORSTR_EMPTY_VALUE = "Data should not be empty.";

	const ERRORNO_MAX_REACHED = 5;
	const ERRORSTR_MAX_REACHED = "Maximum allowed records has been reached.";

	const ERRORNO_INVALID_PARAMETER = 8;
	const ERRORSTR_INVALID_PARAMETER = "Invalid passed parameter.";

	const ERRORNO_DB_VALUE_EXISTS = 9;
	const ERRORSTR_DB_VALUE_EXISTS = "Database value already exists.";

	// a generic error
	const ERRORNO_DB_ERROR = 10;
	const ERRORSTR_DB_ERROR = "Database error.";

	public function __construct() {
		parent::__construct();
	}

	protected function checkArrayKeyExists(array $needles, array $haystack) {
    	foreach ($needles as $needle) {
            if (!array_key_exists($needle, $haystack)) {
                error_log(self::ERRORNO_INVALID_PARAMETER . ": " . self::ERRORSTR_INVALID_PARAMETER . " '$needle' ");
                return array("statusCode" => self::ERRORNO_INVALID_PARAMETER, "statusMessage" => self::ERRORSTR_INVALID_PARAMETER, "statusDesc" => "Missing key: " . $needle);
            }
        }

    	return array("statusCode" => self::ERRORNO_OK, "statusMessage" => self::ERRORSTR_OK);
    }
}