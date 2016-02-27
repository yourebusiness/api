<?php

/* I may consider this https://gist.github.com/nambok/3834056 for extending CI input method. */
class Api extends My_Controller {
	public function getProvinces() {
		$this->load->model("Api_model");
		$this->_response($this->Api_model->getProvinces());
	}

	public function getCity($provinceId) {
		$this->load->model("Api_model");
		$this->_response($this->Api_model->getCity($provinceId));
	}

	public function provincesAndCities() {
		$this->load->model("Api_model");
		$this->_response($this->Api_model->getProvincesAndCities());
	}

	public function signIn() {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("username", "Username", "trim|required|valid_email|xss_clean");
		$this->form_validation->set_rules("password", "Password", "trim|required");

		$data = array("username" => $this->input->post("username"),
					"password" => $this->input->post("password")
				);

		$data["title"] = "Login";
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("templates/header", $data);
			$this->load->view("failedLogin");
		} else {
			$this->load->model("Api_model");
			if ($this->Api_model->signIn($data)) {	// if successful

				$this->load->helper('record');
				$row = getRecordsForLoginByUsername($data["username"]);

				$sess_data = array( "username" => $data["username"], "companyId" => $row["companyId"], "uniqueCode" => $row["uniqueCode"], "userId" => $row["userId"], "id" => $row["id"], "role" => $row["role"]); // username = email
				$this->session->set_userdata($sess_data);

				redirect(site_url("admin"));
			} else {
				$this->load->view("templates/header", $data);
				$this->load->view("failedLogin");
			}
		}
	}

	// this is the resource controller in OAuth2.0
	public function signin2() {
    	// include our OAuth2 Server object
		require_once('../restService/server.php');

		// Handle a request to a resource and authenticate the access token
		if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
		    $server->getResponse()->send();
		    die;
		}
		echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));
    }

    public function resourceController() {
    	$this->load->library("OAuth2");
    	// Handle a request to a resource and authenticate the access token
		if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
		    $server->getResponse()->send();
		    die;
		}
		echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));
    }

	public function forgotPassword() {
		$email = $this->input->get("email");
		$this->load->helper('utility');
		$result = validateEmailAddress($email);

		switch ($result) {
			case 1:
				break;
			case 0:
				$this->_response(array("statusCode" => parent::ERRORNO_INVALID_VALUE,
					"statusMessage" => parent::ERRORSTR_INVALID_VALUE, "statusDesc" => "Invalid email address."));
				die();
				break;
			case false:
				$this->_response(array("statusCode" => parent::ERRORNO_INTERNAL_SERVER_ERROR,
					"statusMessage" => parent::ERRORSTR_INTERNAL_SERVER_ERROR, "statusDesc" => "Internal server error."));
				die();
				break;
		}

		$hash = generateRandomString(40);
		$this->load->model("Api_model");
		$status = $this->Api_model->resetPassword($email, $hash);
		if ($status["statusCode"] != 0) {
			$this->_response($status);
		} else {
			$status = array("statusCode" => parent::ERRORNO_OK, "statusMessage" => parent::ERRORSTR_OK, "statusDesc" => "Okay.");

			global $settings;
            if ($settings["sendEmail"]) {
                if (!$this->_sendEmail($email, $hash)) {	// want to do this in cron instead
                	$status = array("statusCode" => parent::ERRORNO_INTERNAL_SERVER_ERROR, "statusMessage" => parent::ERRORSTR_INTERNAL_SERVER_ERROR, "statusDesc" => "Unable to send link for forgot password.");
                }
            }

			$this->_response($status);
		}

		return; // needed not to execute succeeding codes below.
	}

	public function forgotPasswordReset() {
		$this->method = $_SERVER["REQUEST_METHOD"];

		//$.ajax() is Access-Control-Request-Method, PHP is HTTP_ACCESS_CONTROL_REQUEST_METHOD

		if ($this->method == "OPTIONS" && array_key_exists("HTTP_ACCESS_CONTROL_REQUEST_METHOD", $_SERVER)) {
			if ($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"] == "PUT")
				$this->method = "PUT";
			elseif ($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"] == "OPTIONS")
				$this->method = "OPTIONS";
			elseif ($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"] == "DELETE")
				$this->method = "DELETE";
			else
				throw new Exception("Unexpected Header");
		} else {
			//throw new Exception("Unexpected Header");
			$this->_response(array("Invalid method."), 405);
		}

		switch ($this->method) {
			case "DELETE":
				break;
			case "POST":
				break;
			case "GET":
				break;
			case 'PUT':
				$this->_forgotPasswordReset();
				break;
			default:
				$this->_response(array("Invalid method."), 405);
				break;
		}
	}

	private function _forgotPasswordReset() {
		$hash = $this->input->get("hash");

		if (!$hash) {
			$status = array("statusCode" => parent::ERRORNO_INVALID_PARAMETER, "statusMessage" => parent::ERRORSTR_INVALID_PARAMETER, "statusDesc" => "No hash provided.");
			$this->_response($status);
			return;
		}

		$this->load->model("Api_model");
		$status = $this->Api_model->forgotPasswordReset($hash);
		$this->_response($status);
		return;
	}

	private function _sendEmail($email, $hash) {
        $this->load->library("MY_PHPMailer.php");
        $mail = new PHPMailer;

        global $smtp;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = "smtp.mail.yahoo.com";  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $smtp["username"];                 // SMTP username
        $mail->Password = $smtp["password"];                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->From = $smtp["from"];
        $mail->FromName = 'yourspa Mailer';
        $mail->addAddress($email);             // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Password Reset Request.';

        global $url;

        $message = "You have requested to reset your password based on your email. If you didn't ask this please disregard it.\n Click on the below link to reset your password.
                    <a href='" . $url["temporaryUrl"] . "?hash=" . $hash . "'>Reset password.</a>";
        $mail->Body    = $message;
        $mail->AltBody = "Password reset requested.\n " . $url["temporaryUrl"] . "?hash=" . $hash;

        if(!$mail->send()) {
        	error_log($mail->ErrorInfo);
            return false;
        } else {
            return true;
        }
    }

	public function logout() {
		$this->session->sess_destroy();
		$data["title"] = "Logged Out";
		$this->load->view("templates/header", $data);
		$this->load->view("logout");
	}
}