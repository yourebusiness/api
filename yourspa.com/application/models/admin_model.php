<?php

class Admin_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->library("employee");
		$this->load->library("users");
		$this->load->library("services");
		$this->load->library("profile");
		$this->load->library("customer");
        $this->load->library("company");
	}

	/* model for masseurs */

	public function showMasseurs($arr) {
		return $this->employee->showAll($arr);
	}

	public function masseurAdd($data) {
		return $this->employee->add($data); // return true or false
	}

	public function masseurChangeStatus($data) {
		return $this->employee->changeStatus($data);
	}

	public function masseurDelete($id) {
		return $this->employee->delete($id);
	}

	public function masseurEdit($data) {
		return $this->employee->edit($data);
	}


	/* model for users here */
	public function getAllUsersExceptCurrent($currentUserId) {
		return $this->users->getAllUsersExceptCurrent($currentUserId);
	}

	public function usersAdd($data) {
		return $this->users->add($data);
	}

	public function usersEdit($data) {
		return $this->users->edit($data);
	}

	public function usersDelete($id) {
		return $this->users->delete($id);
	}

	public function usersChangeStatus($data) {
    	return $this->users->changeStatus($data);
    }

    public function usersChangeRights($data) {
    	return $this->users->changeRights($data);
    }

    public function login(array $data) {
        return $this->users->login($data);
    }
    /* end for users */


    /* model for services */
    public function getAllServices() {
    	return $this->services->getAllServices();
    }

    public function serviceDelete($id) {
    	return $this->services->delete($id);
    }

    public function getServiceDetailById($id) {
    	return $this->services->getServiceDetailById($id);
    }

    public function addService($data) {
    	return $this->services->add($data);
    }

    public function editService($data) {
    	return $this->services->edit($data);
    }

    /* end for model services */

    /* model for user's profile */
    public function profile($id) {
    	return $this->profile->getProfileByUserId($id);
    }

    public function updateProfile($data) {
    	return $this->profile->updateProfile($data);
    }

    public function changePassword($data) {
    	return $this->profile->changePassword($data);
    }

    /* end for model profile */

    /* for customer model */

    public function searchCustomersDetails(array $data) {
    	return $this->customer->searchCustomersDetails($data);
    }

    public function getCustomerDetails($data) {
    	return $this->customer->getCustomerDetails($data);
    }

    public function updateCustomer($data) {
    	return $this->customer->update($data);
    }

    public function addCustomer($data) {
    	return $this->customer->add($data);
    }

    public function deleteCustomer($data) {
    	return $this->customer->delete($data);
    }
    /* end for customer model */

    /* for company profile */
    public function getCompanyInfo($companyId) {
        return $this->company->getCompanyInfo($companyId);
    }

    public function getProvinceIdByCompanyId($companyId) {
        return $this->company->getProvinceIdByCompanyId($companyId);
    }
    /* end for company profile */
}