<?php

/**
* Subscriber Controller
*/
class SubscriberController extends Controller
{
	/**
	* Subscriber index edit method
	* This method will be called on Subscriber edit view
	**/
	public function index()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all Subscribers data from DB using Subscriber model 
		**/
		$this->load->model('subscriber');
		$data['result'] = $this->model_subscriber->allSubscribers();
		
		/*Load Language File*/
		$this->language->load('users', 'users');
		
		/* Set page title */
		$data['page_title'] = $this->language->get('text_subscribers');
		$data['page_add'] = $this->user_agent->hasPermission('subscriber/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('subscriber/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('subscriber/delete') ? true : false;

		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action_delete'] = URL.DIR_ROUTE.'subscriber/delete';
		$data['page_active'] = 'user';

		$data['lang']= $this->language->all();
		/*call Subscriber list view*/
		$this->response->setOutput($this->load->view('subscriber/subscriber_list', $data));
	}
	/**
	* Subscriber index add method
	* This method will be called on Subscriber add
	**/
	public function indexAdd()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/* Set empty data to array */
		$data['result'] =  NULL;
		
		/*Load Language File*/
		$this->language->load('users', 'users');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_subscriber');
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'subscriber/add';
		$data['page_active'] = 'user';

		$data['lang']= $this->language->all();
		/*Render Blog add view*/
		$this->response->setOutput($this->load->view('subscriber/subscribe_form', $data));
	}
	/**
	* Subscriber index edit method
	* This method will be called on Subscriber edit view
	**/
	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Subscriber list view 
		**/
		$id = (int)$this->url->get('id');
		if ( empty($id) || !is_int($id) ) {
			$this->url->redirect('subscribers');
		}
		/**
		* Call getSubscriber method from Subscriber model to get data from DB for single Subscriber
		* If Subscriber does not exist then redirect it to Subscriber list view
		**/
		$this->load->model('subscriber');
		$subscriber = $this->model_subscriber->getSubscriber($id);
		if (!$subscriber) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Subscriber does not exist in database!');
			$this->url->redirect('subscribers');
		}
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/* Set Edit Subscriber page title */
		$data['result'] = $subscriber;

		/*Load Language File*/
		$this->language->load('users', 'users');

		$data['page_title'] = $this->language->get('text_edit_subscriber');
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'subscriber/edit';
		$data['page_active'] = 'user';

		$data['lang']= $this->language->all();
		/*Render Subscriber edit view*/
		$this->response->setOutput($this->load->view('subscriber/subscribe_form', $data));
	}
	/**
	* Info index action method
	* This method will be called on subscriber submit/save
	**/
	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('subscribers');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$data = $this->url->post;
		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data['subscriber'])) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['subscriber']['id'])) {
				$this->url->redirect('subscriber/edit&id='.$data['subscriber']['id']);
			} else {
				$this->url->redirect('subscriber/add');
			}
		}

		$data['subscriber']['datetime'] = date('Y-m-d H:i:s');
		$this->load->model('subscriber');
		if (!empty($data['subscriber']['id'])) {
			$this->model_subscriber->updateSubscriber($data['subscriber']);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Subscriber updated successfully.');
			$this->url->redirect('subscriber/edit&id='.$data['subscriber']['id']);
		} else {
			$result = $this->model_subscriber->createSubscriber($data['subscriber']);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Subscriber created successfully.');
			$this->url->redirect('subscriber/edit&id='.$result);
		}
	}
	/**
	* Blog index delete method
	* This method will be called on blog delete action
	**/
	public function indexDelete()
	{
		$this->load->model('subscriber');
		$result = $this->model_subscriber->deleteSubscriber($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Subscriber deleted successfully.');
		$this->url->redirect('subscriber');
	}

	protected function validateField($data)
	{
		$error = [];
		$error_flag = false;
		if ($email = $this->controller_commons->validateEmail($data['mail'])) {
			$error_flag = true;
			$error['email'] = 'Email Address';
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}