<?php

/**
* Proposal Model
*/
class Quote extends Model
{
	public function getQuotes($period, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT p.*, c.company AS company, cr.abbr FROM `" . DB_PREFIX . "proposal` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = p.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON p.currency = cr.id WHERE p.date BETWEEN ? and ? ORDER BY p.date DESC, p.id DESC", array($period['start'], $period['end']));
		} else {
			$query = $this->database->query("SELECT p.*, c.company AS company, cr.abbr FROM `" . DB_PREFIX . "proposal` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = p.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON p.currency = cr.id WHERE p.date BETWEEN ? and ? and c.user_id = ? ORDER BY p.date DESC, p.id DESC", array($period['start'], $period['end'], $user));
		}
		
		return $query->rows;
	}

	public function getQuote($id, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "proposal` WHERE `id` = ? LIMIT 1", array((int)$id));
		} else {
			$query = $this->database->query("SELECT p.* FROM `" . DB_PREFIX . "proposal` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON p.customer = c.id WHERE p.id = ? AND c.user_id = ? LIMIT 1", array((int)$id, $user));
		}
		
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getQuoteView($id, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT p.*, c.company, c.email, c.address, cr.name AS currency_name, cr.abbr AS currency_abbr, pt.name AS `payment_method`, i.id AS invoice FROM `" . DB_PREFIX . "proposal` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON p.customer = c.id LEFT JOIN `" . DB_PREFIX . "payment_method` AS pt ON p.paymenttype = pt.id LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON p.currency = cr.id LEFT JOIN `" . DB_PREFIX . "invoice` AS i ON i.quote_id = p.id WHERE p.id = '".(int)$id."' LIMIT 1");
		} else {
			$query = $this->database->query("SELECT p.*, c.company, c.email, c.address, cr.name AS currency_name, cr.abbr AS currency_abbr, pt.name AS `payment_method`, i.id AS invoice FROM `" . DB_PREFIX . "proposal` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON p.customer = c.id LEFT JOIN `" . DB_PREFIX . "payment_method` AS pt ON p.paymenttype = pt.id LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON p.currency = cr.id LEFT JOIN `" . DB_PREFIX . "invoice` AS i ON i.quote_id = p.id WHERE p.id = '".(int)$id."' AND c.user_id = '".$user."' LIMIT 1");
		}
		
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getQuoteMailStatus($id)
	{
		$query = $this->database->query("SELECT `mail_sent` FROM `" . DB_PREFIX . "proposal` WHERE `id` = ?", array($id));
		return $query->row['mail_sent'];
	}

	public function updateQuoteMailStatus($id)
	{
		$this->database->query("UPDATE `" . DB_PREFIX . "proposal` SET `mail_sent` = ? WHERE `id` = ?", array(1, (int)$id));
	}

	public function createQuote($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "proposal` (`customer`, `project_name`, `proposal`, `paymenttype`, `currency`, `date`, `expiry`, `items`, `subtotal`, `tax`, `discount_value`, `amount`, `note`, `tc`, `status`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array( (int)$data['customer'], $this->database->escape($data['project_name']), $data['proposal'], $this->database->escape($data['paymenttype']), $data['currency'], $data['date'], $data['expiry'], $data['item'], $data['total']['subtotal'], $data['total']['tax'], $data['total']['discount_value'], $data['total']['amount'], $data['note'], $data['tc'], $data['status'], $data['datetime']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function updateQuote($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "proposal` SET `customer` = ?, `project_name` = ?, `proposal` = ?, `paymenttype` = ?, `currency` = ?, `date` = ?, `expiry` = ?, `items` = ?, `subtotal` = ?, `tax` = ?, `discount_value` = ?, `amount` = ?, `note` = ?, `tc` = ?, `status` = ? WHERE `id` = ?", array((int)$data['customer'], $this->database->escape($data['project_name']), $data['proposal'], $this->database->escape($data['paymenttype']), $data['currency'], $data['date'], $data['expiry'], $data['item'], $data['total']['subtotal'], $data['total']['tax'], $data['total']['discount_value'], $data['total']['amount'], $data['note'], $data['tc'], $data['status'], (int)$data['id']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function deleteQuote($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "proposal` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}