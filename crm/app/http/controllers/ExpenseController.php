<?php

/**
* ExpenseController
*/
class ExpenseController extends Controller
{
	/**
	* Expense index method
	* This method will be called on Expense list view
	**/
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/*Load Language File*/
		$this->language->load('expenses', 'expenses');

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
		* Get all Expenses data from DB using User model 
		**/
		$this->load->model('expense');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_expense->getExpenses($data['period'], $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_expense->getExpenses($data['period']);
		}
		/* Set page title */
		$data['page_title'] = $this->language->get('text_expenses');
		$data['page_add'] = $this->user_agent->hasPermission('expense/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('expense/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('expense/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'expense/delete';
		$data['page_active'] = 'expense';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('expense/expense_list', $data));
	}
	/**
	* Expense index ADD method
	* This method will be called on Expense ADD view
	**/
	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		
		/*Load Language File*/
		$this->language->load('expenses', 'expenses');
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('expense');
		$data['result'] = NULL;
		if ($data['common']['user']['role_id'] != '1') {
			$data['admin'] = false;
		} else {
			$data['admin'] = true;
		}
		
		$data['currency'] = $this->model_commons->getCurrencies();
		$data['expensetype'] = $this->model_commons->getExpensesType();
		$data['paymenttype'] = $this->model_commons->getPaymentMethod();

		/* Set page title */
		$data['page_title'] = $this->language->get('text_add_expense');
		$data['action'] = URL.DIR_ROUTE.'expense/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'expense';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('expense/expense_form', $data));
	}
	/**
	* Expense index Edit method
	* This method will be called on Expense Edit view
	**/
	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Expenses list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('expenses');
		}
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('expense');
		if ($data['common']['user']['role_id'] != '1') {
			$data['admin'] = false;
			$data['result'] = $this->model_expense->getExpense($id, $data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['result'] = $this->model_expense->getExpense($id);
		}

		if (empty($data['result'])) {
			$this->url->redirect('expenses');
		}
		
		$data['currency'] = $this->model_commons->getCurrencies();
		$data['expensetype'] = $this->model_commons->getExpensesType();
		$data['paymenttype'] = $this->model_commons->getPaymentMethod();
		$data['receipt'] = $this->model_expense->getReceipt($id);
		
		/*Load Language File*/
		$this->language->load('expenses', 'expenses');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_expense');
		$data['action'] = URL.DIR_ROUTE.'expense/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'expense';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('expense/expense_form', $data));
	}
	/**
	* Expense index Action method
	* This method will be called on Expense Save or Update view
	**/
	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('expenses');
		}

		$data = $this->url->post('expense');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		
		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['id'])) {
				$this->url->redirect('expense/edit&id='.$data['id']);
			} else {
				$this->url->redirect('expense/add');
			}
		}
		$data['purchasedate'] = DateTime::createFromFormat($data['info']['date_format'], $data['purchasedate'])->format('Y-m-d');
		$data['datetime'] = date('Y-m-d H:i:s');

		$this->load->model('expense');
		if (!empty($data['id'])) {
			$result = $this->model_expense->updateExpense($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Expense created successfully.');
		} else {
			$data['user_id'] = $this->session->data['user_id'];
			$data['id'] = $this->model_expense->createExpense($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Expense created successfully.');
		}
		$this->url->redirect('expense/edit&id='.$data['id']);
	}
	/**
	* Expense index Delete method
	* This method will be called on Expense Delete view
	**/
	public function indexDelete()
	{
		$this->load->model('expense');
		$result = $this->model_expense->deleteExpense($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Expense deleted successfully.');
		$this->url->redirect_back();
	}
	/**
	* Expense Validate method
	* Validate input field
	**/
	public function validateField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateText($data['purchaseby'])) {
			$error_flag = true;
			$error['purchaseby'] = 'Purchase By!';
		}
		if ($this->controller_commons->validateDate($data['purchasedate'], $data['info']['date_format'] )) {
			$error_flag = true;
			$error['purchasedate'] = 'Purchase Date!';
		}
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}