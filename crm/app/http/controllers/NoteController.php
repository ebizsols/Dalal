<?php

/**
* NoteController
*/
class NoteController extends Controller
{
	/**
	* Note index method
	* This method will be called on Note list view
	**/
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('notes', 'notes');

		/**
		* Get all Notes from DB using Note model 
		**/
		$this->load->model('note');
		if ($data['common']['user']['role_id'] == '1') {
			$data['result'] = $this->model_note->getNotes();
		} else {
			$data['result'] = $this->model_note->getUserNotes($data['common']['user']['user_id']);
		}
		
		/* Set page title */
		$data['page_title'] = $this->language->get('text_notes');
		$data['page_add'] = $this->user_agent->hasPermission('note/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('note/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('note/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'note/delete';
		$data['page_active'] = 'note';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('note/note_list', $data));
	}
	/**
	* Note index ADD method
	* This method will be called on Note ADD view
	**/
	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$data['lang']['notes'] = $this->language->load('notes', 'notes');

		/**
		* Get all User data from DB using User model 
		**/
		$data['result'] = NULL;
		
		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_note');
		$data['action'] = URL.DIR_ROUTE.'note/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'note';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('note/note_form', $data));
	}
	/**
	* Note index Edit method
	* This method will be called on Note Edit view
	**/
	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('notes');
		}
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('note');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_note->getUserNote($id, $data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_note->getNote($id);
		}

		if (empty($data['result'])) {
			$this->url->redirect('notes');
		}

		/*Load Language File*/
		$this->language->load('notes', 'notes');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_note');
		$data['action'] = URL.DIR_ROUTE.'note/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'note';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('note/note_form', $data));
	}
	/**
	* Note index Action method
	* This method will be called on Note Submit or Save view
	**/
	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('notes');
			exit();
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$data = $this->url->post('note');
		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('notes');
		}

		$data['datetime'] = date('Y-m-d H:i:s');
		$data['user_id'] = $this->session->data['user_id'];
		$this->load->model('note');
		if (!empty($data['id'])) {
			$result = $this->model_note->updateNote($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Note updated successfully.');
		} else {
			$result = $this->model_note->createNote($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Note created successfully.');
		}
		$this->url->redirect('notes');
	}
	/**
	* Note index Delete method
	* This method will be called on Note Delete view
	**/
	public function indexDelete()
	{
		$this->load->model('note');
		$result = $this->model_note->deleteNote($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Note deleted successfully.');
		$this->url->redirect('notes');
	}
	/**
	* Valdiate method
	* This method will be called to validate input field
	**/
	public function validateField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateText($data['title'])) {
			$error_flag = true;
			$error['author'] = 'Note Title!';
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

}