<?php

/**
* Profile Controller
*/
class ProfileController extends Controller
{
	public function index()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['customer']);
		
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$this->load->model('profile');
		$data['result'] = $this->model_profile->getCustomer($this->session->data['customer']);

		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['marketing'] = json_decode($data['result']['marketing'], true);

		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('contact', 'contact');
		$data['page_title'] = $this->language->get('text_my_account');
		$data['action'] = URL_CLIENTS.DIR_ROUTE.'profile';

		$data['lang']= $this->language->all();
		$this->response->setOutput($this->load->view('profile/profile', $data));
	}

	/**
	* Info index action method
	* This method will be called on Info submit/save view
	**/
	public function profileAction()
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
		$data = $this->url->post('contact');
	
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('profile');
		}
		
		$this->load->model('profile');
		
		$data['country'] = $data['address']['country'];
		$data['address'] = json_encode($data['address']);
		$data['marketing']['email'] = isset($data['marketing']['email']) ? $data['marketing']['email']: false;
		$data['marketing']['phone'] = isset($data['marketing']['phone']) ? $data['marketing']['phone']: false;
		$data['marketing']['sms'] = isset($data['marketing']['sms']) ? $data['marketing']['sms']: false;
		$data['marketing']['social'] = isset($data['marketing']['social']) ? $data['marketing']['social']: false;
		$data['marketing'] = json_encode($data['marketing']);

		$data['id'] = $this->session->data['customer'];

		if ($result = $this->model_profile->updateContact($data)) {
			$passdata['ip'] = $this->get_ip_address();
			$passdata['type_id'] = $data['id'];
			$passdata['name'] = "profile";
			$this->model_profile->clientActivity($passdata);
		
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Profile updated successfully.');
			$this->url->redirect('profile');
		} else {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Profile does not updated (Server Error).');
			$this->url->redirect('profile');
		}
	}

	public function indexChangePassword()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['customer']);
		
		/*Load Language File*/
		$this->language->load('common', 'common');
		$data['page_title'] = $this->language->get('text_change_password');
		
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL_CLIENTS.DIR_ROUTE.'changePassword';

		$data['lang']= $this->language->all();
		$this->response->setOutput($this->load->view('profile/change-password', $data));
	}

	public function changePasswordAction()
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
		$data = $this->url->post('profile');
		if ($validate_field = $this->validatePasswordField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => implode(", ",$validate_field).'!');
			$this->url->redirect('profile');
		}

		$data['id'] = $data['id'];
		$data['email'] = $data['email'];
		$data['old_password'] = $data['old-password'];
		$data['new_password'] = $data['new-password'];

		$this->load->model('profile');
		$password = $this->model_profile->getUserData($data);
		
		if (password_verify( $data['old_password'], $password)) {
			$data['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
			$result = $this->model_profile->updatePassword($data);
			if ($result) {
				$passdata['ip'] = $this->get_ip_address();
				$passdata['type_id'] = $this->session->data['customer'];
				$passdata['name'] = "changepassword";
				$this->model_profile->clientActivity($passdata);
				$this->session->data['message'] = array('alert' => 'success', 'value' => 'Account Password updated successfully.');
				$this->url->redirect('changepassword');
			} else {
				$this->session->data['message'] = array('alert' => 'error', 'value' => 'Account Password does not updated(Server Error).');
				$this->url->redirect('changepassword');
			}
		}
		else {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Current Password is not correct.');
			$this->url->redirect('changepassword');
		}
	}

	protected function validateField($data)
	{
		$error = [];
		$error_flag = false;
		$this->load->controller('commons');
		if ($this->controller_commons->validateText($data['firstname'])) {
			$error_flag = true;
			$error['firstname'] = 'First Name';
		}

		if ($this->controller_commons->validateEmail($data['email']) ) {
			$error_flag = true;
			$error['email'] = 'Email Address';
		}

		if ($this->controller_commons->validatePhoneNumber($data['phone'])) {
			$error_flag = true;
			$error['phone'] = 'Mobile Number';
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	protected function validatePasswordField($data)
	{
		$error = [];
		$error_flag = false;
		if (strlen($data['old-password']) < 6) {
			$error_flag = true;
			$error['username'] = 'Please enter minimum 6 letters for Old Password';
		}

		if (strlen($data['new-password']) < 6) {
			$error_flag = true;
			$error['firstname'] = 'Please enter minimum 6 letters for New Password';
		}

		if (strlen($data['confirm-password']) < 6) {
			$error_flag = true;
			$error['lastname'] = 'Please enter minimum 6 letters for Confirm Password';
		}

		if ($data['confirm-password'] != $data['new-password']) {
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
	/**
	 * Get Ip Adddress
	 * @return [varchar] [IP Address]
	 */
	protected function get_ip_address() 
	{

		if (!empty($_SERVER['HTTP_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}

		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
				$iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				foreach ($iplist as $ip) {
					if ($this->validate_ip($ip))
						return $ip;
				}
			} else {
				if ($this->validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
					return $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_X_FORWARDED']))
			return $_SERVER['HTTP_X_FORWARDED'];
		if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
			return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && $this->validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
			return $_SERVER['HTTP_FORWARDED_FOR'];
		if (!empty($_SERVER['HTTP_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_FORWARDED']))
			return $_SERVER['HTTP_FORWARDED'];

		return $_SERVER['REMOTE_ADDR'];
	}
	/**
	 * Ensures an ip address is both a valid IP and does not fall within
	 * a private network range.
	 */
	protected function validate_ip($ip)
	{
		if (strtolower($ip) === 'unknown')
			return false;

		$ip = ip2long($ip);

    	// if the ip is set and not equivalent to 255.255.255.255
		if ($ip !== false && $ip !== -1) {
	        /** make sure to get unsigned long representation of ip
	        * due to discrepancies between 32 and 64 bit OSes and
	        * signed numbers (ints default to signed in PHP) 
	        */
	        $ip = sprintf('%u', $ip);
        	// do private network range checking
	        if ($ip >= 0 && $ip <= 50331647) return false;
	        if ($ip >= 167772160 && $ip <= 184549375) return false;
	        if ($ip >= 2130706432 && $ip <= 2147483647) return false;
	        if ($ip >= 2851995648 && $ip <= 2852061183) return false;
	        if ($ip >= 2886729728 && $ip <= 2887778303) return false;
	        if ($ip >= 3221225984 && $ip <= 3221226239) return false;
	        if ($ip >= 3232235520 && $ip <= 3232301055) return false;
	        if ($ip >= 4294967040) return false;
	    }
	    return true;
	}
}