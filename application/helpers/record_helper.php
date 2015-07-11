<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*being used by Api->signIn*/
if ( ! function_exists('getRecordsForLoginByUsername')) {
    function getRecordsForLoginByUsername($username) {
        $ci =& get_instance();
        $ci->load->database();

        $query = "select companyId, userId, role, uniqueCode FROM users JOIN company USING(companyId) WHERE username = ?";
        $query = $ci->db->query($query, $username);
        if (!$query)
            return FALSE;
        else {
            if ($query->num_rows() < 0)
                return FALSE;
            else
                $row = $query->row_array();

            return $row;
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

if ( ! function_exists('getPriceForCustomer')) {
    function getPriceForCustomer(array $data) {
        $ci =& get_instance();
        $ci->load->database();

        $query = "SELECT price FROM pricelist JOIN services ON pricelist.serviceId = services.id
                WHERE services.id = ?
                    AND pricelistCode = (SELECT (CASE WHEN custType = 0 THEN 0 ELSE 1 END)
                                            FROM customer
                                            WHERE id = ? AND services.companyId = ?)";
        $query = $ci->db->query($query, array($data["serviceId"], $data["customerId"], $data["companyId"]));
        if (!$query)
            return FALSE;
        else {
            if ($query->num_rows() <= 0)
                return FALSE;
            else
                $row = $query->row_array();
            return $row["price"];
        }
    }
}