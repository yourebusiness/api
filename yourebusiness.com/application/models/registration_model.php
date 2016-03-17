<?php

class Registration_model extends My_Model {

    public function __construct() {
        parent::__construct();
    }

    // company phone number must be unique in company table
    private function okToAddTelNo($telNo) {
        $query = $this->db->query("select companyId from company where telNo = ?", array($telNo));
        if ( ! $query) {
            $msg = $this->db->_error_message();
            $num = $this->db->_error_number();
            log_message("error", "Database error ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
        }

        $count = $query->num_rows();
        if ($count < 1)
            return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
        else {
            log_message("error", parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": Tel no: $telNo");
            return array("statusCode" => parent::ERRORNO_DB_VALUE_EXISTS, "statusMessage" => parent::ERRORSTR_DB_VALUE_EXISTS, "statusDesc" => "");;
        }               
    }
    // TIN must be unique in company table
    private function okToAddTIN($tin) {
        $query = $this->db->query("select companyId from company where tin = ?", array($tin));
        if ( ! $query) {
            $msg = $this->db->_error_message();
            $num = $this->db->_error_number();
            log_message("error", "Database error ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
        }

        $count = $query->num_rows();
        if ($count < 1)
            return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
        else {
            log_message("error", parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": TIN: $tin.");
            return array("statusCode" => parent::ERRORNO_DB_VALUE_EXISTS, "statusMessage" => parent::ERRORSTR_DB_VALUE_EXISTS, "statusDesc" => "");;
        }
    }
    // uniqueCode is unique in company table
    private function okToAddUniqueCode($hash) {
        $query = $this->db->query("select companyId from company where uniqueCode = ?", array($hash));
        if ( ! $query) {
            $msg = $this->db->_error_message();
            $num = $this->db->_error_number();
            log_message("error", "Database error ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
        }

        $count = $query->num_rows();
        if ($count < 1)
            return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
        else {
            log_message("error", parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": TIN: $tin.");
            return array("statusCode" => parent::ERRORNO_DB_VALUE_EXISTS, "statusMessage" => parent::ERRORSTR_DB_VALUE_EXISTS, "statusDesc" => "");;
        }
    }
    // username or email should not exist
    private function okToAddUsername($username) { // note: username = email
        $username = trim($username);
        if ($username == "")
            return FALSE;

        $query = $this->db->query("select userId from users where username = ? or email = ?", array($username, $username));
        if ( ! $query) {
            $msg = $this->db->_error_message();
            $num = $this->db->_error_number();
            log_message("error", "Database error ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
        }

        $count = $query->num_rows();
        if ($count < 1)
            return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
        else {
            log_message("error", parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": Username: $username.");
            return array("statusCode" => parent::ERRORNO_DB_VALUE_EXISTS, "statusMessage" => parent::ERRORSTR_DB_VALUE_EXISTS, "statusDesc" => "");;
        }
    }
    private function checkDataForAdd(array $data) {
        if (strlen($data["tin"]) < 12)
            return array("statusCode" => parent::ERRORNO_INVALID_VALUE, "statusMessage" => parent::ERRORSTR_INVALID_VALUE);
        if (!in_array($data["gender"], array("M", "F")))
            return array("statusCode" => parent::ERRORNO_INVALID_VALUE, "statusMessage" => parent::ERRORSTR_INVALID_VALUE);

        return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
    }
    private function okToAddCompany(array $data) {
        if (isset($data["companyWebsite"]) && trim($data["companyWebsite"]) != "") {
            $query = "select companyId from company where companyName=? or website=?";
            $bind_param = array($data["company"], $data["companyWebsite"]);
        } else {
            if (isset($data["company"]) && trim($data["company"]) != "") {
                $query = "select companyId from company where companyName=?";
                $bind_param = array($data["company"]);
            } else {
                // $data["company"] should be set and not empty
                return array("statusCode" => parent::ERRORNO_INVALID_PARAMETER, "statusMessage" => parent::ERRORSTR_INVALID_PARAMETER);
            }
        }

        $query = $this->db->query($query, $bind_param);
        if ( ! $query) {
            $msg = $this->db->_error_message();
            $num = $this->db->_error_number();
            log_message("error", "Database error ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR, "statusDesc" => "");
        }

        $count = $query->num_rows();

        if ($count < 1)
            return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
        else {
            log_message("error", parent::ERRORNO_DB_VALUE_EXISTS . ": " . parent::ERRORSTR_DB_VALUE_EXISTS . ": Company name: " . $data['company']);
            return array("statusCode" => parent::ERRORNO_DB_VALUE_EXISTS, "statusMessage" => parent::ERRORSTR_DB_VALUE_EXISTS, "statusDesc" => " Company name ") . $data["company"] . " already exists.";
        }
    }
    public function add(array $data) {
        $needles = array("company", "province", "city", "address", "phoneNo", "tin", "fName", "lName", "userEmail", "gender", "password", "hash");
        $status = $this->checkArrayKeyExists($needles, $data);
        if ($status["statusCode"] != 0)
            return $status;

        $status = $this->checkDataForAdd($data);
        if ($status["statusCode"] != 0)
            return $status;

        $status = $this->okToAddUsername($data["userEmail"]);
        if ($status["statusCode"] != 0)
            return $status;
        
        $status = $this->okToAddCompany($data);
        if ($status["statusCode"] != 0)
            return $status;

        $status = $this->okToAddTIN($data["tin"]);
        if ($status["statusCode"] != 0)
            return $status;

        $status = $this->okToAddUniqueCode($data["hash"]);
        if ($status["statusCode"] != 0)
            return $status;

        $status = $this->okToAddTelNo($data["phoneNo"]);
        if ($status["statusCode"] != 0)
            return $status;

        $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);

        $sql1 = "INSERT INTO company(companyName, address, province, city, telNo, website, tin, uniqueCode, createDate, captcha)
            VALUES( ?,  ?, ?, ?,  ?,  ?,  ?,  ?, now(), ?);";
        $sql2 = "SET @companyId = LAST_INSERT_ID();";
        $sql3 = "INSERT INTO users(companyId,userId,username, passwd, fName, lName, email, gender, createDate, role)
            values(@companyId, 1,  ?,  ?,  ?,  ?,  ?,  ?, now(), 0);";
        $sql4 = "SET @user = LAST_INSERT_ID();";
        $sql5 = "INSERT INTO `documents`(companyId, documentCode, documentName, lastNo)
                    VALUES(@companyId, 'BP', 'BusinessPartners', 0),
                    (@companyId, 'CU', 'Customers', 1),
                    (@companyId, 'EM', 'Employees', 0),
                    (@companyId, 'SVS', 'Services', 0),
                    (@companyId, 'TRAN', 'Transactions', 0),
                    (@companyId, 'USR', 'Users', 1);";
        $sql6 = "INSERT INTO customers(companyId, customerId, custType, fName, midName, lName, active, defaultCustomer, createdBy, createDate, trans)
                VALUES(@companyId, 1, 0, 'Guest', 'Guest', 'Guest', 'Y', 'Y', @user, now(), 'N');";
        $sql7 = "SET @intervalInMonths = (SELECT intervalInMonths FROM payment WHERE id = 1);"; //Free Registration
        $sql8 = "INSERT INTO company_payment(companyId, paymentId, approvalCode, expiry, amount, createDate, createdBy)
                VALUES(@companyId, 1, 'AAA-BBB-000', DATE_ADD(CURDATE(), INTERVAL @intervalInMonths MONTH), 0, NOW(), @user);";
        $sql9 = "INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES (@user, ?, 'http://fake/');";

        $this->db->trans_start();
        $this->db->query($sql1, array($data["company"], $data["address"], $data["province"], $data["city"], $data["phoneNo"], $data["companyWebsite"], $data["tin"], $data["hash"], $data["captcha"]));
        $this->db->query($sql2);
        $this->db->query($sql3, array($data["userEmail"], $data["password"], $data["fName"], $data["lName"], $data["userEmail"], $data["gender"]));
        $this->db->query($sql4);
        $this->db->query($sql5);
        $this->db->query($sql6);
        $this->db->query($sql7);
        $this->db->query($sql8);
        $this->db->query($sql9, array($data["password"]));
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return array("statusCode" => parent::ERRORNO_DB_ERROR, "statusMessage" => parent::ERRORSTR_DB_ERROR);
        }

        return array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK);
    }

    public function getCompanyInfo($companyId) {
        if ($companyId == "")
            return FALSE;

        $query = "select companyName, address, province, city, telNo, website, tin from company where companyId = ?";
        $query = $this->db->query($query, array($companyId));
        if ( ! $query) {
            $msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return FALSE;
        }

        return $query->result_array();
    }

    public function activateRegistration($hash) {
        if ($hash == "")
            return FALSE;

        $query = "UPDATE company set activated = 'Y' where BINARY uniqueCode = ?";
        $query = $this->db->query($query, array($hash));
        if ( ! $query) {
            $msg = $this->db->_error_number();
            $num = $this->db->_error_message();
            log_message("error", "Error running sql query in " . __METHOD__ . "(). ($num) $msg");
            return FALSE;
        }

        return TRUE;
    }
}