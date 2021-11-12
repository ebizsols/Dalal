<?php

/**
* Expense
*/
class Expense extends Model
{

	public function getExpenses($period, $user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT e.*, et.name, c.abbr FROM `" . DB_PREFIX . "expenses` AS e LEFT JOIN `" . DB_PREFIX . "expense_type` AS et ON et.id = e.expense_type LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = e.currency WHERE e.purchase_date BETWEEN ? and ? ORDER BY e.purchase_date DESC", array($period['start'], $period['end']));	
		} else {
			$query = $this->database->query("SELECT e.*, et.name, c.abbr FROM `" . DB_PREFIX . "expenses` AS e LEFT JOIN `" . DB_PREFIX . "expense_type` AS et ON et.id = e.expense_type LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = e.currency WHERE e.purchase_date BETWEEN ? and ? and e.user_id = ? ORDER BY e.purchase_date DESC", array($period['start'], $period['end'], $user));
		}
		return $query->rows;
	}

	public function getExpense($id, $user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "expenses` WHERE `id` = ? LIMIT 1", array($id));
		} else {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "expenses` WHERE `id` = ? AND `user_id` = ? LIMIT 1", array($id, $user));
		}
		return $query->row;
	}

	public function getCurrency()
	{
		$query = $this->database->query("SELECT `id`, `name`, `abbr` FROM `" . DB_PREFIX . "currency` WHERE `status` = ?", array(1));
		return $query->rows;
	}
	
	public function expensesType()
	{
		$query = $this->database->query("SELECT `id`, `name` FROM `" . DB_PREFIX . "expense_type` WHERE `status` = ? ", array(1));
		return $query->rows;
	}

	public function getReceipt($id)
	{
		$query = $this->database->query("SELECT `id`, `file_name`, `ext` FROM `" . DB_PREFIX . "attached_files` WHERE `file_type` = ? AND `file_type_id` = ?", array('expense', $id));
		return $query->rows;
	}

	public function paymentType()
	{
		$query = $this->database->query("SELECT `id`, `name` FROM `" . DB_PREFIX . "payment_method` WHERE `status` = ? ", array(1));
		return $query->rows;
	}

	public function updateExpense($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "expenses` SET `purchase_by` = ?, `expense_type` = ?, `currency` = ?, `purchase_amount` = ?, `payment_type` = ?, `purchase_date` = ?, `description` = ? WHERE `id` = ?", array($this->database->escape($data['purchaseby']), (int)$data['expensetype'], (int)$data['currency'], $this->database->escape($data['amount']), (int)$data['paymenttype'], $data['purchasedate'], $data['description'], (int)$data['id']));

		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createExpense($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "expenses` (`purchase_by`, `expense_type`, `currency`, `purchase_amount`, `payment_type`, `purchase_date`, `description`, `user_id`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", array( $this->database->escape($data['purchaseby']), (int)$data['expensetype'], (int)$data['currency'], $this->database->escape($data['amount']), (int)$data['paymenttype'], $data['purchasedate'], $data['description'], $data['user_id'], $data['datetime']));
		
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function deleteExpense($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "expenses` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}