<?php

/**
* DomainController
*/
class DomainController extends Controller
{
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('domain');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_domain->getDomains($data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_domain->getDomains();
		}

		if (!empty($data['result'])) {
			foreach ($data['result'] as $key => $value) {
				$temp = new DateTime($value['expiry_date']);
				$current = new DateTime();
				$temp->modify('-1 month');

				if(strtotime($temp->format('Y-m-d')) < strtotime($current->format('Y-m-d'))) {
					$data['result'][$key]['expire'] = 1;
				} else {
					$data['result'][$key]['expire'] = 0;
				}
			}
		}

		/*Load Language File*/
		$this->language->load('domain', 'domain');
		/* Set page title */
		$data['page_title'] = $this->language->get('text_domains');
		$data['page_add'] = $this->user_agent->hasPermission('domain/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('domain/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('domain/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'domain/delete';
		$data['page_active'] = 'domain';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('domain/domain_list', $data));
	}

	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$data['result'] = NULL;

		$this->load->model('domain');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
			$data['customers'] = $this->model_domain->getCustomers($data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['customers'] = $this->model_domain->getCustomers();
		}

		$data['currency'] = $this->model_commons->getCurrencies();
		/*Load Language File*/
		$this->language->load('domain', 'domain');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_add_domain');
		$data['action'] = URL.DIR_ROUTE.'domain/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'domain';
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('domain/domain_form', $data));
	}

	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('domains');
		}
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('domain');
		if ($data['common']['user']['role_id'] != '1') {
			$data['customers'] = $this->model_domain->getCustomers($data['common']['user']['user_id']);
			$data['result'] = $this->model_domain->getDomain($id, $data['common']['user']['user_id']);
		} else {
			$data['customers'] = $this->model_domain->getCustomers();
			$data['result'] = $this->model_domain->getDomain($id);
		}

		if (empty($data['result'])) {
			$this->url->redirect('domains');
		}
		$data['currency'] = $this->model_commons->getCurrencies();
		/*Load Language File*/
		$this->language->load('domain', 'domain');
		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_domain');
		$data['action'] = URL.DIR_ROUTE.'domain/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'domain';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('domain/domain_form', $data));
	}

	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('domains');
		}
		$data = $this->url->post('domain');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		
		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['id'])) {
				$this->url->redirect('domain/edit&id='.$data['id']);
			} else {
				$this->url->redirect('domain/add');
			}
		}

		$data['renew'] = isset($data['renew']) ? $data['renew'] : '0';
		$data['registration_date'] = DateTime::createFromFormat($data['info']['date_format'], $data['r_date'])->format('Y-m-d');
		$data['expiry_date'] = DateTime::createFromFormat($data['info']['date_format'], $data['e_date'])->format('Y-m-d');
		$data['datetime'] = date('Y-m-d H:i:s');
		
		$this->load->model('domain');
		if (!empty($data['id'])) {
			$result = $this->model_domain->updateDomain($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Domain updated successfully.');
		} else {
			$data['id'] = $this->model_domain->createDomain($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Domain created successfully.');
		}
		$this->url->redirect('domain/edit&id='.$data['id']);
	}

	public function indexDelete()
	{
		$this->load->model('domain');
		$result = $this->model_domain->deleteDomain($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Domain deleted successfully.');
		$this->url->redirect('domains');
	}
	/**
	* Contact validate field method
	* This method will be called for validate input field
	**/
	public function validateField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateText($data['name'])) {
			$error_flag = true;
			$error['name'] = 'Name!';
		}
		if ($this->controller_commons->validateDate($data['r_date'], $data['info']['date_format'] )) {
			$error_flag = true;
			$error['registration'] = 'Registration Date!';
		}
		if ($this->controller_commons->validateDate($data['e_date'], $data['info']['date_format'] )) {
			$error_flag = true;
			$error['expiry'] = 'Expiry Date!';
		}
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}