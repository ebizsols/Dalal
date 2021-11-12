<?php

class InvoiceController extends Controller
{
	/**
	* Contact index method
	* This method will be called on Contact list view
	**/
	public function index()
	{
		$customer = $this->session->data['customer'];
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['customer']);

		$this->load->model('invoice');
		if (!empty($customer)) {
			$data['result'] = $this->model_invoice->getInvoices($customer);
		} else {
			$data['result'] = NULL;
		}

		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('invoices', 'invoices');
		
		/* Set page title */
		$data['page_title'] = $this->language->get('text_invoices');
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('invoice/invoice_list', $data));
	}

	public function indexView()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['customer']);
		/**
		* Check if id exist in url if not exist then redirect to Invoice list view 
		**/
		$data['id'] = (int)$this->url->get('id');
		if (empty($data['id']) || !is_int($data['id'])) { $this->url->redirect('invoices'); }

		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('invoices', 'invoices');

		$data['customer'] = $this->session->data['customer'];
		if (empty($data['customer'])) {
			$this->url->redirect('invoices');
		}

		$this->load->model('invoice');
		$data['result'] = $this->model_invoice->getInoviceView($data);

		if (empty($data['result'])) { $this->url->redirect('invoices'); }

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);

		$data['payments'] = $this->model_invoice->getPayments($data['id']);
		$data['attachments'] = $this->model_invoice->getAttachments($data['id']);

		/**
		* Get all User data from DB using Invoice model
		**/
		$data['page_title'] = $this->language->get('text_invoice_view');
		$data['action'] = URL_CLIENTS.DIR_ROUTE.'invoicePayment/action';
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('invoice/invoice_view', $data));
	}

	public function indexPdf()
	{
		/**
		* Check if id exist in url if not exist then redirect to Invoice list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) { $this->url->redirect('invoices');}
		$html_array = $this->createPDFHTML($id);
		$pdf = new Pdf();
		$pdf->createPDF($html_array);
	}

	public function createPDFHTML($id, $printInvoice = NULL)
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$data['id'] = $id;
		$data['customer'] = $this->session->data['customer'];
		if (empty($data['customer'])) { $this->url->redirect('invoices'); }

		$this->load->model('invoice');
		$data['result'] = $this->model_invoice->getInoviceView($data);
		if (empty($data['result'])) { $this->url->redirect('invoices'); }

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);
		
		/*Load Language File*/
		$data['lang']['common'] = $this->language->load('common', 'common');
		$data['lang']['invoices'] = $this->language->load('invoices', 'invoices');
		
		if ($data['result']['status'] == "Paid") {$data['result']['status'] = $this->language->get('text_paid'); }
		elseif ($data['result']['status'] == "Unpaid") {$data['result']['status'] = $this->language->get('text_unpaid'); }
		elseif ($data['result']['status'] == "Pending") {$data['result']['status'] = $this->language->get('text_pending'); }
		elseif ($data['result']['status'] == "In Process") {$data['result']['status'] = $this->language->get('text_in_process'); }
		elseif ($data['result']['status'] == "Cancelled") {$data['result']['status'] = $this->language->get('text_cancelled'); }
		elseif ($data['result']['status'] == "Other") {$data['result']['status'] = $this->language->get('text_other'); }
		elseif ($data['result']['status'] == "Partially Paid") {$data['result']['status'] = $this->language->get('text_partially_paid'); }
		else {$data['result']['status'] = $this->language->get('text_unknown');}

		$data['lang']= $this->language->all();
		$html = $this->load->view('invoice/invoice_pdf_'.$data['common']['info']['invoice']['template'], $data);
		// echo "<pre>";
		// print_r($data);
		// print_r(html_entity_decode($html));
		// exit();
		return array('html' => $html, 'result' => $data['result']);
	}
}