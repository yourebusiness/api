<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_controller extends CI_Controller {
	const ERRORNO_OK = 0;
	const ERRORSTR_OK = "OK";
    const ERRORNO_INVALID_CAPTCHA = 3;
    const ERRORSTR_INVALID_CAPTCHA = "Invalid captcha.";
    const ERRORNO_INTERNAL_SERVER_ERROR = 6;
    const ERRORSTR_INTERNAL_SERVER_ERROR = "Internal server error.";
	const ERRORNO_INVALID_VALUE = 7;
	const ERRORSTR_INVALID_VALUE = "Invalid passed value.";
    const ERRORNO_INVALID_PARAMETER = 8;
    const ERRORSTR_INVALID_PARAMETER = "Invalid passed parameter.";

	public function __construct() {
		parent::__construct();
	}

	protected function _requestStatus($code) {
        $status = array(
                200 => "OK",
                404 => "Not found",
                405 => "Method not allowed",
                500 => "Internal Server Error",
            );

        return ($status[$code]) ? $status[$code] : $status[500];
    }

    protected function _response($data, $status = 200) {
        $this->output
                ->set_header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status))
                ->set_header("Access-Control-Allow-Origin: http://localhost:8080")
                ->set_header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE")
                ->set_content_type("application/json")
                ->set_output(json_encode($data));
    }   
}