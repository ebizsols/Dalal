<?php

/**
* Recurringjob
*/
class Recurringjob extends Model
{
	public function getInvoices()
	{
		$query = $this->database->query("SELECT i.*, c.company, cr.abbr AS `abbr` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.inv_status = 1 ORDER BY i.date_of_joining DESC");
		return $query->rows;
	}

	public function getRecurringInvoices()
	{
		$query = $this->database->query("SELECT ri.*, c.company, cr.abbr AS `abbr`, last_invoice.date_of_joining AS last_invoice FROM `" . DB_PREFIX . "recurring_invoice` AS ri LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = ri.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON ri.currency = cr.id  LEFT JOIN ( SELECT i1.* FROM `" . DB_PREFIX . "invoice` as i1 LEFT JOIN `" . DB_PREFIX . "invoice` AS i2 ON i1.rid = i2.rid AND i1.date_of_joining < i2.date_of_joining WHERE i2.rid IS NULL ) as last_invoice ON (ri.id = last_invoice.rid) WHERE ri.inv_status = 1 ORDER BY ri.date_of_joining DESC");
		return $query->rows;
	}

	public function createInvoice($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "invoice` (`customer`, `duedate`, `invoicedate`, `currency`, `paymenttype`, `items`, `subtotal`, `tax`, `discount`, `discount_type`, `discount_value`, `amount`, `paid`, `due`, `note`, `tc`, `status`, `inv_status`, `rid`, `mail_sent`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array( (int)$data['customer'], $this->database->escape($data['duedate']), $this->database->escape($data['invoicedate']), (int)$data['currency'], (int)$data['paymenttype'], $data['items'], $data['subtotal'], $data['tax'], $data['discount'], $data['discount_type'], $data['discount_value'], $data['amount'], $data['paid'], $data['due'], $data['note'], $data['tc'], $data['status'], $data['inv_status'], $data['id'], 1, $data['datetime']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function getInoviceView($id)
	{
		$query = $this->database->query("SELECT i.*, c.company, c.email, c.address, p.name AS payment, cr.name AS currency_name, cr.abbr AS currency_abbr FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON i.customer = c.id LEFT JOIN `" . DB_PREFIX . "payment_method` AS p ON i.paymenttype = p.id LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.id = ? LIMIT 1", array((int)$id));
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getInoviceView1($id)
	{
		$query = $this->database->query("SELECT i.*, c.company, c.email, p.name AS payment, cr.name AS currency_name, cr.abbr AS currency_abbr FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON i.customer = c.id LEFT JOIN `" . DB_PREFIX . "payment_method` AS p ON i.paymenttype = p.id LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.id = ? LIMIT 1", array((int)$id));
		
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getTemplate($name)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "email_template` WHERE `template` = ? LIMIT 1", array($name));
		$data['template'] = $query->row;
		$query = $this->database->query("SELECT * FROM  `" . DB_PREFIX . "setting` WHERE `name` = ? LIMIT 1", array('siteinfo'));
		$data['common'] = json_decode($query->row['data'], true);
		return $data;
	}

	public function createCronLog($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "recurring_log` (`recurring_type`, `logs`) VALUES (?, ?)", array('invoice', $data));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getToken()
	{
		$query = $this->database->query("SELECT `data` FROM `" . DB_PREFIX . "setting` WHERE `name` = ? LIMIT 1", array('cronsetting'));
		return $query->row['data'];
	}
}