<?php

/**
 * FinanceController
 */
class FinanceController extends Controller
{
	public function paymentMethod()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('settings', 'settings');
		/**
		* Get all Tax data from DB using Tax model 
		**/
		$this->load->model('finance');
		$data['result'] = $this->model_finance->getPaymentMethods();

		/* Set page title */
		$data['page_title'] = $this->language->get('text_payment_method');
		$data['page_add'] = $this->user_agent->hasPermission('paymentmethod/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('paymentmethod/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('paymentmethod/delete') ? true : false;
		$data['action'] = URL.DIR_ROUTE.'paymentmethod/add';
		$data['action_delete'] = URL.DIR_ROUTE.'paymentmethod/delete';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'setting';
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('finance/payment_method', $data));
	}

	public function paymentMethodAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('paymentmethod');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$this->load->controller('commons');
		if ($validate_field = $this->validateField()) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('paymentmethod');
		}

		$this->load->model('finance');
		if (!empty($this->url->post('id'))) {
			$result = $this->model_finance->updatePaymentMethod($this->url->post);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Payment Method updated successfully.');
			$this->url->redirect('paymentmethod');
		} else {
			$result = $this->model_finance->createPaymentMethod($this->url->post);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Payment Method created successfully.');
			$this->url->redirect('paymentmethod');
		}
	}

	public function paymentMethodDelete()
	{
		$this->load->model('finance');
		$result = $this->model_finance->deletePaymentMethod($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Payment Method deleted successfully.');
		$this->url->redirect('paymentmethod');
	}

	public function currency()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('settings', 'settings');

		/**
		* Get all Tax data from DB using Tax model 
		**/
		$this->load->model('finance');
		$data['result'] = $this->model_finance->getCurrency();

		/* Set page title */
		$data['page_title'] = $this->language->get('text_currencies');
		$data['page_add'] = $this->user_agent->hasPermission('currency/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('currency/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('currency/delete') ? true : false;

		$data['action'] = URL.DIR_ROUTE.'currency/add';
		$data['action_delete'] = URL.DIR_ROUTE.'currency/delete';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'setting';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('finance/currency', $data));
	}

	public function currencyAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('currency');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/

		$this->load->controller('commons');
		if ($validate_field = $this->validateField()) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('currency');
		}
		
		$this->load->model('finance');
		if (!empty($this->url->post('id'))) {
			$result = $this->model_finance->updateCurrency($this->url->post);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Currency updated successfully.');
			$this->url->redirect('currency');
		} else {
			$result = $this->model_finance->createCurrency($this->url->post);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Currency created successfully.');
			$this->url->redirect('currency');
		}
	}

	public function currencyDelete()
	{
		$this->load->model('finance');
		$result = $this->model_finance->deleteCurrency($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Currency deleted successfully.');
		$this->url->redirect('currency');
	}

	public function taxes()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('settings', 'settings');

		/**
		* Get all Tax data from DB using Tax model 
		**/
		$this->load->model('finance');
		$data['result'] = $this->model_finance->getTaxes();

		/* Set page title */
		$data['page_title'] = $this->language->get('text_taxes');
		$data['page_add'] = $this->user_agent->hasPermission('tax/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('tax/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('tax/delete') ? true : false;
		$data['action'] = URL.DIR_ROUTE.'tax/add';
		$data['action_delete'] = URL.DIR_ROUTE.'tax/delete';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'setting';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('finance/tax_list', $data));
	}

	public function taxAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('taxes');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$this->load->controller('commons');
		if ($validate_field = $this->validateTaxField()) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('taxes');
		}

		$this->load->model('finance');
		if (!empty($this->url->post('id'))) {
			$result = $this->model_finance->updateTax($this->url->post);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Tax Rate updated successfully.');
			$this->url->redirect('taxes');
		} else {
			$result = $this->model_finance->createTax($this->url->post);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Tax Rate created successfully.');
			$this->url->redirect('taxes');
		}
	}

	public function taxDelete()
	{
		$this->load->model('finance');
		$result = $this->model_finance->deleteTax($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Tax Rate deleted successfully.');
		$this->url->redirect('taxes');
	}

	public function validateTaxField()
	{
		$error = [];
		$error_flag = false;
		if ($this->controller_commons->validateText($this->url->post('name'))) {
			$error_flag = true;
			$error['title'] = 'Tax Name!';
		}

		if ($this->controller_commons->validateNumeric($this->url->post('rate'))) {
			$error_flag = true;
			$error['author'] = 'Tax Rate!';
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	public function paypalGateway()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('settings', 'settings');

		/**
		* Get all Tax data from DB using Tax model 
		**/
		
		$this->load->model('finance');
		$data['result'] = $this->model_finance->getPaypalGateway();
		$data['result'] = json_decode($data['result']['data'], true);

		/* Set page title */
		$data['page_title'] = $this->language->get('text_paypal_gateway');
		$data['action'] = URL.DIR_ROUTE.'paypalgateway';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'setting';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('finance/paypal_gateway', $data));
	}

	public function paypalGatewayAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('paypalgateway');
		}
		/**
		* Validate form data
		**/
		$data = $this->url->post;

		$this->load->controller('commons');
		if ($validate_field = $this->validatePaymentGatewayField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('paypalgateway');
		}
		$data['status'] = $data['paypal']['status'];
		$data['paypal'] = json_encode($data['paypal']);
		$data['datetime'] =  date('Y-m-d H:i:s');
		
		$this->load->model('finance');		
		$result = $this->model_finance->updatePaymentGateway($data);
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Payment Gateway updated successfully.');
		$this->url->redirect('paypalgateway');
	}


	public function validatePaymentGatewayField($data)
	{
		$error = [];
		$error_flag = false;
		if (!empty($data['username']) && $this->controller_commons->validateText($data['username'])) {
			$error_flag = true;
			$error['username'] = 'Username!';
		}
		if (!empty($data['password']) && $this->controller_commons->validateText($data['password'])) {
			$error_flag = true;
			$error['password'] = 'password!';
		}
		if (!empty($data['signature']) && $this->controller_commons->validateText($data['signature'])) {
			$error_flag = true;
			$error['signature'] = 'signature!';
		}
		if (!empty($data['email']) && $this->controller_commons->validateEmail($data['email'])) {
			$error_flag = true;
			$error['email'] = 'email!';
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
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