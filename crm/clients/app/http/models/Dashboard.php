<?php

/**
* Dashboard Model
*/

class Dashboard extends Model
{
	public function invoiceStatus($customer)
	{
		$query = $this->database->query("SELECT COUNT(status) AS `value`, `status` AS `label` FROM `" . DB_PREFIX . "invoice` WHERE `customer` = ? GROUP BY status", array($customer));
		return $query->rows;
	}

	public function ticketStatus($customer)
	{
		$query = $this->database->query("SELECT COUNT(status) AS `value`, IF(status = 1, 'Closed', 'Open') AS `label` FROM `" . DB_PREFIX . "tickets` WHERE `email` = ? GROUP BY status", array($customer));

		return $query->rows;
	}

	public function getLastTicket($email)
	{
		$query = $this->database->query("SELECT t.*, d.name AS `department` FROM `" . DB_PREFIX . "tickets` AS t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.email = '".$email."' ORDER BY t.date_of_joining DESC LIMIT 1");
		return $query->row;
	}

	public function invoiceCount($customer)
	{
		$query = $this->database->query("SELECT COUNT(*) AS `count` FROM `" . DB_PREFIX . "invoice` WHERE `customer` = ?", array($customer));
		return $query->row['count'];
	}

	public function getInvoices($customer)
	{
		$query = $this->database->query("SELECT i.id, c.company, i.amount, i.status, cr.abbr FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON cr.id = i.currency WHERE i.customer = ? ORDER BY i.date_of_joining DESC LIMIT 6", array($customer));
		return $query->rows;
	}

	public function quotesCount($customer)
	{
		$query = $this->database->query("SELECT COUNT(*) AS `count` FROM `" . DB_PREFIX . "proposal` WHERE `customer` = ?", array($customer));
		return $query->row['count'];
	}

	public function ticketCount($customer)
	{
		$query = $this->database->query("SELECT COUNT(*) AS `count` FROM `" . DB_PREFIX . "tickets` WHERE `email` = ?", array($customer));
		return $query->row['count'];
	}

	public function getQuotes($customer)
	{
		$query = $this->database->query("SELECT i.id, c.company, i.amount, cr.abbr FROM `" . DB_PREFIX . "proposal` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON cr.id = i.currency WHERE i.customer = ? ORDER BY i.date_of_joining DESC LIMIT 6", array($customer));
		return $query->rows;
	}
}