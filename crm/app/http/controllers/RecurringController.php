<?php
/**
* RecurringController
*/
class RecurringController extends Controller
{
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('invoices', 'invoices');
		
		$data['period']['start'] = $this->url->get('start');
		$data['period']['end'] = $this->url->get('end');
		$this->load->controller('commons');
		if (!empty($data['period']['start']) && !empty($data['period']['end']) && !$this->controller_commons->validateDate($data['period']['start']) && !$this->controller_commons->validateDate($data['period']['end'])) {
			$data['period']['start'] = date_format(date_create($data['period']['start'].'00:00:00'), "Y-m-d H:i:s");
			$data['period']['end'] = date_format(date_create($data['period']['end'].'23:59:59'), "Y-m-d H:i:s");
		} else {
			$data['period']['start'] = date('Y-m-d '.'00:00:00');
			$data['period']['end'] = date('Y-m-d '.'23:59:59');
		}
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('invoice');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_invoice->getRecurringInvoices($data['period'], $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_invoice->getRecurringInvoices($data['period']);
		}

		/* Set page title */
		$data['page_title'] = $this->language->get('text_recurring_invoices');
		$data['page_view'] = $this->user_agent->hasPermission('recurring/view') ? true : false;
		$data['page_pdf'] = $this->user_agent->hasPermission('recurring/pdf') ? true : false;
		$data['page_add'] = $this->user_agent->hasPermission('recurring/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('recurring/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('recurring/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'recurring/delete';
		$data['page_active'] = 'rinvoice';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('invoice/recurring_invoice_list', $data));
	}
	/**
	* Invoice index view method
	* This method will be called on Invoice view
	**/
	public function indexView()
	{
		/**
		* Check if id exist in url if not exist then redirect to Invoice list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('recurring');
		}
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using Invoice model 
		**/

		$this->load->model('invoice');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_invoice->getRecurringInoviceView($id, $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_invoice->getRecurringInoviceView($id);
		}

		if (empty($data['result'])) {
			$this->url->redirect('recurring');
		}

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);
		$data['recurringInvoices'] = $this->model_invoice->getInvoicesCreatedfromRecurring($id);

		/*Load Language File*/
		$this->language->load('invoices', 'invoices');

