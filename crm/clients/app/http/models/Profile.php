<?php

/**
* Profile
*/
class Profile extends Model
{
	public function getCustomer($id)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "contacts` WHERE `id` = ? LIMIT 1", array($id));

		return $query->row;
	}

	public function updateContact($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "contacts` SET `salutation` = ?, `firstname` = ?, `lastname` = ?, `company` = ?, `phone` = ?, `website` = ?, `address` = ?, `country` = ?, `marketing` = ? WHERE `id` = ? ", array($this->database->escape($data['salutation']), $this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['company']), $this->database->escape($data['phone']), $this->database->escape($data['website']), $data['address'], $this->database->escape($data['country']), $data['marketing'], (int)$data['id']));
		return true;
	}

	public function updateProfile($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "clients` SET `name` = ?, `mobile` = ? WHERE `id` = ? AND `email` = ?", array($this->database->escape($data['name']), $this->database->escape($data['mobile']), (int)$data['id'], $this->database->escape($data['email'])));
		if ($this->database->error()) {
			return $this->database->error();
		} else {
			return true;
		}
	}

	public function updatePassword($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "clients` SET `password` = ? WHERE `id` = ? AND `email` = ?" , array($this->database->escape($data['password']), (int)$data['id'], $this->database->escape($data['email'])));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getUserData($data)
	{
		 $query = $this->database->query("SELECT `password` FROM `" . DB_PREFIX . "clients` WHERE `id` = ? AND `email` = ? LIMIT 1", array($this->database->escape($data['id']), $this->database->escape($data['email'])));
		if ($query->num_rows > 0) {
			return  $query->row['password'];
		} else {
			return false;
		}
	}

	public function clientActivity($data)
	{
		$this->database->query("INSERT INTO `" . DB_PREFIX . "client_activity` (`name`, `type_id`, `ip`) VALUES (?, ?, ?) ", array($this->database->escape($data['name']), $data['type_id'], $data['ip']));
	}
}