<?php

/**
* QuoteController
*/
class QuoteController extends Controller
{
	/**
	* Quotes index method
	* This method will be called on Quotes list view
	**/
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

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
		$this->load->model('quote');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_quote->getQuotes($data['period'], $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_quote->getQuotes($data['period']);
		}

		/*Load Language File*/
		$this->language->load('quotes', 'quotes');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_quotes');
		$data['page_view'] = $this->user_agent->hasPermission('quote/view') ? true : false;
		$data['page_pdf'] = $this->user_agent->hasPermission('quote/pdf') ? true : false;
		$data['page_add'] = $this->user_agent->hasPermission('quote/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('quote/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('quote/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'quote/delete';
		$data['page_active'] = 'quotes';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('quote/quote_list', $data));
	}
	/**
	* Quotes index View method
	* This method will be called on Quotes View view
	**/
	public function indexView()
	{
		/**
		* Check if id exist in url if not exist then redirect to Invoice list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('quotes');
		}
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using Invoice model 
		**/
		$this->load->model('quote');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_quote->getQuoteView($id, $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_quote->getQuoteView($id);
		}
		
		if (empty($data['result'])) {
			$this->url->redirect('quotes');
		}

		/*Load Language File*/
		$this->language->load('quotes', 'quotes');

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);
		
