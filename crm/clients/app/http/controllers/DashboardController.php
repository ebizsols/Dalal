<?php

/**
* Dashboard Controller
*/
class DashboardController extends Controller
{
	/**
	* Dasboard index method
	* This method will be called on dahsboard view
	**/
	public function index()
	{
		$customer = $this->session->data['customer'];
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('dashboard', 'dashboard');
		
		$this->load->model('dashboard');
		$invoice_status = $this->model_dashboard->invoiceStatus($customer);

		if (empty($invoice_status)) {
			$data['invoice_status']['value'] = 0;
			$data['invoice_status']['label'] = 'No Invoice Found';
			$data['invoice_status'] = json_encode($data['invoice_status']);
		} else {
			foreach ($invoice_status as $key => $value) {
				if ($value['label'] == "Paid") {
					$invoice_status[$key]['label'] = $this->language->get('text_paid');
				} elseif ($value['label'] == "Unpaid") { 
					$invoice_status[$key]['label'] = $this->language->get('text_unpaid');
				} elseif ($value['label'] == "Pending") { 
					$invoice_status[$key]['label'] = $this->language->get('text_pending');
				} elseif ($value['label'] == "In Process") {
					$invoice_status[$key]['label'] = $this->language->get('text_in_process');
				} elseif ($value['label'] == "Cancelled") {
					$invoice_status[$key]['label'] = $this->language->get('text_cancelled');
				} elseif ($value['label'] == "Other") {
					$invoice_status[$key]['label'] = $this->language->get('text_other');
				} elseif ($value['label'] == "Partially Paid") { 
					$invoice_status[$key]['label'] = $this->language->get('text_partially_paid');
				} else { 
					$invoice_status[$key]['label'] = $this->language->get('text_unknown');
				}
			}
			$data['invoice_status'] = json_encode($invoice_status);
		}

		$ticket_status = $this->model_dashboard->ticketStatus($data['common']['user']['email']);
		if (empty($ticket_status)) {
			$data['ticket_status']['value'] = 0;
			$data['ticket_status']['label'] = 'No Invoice Found';
			$data['ticket_status'] = json_encode($data['ticket_status']);
		} else {
			foreach ($ticket_status as $key => $value) {
				if ($value['label'] == "Open") 
				{
					$ticket_status[$key]['label'] = $this->language->get('text_open');
				} else { 
					$ticket_status[$key]['label'] = $this->language->get('text_closed');
				}
			}
			$data['ticket_status'] = json_encode($ticket_status);
		}

		$data['lastticket'] = $this->model_dashboard->getLastTicket($data['common']['user']['email']);

		$data['countInvoice'] = $this->model_dashboard->invoiceCount($customer);
		$data['countQuotes'] = $this->model_dashboard->quotesCount($customer);
		$data['countTicket'] = $this->model_dashboard->ticketCount($data['common']['user']['email']);

		$data['invoices'] = $this->model_dashboard->getInvoices($customer);
		$data['quotes'] = $this->model_dashboard->getQuotes($customer);
		$data['page_title'] = $this->language->get('text_dashboard');

		$data['lang']= $this->language->all();

		/*Render dahsboard view*/
		$this->response->setOutput($this->load->view('dashboard/dashboard', $data));
	}
}