<?php

/**
* User Controller
*/
class UserController extends Controller
{
	/**
	* User index method
	* This method will be called on User list view
	**/
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/*Load Language File*/
		$this->language->load('users', 'users');
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('user');
		$data['result'] = $this->model_user->allUsers();

		/* Set page title */
		$data['page_title'] = $this->language->get('text_users');
		$data['page_add'] = $this->user_agent->hasPermission('user/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('user/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('user/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'user/delete';
		$data['role'] = $data['common']['user']['role_id'];
		$data['page_active'] = 'user';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('user/user_list', $data));
	}
	/**
	* User index add method
	* This method will be called on User add
	**/
	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('users', 'users');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_user');
		/* Set empty data to array */
		$this->load->model('user');
		$data['result'] =  NULL;
		$data['meta'] =  NULL;
		$data['role'] =  $this->model_user->userRole();
		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'user/add';
		$data['page_active'] = 'user';
		
		$data['lang']= $this->language->all();
		/*Render User add view*/
		$this->response->setOutput($this->load->view('user/user_form', $data));
	}

	/**
	* User index edit method
	* This method will be called on User edit view
	**/
	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to User list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('user');
		}
		/**
		* Call getUser method from Blog model to get data from DB for single User
		* If User does not exist then redirect it to User list view
		**/

		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$this->load->model('user');
		$data['result'] = $this->model_user->getUser($id);
		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'User does not exist in database!');
			$this->url->redirect('user');
		}

		$role = $data['common']['user']['role_id'];
		if ($role != "1" && $data['result']['user_role'] == "1") {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Access Denied.');
			$this->url->redirect('user');
		}

		/*Load Language File*/
		$this->language->load('users', 'users');
		
		$data['result']['meta'] = json_decode($data['result']['meta'], true);
		$data['role'] =  $this->model_user->userRole();

		$data['page_title'] = $this->language->get('text_edit_user');
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'user/edit';
		$data['page_active'] = 'user';

		$data['lang']= $this->language->all();
		/*Render User edit view*/
		$this->response->setOutput($this->load->view('user/user_form', $data));
	}
	/**
	* Info index action method
	* This method will be called on Info submit/save view
	**/
	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('user');
			exit();
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$data = $this->url->post('user');

		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();

		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter/select valid '.implode(", ",$validate_field).'!');
			if (!empty($this->url->post('id'))) {
				$this->url->redirect('user/edit&id='.$this->url->post('id'));
			} else {
				$this->url->redirect('user/add');
			}
		}

		if (empty($data['meta']['dob'])) {
			$data['meta']['dob'] = '';
		} else {
			$data['meta']['dob'] = DateTime::createFromFormat($data['info']['date_format'], $data['meta']['dob'])->format('Y-m-d');	
		}
		$data['meta'] = json_encode($data['meta']);
		$data['datetime'] = date('Y-m-d H:i:s');

		$this->load->model('user');
		if (!empty($this->url->post('id'))) {
			$this->update($data);
		} else {
			$this->create($data);
		}
	}
	/**
	* User index delete method
	* This method will be called on blog delete action
	**/
	public function indexDelete()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['delete']) || empty($this->url->post('id'))) {
			$this->url->redirect('user');
		}
		/**
		* Call delete method
		**/
		$this->load->model('user');
		$result = $this->model_user->deleteUser($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Account deleted successfully.');
		$this->url->redirect('user');
	}

	protected function update($data)
	{
		$data['id'] = $this->url->post('id');
		$check_user = $this->model_user->checkUserName($data);
		/**
		* Check if @user_name already exist or not in database
		**/
		if ($check_user >= 1) {
			$this->session->data['message'] = array('alert' => "error", 'value' => "User Name ".$data['username']." already exist in database.");
			$this->url->redirect('user/edit&id='.$data['id']);
		}

		$result = $this->model_user->updateUser($data);
		
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Account updated successfully.');
		$this->url->redirect('user/edit&id='.$data['id']);
	}

	protected function create($data)
	{
		/**
		* Set all data to one array to pass it to model
		**/		
		
		$check_user = $this->model_user->checkUserName($data);
		/**
		* Check if @user_name already exist or not in database
		**/
		if ($check_user >= 1) {
			$this->session->data['message'] = array('alert' => "error", 'value' => "User Name ".$data['username']." already exist in database.");
			$this->url->redirect('user/add');
		}

		/**
		* Check if @email already exist or not in database
		**/
		if ($this->model_user->checkUserEmail($data['email']) >= 1) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Email '.$data['email'].' already exist in database.');
			$this->url->redirect('user/add');
		}
		
		/**
		* Call user model to create user
		* If user created than send email and set success message 
		* If not than set error 
		**/
		$data['hash'] = md5(uniqid(mt_rand(), true));
		$data['chars'] = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#";
		$data['password_text'] = substr( str_shuffle( $data['chars'] ), 0, 12 );
		$data['password'] = password_hash($data['password_text'], PASSWORD_DEFAULT);
		
		$result = $this->model_user->createUser($data);
		if ($result) {
			$this->indexMail($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Account created successfully.');
			$this->url->redirect('user/edit&id='.$result);
		} else {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Account does not created (Server Error).');
			$this->url->redirect('user/add');
		}
	}

	private function indexMail($data)
	{
		$this->load->controller('Mail');
		$result = $this->controller_mail->getTemplate('newuser');
		
		if (empty($result['template']) || $result['template']['status'] == '0') {
			return false;
		}

		$site_link = '<a href="'.URL.'">Click Here</a>';		
		$message = $result['template']['message'];
		$message = str_replace('{name}', $data['firstname'].' '.$data['lastname'], $message);
		$message = str_replace('{email}', $data['email'], $message);
		$message = str_replace('{username}', $data['username'], $message);
		$message = str_replace('{password}', $data['password_text'], $message);
		$message = str_replace('{business_name}', $result['common']['name'], $message);
		$message = str_replace('{login_url}', $site_link, $message);

		$data['mail']['name'] = $data['firstname'].' '.$data['lastname'];
		$data['mail']['email'] = $data['email'];
		$data['mail']['subject'] = $result['template']['subject'];
		$data['mail']['message'] = $message;
		$mail_result = $this->controller_mail->sendmail($data['mail']);
		

		if ($mail_result == 1) {
			return true;
		} else {
			return false;
		}
	}
	/**
	* Validate user field from server side
	**/
	protected function validateField($data)
	{
		$error = [];
		$error_flag = false;
		if ($this->controller_commons->validateText($data['firstname'])) {
			$error_flag = true;
			$error['firstname'] = 'First Name';
		}
		if ($this->controller_commons->validateText($data['lastname'])) {
			$error_flag = true;
			$error['lastname'] = 'Last Name';
		}
		if ($this->controller_commons->validateText($data['username'])) {
			$error_flag = true;
			$error['username'] = 'Username';
		}
		if ($this->controller_commons->validateEmail($data['email'])) {
			$error_flag = true;
			$error['email'] = 'Email Address';
		}
		if ($this->controller_commons->validatePhoneNumber($data['mobile'])) {
			$error_flag = true;
			$error['mobile'] = 'Mobile Number';
		}
		if (!empty($data['meta']['dob'])) {
			if ($this->controller_commons->validateDate($data['meta']['dob'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['dob'] = 'Date of birth!';
			}
		}
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	public function userRole()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('users', 'users');

		$this->load->model('user');
		$data['result'] = $this->model_user->getRoles();

		$data['page_title'] = $this->language->get('text_user_roles');
		$data['page_active'] = 'user';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('user/user_role', $data));
	}

	public function userRoleAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('users', 'users');

		$data['result'] = NULL;
		$data['lang']= $this->language->all();
		$data['role'] = $this->getRoleList($data['lang']);
		$data['role_selected'] = array();

		$data['page_title'] = $this->language->get('text_new_user_role');
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'role/add';
		$data['page_active'] = 'user';

		/*Render User list view*/
		$this->response->setOutput($this->load->view('user/user_role_form', $data));
	}

	public function userRoleEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to User list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('role');
		}

		if ($id == "1") {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'You can not change Admin role setting.');
			$this->url->redirect('role');
		}

		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('users', 'users');

		$this->load->model('user');
		$data['result'] = $this->model_user->getRole($id);
		
		$data['role_selected'] = json_decode($data['result']['permission'], true);
		$data['lang']= $this->language->all();
		$data['role'] = $this->getRoleList($data['lang']);

		$data['page_title'] = $this->language->get('text_edit_user_role');
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'role/edit';
		$data['page_active'] = 'user';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('user/user_role_form', $data));
	}

	public function userRoleAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('role');
		}
		
		$this->load->model('user');

		if (!empty($this->url->post('id'))) {
			$data['name'] = $this->url->post('name');
			$data['description'] = $this->url->post('description');
			$data['id'] = $this->url->post('id');
			$data['permission'] = json_encode($this->url->post('role'));
			
			$result = $this->model_user->updateUserRole($data);
		} else {
			$data['name'] = $this->url->post('name');
			$data['description'] = $this->url->post('description');
			$data['permission'] = json_encode($this->url->post('role'));

			$data['id'] = $this->model_user->addUserRole($data);
		}
		$this->url->redirect('role/edit&id='.$data['id']);
	}

	public function userRoleDelete()
	{
		$this->load->model('user');
		$result = $this->model_user->deleteRole($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'User Role deleted successfully.');
		$this->url->redirect('role');
	}

	public function getRoleList($lang)
	{
		$role = array(
			array('contacts' => $this->language->get('text_contacts'),
				'contact/add' => $this->language->get('text_contact_add'),
				'contact/edit' => $this->language->get('text_contact_edit'),
				'contact/delete' => $this->language->get('text_contact_delete'),
				'contact/view' => $this->language->get('text_contact_view')),
			array('contact/sendmail' => $this->language->get('text_contact').' '.$this->language->get('text_send_email') ,
				'contact/import' => $this->language->get('text_contact').' '.$this->language->get('text_import'),
				'2' => '',
				'3' => '',
				'4' => ''),
			array('leads' => $this->language->get('text_lead_list'),
				'lead/add' => $this->language->get('text_lead_add'),
				'lead/edit' => $this->language->get('text_lead_edit'),
				'lead/delete' => $this->language->get('text_lead_delete'),
				'lead/convert' => 'Convert Lead to Contact'),
			array('projects' => $this->language->get('text_project_list'),
				'project/add' => $this->language->get('text_project_add'),
				'project/edit' => $this->language->get('text_project_edit'),
				'project/delete' => $this->language->get('text_project_delete'),
				'project/view' => $this->language->get('text_project').' '.$this->language->get('text_view')),
			array('project/documents' => $this->language->get('text_project').' '.$this->language->get('text_documents'),
				'project/comment' => $this->language->get('text_project').' '.$this->language->get('text_comments'),
				'1' => '',
				'2' => '',
				'3' => ''),
			array('quotes' => $this->language->get('text_quote_list'),
				'quote/add' => $this->language->get('text_quote_add'),
				'quote/edit' => $this->language->get('text_quote_edit'),
				'quote/delete' => $this->language->get('text_quote_delete'),
				'quote/view' => $this->language->get('text_quote_view')),
			array('quote/pdf' => $this->language->get('text_quote').' '.$this->language->get('text_PDF'),
				'quote/autoinvoice' => $this->language->get('text_convert_quote_to_invoice'),
				'1' => '',
				'2' => '',
				'3' => '',),
			array('invoices' => $this->language->get('text_invoice_list'),
				'invoice/add' => $this->language->get('text_invoice_add'),
				'invoice/edit' => $this->language->get('text_invoice_edit'),
				'invoice/delete' => $this->language->get('text_invoice_delete'),
				'invoice/view' => $this->language->get('text_invoice_view')),
			array('invoice/copy' => $this->language->get('text_invoice').' '.$this->language->get('text_copy'),
				'invoice/pdf' => $this->language->get('text_invoice').' '.$this->language->get('text_PDF'),
				'invoice/sendmail' => $this->language->get('text_invoice').' '.$this->language->get('text_send_mail'),
				'invoice/payment' => $this->language->get('text_invoice').' '.$this->language->get('text_add_payment'),
				'invoice/documents' => $this->language->get('text_invoice').' '.$this->language->get('text_upload_attachments'),
				'' => '',),
			array('recurring' => $this->language->get('text_recurring_invoice_list'),
				'recurring/add' => $this->language->get('text_recurring_invoice_add'),
				'recurring/edit' => $this->language->get('text_recurring_invoice_edit'),
				'recurring/delete' => $this->language->get('text_recurring_invoice_delete'),
				'recurring/view' => $this->language->get('text_recurring_invoice_view')),
			array('recurring/pdf' => $this->language->get('text_recurring_invoice').' '.$this->language->get('text_PDF'),
				'1' => '',
				'2' => '',
				'3' => '',
				'4' => '',),
			array('account/transactions' => $this->language->get('text_transactions'),
				'account/transaction/add' => $this->language->get('text_transaction').' '.$this->language->get('text_add'),
				'account/transaction/edit' => $this->language->get('text_transaction').' '.$this->language->get('text_edit'),
				'account/transaction/delete' => $this->language->get('text_transaction').' '.$this->language->get('text_delete'),
				'1' => ''),
			array('accounts' => $this->language->get('text_accounts'),
				'account/add' => $this->language->get('text_account').' '.$this->language->get('text_add'),
				'account/edit' => $this->language->get('text_account').' '.$this->language->get('text_edit'),
				'account/delete' => $this->language->get('text_account').' '.$this->language->get('text_delete'),
				'account/statement' => $this->language->get('text_account').' '.$this->language->get('text_statement')),
			array('makepayment' => $this->language->get('text_make_payment'),
				'makepayment/add' => $this->language->get('text_make_payment').' '.$this->language->get('text_add'),
				'1' => '',
				'2' => '',
				'3' => ''),
			array('managesalary' => $this->language->get('text_manage_salary'),
				'managesalary/add' => $this->language->get('text_manage_salary').' '.$this->language->get('text_add'),
				'managesalary/edit' => $this->language->get('text_manage_salary').' '.$this->language->get('text_edit'),
				'managesalary/view' => $this->language->get('text_manage_salary').' '.$this->language->get('text_view'),
				'1' => ''),
			array('managesalary/history' => $this->language->get('text_payment_history'),
				'managesalary/history/view' => $this->language->get('text_payment_history').' '.$this->language->get('text_view'),
				'managesalary/history/pdf' => $this->language->get('text_payment_history').' '.$this->language->get('text_PDF'),
				'managesalary/history/delete' => $this->language->get('text_payment_history').' '.$this->language->get('text_delete'),
				'2' => '',),
			array('salarytemplate' => $this->language->get('text_salary_template'),
				'salarytemplate/add' => $this->language->get('text_salary_template').' '.$this->language->get('text_add'),
				'salarytemplate/edit' => $this->language->get('text_salary_template').' '.$this->language->get('text_edit'),
				'salarytemplate/delete' => $this->language->get('text_salary_template').' '.$this->language->get('text_delete'),
				'1' => ''),
			array('staffattendance' => $this->language->get('text_attendance'),
				'staffattendance/add' => $this->language->get('text_attendance').' '.$this->language->get('text_add'),
				'staffattendance/view' => $this->language->get('text_attendance').' '.$this->language->get('text_view'),
				'1' => '',
				'2' => ''),
			array('tickets' => $this->language->get('text_ticket_list'),
				'ticket/add' => $this->language->get('text_ticket_add'),
				'ticket/edit' => $this->language->get('text_ticket_edit'),
				'ticket/delete' => $this->language->get('text_ticket_delete'),
				'' => ''),
			array('noticeboard' => $this->language->get('text_noticeboard'),
				'noticeboard/add' => $this->language->get('text_noticeboard').' '.$this->language->get('text_add'),
				'noticeboard/edit' => $this->language->get('text_noticeboard').' '.$this->language->get('text_edit'),
				'noticeboard/delete' => $this->language->get('text_noticeboard').' '.$this->language->get('text_delete'),
				'noticeboard/view' => $this->language->get('text_noticeboard').' '.$this->language->get('text_view')),
			array('domains' => $this->language->get('text_domain_list'),
				'domain/add' => $this->language->get('text_domain_add'),
				'domain/edit' => $this->language->get('text_domain_edit'),
				'domain/delete' => $this->language->get('text_domain_delete'),
				'' => ''),
			array('expenses' => $this->language->get('text_expense_list'),
				'expense/add' => $this->language->get('text_expense_add'),
				'expense/edit' => $this->language->get('text_expense_edit'),
				'expense/delete' => $this->language->get('text_expense_delete'),
				'' => ''),
			array('calendar' => $this->language->get('text_calendar'),
				'calendar/add' => $this->language->get('text_calendar').' '.$this->language->get('text_add'),
				'calendar/edit' => $this->language->get('text_calendar').' '.$this->language->get('text_edit'),
				'calendar/delete' => $this->language->get('text_calendar').' '.$this->language->get('text_delete'),
				'' => ''),
			array('notes' => $this->language->get('text_notes'),
				'note/add' => $this->language->get('text_notes').' '.$this->language->get('text_add'),
				'note/edit' => $this->language->get('text_notes').' '.$this->language->get('text_edit'),
				'note/delete' => $this->language->get('text_notes').' '.$this->language->get('text_delete'),
				'1' => ''),
			array('user' => $this->language->get('text_user_list'),
				'user/add' => $this->language->get('text_user_add'),
				'user/edit' => $this->language->get('text_user_edit'),
				'user/delete' => $this->language->get('text_user_delete'),
				'' => ''),
			array('subscriber' => $this->language->get('text_subscriber_list'),
				'subscriber/add' => $this->language->get('text_subscriber_add'),
				'subscriber/edit' => $this->language->get('text_subscriber_edit'),
				'subscriber/delete' => $this->language->get('text_subscriber_delete'),
				'' => ''),
			array('info' => $this->language->get('text_organisation_info'),
				'cronsetting' => $this->language->get('text_cron_setting'),
				'cronlog' => $this->language->get('text_cron_log'),
				'emaillog' => $this->language->get('text_email_log'),
				'4' => ''),
			array('taxes' => $this->language->get('text_taxes'),
				'tax/add' => $this->language->get('text_tax').' '.$this->language->get('text_add'),
				'tax/edit' => $this->language->get('text_tax').' '.$this->language->get('text_edit'),
				'tax/delete' => $this->language->get('text_tax').' '.$this->language->get('text_delete'),
				'1' => ''),
			array('currency' => $this->language->get('text_currency'),
				'currency/add' => $this->language->get('text_currency').' '.$this->language->get('text_add'),
				'currency/edit' => $this->language->get('text_currency').' '.$this->language->get('text_edit'),
				'currency/delete' => $this->language->get('text_currency').' '.$this->language->get('text_delete'),
				'1' => ''),
			array('paymentmethod' => $this->language->get('text_payment_method'),
				'paymentmethod/add' => $this->language->get('text_payment_method').' '.$this->language->get('text_add'),
				'paymentmethod/edit' => $this->language->get('text_payment_method').' '.$this->language->get('text_edit'),
				'paymentmethod/delete' => $this->language->get('text_payment_method').' '.$this->language->get('text_delete'),
				'1' => ''),
			array('paypalgateway' => $this->language->get('text_paypal_gateway'),
				'1' => '',
				'2' => '',
				'3' => '',
				'4' => ''),
			array('departments' => $this->language->get('text_departments'),
				'department/add' => $this->language->get('text_department').' '.$this->language->get('text_add'),
				'department/edit' => $this->language->get('text_department').' '.$this->language->get('text_edit'),
				'department/delete' => $this->language->get('text_department').' '.$this->language->get('text_delete'),
				'1' => ''),
			array('expensetype' => $this->language->get('text_expense_types'),
				'expensetype/add' => $this->language->get('text_expense_types').' '.$this->language->get('text_add'),
				'expensetype/edit' => $this->language->get('text_expense_types').' '.$this->language->get('text_edit'),
				'expensetype/delete' => $this->language->get('text_expense_types').' '.$this->language->get('text_delete'),
				'1' => ''),
			array('items' => $this->language->get('text_items'),
				'item/add' => $this->language->get('text_items').' '.$this->language->get('text_add'),
				'item/edit' => $this->language->get('text_items').' '.$this->language->get('text_edit'),
				'item/delete' => $this->language->get('text_items').' '.$this->language->get('text_delete'),
				'1' => ''),
			array('send/email' => $this->language->get('text_send_email'),
				'sendbulk/email' => $this->language->get('text_send_bulk_email'),
				'emailsettings' => $this->language->get('text_email_settings'),
				'emailtemplate' => $this->language->get('text_email_template'),
				'1' => ''),
			array('get/media' => $this->language->get('text_media'),
				'media/upload' => $this->language->get('text_media').' '.$this->language->get('text_upload'),
				'media/delete' => $this->language->get('text_media').' '.$this->language->get('text_delete'),
				'1' => '',
				'2' => ''));
		//
		return $role;
	}
}