		/* Set page title */
		$data['page_title'] = $this->language->get('text_quote_view');
		$data['page_pdf'] = $this->user_agent->hasPermission('quote/pdf') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('quote/edit') ? true : false;
		$data['page_convert'] = $this->user_agent->hasPermission('quote/autoinvoice') ? true : false;
		$data['page_invoice_view'] = $this->user_agent->hasPermission('invoice/view') ? true : false;
		$data['page_active'] = 'quotes';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('quote/quote_view', $data));
	}
	/**
	* Quotes index ADD method
	* This method will be called on Quotes ADD view
	**/
	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('quote');
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
		$this->language->load('quotes', 'quotes');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_quote');
		$data['action'] = URL.DIR_ROUTE.'quote/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'quotes';

		$data['lang']= $this->language->all();
		/*Render Invoice list view*/
		$this->response->setOutput($this->load->view('quote/quote_form', $data));
	}
	/**
	* Quotes index Edit method
	* This method will be called on Quotes Edit view
	**/
	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('quotes');
		}
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('quote');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
			$data['result'] = $this->model_quote->getQuote($id, $data['common']['user']['user_id']);
			$data['customers'] = $this->model_commons->getCustomers($data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['result'] = $this->model_quote->getQuote($id);
			$data['customers'] = $this->model_commons->getCustomers();
		}

		if (empty($data['result'])) {
			$this->url->redirect('quotes');
		}
		
		$data['result']['items'] = json_decode($data['result']['items'], true);
		
		$data['payment_type'] = $this->model_commons->getPaymentMethod();
		$data['taxes'] = $this->model_commons->getTaxes();
		$data['currency'] = $this->model_commons->getCurrencies();

		/*Load Language File*/
		$this->language->load('quotes', 'quotes');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_quote');
		$data['action'] = URL.DIR_ROUTE.'quote/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'quotes';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('quote/quote_form', $data));
	}
	/**
	* Quotes index Action method
	* This method will be called on Quotes Action view
	**/
	public function indexAction()
	{
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$data = $this->url->post('invoice');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		
		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			exit();
			if (!empty($this->url->post('id'))) {
				$this->url->redirect('quote/edit&id='.$this->url->post('id'));
			} else {
				$this->url->redirect('quote/add');
			}
		}
		$data['item'] = json_encode($data['item']);
		$data['date'] = DateTime::createFromFormat($data['info']['date_format'], $data['date'])->format('Y-m-d');
		$data['expiry'] = DateTime::createFromFormat($data['info']['date_format'], $data['expiry'])->format('Y-m-d');
		$data['datetime'] = date('Y-m-d H:i:s');
		
		$this->load->model('quote');
		if (!empty($data['id'])) {
			$result = $this->model_quote->updateQuote($data);
			$this->createPdf($data['id']);

			$mail_status = $this->model_quote->getQuoteMailStatus($data['id']);
			if ($mail_status == "0" && $data['status'] == "1") {
				$this->quoteMail($data['id']);
				$this->model_quote->updateQuoteMailStatus($data['id']);
			}
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Quotes updated successfully.');			
		} else {
			$data['id'] = $this->model_quote->createQuote($data);
			if (!empty($data['id'])) {
				$this->createPdf($data['id']);
				if ($data['status'] == "1") {
					$this->quoteMail($data['id']);
					$this->model_quote->updateQuoteMailStatus($data['id']);	
				}
				
			}
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Quotes created successfully.');
		}
		$this->url->redirect('quote/view&id='.$data['id']);
	}
	/**
	* Quotes index Delete method
	* This method will be called on Quotes Delete view
	**/
	public function indexDelete()
	{
		$this->load->model('quote');
		$result = $this->model_quote->deleteQuote($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Quotes deleted successfully.');
		$this->url->redirect_back();
	}
	/**
	* Quotes index mail method
	* This method will be called to send mail
	**/
	public function quoteMail($id)
	{
		$this->load->controller('Mail');
		$result = $this->controller_mail->getTemplate('newquotes');
		
		if (empty($result['template']) || $result['template']['status'] == '0') {
			return false;
		}

		$this->load->model('quote');
		$data = $this->model_quote->getQuoteView($id);

		if (empty($data)) {
			return false;
		}
		
		$site_link = '<a href="'.URL_CLIENTS.DIR_ROUTE.'quote/view&id='.$id.'">Click Here</a>';
		
		$data['id'] = str_pad($data['id'], 4, '0', STR_PAD_LEFT);
		$message = $result['template']['message'];

		$message = str_replace('{company}', $data['company'], $message);
		$message = str_replace('{project_name}', $data['project_name'], $message);
		$message = str_replace('{valid_until}', $data['expiry'], $message);
		$message = str_replace('{amount}', $data['currency_abbr'].$data['amount'], $message);
		$message = str_replace('{quote_url}', $site_link, $message);
		$message = str_replace('{business_name}', $result['common']['name'], $message);

		$data['mail']['name'] = $data['company'];
		$data['mail']['email'] = $data['email'];
		$data['mail']['subject'] = $result['template']['subject'];
		$data['mail']['message'] = $message;
		if (file_exists(DIR.'public/uploads/pdf/quote-'.$id.'.pdf')) {
			$data['mail']['attachments'] = array('file' => DIR.'public/uploads/pdf/quote-'.$id.'.pdf', 'name' => 'Quotation');
		}
		$mail_result = $this->controller_mail->sendmail($data['mail']);
		
		if ($mail_result == 1) {
			return true;
		} else {
			return false;
		}
	}
	/**
	* Quotes index PDF method
	* This method will be called to create Quotes's PDF
	**/
	public function indexPdf()
	{
		/**
		* Check if id exist in url if not exist then redirect to Invoice list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('quotes');
		}

		$html_array = $this->createPDFHTML($id);
		$pdf = new Pdf();
		$pdf->createPDF($html_array);
	}

	public function createPdf($id)
	{
		$html_array = $this->createPDFHTML($id);
		$pdf = new Pdf();
		$pdf->saveQuotePDF($html_array);
	}

	public function createPDFHTML($id, $printQuote = NULL)
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using Invoice model 
		**/
		$this->load->model('quote');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_quote->getQuoteView($id, $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_quote->getQuoteView($id);
		}
		if (empty($data['result'])) { $this->url->redirect('quotes'); }

		/*Load Language File*/
		$this->language->load('quotes', 'quotes');

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);

		$data['lang']= $this->language->all();
		if ($data['common']['info']['invoice']['template'] < '6') {
			$html = $this->load->view('quote/quote_pdf_'.$data['common']['info']['invoice']['template'], $data);
		} else {
			$html = $this->load->view('quote/quote_pdf_1', $data);
		}

		return array('html' => $html, 'result' => $data['result']);
	}

	public function validateField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateNumber($data['customer'])) {
			$error_flag = true;
			$error['customer'] = 'Customer!';
		}

		if (!empty($data['date'])) {
			if ($this->controller_commons->validateDate($data['date'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['date'] = 'Quote Date!';
			}
		}
		if (!empty($data['expiry'])) {
			if ($this->controller_commons->validateDate($data['expiry'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['expiry'] = 'Expiry Date!';
			}
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}
