<?php

/**
* Commons Controller
*/
class CommonsController extends Controller
{
	public function getUser()
	{
		$commons = new Commons();
		$id= $this->session->data['user_id'];
		$data['user'] = $commons->getUserData($id);
		$data['info'] = $commons->getInfo();
		return $data;
	}

	public function info()
	{
		$commons = new Commons();
		$data = $commons->getInfo();
		return $data;
	}

	public function indexIcon()
	{
		$data['user'] = $this->getUser();
		$this->view->render('icon/icon.tpl', $data);
	}

	public function indexCloseTab()
	{
		echo "<script>window.close();</script>";
		exit();
	}

	public function indexErrorLog()
	{
		$data['user'] = $this->getUser();
		/*Render Setting view*/
		$this->view->render('not_found/errorlog.tpl', $data);
	}

	public function validateEmail($email)
	{
		if ((strlen($email) > 96) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function validateText($text)
	{
		if ((strlen($text) < 2) || !is_string($text)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function validateUrl($url)
	{
		if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
			return false;
		} else {
			return true;
		}
	}

	public function validateNumeric($numeric)
	{
		if (is_numeric($numeric)) {
			return false;
		} else {
			return true;
		}
	}

	public function validateNumber($number)
	{
		if (!filter_var($number, FILTER_VALIDATE_INT) === false) {
			return false;
		}
		else {
			return true;
		}
	}

	public function validatePhoneNumber($phone_number)
	{
		if ((strlen($phone_number) < 4) || (strlen($phone_number) > 32)) {
			return true;
		}
		else if ($this->validateNumeric($phone_number)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function validateDate($date)
	{
		if ($this->validateDateFormat($date)) {
			return false;
		}
		else {
			return true;
		}
	}

	protected function validateDateFormat($date, $format = 'Y-m-d')
	{	
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	public function validateToken($token)
	{
		$token_check = hash('sha512', TOKEN . TOKEN_SALT);
		
		if (hash_equals($token_check, $token) === false ){
			$this->session->data['message'] = array('alert' => 'danger', 'value' => 'Invalid token. Please try again.');
			return true;
		}
	}
}