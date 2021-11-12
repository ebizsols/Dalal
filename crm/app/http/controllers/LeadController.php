<?php

/**
* LeadController
*/
class LeadController extends Controller
{
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('lead');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
			$data['result'] = $this->model_lead->getLeads($data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['result'] = $this->model_lead->getLeads();
		}

		/*Load Language File*/
		$this->language->load('contact', 'contact');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_leads');
		$data['page_add'] = $this->user_agent->hasPermission('lead/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('lead/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('lead/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'lead/delete';
		$data['page_active'] = 'lead';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('contact/leads_list', $data));
	}

	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('lead');
		$data['result'] = NULL;

		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
		} else {
			$data['admin'] = true;
			$data['staff'] = $this->model_lead->getStaff();
		}

		/*Load Language File*/
		$this->language->load('contact', 'contact');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_add_lead');
		$data['action'] = URL.DIR_ROUTE.'lead/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'lead';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('contact/lead_form', $data));
	}

	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('leads');
		}
		
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/

		$this->load->model('lead');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
			$data['result'] = $this->model_lead->getLead($id, $data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['result'] = $this->model_lead->getLead($id);
			$data['staff'] = $this->model_lead->getStaff();
		}

		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Leads does not exist in database.');
			$this->url->redirect('leads');
		}

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['marketing'] = json_decode($data['result']['marketing'], true);

		/*Load Language File*/
		$this->language->load('contact', 'contact');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_lead');
		$data['page_convert'] = $this->user_agent->hasPermission('lead/convert') ? true : false;
		$data['page_contact_edit'] = $this->user_agent->hasPermission('contact/edit') ? true : false;
		$data['action'] = URL.DIR_ROUTE.'lead/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'lead';
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('contact/lead_form', $data));
	}

	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('leads');
		}

		$data = $this->url->post('contact');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();

		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('contacts');
		}

		$data['country'] = $data['address']['country'];
		$data['address'] = json_encode($data['address']);
		$data['expire'] = DateTime::createFromFormat($data['info']['date_format'], $data['expire'])->format('Y-m-d');
		$data['marketing']['email'] = isset($data['marketing']['email']) ? $data['marketing']['email']: false;
		$data['marketing']['phone'] = isset($data['marketing']['phone']) ? $data['marketing']['phone']: false;
		$data['marketing']['sms'] = isset($data['marketing']['sms']) ? $data['marketing']['sms']: false;
		$data['marketing']['social'] = isset($data['marketing']['social']) ? $data['marketing']['social']: false;
		$data['marketing'] = json_encode($data['marketing']);
		$data['datetime'] = date('Y-m-d H:i:s');
		
		$this->load->model('lead');
		if (!empty($data['id'])) {
			$this->model_lead->updateLead($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Leads updated successfully.');	
		} else {
			$data['id'] = $this->model_lead->createLead($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Leads created successfully.');
		}
		$this->url->redirect('lead/edit&id='.$data['id']);
	}

	public function convertLead()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('leads');
		}

		$this->load->model('lead');
		$data['result'] = $this->model_lead->getLead($id);
		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Leads does not exist in database.');
			$this->url->redirect('leads');
		}
		$data['result']['datetime'] = date('Y-m-d H:i:s');
		$result = $this->model_lead->convertLeadToContact($data['result']);

		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Contact created successfully.');
		$this->url->redirect('contact/view&id='.$result);
	}
	/**
	* lead index Delete method
	* This method will be called on lead Delete view
	**/
	public function indexDelete()
	{
		$this->load->model('lead');
		$result = $this->model_lead->deleteLead($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Lead deleted successfully.');
		$this->url->redirect('leads');
	}
	/**
	* Contact validate field method
	* This method will be called for validate input field
	**/
	public function validateField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateText($data['firstname'])) {
			$error_flag = true;
			$error['firstname'] = 'Firstname!';
		}
		if ($this->controller_commons->validateText($data['lastname'])) {
			$error_flag = true;
			$error['lastname'] = 'Lastname!';
		}
		if ($this->controller_commons->validateEmail($data['email'])) {
			$error_flag = true;
			$error['email'] = 'Email Address!';
		}
		if ($this->controller_commons->validateNumber($data['phone'])) {
			$error_flag = true;
			$error['phone'] = 'Phone Number!';
		}
		if ($this->controller_commons->validateText($data['company'])) {
			$error_flag = true;
			$error['company'] = 'Company!';
		}
		if (!empty($data['expire'])) {
			if ($this->controller_commons->validateDate($data['expire'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['date'] = 'Expiry Date!';
			}
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}