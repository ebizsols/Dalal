<?php

/**
* Payment
*/
class Payment extends Model
{
	
	public function addInvoicePayment($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "payments` (`invoice_id`, `method`, `amount`, `currency`, `payment_date`) VALUES (?, ?, ?, ?, ?)", array((int)$data['invoice'], (int)$data['method'], $data['amount'], (int)$data['currency'], $data['date']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
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
}