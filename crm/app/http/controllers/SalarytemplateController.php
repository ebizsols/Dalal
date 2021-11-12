<?php

/**
 * SalarytemplateController
 */
class SalarytemplateController extends Controller
{
	public function index()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		
		$this->load->model('salarytemplate');
		$data['result'] = $this->model_salarytemplate->getSalarytemplates();
		/*Load Language File*/
		$this->language->load('managesalary', 'managesalary');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_salary_template');
		$data['page_add'] = $this->user_agent->hasPermission('salarytemplate/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('salarytemplate/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('salarytemplate/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'salarytemplate/delete';
		$data['page_active'] = 'salarytemplate';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('salarytemplate/salarytemplate_list', $data));
	}

	public function indexAdd()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$data['result'] = NULL;
		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		$data['currencies'] = $this->model_commons->getCurrencies();
		/*Load Language File*/
		$this->language->load('managesalary', 'managesalary');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_salary_template');
		$data['action'] = URL.DIR_ROUTE.'salarytemplate/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'salarytemplate';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('salarytemplate/salarytemplate_form', $data));
	}

	public function indexEdit()
	{
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('invoice');
		}

		$this->load->model('salarytemplate');
		$data['result'] = $this->model_salarytemplate->getSalarytemplate($id);
		if (!$data['result']) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Invoice does not exist in database!');
			$this->url->redirect('salarytemplate');
		}
		$data['result']['allowance'] = json_decode($data['result']['allowance'], true);
		$data['result']['deduction'] = json_decode($data['result']['deduction'], true);
		
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		$data['currencies'] = $this->model_commons->getCurrencies();
		/*Load Language File*/
		$this->language->load('managesalary', 'managesalary');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_salary_template');
		$data['action'] = URL.DIR_ROUTE.'salarytemplate/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'salarytemplate';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('salarytemplate/salarytemplate_form', $data));
	}

	public function indexAction()
	{
		$data = $this->url->post;
		$this->load->controller('commons');
		if ($validate_field = $this->validateSalarytemplate($data['salary'])) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['salary']['id'])) {
				$this->url->redirect('salarytemplate/edit&id='.$data['salary']['id']);
			} else {
				$this->url->redirect('salarytemplate/add');
			}
		}
		$data['salary']['allowance'] = json_encode($data['salary']['allowance']);
		$data['salary']['deduction'] = json_encode($data['salary']['deduction']);
		
		$data['salary']['datetime'] = date('Y-m-d H:i:s');
		$this->load->model('salarytemplate');
		if (!empty($data['salary']['id'])) {
			$result = $this->model_salarytemplate->updateSalarytemplate($data['salary']);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Salary Template updated successfully.');
			$this->url->redirect('salarytemplate/edit&id='.$data['salary']['id']);
		} else {
			$data['salary']['id'] = $this->model_salarytemplate->createSalarytemplate($data['salary']);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Salary Template created successfully.');
			$this->url->redirect('salarytemplate/edit&id='.$data['salary']['id']);
		}
	}

	public function indexDelete()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['delete']) || empty($this->url->post('id')) ) {
			$this->url->redirect('salarytemplate');
		}
		/**
		* Call delete method
		**/
		$this->load->model('salarytemplate');
		$result = $this->model_salarytemplate->deleteSalarytemplate($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Salary template deleted successfully.');
		$this->url->redirect('salarytemplate');
	}

	public function validateSalarytemplate($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateText($data['grade'])) {
			$error_flag = true;
			$error['author'] = 'Grade!';
		}
		if ($this->controller_commons->validateNumeric($data['basic_salary'])) {
			$error_flag = true;
			$error['author'] = 'Basic Salary!';
		}
		if ($this->controller_commons->validateNumeric($data['gross_salary'])) {
			$error_flag = true;
			$error['author'] = 'Gross Salary!';
		}
		if ($this->controller_commons->validateNumeric($data['net_salary'])) {
			$error_flag = true;
			$error['author'] = 'Net Salary!';
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}

	}
}