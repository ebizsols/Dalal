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
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		//Load dashboard language
		$this->language->load('dashboard', 'dashboard');
		$this->language->load('tickets', 'tickets');
		$this->load->model('dashboard');
		
		if ($data['common']['user']['role_id'] == '1') {
			$this->dashboardAdmin($data);
		} else {
			$this->dashboardEmployee($data);
		}	
	}


	public function dashboardAdmin($data)
	{
		$data['period']['start'] = date("Y-m-d", strtotime( date( 'Y-m-01' )." -11 months"));
		$data['period']['end'] = date("Y-m-d", strtotime( date( 'Y-m-d' )));

		$data['chart_income'] = $this->formatChartDataWithMonth($this->model_dashboard->getChartIncome());
		$data['chart_expense'] = $this->formatChartDataWithMonth($this->model_dashboard->getChartExpense());
		$data['chart_contacts'] = $this->formatChartDataWithMonth($this->model_dashboard->chartContact());
		$data['chart_leads'] = $this->formatChartDataWithMonth($this->model_dashboard->chartLeads());
		$data['chart_salary'] = $this->formatChartDataWithMonth($this->model_dashboard->chartSalary());
		$data['chart_invoice_status'] = $this->formatPieChartData($this->model_dashboard->getChartInvoiceStatus());
		$data['chart_expense_category'] = $this->formatPieChartData($this->model_dashboard->getChartExpensebyCategory());
		$data['chart_transaction'] = $this->formatTransactionWithMonth($this->model_dashboard->getChartTransaction());

		$data['statistics'] = $this->model_dashboard->getMainStatistics();
		$data['other_statistics'] = $this->model_dashboard->getOtherStatistics();
		$data['ie_stats'] = $this->model_dashboard->getIncomeExpenseStats();

		$data['invoices'] = $this->model_dashboard->getInvoices();
		$data['contacts'] = $this->model_dashboard->getContacts();
		$data['tickets'] = $this->model_dashboard->getTickets();
		$data['notices'] = $this->model_dashboard->getNotices();
		
		$data['page_title'] = $this->language->get('text_dashboard');
		$data['page_active'] = 'dashboard';
		$data['lang']= $this->language->all();
		/*Render dahsboard view*/
		$this->response->setOutput($this->load->view('dashboard/dashboard_admin', $data));
	}

	public function dashboardEmployee($data)
	{
		
		$data['period']['start'] = date("Y-m-d", strtotime( date( 'Y-m-01' )." -11 months"));
		$data['period']['end'] = date("Y-m-d", strtotime( date( 'Y-m-d' )));
		$data['notices'] = $this->model_dashboard->getNotices();
		
		$data['page_title'] = $this->language->get('text_dashboard');
		$data['page_active'] = 'dashboard';
		$data['lang']= $this->language->all();
		/*Render dahsboard view*/
		$this->response->setOutput($this->load->view('dashboard/dashboard_employee', $data));
	}

	public function formatChartDataWithMonth($data)
	{
		$months = array();
		$result['label'] = array();
		$result['value'] = array();
		for ($i = 0; $i < 12; $i++) {
			$month = date("m", strtotime( date( 'Y-m-01' )." -$i months"));
			$month_name = date("M", strtotime( date( 'Y-m-01' )." -$i months"));

			if (!empty($data)) {
				foreach ($data as $key => $value) {
					if ($value['month'] == $month) {
						$result['value'][$i] = number_format((float)$value['amount'], 2, '.', '');
						$result['label'][$i] = $month_name;
					}
				}
			}

			if (!isset($result['value'][$i])) {
				$result['value'][$i] = 0;
				$result['label'][$i] = $month_name;
			}
		}
		
		$result['label'] = json_encode(array_reverse($result['label']));
		$result['value'] = json_encode(array_reverse($result['value']));
		return $result;
	}

	public function formatPieChartData($data)
	{
		$result['label'] = array();
		$result['value'] = array();
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				$result['value'][$key] = number_format((float)$value['value'], 2, '.', '');;
				$result['label'][$key] = $value['label'];
			}
		}
		
		$result['label'] = json_encode(array_reverse($result['label']));
		$result['value'] = json_encode(array_reverse($result['value']));
		return $result;
	}

	public function formatInvoiceChartData($data)
	{
		$arr = array('Paid', 'Partially Paid', 'Unpaid', 'Pending', 'In Process', 'Cancelled');
		$result['label'] = array();
		$result['value'] = array();
		foreach ($arr as $key => $value) {
			if (!empty($data)) {
				foreach ($data as $k => $v) {
					if ($v['label'] == $value) {
						$result['value'][$key] = number_format((float)$v['value'], 2, '.', '');;
						$result['label'][$key] = $v['label'];
					}
				}
			}

			if (!isset($result['value'][$key])) {
				$result['value'][$key] = 0;
				$result['label'][$key] = $value;
			}
		}
		
		$result['label'] = json_encode(array_reverse($result['label']));
		$result['value'] = json_encode(array_reverse($result['value']));
		return $result;
	}

	public function formatTransactionWithMonth($data)
	{
		$months = array();
		$result['label'] = array();
		$result['credit'] = array();
		$result['debit'] = array();
		for ($i = 0; $i < 12; $i++) {
			$month = date("m", strtotime( date( 'Y-m-01' )." -$i months"));
			$month_name = date("M", strtotime( date( 'Y-m-01' )." -$i months"));
			
			if (!empty($data)) {
				foreach ($data as $key => $value) {
					if ($value['month'] == $month) {
						$result['credit'][$i] = number_format((float)$value['credit'], 2, '.', '');
						$result['debit'][$i] = number_format((float)$value['debit'], 2, '.', '');
						$result['label'][$i] = $month_name;
					}
				}
			}

			if (!isset($result['credit'][$i])) {
				$result['credit'][$i] = 0;
				$result['debit'][$i] = 0;
				$result['label'][$i] = $month_name;
			}
		}
		
		$result['label'] = json_encode(array_reverse($result['label']));
		$result['debit'] = json_encode(array_reverse($result['debit']));
		$result['credit'] = json_encode(array_reverse($result['credit']));
		return $result;
	}












	public function index1()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$this->language->load('dashboard', 'dashboard');

		$this->load->model('dashboard');
		/*Get all Expenses*/
		$data['expenses'] = json_encode($this->model_dashboard->getExpenses());
		/*Get income and expenses stats*/
		$data['stats'] = json_encode($this->model_dashboard->getIncomeExpenses());
		
		if ($data['common']['user']['role_id'] == '1') {
			/*Get latest contact*/
			$data['contacts'] = $this->model_dashboard->getLatestContact();
			/*Get latest invoices*/
			$data['invoices'] = $this->model_dashboard->getLatestInvoice();
		} else {
			/*Get latest contact*/
			$data['contacts'] = $this->model_dashboard->getLatestContact($user);
			/*Get latest invoices*/
			$data['invoices'] = $this->model_dashboard->getLatestInvoice($user);
		}

		/*Get Invoice by status */
		$data['invoiceByStatus'] = $this->model_dashboard->getInvoiceByStatus();
		if (!empty($data['invoiceByStatus'])) {
			foreach ($data['invoiceByStatus'] as $key => $value) {
				if ($value['label'] == "Paid") {
					$data['invoiceByStatus'][$key]['label'] = $this->language->get('text_paid'); 
				} elseif ($value['label'] == "Unpaid") { 
					$data['invoiceByStatus'][$key]['label'] = $this->language->get('text_unpaid'); 
				} elseif ($value['label'] == "Pending") { 
					$data['invoiceByStatus'][$key]['label'] = $this->language->get('text_pending');
				} elseif ($value['label'] == "In Process") {
					$data['invoiceByStatus'][$key]['label'] = $this->language->get('text_in_process'); 
				} elseif ($value['label'] == "Cancelled") {
					$data['invoiceByStatus'][$key]['label'] = $this->language->get('text_cancelled'); 
				} elseif ($value['label'] == "Other") {
					$data['invoiceByStatus'][$key]['label'] = $this->language->get('text_other'); 
				} elseif ($value['label'] == "Partially Paid") { 
					$data['invoiceByStatus'][$key]['label'] = $this->language->get('text_partially_paid'); 
				} else { 
					$data['invoiceByStatus'][$key]['label'] = $this->language->get('text_unknown'); 
				}
			}
		}
		$data['invoiceByStatus'] = json_encode($data['invoiceByStatus']);

		/*Get Ticket by status */
		$data['ticketByStatus'] = $this->model_dashboard->getTicketByStatus();
		if (!empty($data['ticketByStatus'])) {
			foreach ($data['ticketByStatus'] as $key => $value) {
				if ($value['label'] == "Open") 
				{
					$data['ticketByStatus'][$key]['label'] = $this->language->get('text_open');
				} else { 
					$data['ticketByStatus'][$key]['label'] = $this->language->get('text_closed'); 
				}
			}
		}
		$data['ticketByStatus'] = json_encode($data['ticketByStatus']);
		/*Get recent list*/
		$data['recents'] = $this->model_dashboard->getRecentlyAdded();
		/*Get total Statistics*/
		$data['statistics'] = $this->model_dashboard->getStatistics();
		$data['page_title'] = $this->language->get('text_dashboard');

		$data['lang']= $this->language->all();
		/*Render dahsboard view*/
		$this->response->setOutput($this->load->view('dashboard/dashboard', $data));
	}

	public function convetInvoiceLanguage($data)
	{
		
	}
}