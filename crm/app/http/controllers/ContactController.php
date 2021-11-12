<?php

/**
* ContactController
*/
class ContactController extends Controller
{
	/**
	* Contact index method
	* This method will be called on Contact list view
	**/
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('contact');
		if ($data['common']['user']['role_id'] != '1') {
			$data['admin'] = false;
			$data['result'] = $this->model_contact->getContacts($data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['result'] = $this->model_contact->getContacts();
		}
		/*Load Language File*/
		$this->language->load('contact', 'contact');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_contacts');
		$data['page_import'] = $this->user_agent->hasPermission('contact/import') ? true : false;
		$data['page_view'] = $this->user_agent->hasPermission('contact/view') ? true : false;
		$data['page_add'] = $this->user_agent->hasPermission('contact/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('contact/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('contact/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'contact/delete';
		$data['page_active'] = 'contact';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('contact/contact_list', $data));
	}
	/**
	* Contact View method
	* This method will be called on Contact View view
	**/
	public function indexView()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) { 
			$this->url->redirect('contacts');
		}
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('contact');
		if ($data['common']['user']['role_id'] != '1') {
			$data['admin'] = false;
			$data['result'] = $this->model_contact->getContact($id, $data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['result'] = $this->model_contact->getContact($id);
		}

		if (empty($data['result'])) {
			$this->url->redirect('contacts');
		}

		$data['client'] = $this->model_contact->getClient($data['result']['email']);
		$data['invoices'] = $this->model_contact->getInvoices($id);
		$data['quotes'] = $this->model_contact->getQuotes($id);
		if (!empty($data['client'])) {
			$data['clientactivity'] = $this->model_contact->getClientActivity($id);
		}
		
		$data['result']['marketing'] = json_decode($data['result']['marketing'], true);
		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['persons'] = json_decode($data['result']['persons'], true);

		/*Load Language File*/
		$this->language->load('contact', 'contact');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_view_contact');
		$data['page_edit'] = $this->user_agent->hasPermission('contact/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('contact/delete') ? true : false;
		$data['page_sendmail'] = $this->user_agent->hasPermission('contact/sendmail') ? true : false;
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'contact';

		$data['lang']= $this->language->all();

		/*Render User list view*/
		$this->response->setOutput($this->load->view('contact/contact_view', $data));
	}
	/**
	* Contact index ADD method
	* This method will be called on ADD page
	**/
	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$data['result'] = NULL;
		$this->load->model('contact');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
		} else {
			$data['admin'] = true;
			$data['staff'] = $this->model_commons->getStaff();
		}

