<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getCompanyIdByUsername')) {
    function getCompanyIdByUsername($username) {
    	$ci =& get_instance();
    	$ci->load->database();

        $query = "select companyId from users where username = ?";
        $query = $ci->db->query($query, array($username));
        if (!$query)
        	return false;
        else {
        	if ($query->num_rows() <= 0)
        		return false;
        	else {
        		$row = $query->row_array();
        		return $row["companyId"];
        	}
        }
    }
}

if ( ! function_exists('getUserIdByUsername')) {
    function getUserIdByUsername($username) {
        $ci =& get_instance();
        $ci->load->database();

        $query = "select userId from users where username = ?";
        $query = $ci->db->query($query, $username);
        if (!$query)
            return false;
        else {
            if ($query->num_rows() <= 0)
                return false;
            else
                $row = $query->row_array();
            return $row["userId"];
        }
    }
}

if ( ! function_exists('checkUserRightsByUserId')) {
    function checkUserRightsByUserId($userId) {
        $ci =& get_instance();
        $ci->load->database();

        $query = "select role from users where userId = ?";
        $query = $ci->db->query($query, $userId);
        if (!$query)
            return false;
        else {
            if ($query->num_rows() <= 0)
                return false;
            else
                $row = $query->row_array();
            return $row["role"];
        }
    }
}