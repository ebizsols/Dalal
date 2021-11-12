<?php
/**
* InvoiceController
*/
class InvoiceController extends Controller 
{
	/**
	* Invoice index method
	* This method will be called on Invoice list view
	**/
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
			$data['result'] = $this->model_invoice->getInvoices($data['period'], $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_invoice->getInvoices($data['period']);
		}

		/* Set page title */
		$data['page_title'] = $this->language->get('text_invoices');
		$data['page_view'] = $this->user_agent->hasPermission('invoice/view') ? true : false;
		$data['page_pdf'] = $this->user_agent->hasPermission('invoice/pdf') ? true : false;
		$data['page_copy'] = $this->user_agent->hasPermission('invoice/copy') ? true : false;
		$data['page_add'] = $this->user_agent->hasPermission('invoice/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('invoice/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('invoice/delete') ? true : false;
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action_delete'] = URL.DIR_ROUTE.'invoice/delete';
		$data['page_active'] = 'invoice';
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('invoice/invoice_list', $data));
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
			$this->url->redirect('invoices');
		}
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using Invoice model 
		**/
		$this->load->model('invoice');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_invoice->getInoviceView($id, $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_invoice->getInoviceView($id);
		}
		
		if (empty($data['result'])) {
			$this->url->redirect('invoices');
		}
		$data['payments'] = $this->model_invoice->getPayments($id);
		$data['attachments'] = $this->model_commons->getAttachments('invoice', $id);

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);
		$data['payment_method'] = $this->model_commons->getPaymentMethod();

		/*Load Language File*/
		$this->language->load('invoices', 'invoices');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_invoice_view');
		$data['page_pdf'] = $this->user_agent->hasPermission('invoice/pdf') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('invoice/edit') ? true : false;
		$data['page_copy'] = $this->user_agent->hasPermission('invoice/copy') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('invoice/delete') ? true : false;
		$data['page_documents'] = $this->user_agent->hasPermission('invoice/documents') ? true : false;
		$data['page_sendmail'] = $this->user_agent->hasPermission('invoice/sendmail') ? true : false;
		$data['page_add_payment'] = $this->user_agent->hasPermission('invoice/payment') ? true : false;
		$data['action'] = URL.DIR_ROUTE.'invoice/payment';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'invoice';

		$data['action_delete'] = URL.DIR_ROUTE.'invoice/payment/delete';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('invoice/invoice_view', $data));
	}
	/**
	* Invoice index Copy method
	* This method will be called on copy Invoice
	**/
	public function indexCopy()
	{
		if ($_POST['submit']) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Inovice is empty.');
			$this->url->redirect('invoices');
		}
		
		$id = (int)$this->url->post('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('invoices');
		}

		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using Invoice model 
		**/
		$this->load->model('invoice');
		if ($data['common']['user']['role_id'] != '1') {
			$invoices = $this->model_invoice->getInoviceView($id, $data['common']['user']['user_id']);
		} else {
			$invoices = $this->model_invoice->getInoviceView($id);
		}

		if (empty($invoices)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Inovice is empty. Please try again.');
			$this->url->redirect('invoices');
		}

		$invoices['duedate'] = date('Y-m-d');
		$invoices['paiddate'] = date('Y-m-d');
		$invoices['datetime'] = date('Y-m-d H:i:s');

		$result = $this->model_invoice->copyInvoice($invoices);
		$this->createPDF($result);
		
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Inovice copied successfully.');
		$this->url->redirect('invoice/view&id='.$result);
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
		$data['payment_method'] = $this->model_commons->getPaymentMethod();
		$data['taxes'] = $this->model_commons->getTaxes();
		$data['currency'] = $this->model_commons->getCurrencies();

		/*Load Language File*/
		$this->language->load('invoices', 'invoices');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_invoice');
		$data['action'] = URL.DIR_ROUTE.'invoice/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'invoice';
		
		$data['lang']= $this->language->all();
		/*Render Invoice list view*/
		$this->response->setOutput($this->load->view('invoice/invoice_form', $data));
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
			$this->url->redirect('invoices');
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
			$data['result'] = $this->model_invoice->getInovice($id, $data['common']['user']['user_id']);
			$data['customers'] = $this->model_commons->getCustomers($data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['result'] = $this->model_invoice->getInovice($id);
			$data['customers'] = $this->model_commons->getCustomers();
		}

		if (empty($data['result'])) {
			$this->url->redirect('invoices');
		}
		/*Load Language File*/
		$this->language->load('invoices', 'invoices');

		$data['result']['items'] = json_decode($data['result']['items'], true);

		$data['payment_method'] = $this->model_commons->getPaymentMethod();
		$data['taxes'] = $this->model_commons->getTaxes();
		$data['currency'] = $this->model_commons->getCurrencies();

		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_invoice');
		$data['action'] = URL.DIR_ROUTE.'invoice/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'invoice';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('invoice/invoice_form', $data));
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
			$this->url->redirect('invoices');
		}

		$data = $this->url->post('invoice');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		
		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['id'])) {
				$this->url->redirect('invoice/edit&id='.$data['id']);
			} else {
				$this->url->redirect('invoice/add');
			}
		}

		$data['duedate'] = DateTime::createFromFormat($data['info']['date_format'], $data['duedate'])->format('Y-m-d');
		$data['invoicedate'] = DateTime::createFromFormat($data['info']['date_format'], $data['invoicedate'])->format('Y-m-d');
		$data['item'] = json_encode($data['item']);
		$data['want_payment'] = isset($data['want_payment']) ? $data['want_payment']: 0;
		$data['want_signature'] = isset($data['want_signature']) ? $data['want_signature']: 0;
		$data['datetime'] = date('Y-m-d H:i:s');

		$this->load->model('invoice');
		if (!empty($data['id'])) {
			$result = $this->model_invoice->updateInvoice($data);
			$this->createPDF($data['id']);

			$mail_status = $this->model_invoice->getInvoiceMailStatus($data);
			if ($mail_status == "0" && $data['inv_status'] == "1") {
				$this->invoiceMail($data['id']);
				$this->model_invoice->updateInvoiceMailStatus($data['id']);
			}

			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Invoice updated successfully.');			
			$this->url->redirect('invoice/view&id='.$data['id']);
		} else {
			$result = $this->model_invoice->createInvoice($data);
			if ($result) {
				$this->createPDF($result);
				if ($data['inv_status'] == "1") {
					$this->invoiceMail($result);
					$this->model_invoice->updateInvoiceMailStatus($result);
				}
				$this->session->data['message'] = array('alert' => 'success', 'value' => 'Invoice created successfully.');
				$this->url->redirect('invoice/view&id='.$result);
			} else {
				$this->session->data['message'] = array('alert' => 'error', 'value' => 'Invoice does not created successfully.');
				$this->url->redirect('invoice/add');
			}	
		}
	}
	/**
	* Quotes index auto invoice method
	* This method will be called to convert quotes to invoice
	**/
	public function indexAutoInvoice()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('quotes');
		}

		$this->load->model('invoice');
		$data = $this->model_invoice->getQuoteView($id);

		$data['paid'] = '0.00';
		$data['due'] = $data['amount'];
		$data['status'] = "Unpaid";
		$data['duedate'] = date('Y-m-d');
		$data['invoicedate'] = date('Y-m-d');
		$data['datetime'] = date('Y-m-d H:i:s');
		
		$result = $this->model_invoice->createQuoteInvoice($data);
		$this->createPDF($result);
		$this->invoiceMail($result);
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Invoice created successfully.');
		$this->url->redirect('invoice/view&id='.$result);
	}
	/**
	* Invoice index delete method
	* This method will be called on Invoice delete
	**/
	public function indexDelete()
	{
		$this->load->model('invoice');
		$result = $this->model_invoice->deleteInvoice($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Invoice deleted successfully.');
		$this->url->redirect_back();
	}
	/**
	* Invoice invoice mail method
	* This method will be called on to mail invoice details when adding new invoices
	**/
	private function invoiceMail($id)
	{
		$this->load->controller('Mail');
		$result = $this->controller_mail->getTemplate('newinvoice');
		
		if (empty($result['template']) || $result['template']['status'] == '0') {
			return false;
		}

		$this->load->model('invoice');
		$data = $this->model_invoice->getInoviceView($id);

		$data['id'] = str_pad($data['id'], 4, '0', STR_PAD_LEFT);
		$site_link = '<a href="'.URL_CLIENTS.DIR_ROUTE.'invoice/view&id='.$id.'">Click Here</a>';

		$message = $result['template']['message'];
		$message = str_replace('{company}', $data['company'], $message);
		$message = str_replace('{invoice_id}', $result['common']['invoice']['prefix'].$data['id'], $message);
		$message = str_replace('{invoice_amount}', $data['currency_abbr'].$data['amount'], $message);
		$message = str_replace('{invoice_paid}', $data['currency_abbr'].$data['paid'], $message);
		$message = str_replace('{invoice_due}', $data['currency_abbr'].$data['due'], $message);
		$message = str_replace('{invoice_due_date}', $data['duedate'], $message);
		$message = str_replace('{business_name}', $result['common']['name'], $message);
		$message = str_replace('{invoice_url}', $site_link, $message);

		$data['mail']['name'] = $data['company'];
		$data['mail']['email'] = $data['email'];
		$data['mail']['subject'] = $result['template']['subject'];
		$data['mail']['message'] = $message;

		if (file_exists(DIR.'public/uploads/pdf/invoice-'.$id.'.pdf')) {
			$data['mail']['attachments'] = array('file' => DIR.'public/uploads/pdf/invoice-'.$id.'.pdf', 'name' => 'Invoice');
		}
		
		$mail_result = $this->controller_mail->sendmail($data['mail']);
		if ($mail_result == 1) {
			return true;
		} else {
			return false;
		}
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

		$html_array = $this->createPDFHTML($id);
		$pdf = new Pdf();
		$pdf->createPDF($html_array);
	}
	/**
	* Index Payment method
	* This method will be called to create Invoice Payment
	**/
	public function indexPayment()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('invoices');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/

		$this->load->model('commons');
		$data = $this->url->post('payment');
		$data['info'] = $this->model_commons->getSiteInfo();

		$this->load->controller('commons');
		if ($validate_field = $this->validatePaymentField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect_back();
		}

		$this->load->model('invoice');
		$data['date'] = DateTime::createFromFormat($data['info']['date_format'], $data['date'])->format('Y-m-d');

		$result = $this->model_invoice->addInvoicePayment($data);
		$this->model_invoice->invoiceTotal($data);

		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Payment added successfully');
		$this->url->redirect('invoice/view&id='.$data['invoice']);
	}

	public function indexPaymentDelete()
	{
		$data = $this->url->post;

		$this->load->model('invoice');
		$result = $this->model_invoice->deleteInvoicePayment($data);
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Invoice deleted successfully.');
		$this->url->redirect_back();
	}

	public function indexItems()
	{
		$this->load->model('invoice');
		$data = $this->model_invoice->getItems();
		echo json_encode($data);
	}

	public function createPDF($id)
	{
		$html_array = $this->createPDFHTML($id);
		$pdf = new Pdf();
		$pdf->saveInvoicePDF($html_array);
	}

	private function createPDFHTML($id, $printInvoice = NULL)
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using Invoice model 
		**/
		$this->load->model('invoice');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_invoice->getInoviceView($id, $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_invoice->getInoviceView($id);
		}

		if (empty($data['result'])) { $this->url->redirect('invoices'); }

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);

		/*Load Language File*/
		$this->language->load('invoices', 'invoices');

		if ($data['result']['status'] == "Paid") {$data['result']['status'] = $this->language->get('text_paid');}
		elseif ($data['result']['status'] == "Unpaid") {$data['result']['status'] = $this->language->get('text_unpaid');}
		elseif ($data['result']['status'] == "Pending") {$data['result']['status'] = $this->language->get('text_pending');}
		elseif ($data['result']['status'] == "In Process") {$data['result']['status'] = $this->language->get('text_in_process'); }
		elseif ($data['result']['status'] == "Cancelled") {$data['result']['status'] = $this->language->get('text_cancelled');}
		elseif ($data['result']['status'] == "Other") {$data['result']['status'] = $this->language->get('text_other');}
		elseif ($data['result']['status'] == "Partially Paid") {$data['result']['status'] = $this->language->get('text_partially_paid');}
		else {$data['result']['status'] = $this->language->get('text_unknown');}

		$data['lang']= $this->language->all();
		$html = $this->load->view('invoice/invoice_pdf_'.$data['common']['info']['invoice']['template'], $data);
		// $html = $this->load->view('invoice/invoice_pdf_5', $data);
		// print_r(html_entity_decode($html));
		// exit();
		return array('html' => $html, 'result' => $data['result']);
	}
	/**
	* Invoice index mail method
	* This method will be called to maiil invoice reminder etc
	**/
	public function indexMail()
	{
		if (!isset($_POST['submit'])) {
			$this->url->redirect('invoices');
		}

		$data = $this->url->post;
		$this->load->controller('commons');
		$this->load->model('invoice');
		$result = $this->model_invoice->getInoviceView($data['mail']['invoice']);
		if (empty($result)) {
			$this->url->redirect('invoices');
		}

		$data['mail']['email'] = $result['email'];
		$data['mail']['name'] = $result['company'];
		$data['mail']['bcc'] = $data['mail']['bcc'];
		$data['mail']['redirect'] = 'invoice/view&id='.$result['id'];
		if ($data['mail']['attachPdf'] == '1' && file_exists(DIR.'public/uploads/pdf/invoice-'.$data['mail']['invoice'].'.pdf')) {
			$data['mail']['attachments'] = array('file' => DIR.'public/uploads/pdf/invoice-'.$data['mail']['invoice'].'.pdf', 'name' => 'Invoice');
		}
		
		$this->load->controller('Mail');
		$mail_result = $this->controller_mail->sendmail($data['mail']);

		if ($mail_result == 1) {
			$data['mail']['type'] = 'invoice';
			$data['mail']['type_id'] = $data['mail']['invoice'];
			$data['mail']['user_id'] = $this->session->data['user_id'];
			$this->controller_mail->createMailLog($data['mail']);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Success: Message sent successfully.');
			$this->url->redirect('invoice/view&id='.$result['id']);
		} else {
			$this->session->data['message'] = array('alert' => 'error', 'value' => $mail_result);
			$this->url->redirect('invoice/view&id='.$result['id']);
		}
	}

	public function mailMessage()
	{
		$data = $this->url->post;

		/* Load common data */
		$this->load->model('commons');
		$common = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using Invoice model 
		**/
		$this->load->model('invoice');
		if ($common['user']['role_id'] != '1') {
			$invoice = $this->model_invoice->getInoviceView($data['invoice'], $common['user']['user_id']);
		} else {
			$invoice = $this->model_invoice->getInoviceView($data['invoice']);
		}

		if (empty($invoice)) {
			echo json_encode(array('error' => true, 'message' => 'No Data Found.'));
		}

		$template = $this->model_commons->getTemplateAndInfo($data['name']);
		$site_link = '<a href="'.URL_CLIENTS.DIR_ROUTE.'invoice/view&id='.$invoice['id'].'">Click Here</a>';

		$message = $template['template']['message'];
		$message = str_replace('{company}', $invoice['company'], $message);
		$message = str_replace('{invoice_id}', $template['common']['invoice']['prefix'].str_pad($invoice['id'], 4, '0', STR_PAD_LEFT), $message);
		$message = str_replace('{invoice_amount}', $invoice['currency_abbr'].$invoice['amount'], $message);
		$message = str_replace('{invoice_due}', $invoice['currency_abbr'].$invoice['amount'], $message);
		$message = str_replace('{invoice_paid}', $invoice['currency_abbr'].$invoice['paid'], $message);
		$message = str_replace('{invoice_due}', $invoice['currency_abbr'].$invoice['due'], $message);
		$message = str_replace('{invoice_date}', date_format(date_create($invoice['invoicedate']), $template['common']['date_format']), $message);
		$message = str_replace('{invoice_due_date}', date_format(date_create($invoice['duedate']), $template['common']['date_format']), $message);
		$message = str_replace('{business_name}', $template['common']['name'], $message);
		$message = str_replace('{invoice_url}', $site_link, $message);

		$result['subject'] = $template['template']['subject'];
		$result['message'] = html_entity_decode($message);

		echo json_encode(array('error' => false, 'result' => $result));
	}

	public function markAs()
	{
		$data = $this->url->post;

		/* Load common data */
		$this->load->model('commons');
		$common = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using Invoice model 
		**/
		$this->load->model('invoice');
		if ($common['user']['role_id'] != '1') {
			$invoice = $this->model_invoice->getInoviceView($data['id'], $common['user']['user_id']);
		} else {
			$invoice = $this->model_invoice->getInoviceView($data['id']);
		}

		if (empty($invoice)) {
			$this->url->redirect_back();
		}

		$this->model_invoice->invoiceMarkAs($data);

		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Invoice updated successfully.');
		$this->url->redirect('invoice/view&id='.$invoice['id']);
	}

	public function validateField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateNumber($data['customer'])) {
			$error_flag = true;
			$error['customer'] = 'Customer!';
		}
		
		if (!empty($data['duedate'])) {
			if ($this->controller_commons->validateDate($data['duedate'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['duedate'] = 'Due Date!';
			}
		}
		if (!empty($data['invoicedate'])) {
			if ($this->controller_commons->validateDate($data['invoicedate'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['invoicedate'] = 'Invoice Date!';
			}
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	protected function validatePaymentField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateNumeric($data['method'])) {
			$error_flag = true;
			$error['method'] = 'Payment Method';
		}
		if (!empty($data['date'])) {
			if ($this->controller_commons->validateDate($data['date'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['date'] = 'Date!';
			}
		} else {
			$error_flag = true;
			$error['date'] = 'Date';
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}