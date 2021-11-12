<?php

/**
* ProjectController
*/
class ProjectController extends Controller
{
	/**
	* Project index method
	* This method will be called on Project list view
	**/
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('project');
		if ($data['common']['user']['role_id'] != '1') {
			$data['result'] = $this->model_project->getProjects($data['common']['user']['user_id']);
		} else {
			$data['result'] = $this->model_project->getProjects();
		}

		/*Load Language File*/
		$this->language->load('project', 'project');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_projects');
		$data['page_view'] = $this->user_agent->hasPermission('project/view') ? true : false;
		$data['page_add'] = $this->user_agent->hasPermission('project/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('project/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('project/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'project/delete';
		$data['page_active'] = 'project';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('project/project_list', $data));
	}
	/**
	* Project index view method
	* This method will be called on Project view
	**/
	public function indexView()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('projects');
		}
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('project');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
			$data['customers'] = $this->model_project->getCustomers($data['common']['user']['user_id']);
			$data['result'] = $this->model_project->getProjectView($id, $data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['customers'] = $this->model_project->getCustomers();
			$data['result'] = $this->model_project->getProjectView($id);
		}
		
		if (empty($data['result'])) {
			$this->url->redirect('projects');
		}

		$data['result']['staff'] = json_decode($data['result']['staff'], true);
		$data['result']['task'] = json_decode($data['result']['task'], true);
		$data['comments'] = $this->model_project->getComments($id);
		$data['documents'] = $this->model_project->getDocuments($id);
		$data['staff'] = $this->model_project->getStaff();

		/*Load Language File*/
		$this->language->load('project', 'project');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_view_project');
		$data['page_edit'] = $this->user_agent->hasPermission('project/edit') ? true : false;
		$data['page_documents'] = $this->user_agent->hasPermission('project/documents') ? true : false;
		$data['page_comments'] = $this->user_agent->hasPermission('project/comment') ? true : false;
		$data['action'] = URL.DIR_ROUTE.'project/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'project';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('project/project_view', $data));
	}
	/**
	* Project index ADD method
	* This method will be called on Project Add view
	**/
	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/

		$this->load->model('project');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['customers'] = $this->model_commons->getCustomers($data['common']['user']['user_id']);
		} else {
			$data['customers'] = $this->model_commons->getCustomers();
		}

		$data['result'] = NULL;
		$data['currency'] = $this->model_commons->getCurrencies();
		$data['staff'] = $this->model_commons->getStaff();

		/*Load Language File*/
		$this->language->load('project', 'project');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_project');
		$data['action'] = URL.DIR_ROUTE.'project/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'project';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('project/project_form', $data));
	}
	/**
	* Project index Edit method
	* This method will be called on Project Edit view
	**/
	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('projects');
		}
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using User model 
		**/
		$this->load->model('project');
		if ($data['common']['user']['role_id'] != '1') {
			$data['user']['user_id'] = $this->session->data['user_id'];
			$data['admin'] = false;
			$data['result'] = $this->model_project->getProject($id, $data['common']['user']['user_id']);
			$data['customers'] = $this->model_commons->getCustomers($data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['result'] = $this->model_project->getProject($id);
			$data['customers'] = $this->model_commons->getCustomers();
		}

		if (empty($data['result'])) {
			$this->url->redirect('projects');
		}
		$data['currency'] = $this->model_commons->getCurrencies();
		$data['staff'] = $this->model_commons->getStaff();
		$data['comments'] = $this->model_project->getComments($id);
		$data['documents'] = $this->model_project->getDocuments($id);

		$data['result']['staff'] = json_decode($data['result']['staff'], true);
		$data['result']['task'] = json_decode($data['result']['task'], true);

		/*Load Language File*/
		$this->language->load('project', 'project');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit').' '.$this->language->get('text_project');
		$data['action'] = URL.DIR_ROUTE.'project/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'project';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('project/project_form', $data));
	}
	/**
	* Project index Action method
	* This method will be called on Project Submit or update
	**/
	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('projects');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/

		$data = $this->url->post('project');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		
		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['id'])) {
				$this->url->redirect('project/edit&id='.$data['id']);
			} else {
				$this->url->redirect('project/add');
			}
		}

		$data['staff'] = json_encode($data['staff']);
		$data['task'] = json_encode($data['task']);
		$data['start_date'] = DateTime::createFromFormat($data['info']['date_format'], $data['start_date'])->format('Y-m-d');
		$data['due_date'] = DateTime::createFromFormat($data['info']['date_format'], $data['due_date'])->format('Y-m-d');
		$data['datetime'] = date('Y-m-d H:i:s');

		$this->load->model('project');
		if (!empty($data['id'])) {
			$this->model_project->updateProject($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Project updated successfully.');
		}
		else {
			$data['id'] = $this->model_project->createProject($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Project created successfully.');
		}
		$this->url->redirect('project/view&id='.$data['id']);
	}
	/**
	* Project make Comment Method
	* This method will be called to add comment on project
	**/
	public function makeComment()
	{
		$this->load->controller('commons');
		if ($this->controller_commons->validateText($this->url->post('comment'))) {
			echo 1;
		}

		$data = $this->url->post;
		$data['comment_by'] = $this->session->data['user_id'];
		
		$this->load->model('project');
		$result = $this->model_project->createComment($data);
		echo $result;
	}
	/**
	* Project index Delete method
	* This method will be called on Project Delete view
	**/
	public function indexDelete()
	{
		$this->load->model('project');
		$result = $this->model_project->deleteProject($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Project deleted successfully.');
		$this->url->redirect('projects');
	}
	/**
	 * Validate input field
	 * @return [boolean] [true][false]
	 */
	public function validateField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateText($data['name'])) {
			$error_flag = true;
			$error['author'] = 'Project name!';
		}
		if (!empty($data['start_date'])) {
			if ($this->controller_commons->validateDate($data['start_date'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['start_date'] = 'Start Date!';
			}
		}
		if (!empty($data['due_date'])) {
			if ($this->controller_commons->validateDate($data['due_date'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['due_date'] = 'Due Date!';
			}
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}