		/*Load Language File*/
		$this->language->load('contact', 'contact');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_new').' '.$this->language->get('text_contact');
		$data['action'] = URL.DIR_ROUTE.'contact/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'contact';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('contact/contact_form', $data));
	}
	/**
	* Contact index Edit method
	* This method will be called on Contact Edit view
	**/
	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('contacts');
		}
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('contact');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
			$data['result'] = $this->model_contact->getContact($id, $data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['result'] = $this->model_contact->getContact($id);
		}

		if (empty($data['result'])) {
			$this->url->redirect('contacts');
		}
		
		$data['client'] = $this->model_contact->getClient($data['result']['email']);
		$data['invoices'] = $this->model_contact->getInvoices($id);
		$data['quotes'] = $this->model_contact->getQuotes($id);
		$data['staff'] = $this->model_commons->getStaff();
		
		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['persons'] = json_decode($data['result']['persons'], true);
		$data['result']['marketing'] = json_decode($data['result']['marketing'], true);

		/*Load Language File*/
		$this->language->load('contact', 'contact');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit').' '.$this->language->get('text_contact');
		$data['action'] = URL.DIR_ROUTE.'contact/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'contact';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('contact/contact_form', $data));
	}
	/**
	* Contact index Import method
	* This method will be called on Contact Import
	**/
	public function indexImport()
	{
		$filename = $_FILES["file"]["tmp_name"];
		$data = array();
		$row = 0;
		$expire = date('Y-m-d', strtotime('+1 years'));
		$this->load->model('contact');
		if($_FILES["file"]["size"] > 0) {
			$file = fopen($filename, "r");
			while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
				if ($row == 0 && $getData[0] !== 'Salutation' && $getData[1] !== 'First Name' && $getData[2] !== 'Last Name' && $getData[3] !== 'Company' && $getData[4] !== 'Email Address' && $getData[5] !== 'Phone Number' && $getData[6] !== 'Website' && $getData[7] !== 'Address Line 1' && $getData[8] !== 'Address Line 2' && $getData[9] !== 'City' && $getData[10] !== 'State' && $getData[11] !== 'Country' && $getData[12] !== 'Postal Code') {
					echo 0;
					exit();
				}
				if ($row > 0) {
					$temp = array('address1' => $getData[7],'address2' => $getData[8],'city' => $getData[9],'state' => $getData[10],'country' => $getData[11],'pin' => $getData[12]);

					$data['salutation'] = $getData[0];
					$data['firstname'] = $getData[1];
					$data['lastname'] = $getData[2];
					$data['company'] = $getData[3];
					$data['email'] = $getData[4];
					$data['phone'] = $getData[5];
					$data['website'] = $getData[6];
					$data['address'] = json_encode($temp);
					$data['country'] = $getData[11];
					$data['expire'] = date('Y-m-d', strtotime('+1 years'));
					$data['datetime'] = date('Y-m-d');
					$data['user_id'] = $this->session->data['user_id'];
					$result = $this->model_contact->importContact($data);
				}
				$row++;
			}
			fclose($file);
		}
		
		if ($result) { echo 1; }
		else { echo 0; }
	}

	public function indexSmapleDownload()
	{
		if(file_exists(DIR.'public/uploads/contact_sample.csv')){
			$filepath = "public/uploads/contact_sample.csv";
			
			if(file_exists($filepath)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($filepath));
				flush();
				readfile($filepath);
				echo "<script>window.close();</script>";
				exit;
			}
		} else {
			echo "<script>window.close();</script>";
			exit;
		}
	}
	/**
	* Contact Search method
	* This method will be called on Contact Search
	**/
	public function indexContactSearch()
	{
		$this->load->model('commons');
		$common = $this->model_commons->getCommonData($this->session->data['user_id']);
		$data = $this->url->get;

		$this->load->model('contact');
		if ($common['user']['role_id'] != '1') {
			$result = $this->model_contact->getSearchedContact($data['term'], $common['user']['user_id']);
			// $data['result'] = $this->model_contact->getContacts($common['user']['user_id']);
		} else {
			$result = $this->model_contact->getSearchedContact($data['term']);
		}		
		echo json_encode($result);
	}
	/**
	* Contact index method
	* This method will be called on Contact ADD or Update view
	**/
	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('contacts');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$data = $this->url->post('contact');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();

		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['id'])) {
				$this->url->redirect('contact/edit&id='.$data['id']);
			} else {
				$this->url->redirect('contact/add');
			}
		}
		
		$data['country'] = $data['address']['country'];
		$data['address'] = json_encode($data['address']);
		if (!empty($data['person'][0]['name'])) {
			$data['person'] = json_encode($data['person']);	
		} else {
			$data['person'] = json_encode(array());
		}
		
		$data['marketing']['email'] = isset($data['marketing']['email']) ? $data['marketing']['email']: false;
		$data['marketing']['phone'] = isset($data['marketing']['phone']) ? $data['marketing']['phone']: false;
		$data['marketing']['sms'] = isset($data['marketing']['sms']) ? $data['marketing']['sms']: false;
		$data['marketing']['social'] = isset($data['marketing']['social']) ? $data['marketing']['social']: false;
		$data['marketing'] = json_encode($data['marketing']);

		$data['expire'] = DateTime::createFromFormat($data['info']['date_format'], $data['expire'])->format('Y-m-d');
		$data['datetime'] = date('Y-m-d H:i:s');
		$this->load->model('contact');
		if (!empty($data['id'])) {
			$data['client'] = $this->url->post('client');
			$result = $this->model_contact->updateContact($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Contact updated successfully.');
			
		} else {
			$data['id'] = $this->model_contact->createContact($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Contact created successfully.');
		}
		$this->url->redirect('contact/view&id='.$data['id']);
	}
	/**
	* Contact index Delete method
	* This method will be called on Contact Delete view
	**/
	public function indexDelete()
	{
		$this->load->model('contact');
		$data = $this->url->post;

		$data['all_data'] = !empty($data['all_data']) ? 1 : false;
		
		$result = $this->model_contact->deleteContact($data);
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Contact deleted successfully.');
		$this->url->redirect('contacts');
	}
	/**
	* Contact index Mail method
	* This method will be called on Contact Mail
	**/
	public function indexMail()
	{
		$data = $this->url->post;
		
		if (!isset($_POST['submit'])) {
			$this->url->redirect('contacts');
		}

		$data = $this->url->post;

		$this->load->controller('commons');
		$this->load->model('contact');
		
		$result = $this->model_contact->getContact($data['mail']['contact']);
		if (empty($result)) {
			$this->url->redirect('contacts');
		}

		$data['mail']['email'] = $result['email'];
		$data['mail']['name'] = $result['company'];
		$data['mail']['bcc'] = $data['mail']['bcc'];

		$this->load->controller('Mail');
		$mail_result = $this->controller_mail->sendmail($data['mail']);
		
		if ($mail_result == 1) {
			$data['mail']['type'] = 'contact';
			$data['mail']['type_id'] = $data['mail']['contact'];
			$data['mail']['user_id'] = $this->session->data['user_id'];
			
			$this->controller_mail->createMailLog($data['mail']);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Success: Message sent successfully.');
			$this->url->redirect('contact/view&id='.$result['id']);
		} else {
			$this->session->data['message'] = array('alert' => 'error', 'value' => $mail_result);
			$this->url->redirect('contact/view&id='.$result['id']);
		}
	}
	/**
	* Validate Field Method
	* This method will be called on to validate invoice field
	**/
	private function vaildateMailField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->commons->validateText($data['to'])) {
			$error_flag = true;
			$error['to'] = 'Email!';
		}

		if ($this->commons->validateText($data['subject'])) {
			$error_flag = true;
			$error['subject'] = 'Subject!';
		}

		if ($this->commons->validateText($data['message'])) {
			$error_flag = true;
			$error['message'] = 'Message!';
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
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