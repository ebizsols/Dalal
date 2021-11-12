<?php

/**
* ItemController
*/
class ItemsController extends Controller
{
	/**
	* Item index method
	* This method will be called on Items list view
	**/
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('settings', 'settings');

		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('items');
		$data['result'] = $this->model_items->getItems();

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		/* Set page title */
		$data['page_title'] = $this->language->get('text_items');
		$data['page_add'] = $this->user_agent->hasPermission('item/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('item/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('item/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'item/delete';
		$data['page_active'] = 'setting';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('items/items_list', $data));
	}
	/**
	* Item index ADD method
	* This method will be called on Item ADD view
	**/
	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('settings', 'settings');

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		$this->load->model('items');
		$data['result'] = NULL;
		$data['currency'] = $this->model_commons->getCurrencies();
		
		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_item');
		$data['action'] = URL.DIR_ROUTE.'item/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'setting';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('items/items_form', $data));
	}
	/**
	* Item index Edit method
	* This method will be called on Item Edit view
	**/
	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('items');
		}
		/*Get User name and role*/
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('settings', 'settings');

		$this->load->model('items');
		$data['result'] = $this->model_items->getItem($id);
		
		if (empty($data['result'])) {
			$this->url->redirect('items');
		}

		$data['currency'] = $this->model_commons->getCurrencies();
		
		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_item');
		$data['action'] = URL.DIR_ROUTE.'item/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'setting';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('items/items_form', $data));
	}
	/**
	* Item index Action method
	* This method will be called on Item Save or Update view
	**/
	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('items');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$data = $this->url->post;
		$this->load->controller('commons');
		if ($validate_field = $this->validateField()) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($this->url->post('id'))) {
				$this->url->redirect('item/edit&id='.$this->url->post('id'));
			} else {
				$this->url->redirect('item/add');
			}
		}
		
		$data['datetime'] = date('Y-m-d H:i:s');
		$this->load->model('items');
		if (!empty($this->url->post('id'))) {
			$result = $this->model_items->updateItem($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Item updated successfully.');
		}
		else {
			$result = $this->model_items->createItem($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Item created successfully.');
		}
		$this->url->redirect('items');
	}
	/**
	* Item index Delete method
	* This method will be called on Item Delete view
	**/
	public function indexDelete()
	{
		$this->load->model('items');
		$result = $this->model_items->deleteItem($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Item deleted successfully.');
		$this->url->redirect('items');
	}

	public function indexItemSearch()
	{
		$data = $this->url->get;
		$this->load->model('items');
		$result = $this->model_items->getSearchedItems($data['term']);
		echo json_encode($result);
	}
	/**
	* Item validate method
	* This method will be called to validate input field 
	**/
	public function validateField()
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateNumeric($this->url->post('type'))) {
			$error_flag = true;
			$error['author'] = 'Item Type!';
		}
		if ($this->controller_commons->validateText($this->url->post('name'))) {
			$error_flag = true;
			$error['title'] = 'Item Name!';
		}

		if ($this->controller_commons->validateNumeric($this->url->post('price'))) {
			$error_flag = true;
			$error['author'] = 'Item Rate!';
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}