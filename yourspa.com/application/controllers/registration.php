<?php

require_once "../.inc/globals.inc.php";

class Registration extends CI_Controller {	
	public function view($page = 'register_view') {

		if (!file_exists('application/views/' . $page . '.php'))
			show_404();

		$this->load->model("Api_model");
		$data["province"] = $this->Api_model->getProvince();
		$data['title'] = "Register";
		$this->load->view('templates/header', $data);
		$this->load->view($page);
		$this->load->view('templates/footer');
	}

	private function generateRandomString($length = 20) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

    private function checkPWAndConfirmPW(array $data) {
        if ($data["password"] !== $data["confirmPassword"])
            return false;

        return true;
    }

	public function register() {
        $data = array();
        $data["password"] = $this->input->post("password");
        $data["confirmPassword"] = $this->input->post("confirmPassword");

        if (!$this->checkPWAndConfirmPW($data))
            return false;

        $data["company"] = $this->input->post("company");
        $data["province"] = $this->input->post("province");
        $data["city"] = $this->input->post("city");
        $data["address"] = $this->input->post("address");
        $data["phoneNo"] = $this->input->post("phoneNo");
        $data["companyWebsite"] = $this->input->post("companyWebsite");
        $data["tin"] = $this->input->post("tin");
        $data["fName"] = $this->input->post("fName");
        $data["lName"] = $this->input->post("lName");
        $data["gender"] = $this->input->post("gender");
        $data["userEmail"] = $this->input->post("userEmail");
        $data["hash"] = $this->generateRandomString();

        $this->load->model("Register_model");
    	if ($this->Register_model->add($data)) {
            global $settings;
            if ($settings["sendEmail"])
                $this->sendEmail($data["userEmail"], $data["hash"]);

            $data["title"] = "Registration";
            $this->load->view("templates/header", $data);
            $this->load->view("register_success");

            return TRUE;
    	} else {
            redirect(base_url("registration/view"));
            return FALSE;
        }
	}

    private function sendEmail($email, $hash) {
        $this->load->library("MY_PHPMailer.php");
        $mail = new PHPMailer;

        global $smtp;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = "smtp.mail.yahoo.com";  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $smtp["username"];                 // SMTP username
        $mail->Password = $smtp["password"];                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;                                    // TCP port to connect to

        $mail->From = $smtp["from"];
        $mail->FromName = 'yourspa Mailer';
        $mail->addAddress($smtp["recipients"]);             // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Registration to www.yourspa.com';

        $message = "Thank you for registering at our site. Click on the below link to activate your registration.\n
                    <a href='http://yourspa.com/registration/activateRegistration/$hash'>Activate Registration.</a>";
        $mail->Body    = $message;
        $mail->AltBody = "Thank you for registering at our site. Click on the below link to activate your registration.\n
                    http://yourspa.com/registration/activateregistration/$hash";

        if(!$mail->send())
            return false;
        else
            return true;
    }

    public function activateRegistration($hash = "") {
        $this->load->model("register_model");
        if ($this->register_model->activateRegistration($hash))
            $data["message"] = "Congratulations!!! You have successfully activated your account.";
        else
            $data["message"] = "Activation has NOT been successful.";

        $headerData["title"] = "Activation";
        $this->load->view("templates/header", $headerData);
        $this->load->view("activated", $data);
        $this->load->view("templates/footer2");
    }
}