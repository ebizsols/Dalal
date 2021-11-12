<?php

/**
* SettingController
*/
class SettingController extends Controller
{
	/**
	* Info index method
	* This method will be called on Info edit view
	**/
	public function siteInfo()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('settings', 'settings');

		/**
		* Get all info data from DB using info model's method
		**/
		$this->load->model('setting');
		$data['result'] = $this->model_setting->getInfo();
		$data['result'] = json_decode($data['result'], true);
		
		$this->load->controller('commons');
		$data['timezone'] = $this->controller_commons->getTimezones();
		
		$data['page_title'] = $this->language->get('text_organisation_info');
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		/*Set action method for form submit call*/
		$data['action'] = URL.DIR_ROUTE.'info';
		$data['page_active'] = 'setting';

		$data['lang']= $this->language->all();
		/*Render Info view*/
		$this->response->setOutput($this->load->view('setting/info', $data));
	}
	/**
	* Info index action method
	* This method will be called on Info submit/save view
	**/
	public function siteInfoAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('info');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$data = $this->url->post('info');
		$this->load->controller('commons');
		if ($validate_field = $this->validateInfoField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('info');
		}

		$this->load->model('setting');
		$result = $this->model_setting->updateInfo(json_encode($data));
		
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Info updated successfully.');
		$this->url->redirect('info');
	}

	protected function validateInfoField($data)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateText($data['name'])) {
			$error_flag = true;
			$error['name'] = 'Name';
		}

		if ($this->controller_commons->validateEmail($data['email'])) {
			$error_flag = true;
			$error['email'] = 'Email Address';
		}

		if ($this->controller_commons->validateText($data['phone'])) {
			$error_flag = true;
			$error['mobile'] = 'Phone number';
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	public function customization()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$this->language->load('info', 'info');
		/**
		* Get all info data from DB using info model's method
		**/
		$this->load->model('setting');

		$data['result'] = json_decode($this->model_setting->getCustomization(), true);
		
		$data['page_title'] = $this->language->get('text_theme_customization');
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		/*Set action method for form submit call*/
		$data['action'] = URL.DIR_ROUTE.'customization';
		$data['page_active'] = 'customization';

		$data['lang']= $this->language->all();
		/*Render Info view*/
		$this->response->setOutput($this->load->view('setting/customization', $data));
	}

	public function customizationAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('customization');
		}

		$data = $this->url->post;
		$data['layout_menu'] = isset($data['layout_menu']) ? $data['layout_menu'] : false;
		$data['sidemenu'] = isset($data['sidemenu']) ? $data['sidemenu'] : false;
		unset($data['_token']);
		$this->load->model('setting');
		$result = $this->model_setting->updateCustomization(json_encode($data));
		
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Customization updated successfully.');
		$this->url->redirect('customization');
	}
	
	public function cronSetting()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('settings', 'settings');

		$name = $this->url->get('page');
		$this->load->model('setting');
		$result = $this->model_setting->getCronSettings();

		$data['result']['data'] = json_decode($result['data'], true);
		$data['result']['status'] = $result['status'];

		/* Set Page title and action */
		$data['page_title'] = $this->language->get('text_cron_automation_setting');
		$data['action'] = URL.DIR_ROUTE.'cronsetting';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'setting';
		
		$data['lang']= $this->language->all();
		/*Render Calendar list view*/
		$this->response->setOutput($this->load->view('setting/cronsetting', $data));
	}

	public function cronSettingAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('dashboard');
		}

		$data['setting'] = $this->url->post('name');
		
		$this->load->controller('commons');
		$this->load->model('setting');

		$data['data'] = json_encode(rand(10000000,999999999));
		$data['status'] = 1;
		$result = $this->model_setting->updateSetting($data);
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Setting updated successfully.');
		$this->url->redirect('cronsetting');
	}
}