<?php

/**
* Login Controller
*/
class LoginController extends Controller
{
	public function index()
	{
		$data['error'] = '';
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		$data['success'] = '';
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
			$this->session->data['refferal'] = $_SERVER['HTTP_REFERER'];
		}

		$data['common']['url'] = URL_CLIENTS;
		$data['common']['dir_route'] = DIR_ROUTE;
		$data['common']['url_route'] = URL_CLIENTS.DIR_ROUTE;
		$data['common']['admin_url_route'] = URL.DIR_ROUTE;
		$data['common']['dir'] = DIR_CLIENTS;

		$this->language->load('common', 'common');
		$this->language->load('login', 'login');

		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		$data['theme'] = $this->model_commons->getTheme();

		$data['page_title'] = $this->language->get('text_login').' | '.$data['info']['name'];
		
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL_CLIENTS.DIR_ROUTE.'login';
		$data['lang']= $this->language->all();
		$this->response->setOutput($this->load->view('auth/login', $data));
	}

	public function login()
	{
		$username = $this->url->post('username');
		$password = $this->url->post('password');

		if (!$this->validate($username, $password)) {
			$this->session->data['error'] = 'Warning: Please enter valid data in input box.';
			$this->url->redirect('login');
		}

		unset($this->session->data['user_id']);
		unset($this->session->data['login_token']);
		unset($this->session->data['role']);

		/** 
		* If the user exists
		* Check his account and login attempts
		* Get user data 
        **/
		$this->load->model('login');
		$user = $this->model_login->checkUser($username);

		if (!empty($user)) {
			/** 
			* User exists now We check if
			* The account is locked From too many login attempts 
            **/
			if (!$this->checkLoginAttempts($user['email'])) {
				$this->session->data['error'] = 'Warning: Your account has exceeded allowed number of login attempts. Please try again in 1 hour.';
				$this->url->redirect('login');
			} else if ($user['status'] == "1") {
	            /** 
	            * Check if the password in the database matches the password user submitted.
	            * We are using the password_verify function to avoid timing attacks.
	            **/
	            if (password_verify( $password, $user['password'])) {
	            	$this->model_login->deleteAttempt($user['email']);
	            	/** 
	            	* Start session for user create session varible 
		            * Create session login string for authentication
		            **/

	            	$passdata['ip'] = $this->get_ip_address();
	            	$passdata['type_id'] = $user['customer'];
	            	$passdata['name'] = "login";
	            	$this->model_login->clientActivity($passdata);
	            	$this->session->data['user_id'] = preg_replace("/[^0-9]+/", "", $user['id']); 
	            	$this->session->data['customer'] = preg_replace("/[^0-9]+/", "", $user['customer']); 
	            	$this->session->data['login_token'] = hash('sha512', AUTH_KEY . LOGGED_IN_SALT);
	            	$this->url->redirect('dashboard');
	            } else {
	            	/** 
	            	* Add login attemt to Db
		            * Redirect back to login page and set error for user
		            **/
	            	$this->model_login->addAttempt($user['email']);
	            	$this->session->data['error'] = 'Warning: No match for Username and/or Password.';
	            	$this->url->redirect('login');
	            }
	        }
	        else {
	        	/** 
	        	* If account is disabled by admin 
		        * Then Show error to user
		        **/
	        	$this->session->data['error'] = 'Warning: Your account has disabled. For more info contact us.';
	        	$this->url->redirect('login');
	        }
	    }
	    else {
	    	/** 
	        * If email address not found in DB 
		    * Show error to user for creating account
		    **/
	    	$this->session->data['error'] = 'Warning: No match for Username and/or Password.';
	    	$this->url->redirect('login');
	    }
	}

	public function indexRegister()
	{
		$data['error'] = '';
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		$data['success'] = '';
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		$data['common']['url'] = URL_CLIENTS;
		$data['common']['dir_route'] = DIR_ROUTE;
		$data['common']['url_route'] = URL_CLIENTS.DIR_ROUTE;
		$data['common']['admin_url_route'] = URL_CLIENTS.DIR_ROUTE;
		$data['common']['dir'] = DIR_CLIENTS;
		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('login', 'login');

		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		$data['theme'] = $this->model_commons->getTheme();

		$data['page_title'] = $this->language->get('text_create_account').' | '.$data['info']['name'];
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL_CLIENTS.DIR_ROUTE.'register';

		$data['lang']= $this->language->all();
		$this->response->setOutput($this->load->view('auth/register', $data));
	}

	public function logout()
	{
		$this->session->destroy();
		$this->url->redirect('login');
	}

	/**
	* Register method
	* It will call on Register submit
	**/
	public function register() 
	{
		/**
		* Intilize Url class for post and get request
		**/
		$data = $this->url->post;
		$data['name'] = $data['fname'].' '.$data['lname'];
		/** 
		* Check submit is POST request 
		* Validaate input field
        **/

		if (!isset($_POST['register']) || !$this->validateRegister($data)) {
			$this->session->data['error'] = 'Warning: Please enter valid data.';
			$this->url->redirect('register');
		}
		/** 
		* Check if user already registerd or not
		* If the user exists Show error 
        **/
		$this->load->model('login');
		if ($user = $this->model_login->checkClient($data['mail'])) {
			$this->session->data['error'] = 'Warning: Account already exist. Login Now';
			$this->url->redirect('login');
		} else {

			/**
			* Unique hash value for email verification
			**/
			$data['temp_hash'] = md5(uniqid(mt_rand(), true));
			/**
			* Create user account
			* Insert value in DB
			**/
			$data['datetime'] = date('Y-m-d H:i:s');
			$data['expire'] = date('Y-m-d', strtotime('+2 year'));
			$data['expire'] = date_format(date_create($data['expire']), 'Y-m-d');
			
			$passdata['type_id'] = $this->model_login->createAccount($data);
			$passdata['ip'] = $this->get_ip_address();
			$passdata['name'] = "register";
			$this->model_login->clientActivity($passdata);

			$this->load->controller('Mail');
			$result = $this->controller_mail->getTemplate('newclient');

			if (!empty($result['template']) || $result['template']['status'] == '1') {
				$this->load->model('commons');
				$info = $this->model_commons->getSiteInfo();
				$message = $result['template']['message'];

				$message = str_replace('{name}', $data['name'], $message);
				$message = str_replace('{email}', $data['mail'], $message);
				$message = str_replace('{business_name}', $info['name'], $message);
				$message = str_replace('{client_login_url}', URL_CLIENTS, $message);

				$mail['name'] = $data['name'];
				$mail['email'] = $data['mail'];
				$mail['subject'] = $result['template']['subject'];
				$mail['message'] = $message;
				$mail_result = $this->controller_mail->sendmail($mail);
			}
			
			/**
			* Set success message 
			* Redirect to login page for login
			**/
			$this->session->data['success'] = 'Success: Account created succefully, check your mail for more info. Login Now';
			$this->url->redirect('login');
		}
	}

	public function userVerfiy() 
	{
		$data['email'] = $this->url->get('id');
		$data['hash'] = $this->url->get('code');

		if ((strlen($data['email']) > 96) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$this->url->redirect('login');
		} elseif (empty($data['hash'])) {
			$this->url->redirect('login');
		}

		if ($this->registerModel->checkEmailHash($data)) {
			$confirmAccount = $this->registerModel->confirmAccount($data);
			$this->session->data['success'] = 'Your Email address has been confirmed. Login now.';
			$this->url->redirect('login');
		} else {
			$this->url->redirect('login');
		}
	}

	/**
	* Validate Register data on server side
	* Validation is also done on client side (Using html5 and javascripts)
	**/
	protected function validateRegister($data) 
	{
		/** 
		* Validate input field on server side
		* Check if email and password contains valid phrases
		**/
		if ((strlen(trim($data['fname'])) < 2) || (strlen(trim($data['fname'])) > 48)) {
			/** 
			* If First name is not valid ( min 2 character or max 48 ) 
			* Return false
			**/
			return false;
		}  elseif ((strlen($data['mail']) > 96) || !filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
			/** 
			* If email is not valid
			* Return false
			**/
			return false;
		} elseif (strlen($data['password']) < 6) {
			/** 
			* If Password is not valid ( min 6 character ) 
			* Return false
			**/
			return false;
		} elseif ($data['confirmpassword'] != $data['password']) {
			/** 
			* If Password does not match with confirmpassword 
			* Return false
			**/
			return false;
		} else {
			/** 
			* Everything looks good 
			* Return True
			**/
			return true;
		}
	}

	/**
	* Validate login credentials on server side
	* Validation is also done on client side (Using html5 and javascripts)
	**/
	protected function validate($email, $password) 
	{
		/** 
		* Check if email and password contains valid phrases
		**/
		if (strlen($email) < 4 ) {
			/** 
			* If email is not valid
			* Return false
			**/
			return false;
		}
		elseif (strlen($password) < 6) {
			/** 
			* If password is not valid or minimum 6 character
			* Return false
			**/
			return false;
		}
		else {
			return true;
		}
	}

	/** 
	* Check login attempts of user for brute force attacks 
	**/
	protected function checkLoginAttempts($email)
	{
		/**
		* Get attempts from DB and check with predefined field
		* All login attempts are counted from the past 1 hours. 
		**/
		$login_attempts = $this->model_login->checkAttempts($email);
		if ($login_attempts && ($login_attempts['count'] >= 4) && strtotime('-1 hour') < strtotime($login_attempts['date_modified'])) {
			return false;
		}
		else {
			return true;
		}
	}

	public function indexForgot()
	{
		$data['error'] = '';
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		$data['success'] = '';
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		$data['common']['url'] = URL_CLIENTS;
		$data['common']['dir_route'] = DIR_ROUTE;
		$data['common']['url_route'] = URL_CLIENTS.DIR_ROUTE;
		$data['common']['admin_url_route'] = URL_CLIENTS.DIR_ROUTE;
		$data['common']['dir'] = DIR_CLIENTS;
		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('login', 'login');

		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		$data['theme'] = $this->model_commons->getTheme();

		$data['page_title'] = $this->language->get('text_forgotten_password').' | '.$data['info']['name'];
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL_CLIENTS.DIR_ROUTE.'forgotpassword';
		
		$data['lang']= $this->language->all();
		$this->response->setOutput($this->load->view('auth/forgot', $data));
	}

	public function forgot()
	{
		/**
		* Intilize Url class for post and get request
		**/
		$email = $this->url->post('mail');
		/** 
		* Check submit is POST request 
		* Validate input field
        **/
		if (!isset($_POST['forgot']) || !$this->validateForgot($email)) {
			$this->session->data['error'] = 'Warning: Please enter valid data in input box.';
			$this->url->redirect('forgot');
		}

		/** 
		* If the user exists
		* Check his account and login attempts
		* Get user data 
        **/
		$this->load->model('login');
		if ($user = $this->model_login->checkUser($email)) {
			
			/** 
			* Check Login attempt
			* The account is locked From too many login attempts 
            **/
			if (!$this->checkLoginAttempts($email)) {
				$this->session->data['error'] = 'Warning: Your account has exceeded allowed number of login attempts. Please try again in 1 hour.';
				$this->url->redirect('login');
			} elseif ( $user['status'] === 1 ) {
				
				$data['hash'] = md5(uniqid(mt_rand(), true));
				$data['email'] = $email;
				$this->model_login->editHash($data);

				$passdata['type_id'] = $user['customer'];
				$passdata['ip'] = $this->get_ip_address();
				$passdata['name'] = "forgot";
				$this->model_login->clientActivity($passdata);

				$this->load->controller('Mail');
				$result = $this->controller_mail->getTemplate('forgotpassword');

				if (!empty($result['template']) || $result['template']['status'] == '1') {
					$this->load->model('commons');
					$info = $this->model_commons->getSiteInfo();
					$message = $result['template']['message'];

					$reset_link = '<a href="'.URL_CLIENTS.DIR_ROUTE.'resetpassword&id='.$data['email'].'&temp='.$data['hash'].'">Click Here</a>';

					$message = str_replace('{name}', $user['name'], $message);
					$message = str_replace('{email}', $email, $message);
					$message = str_replace('{business_name}', $info['name'], $message);
					$message = str_replace('{reset_link}', $reset_link, $message);

					$mail['name'] = $user['name'];
					$mail['email'] = $user['email'];
					$mail['subject'] = $result['template']['subject'];
					$mail['message'] = $message;
					$mail_result = $this->controller_mail->sendmail($mail);
				}
				
				$this->session->data['success'] = 'Success: Reset instruction sent to your E-mail address.';
				$this->url->redirect('forgot');
			} else {
	        	/** 
	        	* If account is disabled by admin 
		        * Then Show error to user
		        **/
	        	$this->session->data['success'] = 'Success: Your account has disabled by admin, For more info contact us.';
	        	$this->url->redirect('login');
	        }

			/** 
			* User exists now We check if
			* Send Mail to user for reset password
            **/

		} else {
			$this->session->data['error'] = 'Warning: Account does not exists.';
			$this->url->redirect('forgot');
		}
	}
	/** 
	* Validate input field on server side
	* Check if email and password contains valid phrases
	**/
	protected function validateForgot($email)
	{
		if ((strlen($email) > 96) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			/** 
			* If email is not valid
			* Return false
			**/
			return false;
		} else {
			/** 
			* If email looks good
			* Return true for further info
			**/
			return true;
		}
	}

	public function resetPassword()
	{
		$data['error'] = '';
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		$data['success'] = '';
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		$data['mail'] = $this->url->get('id');
		$data['hash'] = $this->url->get('temp');
		if (empty($data['mail']) && empty($data['hash']) ) {
			$this->url->redirect('login');
		}

		$data['common']['url'] = URL_CLIENTS;
		$data['common']['dir_route'] = DIR_ROUTE;
		$data['common']['url_route'] = URL_CLIENTS.DIR_ROUTE;
		$data['common']['admin_url_route'] = URL_CLIENTS.DIR_ROUTE;
		$data['common']['dir'] = DIR_CLIENTS;
		
		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('login', 'login');

		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		$data['theme'] = $this->model_commons->getTheme();

		$data['page_title'] = $this->language->get('text_reset_password').' | '.$data['info']['name'];

		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL_CLIENTS.DIR_ROUTE.'resetpassword';
		
		$data['lang']= $this->language->all();
		$this->response->setOutput($this->load->view('auth/reset_password', $data));
	}

	public function resetPasswordAction()
	{
		$data = $this->url->post;
		
		if (!isset($_POST['reset']) || !$this->validateReset($data)) {
			$this->session->data['error'] = 'Warning: Please enter valid data in input box.';
			$this->url->redirect('reset');
		}
		
		$this->load->model('login');
		if ($user = $this->model_login->checkUser($data['mail'])) {

			$check = $this->model_login->checkEmailHash($data);
			if (empty($check)) {
				$this->url->redirect('login');
			}

			$result = $this->model_login->updatePassword($data);
			$passdata['type_id'] = $user['customer'];
			$passdata['ip'] = $this->get_ip_address();
			$passdata['name'] = "reset";
			$this->model_login->clientActivity($passdata);

			$this->session->data['success'] = 'Success: Your Password Changed Succesfully.';
		}
		
		$this->url->redirect('login');
	}
	/** 
	* Validate input field on server side
	* Check if email and password contains valid phrases
	**/
	protected function validateReset($data)
	{
		/** 
		* Validate input field on server side
		* Check if email and password contains valid phrases
		**/
		if ((strlen(trim($data['hash'])) < 10) || (strlen(trim($data['hash'])) > 255)) {
			/** 
			* If First name is not valid ( min 2 character or max 48 ) 
			* Return false
			**/
			return false;
		}  elseif ((strlen($data['mail']) > 96) || !filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
			/** 
			* If email is not valid
			* Return false
			**/
			return false;
		} elseif (strlen($data['password']) < 6) {
			/** 
			* If Password is not valid ( min 6 character ) 
			* Return false
			**/
			return false;
		} elseif ($data['confirmpassword'] != $data['password']) {
			/** 
			* If Password does not match with confirmpassword 
			* Return false
			**/
			return false;
		} else {
			/** 
			* Everything looks good 
			* Return True
			**/
			return true;
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