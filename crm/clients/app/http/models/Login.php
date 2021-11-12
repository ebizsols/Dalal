<?php

/**
* Login Model
*/
class Login extends Model
{
	public function checkUser($email)
	{
		$query = $this->database->query( "SELECT c.id, c.name, c.email, c.password, c.status, ct.id AS `customer` FROM `" . DB_PREFIX . "clients` AS c LEFT JOIN `" . DB_PREFIX . "contacts` AS ct ON c.email = ct.email WHERE c.email = ? LIMIT 1", array($this->database->escape($email)));
		
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getTemplate($name)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "email_template` WHERE `template` = ? LIMIT 1", array($name));
		return $query->row;
	}

	public function checkUserId($id)
	{
		$query = $this->database->query( "SELECT `id`, `name`, `email`, `status` FROM `" . DB_PREFIX . "clients` WHERE `id` = ? AND `status` = ? LIMIT 1", array($this->database->escape($id), 1));
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function checkAttempts($email)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "login_attempts` WHERE `email` = ?", array($this->database->escape($email)));
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function addAttempt($email)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "login_attempts` WHERE `email` = ? ", array($this->database->escape($email)));
		if ($query->num_rows > 0) {
			$this->database->query("UPDATE `" . DB_PREFIX . "login_attempts` SET `count` = ?, `date_modified` = ? WHERE `email` = ?", array( $query->row['count'] + 1 , date('Y-m-d H:i:s'), $email));
		}
		else {
			$this->database->query("INSERT INTO `" . DB_PREFIX . "login_attempts` SET `email` = ?, `count` = ?, `date_added` = ?, `date_modified` = ?", array($email, 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')));
		}
	}

	public function deleteAttempt($email)
	{
		$this->database->query("DELETE FROM `" . DB_PREFIX . "login_attempts` WHERE `email` = ?", array($this->database->escape($email)));
	}

	public function checkClient($email)
	{
		$query = $this->database->query( "SELECT `email` FROM `" . DB_PREFIX . "clients` WHERE `email` = ?", array($this->database->escape($email)));
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function createAccount($data) 
	{
		$passwordhash = password_hash($data['password'], PASSWORD_DEFAULT);
		$this->database->query("INSERT INTO `" . DB_PREFIX . "clients` (`name`, `email`, `password`, `hash`, `status`) VALUES (?, ?, ?, ?, ?) ",  array($this->database->escape($data['name']), $this->database->escape($data['mail']), $this->database->escape($passwordhash), $this->database->escape($data['temp_hash']), 1));

		$query = $this->database->query( "SELECT `id` FROM `" . DB_PREFIX . "contacts` WHERE `email` = ? LIMIT 1", array($this->database->escape($data['mail'])));
		
		if ($query->num_rows < 1) {
			$this->database->query("INSERT INTO `" . DB_PREFIX . "contacts` (`firstname`, `lastname`, `company`, `email`, `phone`, `expire`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?) ", array($data['fname'], $data['lname'], $data['fname'], $this->database->escape($data['mail']), $this->database->escape($data['mobile']), $data['expire'], $data['datetime']));
			return $this->database->last_id();
		} else {
			return $query->row['id'];
		}
	}

	public function clientActivity($data)
	{
		$this->database->query("INSERT INTO `" . DB_PREFIX . "client_activity` (`name`, `type_id`, `ip`) VALUES (?, ?, ?) ", array($this->database->escape($data['name']), $data['type_id'], $data['ip']));
	}

	public function editHash($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "clients` SET `hash` = ? WHERE `email` = ? ", array($this->database->escape($data['hash']), $this->database->escape($data['email'])));
	}

	public function checkEmailHash($data)
	{
		$query = $this->database->query( "SELECT `email` FROM `" . DB_PREFIX . "clients` WHERE `email` = ? AND `hash` = ?", array($this->database->escape($data['mail']), $data['hash']));
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function updatePassword($data)
	{
		$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		$this->database->query("UPDATE `" . DB_PREFIX . "clients` SET `password` = ?, `hash` = ? WHERE `email` = ? AND `hash` = ? ", array($data['password'], '', $data['mail'], $data['hash']));
	}
}