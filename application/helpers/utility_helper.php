<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('generateRandomString')) {
	function generateRandomString($length = 20) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
}

if ( ! function_exists('arrayToCSV')) {
	function arrayToCSV(array $data) {
		if (isset($data[0]))
			$columns = array_keys($data[0]);
		else
			return $csv = "";	// if nothing, early exit

		// we want to escape comma inside the column names
		foreach ($columns as $key => $column)
			if (strpos($column, ","))
				$columns[$key] = '"' . $column . '"';

		$csv = implode(",", $columns) . "\r\n";

		// we want to escape comma inside the field values
    	foreach ($data as $row) {
    		foreach ($row as $key => $field) {
    			if (strpos($field, ","))
					$row[$key] = '"' . $field . '"';
    		}

    		$csv .= implode(",", $row) . "\r\n";
    	}

    	return $csv;
	}
}

if ( ! function_exists('isCurrency')) {
	function isCurrency($value) {
		$return = preg_match("/\b\d{1,3}(?:,?\d{3})*(?:\.\d{2})?\b/", $value);

		if ($return == 1)
			return TRUE;
		else
			return FALSE;
	}
}

/* remove comma from monetary value and replaced with empty string. */

if ( ! function_exists('normalizeNumber')) {
	function normalizeNumber($value) {
		return preg_replace("/[,]/", "", $value);
	}
}