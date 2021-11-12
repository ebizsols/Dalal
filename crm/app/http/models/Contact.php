<?php

/**
* Contact Model
*/
class Contact extends Model
{
	public function getContacts($user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT `id`, `company`, `email`, `phone`, `expire`, `date_of_joining` FROM `" . DB_PREFIX . "contacts` ORDER BY `date_of_joining` DESC");
		} else {
			$query = $this->database->query("SELECT `id`, `company`, `email`, `phone`, `expire`, `date_of_joining` FROM `" . DB_PREFIX . "contacts` WHERE `user_id` = ? ORDER BY date_of_joining DESC", array($user));
		}
		return $query->rows;
	}

	public function getContact($id, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT c.*, u.user_name AS username FROM `" . DB_PREFIX . "contacts` AS c LEFT JOIN `" . DB_PREFIX . "users` AS u ON u.user_id = c.user_id WHERE `id` = ? LIMIT 1", array((int)$id));
			return $query->row;
		} else {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "contacts` WHERE `id` = ? AND `user_id` = ? LIMIT 1", array((int)$id, $user));
			return $query->row;
		}
	}

	public function getInvoices($id)
	{
		$query = $this->database->query("SELECT i.id, i.amount, i.due, i.invoicedate, i.duedate, i.date_of_joining, i.status, cr.abbr AS `abbr` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE `customer` = ? ORDER BY i.date_of_joining DESC LIMIT 10", array($id));
		return $query->rows;
	}

	public function getQuotes($id)
	{
		$query = $this->database->query("SELECT p.*, cr.abbr AS `abbr` FROM `" . DB_PREFIX . "proposal` AS p LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON p.currency = cr.id WHERE p.customer = ? ORDER BY p.date_of_joining DESC LIMIT 10", array($id));
		return $query->rows;
	}

	public function getClient($email)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "clients` WHERE `email` = ? LIMIT 1", array($email));
		return $query->row;
	}

	public function getClientActivity($id)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "client_activity` WHERE `type_id` = ? ORDER BY `date_of_joining` DESC", array($id));
		return $query->rows;
	}

	public function updateContact($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "contacts` SET `salutation` = ?, `firstname` = ?, `lastname` = ?, `company` = ?, `email` = ?, `phone` = ?, `website` = ?, `address` = ?, `country` = ?, `persons` = ?, `remark` = ?, `marketing` = ?, `expire` = ?, `user_id` = ? WHERE `id` = ? ", array($this->database->escape($data['salutation']), $this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['company']), $this->database->escape($data['email']), $this->database->escape($data['phone']), $this->database->escape($data['website']), $data['address'], $this->database->escape($data['country']), $data['person'], $data['remark'], $data['marketing'], $data['expire'], $data['staff'], (int)$data['id']));
		if (!empty($data['client']['client_id'])) {
			$this->database->query("UPDATE `" . DB_PREFIX . "clients` SET `status` = ? WHERE `id` = ? ", array((int)$data['client']['status'], (int)$data['client']['client_id']));
		}
	}

	public function createContact($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "contacts` (`salutation`, `firstname`, `lastname`, `company`, `email`, `phone`, `website`, `address`, `country`, `persons`, `remark`, `marketing`, `expire`, `user_id`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['salutation']), $this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['company']), $this->database->escape($data['email']), $this->database->escape($data['phone']), $this->database->escape($data['website']), $data['address'], $this->database->escape($data['country']), $data['person'], $data['remark'], $data['marketing'], $data['expire'], $data['staff'], $data['datetime']));
		
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function importContact($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "contacts` (`salutation`, `firstname`, `lastname`, `company`, `email`, `phone`, `website`, `address`, `country`, `expire`, `user_id`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",array($data['salutation'], $data['firstname'], $data['lastname'], $data['company'], $data['email'], $data['phone'], $data['website'], $data['address'], $data['country'], $data['expire'], $data['user_id'], $data['datetime']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function emailLog($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "email_logs` (`email_to`, `email_bcc`, `subject`, `message`, `type`, `type_id`, `user_id`) VALUES (?, ?, ?, ?, ?, ?, ?)", array( $data['to'], $data['bcc'], $data['subject'], $data['message'], $data['type'], $data['type_id'], $data['user_id']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function getSearchedContact($data, $user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT id, CONCAT(firstname, ' ', lastname) AS label, company, email, phone AS mobile FROM `" . DB_PREFIX . "contacts` WHERE firstname like '%".$data."%' OR lastname like '%".$this->database->escape($data)."%' OR company like '%".$this->database->escape($data)."%' LIMIT 7");
		} else {
			$query = $this->database->query("SELECT id, CONCAT(firstname, ' ', lastname) AS label, company, email, phone AS mobile FROM `" . DB_PREFIX . "contacts` WHERE firstname like '%".$data."%' OR lastname like '%".$this->database->escape($data)."%' OR company like '%".$this->database->escape($data)."%' AND `user_id` = '".$user."' LIMIT 7");
		}
		
		return $query->rows;
	}

	public function deleteContact($data)
	{
		$query = $this->database->query("SELECT `email` FROM `" . DB_PREFIX . "contacts` WHERE `id` = ?", array((int)$data['id'] ));
		
		if ($query->num_rows > 0) {
			$email = $query->row['email'];
			$this->database->query("DELETE FROM `" . DB_PREFIX . "contacts` WHERE `id` = ?", array((int)$data['id']));
			$this->database->query("DELETE FROM `" . DB_PREFIX . "clients` WHERE `email` = ?", array($email));
			$this->database->query("DELETE FROM `" . DB_PREFIX . "client_activity` WHERE `type_id` = ?", array((int)$data['id']));

			if ($data['all_data'] == 1) {
				$this->database->query("DELETE FROM `" . DB_PREFIX . "projects` WHERE `customer` = ?", array((int)$data['id']));
				$this->database->query("DELETE FROM `" . DB_PREFIX . "invoice` WHERE `customer` = ?", array((int)$data['id']));
				$this->database->query("DELETE FROM `" . DB_PREFIX . "proposal` WHERE `customer` = ?", array((int)$data['id']));
			}
		}
		return true;
	}

	public function getClients()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "clients`");
		return $query->rows;
	}

	public function getClientByID($id)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "clients` WHERE id = ?", array((int)$id));
		return $query->row;
	}

	public function updateClient($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "clients` SET `name` = ?, `mobile` = ?, `status` = ? WHERE `id` = ? ", array($this->database->escape($data['name']), $this->database->escape($data['mobile']), (int)$data['status'], (int)$data['id']));
	}

	public function deleteClient($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "clients` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}