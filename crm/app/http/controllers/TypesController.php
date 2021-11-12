<?php

/**
* TypeController
*/
class TypesController extends Controller
{
	public function departments()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('settings', 'settings');

		/**
		* Get all Tax data from DB using Tax model 
		**/
		$this->load->model('types');
		$data['result'] = $this->model_types->getDepartments();
		
		/* Set page title */
		$data['page_title'] = $this->language->get('text_departments');
		$data['page_add'] = $this->user_agent->hasPermission('department/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('department/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('department/delete') ? true : false;
		$data['action'] = URL.DIR_ROUTE.'department/add';
		$data['action_delete'] = URL.DIR_ROUTE.'department/delete';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'setting';
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('setting/department', $data));
	}

	public function departmentAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('departments');
			exit();
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$this->load->controller('commons');
		if ($validate_field = $this->validateField()) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('departments');
		}
		
		$this->load->model('types');
		if (!empty($this->url->post('id'))) {
			$result = $this->model_types->updateDepartment($this->url->post);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Department updated successfully.');
			$this->url->redirect('departments');
		}
		else {
			$result = $this->model_types->createDepartment($this->url->post);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Department created successfully.');
			$this->url->redirect('departments');
		}
	}

	public function departmentDelete()
	{
		$this->load->model('types');
		$result = $this->model_types->deleteDepartment($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Department deleted successfully.');
		$this->url->redirect('departments');
	}

	
	public function expenseType()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$data['lang']['settings'] = $this->language->load('settings', 'settings');
		/**
		* Get all Tax data from DB using Tax model 
		**/
		$this->load->model('types');
		$data['result'] = $this->model_types->getExpenseTypes();

		/* Set page title */
		$data['page_title'] = $this->language->get('text_expense_types');
		$data['page_add'] = $this->user_agent->hasPermission('expensetype/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('expensetype/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('expensetype/delete') ? true : false;

		$data['action'] = URL.DIR_ROUTE.'expensetype/add';
		$data['action_delete'] = URL.DIR_ROUTE.'expensetype/delete';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'setting';
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('setting/expense_type', $data));
	}

	public function expenseTypeAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('expensetype');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$this->load->controller('commons');
		if ($validate_field = $this->validateField()) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('expensetype');
		}
		
		$this->load->model('types');
		if (!empty($this->url->post('id'))) {
			$result = $this->model_types->updateExpenseType($this->url->post);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Expense Type updated successfully.');
			$this->url->redirect('expensetype');
		}
		else {
			$result = $this->model_types->createExpenseType($this->url->post);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Expense Type created successfully.');
			$this->url->redirect('expensetype');
		}
	}

	public function expenseTypeDelete()
	{
		$this->load->model('types');
		$result = $this->model_types->deleteExpenseType($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Expense Type deleted successfully.');
		$this->url->redirect('expensetype');
	}

	public function validateField()
	{
		$error = [];
		$error_flag = false;
		if ($this->controller_commons->validateText($this->url->post('name'))) {
			$error_flag = true;
			$error['title'] = 'Name!';
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}