<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('generateRandomString')) {
	function generateRandomString($length = 20) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
}

if ( ! function_exists('arrayToCSV')) {
	function arrayToCSV(array $data) {
		$columns = array_keys($data[0]);
		$csv = implode(",", $columns) . "\r\n";

    	foreach ($data as $row)
    		$csv .= implode(",", $row) . "\r\n";

    	return $csv;
	}
}