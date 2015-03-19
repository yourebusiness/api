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

		$this->load->view("templates/header", $data);
		$this->load->view("sessioned/home");
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
		$data["users"] = $this->getAllUsers();
		$this->load->helper("record");
		$data["userRights"] = checkUserRightsByUserId($this->session->userdata["userId"]);
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
			echo "Not an administrator.";
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
		$headerData["title"] = "Add new Services";
		$headerData["username"] = $this->username;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/addService_view");
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
		$headerData["title"] = "Company";
		$headerData['username'] = $this->username;
		$this->load->view("templates/header", $headerData);
		$this->load->view("sessioned/company_view");
	}

	// we have to avoid hacking here.
    private function assertEqualCompanyId($companyId) {
        if ($companyId !== $this->session->userdata["companyId"])
            return false;

        return true;
    }

    public function editCompanyProfile() {
        $this->load->helper("cookie");
        if ($this->input->cookie("yourspa_companyProfile")) {
            $data = array();
            $data["companyId"] = $this->input->get("companyId");

            if (!$this->assertEqualCompanyId($data["companyId"]))
                return false;

            $data["company"] = $this->input->get("company");
            $data["province"] = $this->input->get("province");
            $data["city"] = $this->input->get("city");
            $data["address"] = $this->input->get("address");
            $data["phoneNo"] = $this->input->get("phoneNo");
            $data["companyWebsite"] = $this->input->get("companyWebsite");
            $data["tin"] = $this->input->get("tin");        

            $this->load->model("Register_model");
            if ($this->Register_model->edit($data)) {
                $data["title"] = "Update Company Profile";
                $this->load->view("templates/header", $data);
                $this->load->view("editCompanyProfileSuccess_view");
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
    	$v = $this->input->post("v");
    	if ($v == "" || $v == 0)
    		redirect(base_url());

    	// current user should match with what the user input.
    	$username = $this->input->post("username");
    	if ($username != $this->session->userdata["username"])
    		redirect(base_url());

    	$data = array("username" => $username, "password" => $this->input->post("password"));
    	if ($v == "companyProfile") {
    		$this->load->model("Admin_model");
    		if (!$this->Admin_model->login($data))
    			redirect(base_url("admin/login"));
    		else {
    			$headerData["title"] = "Company";
	    		$headerData['username'] = $this->username;
		        $this->load->view("templates/header", $headerData);
		        $this->load->view("sessioned/company_view");
    		}
    	}

    }

} /* end of admin.php class */