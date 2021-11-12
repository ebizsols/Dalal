<?php

/**
* Payment
*/
class Payment extends Model
{
	public function getInvoice($id)
	{
		$query = $this->database->query("SELECT i.*, c.firstname, c.company, c.email, cr.id AS currency_id, cr.name AS currency, cr.abbr FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON cr.id = i.currency LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer WHERE i.id = '".(int)$id."' LIMIT 1");
		
		return $query->row;
	}

	public function getPaymentGateway()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE name = ? LIMIT 1", array('paypalgateway'));
		return $query->row['data'];
	}

	public function checkTransaction($param, $transaction_id)
	{
		$query = $this->database->query("SELECT `id`, `txn_id` FROM `" . DB_PREFIX . "payments` WHERE `txn_id` = ?", array($transaction_id));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createPayment($data)
	{
		$this->database->query("INSERT INTO `" . DB_PREFIX . "payments` (`invoice_id`, `txn_id`, `amount`, `currency`, `payer_email`, `payment_date`, `is_online`, `gateway`, `contact_id`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($data['invoice']['id'], $data['txn_id'], $data['amount'], $data['invoice']['currency_id'], $data['invoice']['email'], $data['datetime'], 1, 'Paypal', $data['contact_id'], $data['datetime']));
	}

	public function updateInvoice($data)
	{
		$this->database->query("UPDATE `" . DB_PREFIX . "invoice` SET `status` = ?, `paid` = ?, `due` = ? WHERE `id` = ?", array($data['status'], $data['paid'], $data['due'], (int)$data['id']));
	}

	public function getTemplate()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "email_template` WHERE `template` = ? LIMIT 1", array('paymentconfirmation'));
		return $query->row;
	}

	public function getSiteInfo()
	{
		$query = $this->database->query("SELECT data FROM `" . DB_PREFIX . "setting` WHERE `name` = ? LIMIT 1", array('siteinfo'));
		if ($query->num_rows > 0) {
			return $query->row['data'];
		} else {
			return false;
		}
	}
}