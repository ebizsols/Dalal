<?php

/**
* Commmons Model
*/
class Commons extends Model
{
	public function getCommonData($user_id)
	{
		$data['user'] = $this->getUserInfo($user_id);
		$data['info'] = $this->getSiteInfo();
		$data['theme'] = $this->getTheme();
		$data['permission'] = $this->user_agent->getPermission();
		$data['is_admin'] = $data['user']['role_id'] == '1' ? true : false;
		$data['page_search'] = $this->user_agent->hasPermission('contacts');
		$data['new_transaction'] = $this->user_agent->hasPermission('account/transaction/add');
		$data['new_contact'] = $this->user_agent->hasPermission('contact/add');
		$data['new_invoice'] = $this->user_agent->hasPermission('invoice/add');
		$data['new_expense'] = $this->user_agent->hasPermission('expense/add');
		
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['url'] = URL;
		$data['dir_route'] = DIR_ROUTE;
		$data['url_route'] = URL.DIR_ROUTE;
		$data['dir'] = DIR;
		$this->language->load('common', 'common');
		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}

		return $data;
	}

	public function getUserInfo($user_id)
	{
		$data = $this->user_agent->getUserData();
		if (!empty($data['picture']) && file_exists(DIR.'public/uploads/'.$data['picture'])) {
			$data['picture'] = 'public/uploads/'.$data['picture'];
		} else {
			$data['picture'] = false;
		}
		return $data;
	}

	public function getTheme()
	{
		$data = $this->user_agent->getTheme();
		if (!empty($data['logo']) && file_exists(DIR.'public/uploads/'.$data['logo'])) {
			$data['logo'] = 'public/uploads/'.$data['logo'];
		} else {
			$data['logo'] = 'public/images/logo.png';
		}
		if (!empty($data['logo_icon']) && file_exists(DIR.'public/uploads/'.$data['logo_icon'])) {
			$data['logo_icon'] = 'public/uploads/'.$data['logo_icon'];
		} else {
			$data['logo_icon'] = 'public/images/icon.png';
		}
		if (!empty($data['favicon']) && file_exists(DIR.'public/uploads/'.$data['favicon'])) {
			$data['favicon'] = 'public/uploads/'.$data['favicon'];
		} else {
			$data['favicon'] = 'public/images/favicon.png';
		}

		return $data;
	}

	public function getSiteInfo()
	{
		$data = $this->user_agent->getInfo();
		if (!empty($data['logo']) && file_exists(DIR.'public/uploads/'.$data['logo'])) {
			$data['logo'] = 'public/uploads/'.$data['logo'];
		} else {
			$data['logo'] = 'public/images/logo.png';
		}
		
		$data['picker_date_format'] = str_replace(['d', 'm', 'Y'], ['dd', 'mm', 'yy'], $data['date_format']);
		$data['picker_my_format'] = str_replace(['m', 'Y'], ['mm', 'yy'], $data['date_my_format']);
		$data['range_my_format'] = str_replace(['m', 'Y'], ['MM', 'YYYY'], $data['date_my_format']);
		$data['range_date_format'] = str_replace(['d', 'm', 'Y'], ['DD', 'MM', 'YYYY'], $data['date_format']);
		
		return $data;
	}

	public function getAdminTheme()
	{
		$query = $this->database->query("SELECT `data` FROM `" . DB_PREFIX . "setting` WHERE `name` = ?", array('admintheme'));
		$data = json_decode($query->row['data'], true);
		if (!empty($data['logo']) && file_exists(DIR.'public/uploads/'.$data['logo'])) {
			$data['logo'] = 'public/uploads/'.$data['logo'];
		} else {
			$data['logo'] = 'public/images/logo.png';
		}
		if (!empty($data['favicon']) && file_exists(DIR.'public/uploads/'.$data['favicon'])) {
			$data['favicon'] = 'public/uploads/'.$data['favicon'];
		} else {
			$data['favicon'] = 'public/images/logo.png';
		}
		return $data;
	}

	public function getCustomers($user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT `id`, `company` FROM `" . DB_PREFIX . "contacts`");
		} else {
			$query = $this->database->query("SELECT `id`, `company` FROM `" . DB_PREFIX . "contacts` WHERE `user_id` = ?",array($user));
		}
		return $query->rows;
	}

	public function getMailInfo()
	{
		$query = $this->database->query("SELECT `data` FROM `" . DB_PREFIX . "setting` WHERE `name` = ?", array('emailsetting'));
		return json_decode($query->row['data'], true);
	}

	public function getCurrencies()
	{
		$query = $this->database->query("SELECT `id`, `name`, `abbr` FROM `" . DB_PREFIX . "currency` WHERE `status` = ?", array(1));
		return $query->rows;
	}
	
	public function getExpensesType()
	{
		$query = $this->database->query("SELECT `id`, `name` FROM `" . DB_PREFIX . "expense_type` WHERE `status` = ? ", array(1));
		return $query->rows;
	}

	public function getPaymentMethod()
	{
		$query = $this->database->query("SELECT `id`, `name` FROM `" . DB_PREFIX . "payment_method` WHERE `status` = ?", array(1));
		return $query->rows;
	}

	public function getAttachments($type, $id)
	{
		$query = $this->database->query("SELECT `id`, `file_name`, `ext` FROM `" . DB_PREFIX . "attached_files` WHERE `file_type` = ? AND `file_type_id` = ?", array($type, $id));
		return $query->rows;
	}

	public function getTaxes()
	{
		$query = $this->database->query("SELECT `id`, `name`, `rate` FROM `" . DB_PREFIX . "taxes`");
		return $query->rows;
	}

	public function getInvoiceData()
	{
		return $this->user_agent->getInfo();
	}

	public function getStaff()
	{
		$query = $this->database->query("SELECT `user_id`, concat(`firstname`, ' ', `lastname`) AS `name`, `user_name`, email, mobile FROM `" . DB_PREFIX . "users` WHERE user_role != ? ", array(1));
		return $query->rows;
	}
	
	public function getUserData($id)
	{
		$query = $this->database->query("SELECT `firstname`, `lastname` FROM `" . DB_PREFIX . "users` WHERE `user_id` = ?", array((int)$id));
		if ($query->num_rows > 0) {
			return $query->row['firstname'].' '.$query->row['lastname'];
		} else {
			return '';
		}
	}

	public function getTemplateAndInfo($id)
	{
		$query = $this->database->query("SELECT subject, message FROM `" . DB_PREFIX . "email_template` WHERE `template` = ? LIMIT 1", array($id));
		$data['template'] = $query->row;
		$data['common'] = $this->user_agent->getInfo();
		return $data;
	}
}