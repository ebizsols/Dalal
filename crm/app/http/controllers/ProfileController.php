<?php

/**
* Profile Controller
*/
class ProfileController extends Controller
{

	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		
		$this->load->model('profile');
		$data['result'] = $this->model_profile->getProfile($this->session->data['user_id']);
		
		$data['page_title'] = $this->language->get('text_my_profile');
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = '';

		$data['lang']= $this->language->all();
		/*call profile view*/
		$this->response->setOutput($this->load->view('profile/profile', $data));
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
			$this->url->redirect('profile');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$this->load->controller('commons');
		if ($validate_field = $this->validateField()) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('profile');
		}

		$this->load->model('profile');
		$data = $this->url->post;
		$data['user_id'] = $this->session->data['user_id'];

		$check_user = $this->model_profile->checkUserName($this->url->post('username'), $this->url->post('email'));

		if ($check_user >= 1) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => "User Name ".$data['username']." already exist in database.");
			$this->url->redirect('profile');
		}
		
		if ($this->model_profile->updateProfile($data)) {
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Profile updated successfully.');
			$this->url->redirect('profile');
		} else {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Profile does not updated (Server Error).');
			$this->url->redirect('profile');
		}
	}

	public function indexPassword()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('profile');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$this->load->controller('commons');
		if ($validate_field = $this->validatePasswordField()) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => implode(", ",$validate_field).'!');
			$this->url->redirect('profile');
		}

		$this->load->model('profile');

		$data['user_id'] = $this->url->post('id');
		$data['old_password'] = $this->url->post('old-password');
		$data['new_password'] = $this->url->post('new-password');

		$password = $this->model_profile->getUserData($data['user_id']);

		if (password_verify( $data['old_password'], $password)) {
			$data['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
			$result = $this->model_profile->updatePassword($data);
			if ($result) {
				$this->session->data['message'] = array('alert' => 'success', 'value' => 'Account Password updated successfully.');
				$this->url->redirect('profile');
			} else {
				$this->session->data['message'] = array('alert' => 'error', 'value' => 'Account Password does not updated(Server Error).');
				$this->url->redirect('profile');
			}
		} else {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Current Password is not correct.');
			$this->url->redirect('profile');
		}
	}

	protected function validateField()
	{
		$error = [];
		$error_flag = false;
		if ($this->controller_commons->validateText($this->url->post('username'))) {
			$error_flag = true;
			$error['username'] = 'User name!';
		}

		if ($this->controller_commons->validateText($this->url->post('firstname'))) {
			$error_flag = true;
			$error['firstname'] = 'First Name';
		}

		if ($this->controller_commons->validateText($this->url->post('lastname'))) {
			$error_flag = true;
			$error['lastname'] = 'Last Name';
		}

		if ($this->controller_commons->validateEmail($this->url->post('email')) ) {
			$error_flag = true;
			$error['email'] = 'Email Address';
		}

		if ($this->controller_commons->validatePhoneNumber($this->url->post('mobile')) ) {
			$error_flag = true;
			$error['mobile'] = 'Mobile Number';
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	protected function validatePasswordField()
	{
		$error = [];
		$error_flag = false;
		if (strlen($this->url->post('old-password')) < 6) {
			$error_flag = true;
			$error['username'] = 'Please enter minimum 6 letters for Old Password';
		}

		if (strlen($this->url->post('new-password')) < 6) {
			$error_flag = true;
			$error['firstname'] = 'Please enter minimum 6 letters for New Password';
		}

		if (strlen($this->url->post('confirm-password')) < 6) {
			$error_flag = true;
			$error['lastname'] = 'Please enter minimum 6 letters for Confirm Password';
		}

		if ($this->url->post('confirm-password') != $this->url->post('new-password')) {
			$error_flag = true;
			$error['match'] = 'Confirm Password does not match with new password';
		}

		if ($error_flag) {
			return $error;
		}
		else {
			return false;
		}
	}
}