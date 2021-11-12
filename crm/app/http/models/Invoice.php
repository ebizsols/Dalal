<?php

/**
* Invoice Model
*/
class Invoice extends Model
{
	public function getInvoices($period, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT i.*, c.company, cr.abbr AS `abbr` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.invoicedate BETWEEN ? and ? ORDER BY i.invoicedate DESC, i.id DESC", array($period['start'], $period['end']));
		} else {
			$query = $this->database->query("SELECT i.*, c.company, cr.abbr AS `abbr` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.invoicedate BETWEEN ? and ? and c.user_id = ? ORDER BY i.invoicedate DESC, i.id DESC", array($period['start'], $period['end'], $user));
		}
		return $query->rows;
	}

	public function getInovice($id, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "invoice` WHERE `id` = ? LIMIT 1", array((int)$id));
		} else {
			$query = $this->database->query("SELECT i.* FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON i.customer = c.id WHERE i.id = ? AND c.user_id = ? LIMIT 1", array((int)$id, $user));
		}
		
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getInoviceView($id, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT i.*, c.company, c.email, c.address, p.name AS payment, cr.name AS currency_name, cr.abbr AS currency_abbr FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON i.customer = c.id LEFT JOIN `" . DB_PREFIX . "payment_method` AS p ON i.paymenttype = p.id LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.id = ? LIMIT 1", array((int)$id));
		} else {
			$query = $this->database->query("SELECT i.*, c.company, c.email, c.address, p.name AS payment, cr.name AS currency_name, cr.abbr AS currency_abbr FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON i.customer = c.id LEFT JOIN `" . DB_PREFIX . "payment_method` AS p ON i.paymenttype = p.id LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.id = ? AND c.user_id = ? LIMIT 1", array((int)$id, $user));
		}

		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}	
	}

	public function getAttachments($id)
	{
		$query = $this->database->query("SELECT `id`, `file_name`, `ext` FROM `" . DB_PREFIX . "attached_files` WHERE `file_type` = ? AND `file_type_id` = ?", array('invoice', $id));
		return $query->rows;
	}

	public function getQuoteView($id)
	{
		$query = $this->database->query("SELECT p.*, c.company, c.email, cr.name AS currency_name, cr.abbr AS currency_abbr, pt.name AS `payment_method` FROM `" . DB_PREFIX . "proposal` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON p.customer = c.id LEFT JOIN `" . DB_PREFIX . "payment_method` AS pt ON p.paymenttype = pt.id LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON p.currency = cr.id WHERE p.id = ? LIMIT 1", array((int)$id));
		
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getPayments($id)
	{
		$query = $this->database->query("SELECT p.*, pm.name AS method_name FROM `" . DB_PREFIX . "payments` AS p LEFT JOIN `" . DB_PREFIX . "payment_method` AS pm ON pm.id = p.method WHERE `invoice_id` = ?", array((int)$id));
		return $query->rows;
	}

	public function getInvoiceMailStatus($data)
	{
		$query = $this->database->query("SELECT `mail_sent` FROM `" . DB_PREFIX . "invoice` WHERE `id` = ?", array($data['id']));
		return $query->row['mail_sent'];
	}

	public function updateInvoiceMailStatus($id)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "invoice` SET `mail_sent` = ? WHERE `id` = ?", array(1, (int)$id));
	}

	public function updateInvoice($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "invoice` SET `customer` = ?, `duedate` = ?, `invoicedate` = ?, `currency` = ?, `paymenttype` = ?, `items` = ?, `subtotal` = ?, `tax` = ?, `discount_value` = ?, `amount` = ?, `paid` = ?, `due` = ?, `note` = ?, `tc` = ?, `want_payment` = ?, `want_signature` = ?, `status` = ?, `inv_status` = ? WHERE `id` = ?", array((int)$data['customer'], $this->database->escape($data['duedate']), $this->database->escape($data['invoicedate']), (int)$data['currency'], (int)$data['paymenttype'], $data['item'], $data['subtotal'], $data['tax'], $data['discount_value'], $data['amount'], $data['paid'], $data['due'], $data['note'], $data['tc'], $data['want_payment'], $data['want_signature'], $data['status'], $data['inv_status'], (int)$data['id']));

		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function invoiceMarkAs($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "invoice` SET `status` = ? WHERE `id` = ?", array($data['status'], (int)$data['id']));

		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createInvoice($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "invoice` (`customer`, `duedate`, `invoicedate`, `currency`, `paymenttype`, `items`, `subtotal`, `tax`, `discount_value`, `amount`, `paid`, `due`, `note`, `tc`, `want_payment`, `want_signature`, `status`, `inv_status`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array( (int)$data['customer'], $this->database->escape($data['duedate']), $this->database->escape($data['invoicedate']), (int)$data['currency'], (int)$data['paymenttype'], $data['item'], $data['subtotal'], $data['tax'], $data['discount_value'], $data['amount'], $data['paid'], $data['due'], $data['note'], $data['tc'], $data['want_payment'], $data['want_signature'], $data['status'], $data['inv_status'], $data['datetime']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function copyInvoice($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "invoice` (`customer`, `duedate`, `invoicedate`, `currency`, `paymenttype`, `items`, `subtotal`, `tax`, `discount_value`, `amount`, `paid`, `due`, `note`, `tc`, `want_payment`, `want_signature`, `status`, `inv_status`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array( (int)$data['customer'], $this->database->escape($data['duedate']), $this->database->escape($data['invoicedate']), (int)$data['currency'], (int)$data['paymenttype'], $data['items'], $data['subtotal'], $data['tax'], $data['discount_value'], $data['amount'], $data['paid'], $data['due'], $data['note'], $data['tc'], $data['want_payment'], $data['want_signature'], 'Unpaid', 0, $data['datetime']));

		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function createQuoteInvoice($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "invoice` (`customer`, `currency`, `duedate`, `invoicedate`, `paymenttype`, `items`, `subtotal`, `tax`, `discount_value`, `amount`, `paid`, `due`, `note`, `tc`, `quote_id`, `status`, `inv_status`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array( (int)$data['customer'], (int)$data['currency'], $this->database->escape($data['duedate']), $this->database->escape($data['invoicedate']), (int)$data['paymenttype'], $data['items'], $data['subtotal'], $data['tax'], $data['discount_value'], $data['amount'], $data['paid'], $data['due'], $data['note'], $data['tc'], $data['id'], $data['status'], 1, $data['datetime']));
		if ($query->num_rows > 0) {
			$id = $this->database->last_id();
			$this->database->query("UPDATE `" . DB_PREFIX . "proposal` SET `invoice_id` = ? WHERE `id` = ?", array((int)$id, (int)$data['id']));
			return $id;
		} else {
			return false;
		}
	}

	public function addInvoicePayment($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "payments` (`invoice_id`, `method`, `amount`, `currency`, `payment_date`) VALUES (?, ?, ?, ?, ?)", array((int)$data['invoice'], (int)$data['method'], $data['amount'], (int)$data['currency'], $data['date']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function deleteInvoicePayment($data)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "payments` WHERE `id` = ? LIMIT 1", array((int)$data['id']));
		$payment = $query->row;
		
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "invoice` WHERE `id` = ? LIMIT 1", array((int)$payment['invoice_id']));
		$invoice = $query->row;

		$total['paid'] = $invoice['paid'] - $payment['amount'];
		$total['due'] = $invoice['due'] + $payment['amount'];

		$this->database->query("UPDATE `" . DB_PREFIX . "invoice` SET `paid` = ?, `due` = ? WHERE `id` = ?", array($total['paid'], $total['due'], (int)$invoice['id']));

		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "payments` WHERE `id` = ?", array((int)$payment['id'] ));
	}

	public function invoiceTotal($data)
	{
		$query = $this->database->query("SELECT `amount`, `paid`, `due` FROM `" . DB_PREFIX . "invoice` WHERE `id` = ? LIMIT 1", array((int)$data['invoice']));

		if ($query->num_rows > 0) {
			$total = $query->row;
			$total['paid'] = $total['paid'] + $data['amount'];
			$total['due'] = $total['due'] - $data['amount'];
			if ($total['due'] <= 0) {
				$status = "Paid";
			} else {
				$status = "Partially Paid";
			}
			$this->database->query("UPDATE `" . DB_PREFIX . "invoice` SET `paid` = ?, `due` = ?, `status` = ? WHERE `id` = ?", array($total['paid'], $total['due'], $status, (int)$data['invoice']));

			return true;
		} else {
			return false;
		}
	}
	
	public function deleteInvoice($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "invoice` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getRecurringInvoices($period, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT i.*, c.company, cr.abbr AS `abbr` FROM `" . DB_PREFIX . "recurring_invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.inv_date BETWEEN ? and ? ORDER BY i.inv_date DESC", array($period['start'], $period['end']));
		} else {
			$query = $this->database->query("SELECT i.*, c.company, cr.abbr AS `abbr` FROM `" . DB_PREFIX . "recurring_invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.inv_date BETWEEN ? and ? and c.user_id = ? ORDER BY i.inv_date DESC", array($period['start'], $period['end'], $user));
		}
		
		return $query->rows;
	}

	public function getRecurringInvoice($id, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "recurring_invoice` WHERE `id` = ? LIMIT 1", array((int)$id));
		} else {
			$query = $this->database->query("SELECT i.* FROM `" . DB_PREFIX . "recurring_invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer WHERE i.id = ? AND c.user_id = ? LIMIT 1", array((int)$id, $user));
		}

		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getRecurringInoviceView($id, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT i.*, c.company, c.email, c.address, p.name AS payment, cr.name AS currency_name, cr.abbr AS currency_abbr FROM `" . DB_PREFIX . "recurring_invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON i.customer = c.id LEFT JOIN `" . DB_PREFIX . "payment_method` AS p ON i.paymenttype = p.id LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.id = ? LIMIT 1", array((int)$id));
		} else {
			$query = $this->database->query("SELECT i.*, c.company, c.email, c.address, p.name AS payment, cr.name AS currency_name, cr.abbr AS currency_abbr FROM `" . DB_PREFIX . "recurring_invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON i.customer = c.id LEFT JOIN `" . DB_PREFIX . "payment_method` AS p ON i.paymenttype = p.id LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.id = ? AND c.user_id = ? LIMIT 1", array((int)$id, $user));
		}
		
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getInvoicesCreatedfromRecurring($id)
	{
		$query = $this->database->query("SELECT i.*, c.company, cr.abbr AS `abbr` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON i.currency = cr.id WHERE i.rid = ? ORDER BY i.date_of_joining DESC", array($id));
		return $query->rows;
	}

	public function createRecurringInvoice($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "recurring_invoice` (`customer`, `currency`, `paymenttype`, `items`, `subtotal`, `tax`, `discount_value`, `amount`, `note`, `tc`, `want_payment`, `want_signature`, `repeat_every`, `inv_status`, `inv_date`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array( (int)$data['customer'], (int)$data['currency'], (int)$data['paymenttype'], $data['item'], $data['subtotal'], $data['tax'], $data['discount_value'], $data['amount'], $data['note'], $data['tc'], $data['want_payment'], $data['want_signature'], $data['repeat_every'], $data['inv_status'], $data['inv_date'], $data['datetime']));

		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function updateRecurringInvoice($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "recurring_invoice` SET `customer` = ?, `currency` = ?, `paymenttype` = ?, `items` = ?, `subtotal` = ?, `tax` = ?, `discount_value` = ?, `amount` = ?, `note` = ?, `tc` = ?, `want_payment` = ?, `want_signature` = ?, `repeat_every` = ?, `inv_status` = ?, `inv_date` = ? WHERE `id` = ?", array((int)$data['customer'], (int)$data['currency'], (int)$data['paymenttype'], $data['item'], $data['subtotal'], $data['tax'], $data['discount_value'], $data['amount'], $data['note'], $data['tc'], $data['want_payment'], $data['want_signature'], $data['repeat_every'], $data['inv_status'], $data['inv_date'], (int)$data['id']));

		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function deleteRecurringInvoice($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "recurring_invoice` WHERE `id` = ?", array((int)$id ));
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
}