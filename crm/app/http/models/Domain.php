<?php

/**
* Domain
*/
class Domain extends Model
{
	public function getDomains($user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT d.*, c.company FROM `" . DB_PREFIX . "domains` AS d LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = d.customer");
		} else {
			$query = $this->database->query("SELECT d.*, c.company FROM `" . DB_PREFIX . "domains` AS d LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = d.customer WHERE c.user_id = ?",array($user));
		}
		return $query->rows;
	}

	public function getDomain($id, $user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "domains` WHERE `id` = ? LIMIT 1", array((int)$id));
		} else {
			$query = $this->database->query("SELECT d.* FROM `" . DB_PREFIX . "domains` AS d LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = d.customer WHERE d.id = ? AND c.user_id = ? LIMIT 1", array((int)$id, $user));
		}
		
		return $query->row;
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

	public function getStaff()
	{
		$query = $this->database->query("SELECT `user_id`, concat(`firstname`, ' ', `lastname`) AS `name`, `user_name` FROM `" . DB_PREFIX . "users` WHERE user_role != ? ", array(1));
		return $query->rows;
	}

	public function getCurrency()
	{
		$query = $this->database->query("SELECT `id`, `name`, `abbr` FROM `" . DB_PREFIX . "currency`");
		return $query->rows;
	}

	public function updateDomain($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "domains` SET `name` = ?, `url` = ?, `registration_date` = ?, `expiry_date` = ?, `provider` = ?, `hosting` = ?, `customer` = ?, `price` = ?, `currency` = ?, `status` = ?, `renew` = ?, `remark` = ? WHERE `id` = ?", array($this->database->escape($data['name']), $data['url'], $data['registration_date'], $data['expiry_date'], $data['provider'], $data['hosting'], $data['customer'], $data['price'], $data['currency'], $data['status'], $data['renew'], $data['remark'], (int)$data['id']));
		
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createDomain($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "domains` (`name`, `url`, `registration_date`, `expiry_date`, `provider`, `hosting`, `customer`, `price`, `currency`, `status`, `renew`, `remark`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array( $this->database->escape($data['name']), $data['url'], $data['registration_date'], $data['expiry_date'], $data['provider'], $data['hosting'], $data['customer'], $data['price'], $data['currency'], $data['status'], $data['renew'], $data['remark'], $data['datetime']));

		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function deleteDomain($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "domains` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}