		/**
		* Get all User data from DB using Invoice model 
		**/
		/* Set page title */
		$data['page_title'] = $this->language->get('text_recurring_invoice_view');
		$data['page_pdf'] = $this->user_agent->hasPermission('recurring/pdf') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('recurring/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('recurring/delete') ? true : false;

		$data['page_invoice_view'] = $this->user_agent->hasPermission('invoice/view') ? true : false;
		$data['page_invoice_pdf'] = $this->user_agent->hasPermission('invoice/pdf') ? true : false;
		$data['page_invoice_edit'] = $this->user_agent->hasPermission('invoice/edit') ? true : false;
		$data['page_invoice_delete'] = $this->user_agent->hasPermission('invoice/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'invoice/delete';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'rinvoice';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('invoice/recurring_invoice_view', $data));
	}
	/**
	* Invoice index ADD method
	* This method will be called on add Invoice
	**/
	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using Invoice model 
		**/
		$this->load->model('invoice');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
			$data['customers'] = $this->model_commons->getCustomers($data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['customers'] = $this->model_commons->getCustomers();
		}

		$data['result'] = NULL;
		$data['payment_type'] = $this->model_commons->getPaymentMethod();
		$data['taxes'] = $this->model_commons->getTaxes();
		$data['currency'] = $this->model_commons->getCurrencies();

		/*Load Language File*/
		$this->language->load('invoices', 'invoices');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_recurring_invoice');
		$data['action'] = URL.DIR_ROUTE.'recurring/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'rinvoice';

		$data['lang']= $this->language->all();
		/*Render Invoice list view*/
		$this->response->setOutput($this->load->view('invoice/recurring_invoice_form', $data));
	}
	/**
	* Invoice index edit method
	* This method will be called on edit Invoice
	**/
	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('recurring');
		}
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('invoice');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
			$data['result'] = $this->model_invoice->getRecurringInvoice($id, $data['common']['user']['user_id']);
			$data['customers'] = $this->model_commons->getCustomers($data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['result'] = $this->model_invoice->getRecurringInvoice($id);
			$data['customers'] = $this->model_commons->getCustomers();
		}

		if (empty($data['result'])) {
			$this->url->redirect('recurring');
		}
		$data['result']['items'] = json_decode($data['result']['items'], true);
		$data['payment_type'] = $this->model_commons->getPaymentMethod();
		$data['taxes'] = $this->model_commons->getTaxes();
		$data['currency'] = $this->model_commons->getCurrencies();

		/*Load Language File*/
		$this->language->load('invoices', 'invoices');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_recurring_invoice');
		$data['action'] = URL.DIR_ROUTE.'recurring/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'rinvoice';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('invoice/recurring_invoice_form', $data));
	}
	/**
	* Invoice index action or submit method
	* This method will be called on Invoice save
	**/
	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('recurring');
		}

		$data = $this->url->post('invoice');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		
		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['id'])) {
				$this->url->redirect('recurring/edit&id='.$data['id']);
			} else {
				$this->url->redirect('recurring/add');
			}
		}

		$data['inv_date'] = DateTime::createFromFormat($data['info']['date_format'], $data['inv_date'])->format('Y-m-d');
		$data['item'] = json_encode($data['item']);
		$data['want_payment'] = isset($data['want_payment']) ? $data['want_payment']: 0;
		$data['want_signature'] = isset($data['want_signature']) ? $data['want_signature']: 0;
		$data['datetime'] = date('Y-m-d H:i:s');
		
		$this->load->model('invoice');
		if (!empty($data['id'])) {
			$result = $this->model_invoice->updateRecurringInvoice($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Recurring Inovice updated successfully.');
			$this->url->redirect('recurring/view&id='.$data['id']);
		} else {
			$result = $this->model_invoice->createRecurringInvoice($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Recurring Inovice created successfully.');
			$this->url->redirect('recurring/view&id='.$result);
		}
	}
	/**
	* Invoice index delete method
	* This method will be called on Invoice delete
	**/
	public function indexDelete()
	{
		$this->load->model('invoice');
		$result = $this->model_invoice->deleteRecurringInvoice($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Recurring Invoice deleted successfully.');
		$this->url->redirect('recurring');
	}
	/**
	* Invoice index PDF method
	* This method will be called to create PDF
	**/
	public function indexPdf()
	{
		/**
		* Check if id exist in url if not exist then redirect to Invoice list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('invoices');
		}
		
		$this->createPDF($id);
	}

	public function createPDF($id, $printInvoice = NULL)
	{
		/**
		* Get all User data from DB using Invoice model 
		**/
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$this->load->model('invoice');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_invoice->getRecurringInoviceView($id, $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_invoice->getRecurringInoviceView($id);
		}

		if (empty($data['result'])) {
			$this->url->redirect('recurring');
		}
		$html_array['result'] = $data['result'];
		/*Load Language File*/
		$this->language->load('invoices', 'invoices');

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);

		$data['lang']= $this->language->all();
		$html_array['html'] = $this->load->view('invoice/recurring_invoice_pdf_'.$data['common']['info']['invoice']['template'], $data);
		// $html_array['html'] = $this->load->view('invoice/recurring_invoice_pdf_5', $data);
		// print_r($html_array['html']);
		// exit();
		$pdf = new Pdf();
		$pdf->createPDF($html_array);
	}
	
	/**
	* Validate Field Method
	* This method will be called on to validate invoice field
	**/
	private function vaildateMailField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateText($data['to'])) {
			$error_flag = true;
			$error['to'] = 'Email!';
		}

		if ($this->controller_commons->validateText($data['subject'])) {
			$error_flag = true;
			$error['subject'] = 'Subject!';
		}

		if ($this->controller_commons->validateText($data['message'])) {
			$error_flag = true;
			$error['message'] = 'Message!';
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	public function validateField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateNumber($data['customer'])) {
			$error_flag = true;
			$error['customer'] = 'Customer!';
		}
		
		if (!empty($data['inv_date'])) {
			if ($this->controller_commons->validateDate($data['inv_date'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['inv_date'] = 'Invoice Date!';
			}
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}