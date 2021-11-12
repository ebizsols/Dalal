<?php
/**
* QuoteController
*/

class QuoteController extends Controller
{
	public function index()
	{
		$customer = $this->session->data['customer'];
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['customer']);

		$this->load->model('quote');
		if (!empty($customer)) {
			$data['result'] = $this->model_quote->getQuotes($customer);
		} else {
			$data['result'] = NULL;
		}
		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('quotes', 'quotes');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_quotations');

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('quote/quote_list', $data));
	}
	
	public function indexView()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['customer']);

		$data['id'] = (int)$this->url->get('id');
		if (empty($data['id']) || !is_int($data['id'])) {
			$this->url->redirect('quotes');
		}

		$data['customer'] = $this->session->data['customer'];
		if (empty($data['customer'])) { $this->url->redirect('quotes'); }

		$this->load->model('quote');
		$data['result'] = $this->model_quote->getQuoteView($data);
		
		if (empty($data['result'])) { $this->url->redirect('quotes'); }
		
		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);
		
		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('quotes', 'quotes');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_quotation_view');
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('quote/quote_view', $data));
	}

	public function indexPdf()
	{
		/**
		* Check if id exist in url if not exist then redirect to Invoice list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) { $this->url->redirect('quotes'); }

		$html_array = $this->createPDFHTML($id);
		$pdf = new Pdf();
		$pdf->createPDF($html_array);
	}

	public function createPDFHTML($id, $printQuote = NULL)
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		$data['id'] = $id;
		
		$data['customer'] = $this->session->data['customer'];
		if (empty($data['customer'])) { $this->url->redirect('quotes'); }

		$this->load->model('quote');
		$data['result'] = $this->model_quote->getQuoteView($data);
		if (empty($data['result'])) { $this->url->redirect('quotes'); }

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);

		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('quotes', 'quotes');

		$data['lang']= $this->language->all();
		$html = $this->load->view('quote/quote_pdf_'.$data['common']['info']['invoice']['template'], $data);
		return array('html' => $html, 'result' => $data['result']);
	}

	public function indexAutoInvoice()
	{
		if (!isset($_POST['submit'])) {
			$this->url->redirect('quotes');
		}

		$passData['id'] = (int)$this->url->post('id');
		
		if (empty($passData['id'])) {
			$this->url->redirect('quotes');
		}
		
		$this->load->model('quote');
		$passData['customer'] = $this->session->data['customer'];
		$data = $this->model_quote->getQuoteView($passData);

		$data['paid'] = '0.00';
		$data['due'] = $data['amount'];
		$data['status'] = "Unpaid";
		$data['inv_status'] = 1;
		$data['duedate'] = date_format(date_create(), "Y-m-d");
		$data['invoicedate'] = date_format(date_create(), "Y-m-d");
		$data['paiddate'] = date_format(date_create(), "Y-m-d");
		$data['datetime'] = date('Y-m-d H:i:s');
		
		$result = $this->model_quote->createQuoteInvoice($data);
		$this->createPDF($result);
		$this->invoiceMail($result);
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Invoice created successfully.');
		$this->url->redirect('invoice/view&id='.$result);
	}

	public function createPDF($id)
	{
		/**
		* Get all User data from DB using Invoice model 
		**/
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		$this->load->model('quote');
		$data['result'] = $this->model_quote->getInoviceView($id);

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);
		
		/*Load Language File*/
		$data['lang']['common'] = $this->language->load('common', 'common');
		$data['lang']['invoices'] = $this->language->load('invoices', 'invoices');

		if ($data['result']['status'] == "Paid") {$data['result']['status'] = $data['lang']['invoices']['text_paid'];}
		elseif ($data['result']['status'] == "Unpaid") {$data['result']['status'] = $data['lang']['invoices']['text_unpaid'];}
		elseif ($data['result']['status'] == "Pending") {$data['result']['status'] = $data['lang']['invoices']['text_pending'];}
		elseif ($data['result']['status'] == "In Process") {$data['result']['status'] = $data['lang']['invoices']['text_in_process']; }
		elseif ($data['result']['status'] == "Cancelled") {$data['result']['status'] = $data['lang']['invoices']['text_cancelled'];}
		elseif ($data['result']['status'] == "Other") {$data['result']['status'] = $data['lang']['invoices']['text_other'];}
		elseif ($data['result']['status'] == "Partially Paid") {$data['result']['status'] = $data['lang']['invoices']['text_partially_paid'];}
		else {$data['result']['status'] = $data['lang']['invoices']['text_unknown'];}

		$html = $this->load->view('invoice/invoice_pdf_'.$data['common']['info']['invoice']['template'], $data);
		
		$html_array = array('html' => $html, 'result' => $data['result']);
		$pdf = new Pdf();
		$pdf->saveInvoicePDF($html_array);
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

		$this->load->model('commons');
		$info = $this->model_commons->getSiteInfo();
		$this->load->model('quote');
		$data = $this->model_quote->getInoviceView($id);
		if (empty($data)) {
			return false;
		}
		
		$site_link = '<a href="'.URL_CLIENTS.DIR_ROUTE.'invoice/view&id='.$id.'">Click Here</a>';
		
		$message = $result['template']['message'];
		$message = str_replace('{company}', $data['company'], $message);
		$message = str_replace('{invoice_id}', $info['invoice']['prefix'].$data['id'], $message);
		$message = str_replace('{invoice_amount}', $data['currency_abbr'].$data['amount'], $message);
		$message = str_replace('{invoice_paid}', $data['currency_abbr'].$data['paid'], $message);
		$message = str_replace('{invoice_due}', $data['currency_abbr'].$data['due'], $message);
		$message = str_replace('{invoice_due_date}', $data['duedate'], $message);
		$message = str_replace('{business_name}', $info['name'], $message);
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
}