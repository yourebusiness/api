<?php

class Admin extends CI_Controller {
	private $username = "";
	private $userId = 0;
	private $companyId = 0;
	private $role = "-1";	//enum("0, 1")

	private $method;

	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata["username"])
			redirect(site_url("api/signin"));
		else {
			$this->username = $this->session->userdata["username"];
			$this->userId = $this->session->userdata["userId"];
			$this->companyId = $this->session->userdata["companyId"];
			$this->role = $this->session->userdata["role"];
		}
	}

	private function showMasseurs($arr) {
		$this->load->model("Admin_model");
		return $this->Admin_model->showMasseurs($arr);
	}

	public function index() {
		file_put_contents("/tmp/users.txt", $this->username);

		$data["title"] = "Your Spa";
		$data["username"] = $this->username;
		$data["userRights"] = $this->session->userdata["role"];

		$this->load->view("templates/v2/header", $data);
		$this->load->view("sessioned/v2/home");

		// we want this to delete after exiting from company profile page.
		$this->load->helper("cookie");
		delete_cookie("yourspaFunc_CompanyProfile");
	}

	public function masseur() {
		$arr = array("companyId" => $this->companyId);
		
		$masseurs = $this->showMasseurs($arr);
		if ($masseurs)
			$data["masseurs"] = $masseurs;
		else
			$data["masseurs"] = array();

		$data["title"] = "Masseurs";
		$data["username"] = $this->username;
		$this->load->view("templates/header", $data);
		$this->load->view("sessioned/masseur_view", $data); // includes footer
	}

	public function masseuradd_view() {
		$data["title"] = "Add Masseur";
		$data["username"] = $this->username;
		$this->load->view("templates/header", $data);
		$this->load->view("sessioned/masseuradd_view");
	}

	public function masseuradd() {
		
		$midName = $this->input->get("midName");
		if ($midName == null || $midName == "")
			$midName = null;

		$data = array("companyId" => $this->session->userdata["companyId"],
					"createdBy" => $this->session->userdata["userId"],
					"nickname" => $this->input->get("nickname"),
					"fName" => $this->input->get("fName"),
					"midName" => $midName,
					"lName" => $this->input->get("lName")
				);

		$this->load->model("Admin_model");
		if (!$this->Admin_model->masseurAdd($data))
			echo "Error adding new record.";
		else
			return true;
	}

	public function masseurChangeStatus($id, $status) {
		if ($status == "Y")
			$status = "N";
		else
			$status = "Y";

		$data = array("id" => $id, "status" => $status, "updatedBy" => $this->session->userdata["userId"]);

		$this->load->model("Admin_model");
		if (!$this->Admin_model->masseurChangeStatus($data))
			echo "Error updating status";
		else
			return true;
	}

	public function masseurDelete($id) {
		$this->load->model("Admin_model");
		if (!$this->Admin_model->masseurDelete($id))
			echo "Error deleting masseur.";
		else
			return true;
	}

	public function masseurEdit($id = 0, $nickname = "", $fName = "", $midName = "", $lName = "") {
		$midName = $this->input->get("midName");
		if ($midName == null || empty($midName))
			$midName = null;

		$data = array("id" => $this->input->get("id"),
					"updatedBy" => $this->session->userdata["userId"],
					"nickname" => $this->input->get("nickname"),
					"fName" => $this->input->get("fName"),
					"midName" => $midName,
					"lName" => $this->input->get("lName")
				);

		$this->load->model("Admin_model");
		if (!$this->Admin_model->masseurEdit($data))
			echo "Error adding new record.";
		else
			return true;
	}


	/* controller for users */

	private function getAllUsers() {
		$this->load->model("Admin_model");
		return $this->Admin_model->getAllUsers();
	}

	private function _getUsersExceptCurrentByCompanyId() {
		if ($this->role == 0) {
			$this->load->model("Users");
			$users = $this->Users->getUsersExceptCurrentByCompanyId($this->userId, $this->companyId);
			return $users;
		}
	}

	private function _requestStatus($code) {
		$status = array(
				200 => "OK",
				404 => "Not found",
				405 => "Method not allowed",
				500 => "Internal Server Error",
			);

		return ($status[$code]) ? $status[$code] : $status[500];
	}

	private function _response($data, $status = 200) {
		$this->output
				->set_header("HTTP/1.1 " . $status . " " . $this->_requestStatus(200))
				->set_content_type('application/json')
				->set_output(json_encode($data));		
	}

	public function users() {
		$headerData["title"] = "Users list";
		$headerData['username'] = $this->username;

		$this->method = $_SERVER["REQUEST_METHOD"];
		if ($this->method == "POST" && array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER)) {
			if ($_SERVER["HTTP_X_HTTP_METHOD"] == "DELETE")
				$this->method = "DELETE";
			elseif ($_SERVER["HTTP_X_HTTP_METHOD"] == "PUT")
				$this->method = "PUT";
			else
				throw new Exception("Unexpected Header");
		}

		switch ($this->method) {
			case "DELETE":
				break;
			case "POST":	//add a user
				$this->_usersAdd();
				break;
			case "GET":
				$currentUser = (string)$this->input->get("current-user");

				if ($currentUser != NULL && ($currentUser == "false"))
					$this->_response($this->_getUsersExceptCurrentByCompanyId());
				elseif ($currentUser != NULL && ($currentUser == "true"))
					$this->_response(array()); // no emplimentation yet
				else {
					if ($this->role == 0) {
						$this->load->view("templates/v2/header2", $headerData);
				    	$this->load->view("sessioned/v2/users_view");
				    } else {
				    	$this->output->set_status_header('401');
				    	$this->load->view("sessioned/401Unauthorized.php");
					}
				}
				break;
			case 'PUT':
				$this->_usersEdit();
				break;
			default:
				$this->_response(array("Invalid method."), 405);
				break;
		}
	}

	private function _usersAdd() {
		$midName = $this->input->post("midName");
		if ($midName == null || empty($midName))
			$midName = null;

		$address = $this->input->post("address");
		if ($address == null || empty($address))
			$address = null;

		$this->load->helper("utility");
		$password = generateRandomString();

		$data = array("username" => $this->input->post("username"),
					"password" => $password,
					"fName" => $this->input->post("fName"),
					"midName" => $midName,
					"lName" => $this->input->post("lName"),
					"address" => $address,
					"gender" => $this->input->post("gender"),
					"role" => $this->input->post("role"),
					"createdBy" => $this->session->userdata["userId"],
					"companyId" => $this->session->userdata["companyId"]
				);

		$this->load->model("Users");
		$this->_response($this->Users->add($data));
	}

	private function _usersEdit() {
		//username,passwd,fName,midName,lName,email,address,gender,updatedBy

		$midName = $this->input->get("midName");
		if ($midName == null || empty($midName))
			$midName = null;
		$address = $this->input->get("address");
		if ($address == null || empty($address))
			$address = null;

		$data = array("username" => $this->input->get("username"),
					"fName" => $this->input->get("fName"),
					"midName" => $midName,
					"lName" => $this->input->get("lName"),
					"address" => $this->input->get("address"),
					"gender" => $this->input->get("gender"),
					"updatedBy" => $this->session->userdata["userId"]
				);

		$this->load->model("Admin_model");
		if (!$this->Admin_model->usersEdit($data))
			echo "Error editting record.";
		else
			return true;
	}

	public function usersDelete($id) {
		$this->load->model("Admin_model");
		if (!$this->Admin_model->usersDelete($id))
			echo "Deleting record was not successful.";
		else
			return TRUE;
	}

	public function usersChangeStatus($id, $status) {
		if ($status == "Y")
			$status = "N";
		else
			$status = "Y";

		$data = array("id" => $id, "status" => $status, "updatedBy" => $this->session->userdata["userId"]);

		$this->load->model("Admin_model");
		if (!$this->Admin_model->usersChangeStatus($data))
			echo "Error updating status";
		else
			return true;
	}

	public function changeUserRights($id, $userRights) {
		/* administrator = 0; User = 1 */

		$userRights = strtolower($userRights);
		if ($userRights == "administrator")
			$userRights = 1;
		elseif ($userRights == 'user')
			$userRights = 0;
		else
			return false;

		$data = array("id" => $id, "role" => $userRights, "updatedBy" => $this->session->userdata["userId"]);

		$this->load->model("Admin_model");
		if (!$this->Admin_model->usersChangeRights($data))
			echo "Error updating status";
		else
			return true;
	}


	/* controller for services */

	public function getAllServices() {		
		$this->load->model("Admin_model");
		$result = $this->Admin_model->getAllServices();
		header('Content-type: application/json');
		echo json_encode($result);
	}

	public function services() {
		$this->load->helper("record");
		$data["userRights"] = checkUserRightsByUserId($this->session->userdata["userId"]);

		if ($data["userRights"] == 0) { // 0 = administrator
			$headerData["title"] = "Services";
			$headerData['username'] = $this->username;
			$this->load->view("templates/header", $headerData);
			$this->load->view("sessioned/services_view");
		} else {
			header("HTTP/1.1 401 Unauthorized.");
			echo "Unauthorized.<br />Go to <a href='" . site_url("admin") . "''>Home Page</a>";
		}
	}

	public function deleteService($id = 0) {
		$id = $this->input->get("id");
		if (empty($id) || $id == "")
			return false;

		$this->load->model("Admin_model");
		$this->Admin_model->serviceDelete($id);
	}

	public function addService_view() {
		$data["userRights"] = $this->session->userdata["role"];
		if ($data["userRights"] == 0) {
			$headerData["title"] = "Add new Services";
			$headerData["username"] = $this->username;
			$data["userRights"] = $this->session->userdata["role"];
			$this->load->view("templates/header", $headerData);
			$this->load->view("sessioned/addService_view", $data);
		} else {
			header("HTTP/1.1 401 Unauthorized.");
			echo "Unauthorized.<br />Go to <a href='" . site_url("admin") . "''>Home Page</a>";
		}
	}

	private function checkDataForAdd($data) {
		if (empty($data["serviceName"]) || $data["regPrice"] == "" || $data["memberPrice"] == "" || empty($data["createdBy"]))
			return false;

		return true;
	}

	public function addService() {
		$data = array("companyId" => $this->session->userdata["companyId"],
					"serviceName" => $this->input->get("serviceName"),
					"description" => $this->input->get("description"),
					"regPrice" => $this->input->get("regPrice"),
					"memberPrice" => $this->input->get("memberPrice"),
					"createdBy" => $this->session->userdata["userId"]
				);

		if (!$this->checkDataForAdd($data))
			return false;
		
		$this->load->model("Admin_model");
		if (!$this->Admin_model->addService($data))
			return FALSE;
		else
			return TRUE;
	}

	private function getServiceDetailById($id) {
		$this->load->model("Admin_model");
		return $this->Admin_model->getServiceDetailById($id);
	}

	public function editService_view() {
		$id = $this->input->get("id");

		if (empty($id) || $id == "")
			$id = 0;

		$headerData["title"] = "Edit Service Info";
		$headerData["username"] = $this->username;

		$data['serviceDetails'] = $this->getServiceDetailById($id);

		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/editService_view", $data);
	}

	public function editService() {
		$data = array("serviceId" => $this->input->get("serviceId"),
					"serviceName" => $this->input->get("serviceName"),
					"description" => $this->input->get("description"),
					"regPrice" => $this->input->get("regPrice"),
					"memberPrice" => $this->input->get("memberPrice"),
					"createdBy" => $this->session->userdata["userId"]
				);

		$this->load->model("Admin_model");
		$this->Admin_model->editService($data);
	}
	/* end for services */

	/* profile */
	public function profile() {
		$data["userId"] = $this->session->userdata["userId"];
		$this->load->model("Admin_model");
		$profileDetails = $this->Admin_model->profile($data["userId"]); //returns array otherwise false.
		if($profileDetails)
			$data["profileDetails"] = $profileDetails;
		else
			$data["profileDetails"] = array();

		$headerData["title"] = "Profile";
		$headerData['username'] = $this->username;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/profile_view", $data);
	}	

	public function updateProfile() {
		$data = array("userId" => $this->input->get("userId"),
					"username" => $this->input->get("username"),
					"fName" => $this->input->get("fName"),
					"midName" => $this->input->get("midName"),
					"lName" => $this->input->get("lName"),
					"gender" => $this->input->get("gender"),
					"address" => $this->input->get("address"),
					"updatedBy" => $this->session->userdata["userId"]
				);

		$this->load->model("Admin_model");
		$this->Admin_model->updateProfile($data);
	}

	public function changePassword() {
		$oldPassword = $this->input->post("oldPassword");
		$newPassword = $this->input->post("newPassword");
		$confirmPassword = $this->input->post("confirmPassword");

		if ($newPassword !== $confirmPassword)
			return false;

		$data = array("userId" => $this->session->userdata["userId"],
					"newPassword" => $newPassword,
					"oldPassword" => $oldPassword
				);
		$this->load->model("Admin_model");
		if ($this->Admin_model->changePassword($data))
			$this->session->sess_destroy();
	}

	public function changePasswordView() {
		$data["userId"] = $this->session->userdata["userId"];
		$headerData["title"] = "Change Password";
		$headerData['username'] = $this->username;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/changePassword_view", $data);
	}

	/* Subscription methods */
	
	public function addSubscription() {
		$data = array("companyId" => 1,
					"paymentId" => 1,
					"stripeToken" => "sk_test_BQokikJOvBiI2HlWgH4olfQ2",
					"createdBy" => 1
				);
		$this->load->model("Subscription_model");
		if (!$this->Subscription_model->add($data))
			return FALSE;
		else
			return TRUE;
	}

	/* End for subscription methods*/

	/* transaction functions */

	public function transactions() {
		$this->load->model("Transactions_model");
		$subscription = $this->Transactions_model->withActiveSubscription($this->companyId);
		
		$headerData["title"] = "Transactions";
		$headerData["username"] = $this->username;
		$headerData["userRights"] = $this->role;

		$this->load->model("Employee_model");
		$this->load->model("Customers_model");
		$this->load->model("Services_model");
		$data["masseurs"] = $this->Employee_model->getMasseurNamesByCompanyId($this->companyId);
		$data["services"] = $this->Services_model->getServicesByCompanyId($this->companyId);
		$data["customers"] = $this->Customers_model->getCustomersByCompanyId($this->companyId);
		$data["companyId"] = $this->companyId;

		$this->load->view("templates/header", $headerData);
		if (empty($data["masseurs"])) {
			$this->load->view("sessioned/masseuradd_view");
			return;
		}
		if (empty($data["services"])) {
			$this->load->view("sessioned/addService_view");
			return;
		}
		if (empty($data["customers"])) {
			$this->load->view("sessioned/addCustomer_view");
			return;
		}

		if ($subscription)
			$this->load->view("sessioned/transaction_view", $data);
		else
			$this->load->view("sessioned/alertSubscription_view");
	}

	public function addTransaction() {
		$remarks = $this->input->get("remarks");
		( ! isset($remarks)) ? $remarks = NULL : $remarks;

		$data = array("companyId" => $this->companyId,
			"serviceId" => $this->input->get("serviceId"),
			"serviceName" => $this->input->get("serviceName"),
			"customerId" => $this->input->get("customerId"),
			"customerName" => $this->input->get("customerName"),
			"employeeId" => $this->input->get("employeeId"),
			"price" => $this->input->get("price"),
			"discount" => 0, // we don't support this one yet
			"total" => $this->input->get("price"), // we don't support discount yet for now
			"createdBy" => $this->userId,
			"remarks" => $remarks, // we don't support this one yet
			);

		$this->load->model("Transactions_model");
		if ($this->Transactions_model->add($data))
			return TRUE;
		else
			return FALSE;
	}

	public function getPriceForCustomer() {
		$data = array("serviceId" => $this->input->get("serviceId"),
					"customerId" => $this->input->get("customerId"),
					"companyId" => $this->companyId,
				);
		
		$this->load->helper("record");
		header("Content-type: application/json");
		$price = getPriceForCustomer($data);
		if ($price === FALSE)
			$price = "0.00";

		echo json_encode($price);
	}

	public function successaddtransaction() {
		$headerData["title"] = "Transactions";
		$headerData["username"] = $this->username;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/successAddTransaction_view");
	}

	/* end for transactions */

	/* controller for customer */

	public function getAllCustomersByCompanyId() {
		$companyId = $this->input->get("companyId");
		$this->load->model("Customers_model");
		$customersList = $this->Customers_model->getAllCustomersByCompanyId($companyId);

		header("Content-type: application/json");
		echo json_encode($customersList);
	}

	public function customers() {
		$headerData["title"] = "Customers";
		$headerData['username'] = $this->username;
		$companyId = $this->session->userdata["companyId"];
		$data["companyId"] = $companyId;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/customers_view", $data);
	}

	public function addCustomer_view() {
		$headerData["title"] = "AddCustomers";
		$headerData['username'] = $this->username;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/addCustomer_view");
	}

	public function addCustomer() {
		$data = array("companyId" => $this->session->userdata["companyId"],
					"custType" => $this->input->get("custType"),
					"fName" => $this->input->get("fName"),
					"midName" => $this->input->get("midName"),
					"lName" => $this->input->get("lName"),
					"createdBy" => $this->session->userdata["userId"]
				);
		$this->load->model("Customers_model");
		if ($this->Customers_model->add($data))
			return TRUE;
		else
			return FALSE;
	}

	public function searchCustomers($searchText = "") {
		header("Content-type: application/json");
		//if ($searchText == "")
	}

	/* end for customer controller */

	/* controller for company profile */

	public function companyProfile() {
		$cookie = $this->input->cookie("yourspaFunc_CompanyProfile");
		if (password_verify($this->session->userdata["username"], $cookie)) {
			$headerData["title"] = "Company";
			$headerData['username'] = $this->username;
			
			$data["companyId"] = $this->session->userdata["companyId"];
			$data["uniqueCode"] = $this->session->userdata["uniqueCode"];

			$this->load->model("Api_model");
			$data["provinces"] = $this->Api_model->getProvince(); // get all province list

			$this->load->model("Company_model");
			$provinceId = $this->Company_model->getProvinceIdByCompanyId($data["companyId"])[0]["province"];

			$data["cities"] = $this->Api_model->getCity($provinceId);
			$data["companyInfo"] = $this->Company_model->getCompanyInfo($data["companyId"]);
			$this->load->view("templates/v2/header2", $headerData);
			$this->load->view("sessioned/v2/companyProfile_view", $data);
		} else {
			redirect("admin/adminLogin?v=companyProfile");
		}
	}

	// we have to avoid hacking here.
    private function assertEqualCompanyId($companyId) {
        if ($companyId !== $this->session->userdata["companyId"])
            return false;

        return true;
    }

    // we have to avoid hacking here also.
    private function assertEqualsCompanyUniqueCode($uniqueCode) {
    	if ($uniqueCode !== $this->session->userdata["uniqueCode"])
    		return FALSE;

    	return TRUE;
    }

    public function editCompanyProfile() {
        $cookie = $this->input->cookie("yourspaFunc_CompanyProfile");
        if (!$cookie)
        	redirect("admin/adminLogin?v=companyProfile");

		if (password_verify($this->session->userdata["username"], $cookie)) {
            $data = array();
            $data["companyId"] = $this->input->get("comId");
            $data["uniqueCode"] = $this->input->get("uniqCod");

            if (!$this->assertEqualCompanyId($data["companyId"]))
                return FALSE;

            if (!$this->assertEqualsCompanyUniqueCode($data["uniqueCode"]))
            	return FALSE;

            $data["company"] = $this->input->get("company");
            $data["province"] = $this->input->get("province");
            $data["city"] = $this->input->get("city");
            $data["address"] = $this->input->get("address");
            $data["phoneNo"] = $this->input->get("phoneNo");
            $data["tin"] = $this->input->get("tin");
            $data["companyWebsite"] = $this->input->get("companyWebsite");

            $this->load->model("Company_model");
            if ($this->Company_model->edit($data)) {
            	delete_cookie("yourspaFunc_CompanyProfile");
            	return TRUE;
            }
        } else { // load the login page
            $data["title"] = "Update Company Profile";
            $this->load->view("templates/header", $data);
            $this->load->view("sessioned/v2/adminLogin");
        }
    }

    public function adminLogin() {
    	if ($this->input->get("v") == "companyProfile") {
    		$headerData["title"] = "Your Spa - Login Company";
    		$headerData['username'] = $this->username;
	        $this->load->view("templates/v2/header2", $headerData);
	        $this->load->view("sessioned/v2/adminLogin_view");
    	}        
    }

    public function checkLogin() {
    	$v = trim($this->input->post("v"));
    	if ($v == "") {
    		redirect(site_url("admin"));
    		exit(0);
    	}

    	// current user should match with what the user input.
    	$username = $this->input->post("username");
    	if ($username != $this->session->userdata["username"]) {
    		redirect(site_url("admin"));
    		exit(0);
    	}
    	
    	$data = array("username" => $username, "password" => $this->input->post("password"));
    	if ($v == "companyProfile") {
    		 $this->load->model("Admin_model");
    		if ($this->Admin_model->login($data)) {    			
    			$this->load->helper("cookie");
    			
    			$hashed_password = password_hash($username, PASSWORD_BCRYPT);

    			// we add new session data to know what are we logging into
				$cookie = array('name'   => 'yourspaFunc_CompanyProfile',
					    'value'  => $hashed_password,
					    'expire' => '300', // 5 minutes
					    'secure' => FALSE);
				$this->input->set_cookie($cookie);
    		}
    	}
    }

} /* end of admin.php class */