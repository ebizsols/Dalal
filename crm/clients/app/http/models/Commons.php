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
		$data['page_search'] = $this->user_agent->hasPermission('customers');
		
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['url'] = URL_CLIENTS;
		$data['dir_route'] = DIR_ROUTE;
		$data['url_route'] = URL_CLIENTS.DIR_ROUTE;
		$data['dir'] = DIR_CLIENTS;
		$data['url_admin'] = URL;
		
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
			$data['picture'] = '../public/uploads/'.$data['picture'];
		} else {
			$data['picture'] = false;
		}
		return $data;
	}

	public function getTheme()
	{
		$data = $this->user_agent->getTheme();
		if (!empty($data['logo']) && file_exists(DIR.'public/uploads/'.$data['logo'])) {
			$data['logo'] = '../public/uploads/'.$data['logo'];
		} else {
			$data['logo'] = '../public/images/logo.png';
		}
		if (!empty($data['logo_icon']) && file_exists(DIR.'public/uploads/'.$data['logo_icon'])) {
			$data['logo_icon'] = '../public/uploads/'.$data['logo_icon'];
		} else {
			$data['logo_icon'] = '../public/images/icon.png';
		}
		if (!empty($data['favicon']) && file_exists(DIR.'public/uploads/'.$data['favicon'])) {
			$data['favicon'] = '../public/uploads/'.$data['favicon'];
		} else {
			$data['favicon'] = '../public/images/favicon.png';
		}

		return $data;
	}

	public function getSiteInfo()
	{
		$data = $this->user_agent->getInfo();
		$data['logo_name'] = URL.'public/uploads/'.$data['logo'];
		if (!empty($data['logo']) && file_exists(DIR.'public/uploads/'.$data['logo'])) {
			$data['logo'] = '../public/uploads/'.$data['logo'];
		} else {
			$data['logo'] = '../public/images/logo.png';
		}
		
		$data['picker_date_format'] = str_replace(['d', 'm', 'Y'], ['dd', 'mm', 'yy'], $data['date_format']);
		$data['picker_my_format'] = str_replace(['m', 'Y'], ['mm', 'yy'], $data['date_my_format']);
		$data['range_my_format'] = str_replace(['m', 'Y'], ['MM', 'YYYY'], $data['date_my_format']);
		$data['range_date_format'] = str_replace(['d', 'm', 'Y'], ['DD', 'MM', 'YYYY'], $data['date_format']);
		
		return $data;
	}

	public function getTemplateAndInfo($id)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "email_template` WHERE `template` = ? LIMIT 1", array($id));
		$data['template'] = $query->row;
		$query = $this->database->query("SELECT * FROM  `" . DB_PREFIX . "setting` WHERE `name` = ? LIMIT 1", array('siteinfo'));
		$data['common'] = json_decode($query->row['data'], true);
		return $data;
	}

	public function getMailInfo()
	{
		$query = $this->database->query("SELECT `data` FROM `" . DB_PREFIX . "setting` WHERE `name` = ?", array('emailsetting'));
		return json_decode($query->row['data'], true);
	}

	public function getUserData($id)
	{
		$query = $this->database->query("SELECT `id`, `name`, `email`, `mobile` FROM `" . DB_PREFIX . "clients` WHERE `id` = ? LIMIT 1", array((int)$id));
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return '';
		}
	}

	public function insertAttchedFile($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "attached_files` (`file_name`, `file_type`, `file_type_id`) VALUES (?, ?, ?) ", array($this->database->escape($data['name']), $data['type'], $data['type_id']));
	}

	public function deleteAttchedFile($data)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "attached_files` WHERE `file_name` = ? AND `file_type` = ?" , array($this->database->escape($data['name']), $data['type']));

	}}