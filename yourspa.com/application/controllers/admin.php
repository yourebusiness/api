<?php

class Admin extends CI_Controller {
	private $username = "";

	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata["username"])
			redirect(site_url("api/signin"));
		else {
			$this->username = $this->session->userdata["username"];
			$this->companyId = $this->session->userdata["companyId"];
		}
	}

	private function showMasseurs($arr) {
		$this->load->model("Admin_model");
		return $this->Admin_model->showMasseurs($arr);
	}

	public function index() {
		$data["title"] = "Welcome";
		$data["username"] = $this->username;
		$data["userRights"] = $this->session->userdata["role"];

		$this->load->view("templates/header", $data);
		$this->load->view("sessioned/home");

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
		if ($midName == null || empty($midName))
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

	public function users() {
		$currentUserId = $this->session->userdata["userId"];
		$data["users"] = $this->getAllUsersExceptCurrent($currentUserId);
		$this->load->helper("record");
		$data["userRights"] = checkUserRightsByUserId($currentUserId);
		$headerData["title"] = "Users";
		$headerData['username'] = $this->username;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/users_view", $data);
	}

	public function usersAdd_view() {
		$headerData["title"] = "Add Users";
		$data["username"] = $this->username;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/usersadd_view", $data);
	}

	public function usersAdd() {
		$midName = $this->input->get("midName");
		if ($midName == null || empty($midName))
			$midName = null;

		$address = $this->input->get("address");
		if ($address == null || empty($address))
			$address = null;

		$data = array("username" => $this->input->get("username"),
					"password" => $this->input->get("password"),
					"fName" => $this->input->get("fName"),
					"midName" => $midName,
					"lName" => $this->input->get("lName"),
					"address" => $address,
					"gender" => $this->input->get("gender"),
					"role" => $this->input->get("role"),
					"createdBy" => $this->session->userdata["userId"],
					"companyId" => $this->session->userdata["companyId"]
				);

		$this->load->model("Admin_model");
		if (!$this->Admin_model->usersAdd($data))
			echo "Error adding new record.";
		else
			return true;
	}

	public function usersEdit() {
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
			return true;
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
			echo "Unauthorized.<br />Go to <a href='" . base_url("admin") . "''>Home Page</a>";
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
			echo "Unauthorized.<br />Go to <a href='" . base_url("admin") . "''>Home Page</a>";
		}
	}

	private function validateDataForAdd($data) {
		if ($data["serviceName"] == "" || $data["description"] == "" || $data["regPrice"] == "" || $data["memberPrice"] == "" || $data["createdBy"] == "")
			return false;

		return true;
	}

	public function addService() {
		$this->load->helper("record");
		$data = array("serviceName" => $this->input->get("serviceName"),
					"description" => $this->input->get("description"),
					"regPrice" => $this->input->get("regPrice"),
					"memberPrice" => $this->input->get("memberPrice"),
					"createdBy" => $this->session->userdata["userId"]
				);

		if (!$this->validateDataForAdd($data))
			return false;
		
		$this->load->model("Admin_model");
		$this->Admin_model->addService($data);
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

	/* transaction functions */

	public function transactions() {
		$data["userId"] = $this->session->userdata["userId"];
		$headerData["title"] = "Transactions";
		$headerData['username'] = $this->username;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/transaction_view", $data);
	}

	public function transact() {
		
	}

	/* end for transactions */

	/* controller for customer */

	public function getCustomersDetails() {
		$searchText = $this->input->get("searchText");
		$companyId = $this->session->userdata["companyId"];
		$data = array("companyId" => $companyId, "searchText" => $searchText);
		$this->load->model("Admin_model");
		header("Content-type: application/json");
		echo json_encode($this->Admin_model->searchCustomersDetails($data));
	}

	public function customers() {
		$headerData["title"] = "Customers";
		$headerData['username'] = $this->username;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/customers_view");
	}

	/* end for customer controller */

	/* controller for company profile */

	public function companyProfile() {
		$cookie = $this->input->cookie("yourspaFunc_CompanyProfile");
		if (password_verify($this->session->userdata["username"], $cookie)) {
			$headerData["title"] = "Company";
			$headerData['username'] = $this->username;
			$data["companyId"] = $this->session->userdata["companyId"];
			$this->load->model("Api_model");
			$data["provinces"] = $this->Api_model->getProvince(); // get all province list

			$this->load->model("Admin_model");
			$provinceId = $this->Admin_model->getProvinceIdByCompanyId($data["companyId"])[0]["province"];

			$data["cities"] = $this->Api_model->getCity($provinceId);

			$data["companyInfo"] = $this->Admin_model->getCompanyInfo($data["companyId"]);
			$this->load->view("templates/header", $headerData);
			$this->load->view("sessioned/company_view", $data);
		} else {
			redirect("admin/login?v=companyProfile");
		}
	}

	// we have to avoid hacking here.
    private function assertEqualCompanyId($companyId) {
        if ($companyId !== $this->session->userdata["companyId"])
            return false;

        return true;
    }

    public function editCompanyProfile() {
        $cookie = $this->input->cookie("yourspaFunc_CompanyProfile");
		if (password_verify($this->session->userdata["username"], $cookie)) {
            $data = array();
            $data["companyId"] = $this->input->get("companyId");

            if (!$this->assertEqualCompanyId($data["companyId"]))
                return false;

            $data["company"] = $this->input->get("company");
            $data["province"] = $this->input->get("province");
            $data["city"] = $this->input->get("city");
            $data["address"] = $this->input->get("address");
            $data["phoneNo"] = $this->input->get("phoneNo");
            $data["tin"] = $this->input->get("tin");
            $data["companyWebsite"] = $this->input->get("companyWebsite");

            $this->load->model("Register_model");
            if ($this->Register_model->edit($data)) {
                $headerData["title"] = "Update Company Profile";
    			$headerData['username'] = $this->username;
                $this->load->view("templates/header", $headerData);
                $this->load->view("sessioned/editCompanyProfileSuccess_view");
            }
        } else { // load the login page
            $data["title"] = "Update Company Profile";
            $this->load->view("templates/header", $data);
            $this->load->view("sessioned/login");
        }

    }

    public function login() {
    	if ($this->input->get("v") == "companyProfile") {
    		$headerData["title"] = "Login";
    		$headerData['username'] = $this->username;
	        $this->load->view("templates/header", $headerData);
	        $this->load->view("sessioned/login");
    	}        
    }

    public function checkLogin() {
    	$v = trim($this->input->post("v"));
    	if ($v == "") {
    		redirect(base_url("admin"));
    		exit(0);
    	}

    	// current user should match with what the user input.
    	$username = $this->input->post("username");
    	if ($username != $this->session->userdata["username"]) {
    		redirect(base_url("admin"));
    		exit(0);
    	}
    	
    	$data = array("username" => $username, "password" => $this->input->post("password"));
    	if ($v == "companyProfile") {
    		$this->load->model("Admin_model");
    		if ($this->Admin_model->login($data)) {
    			$this->load->helper("cookie");
    			// we add new session data to know what are we logging into
    			$cookieValue = password_hash($username, PASSWORD_BCRYPT);
				$cookie = array(
					    'name'   => 'yourspaFunc_CompanyProfile',
					    'value'  => $cookieValue,
					    'expire' => '600',
					    'secure' => FALSE
					);

					$this->input->set_cookie($cookie);
    		}
    	}
    }

} /* end of admin.php class */