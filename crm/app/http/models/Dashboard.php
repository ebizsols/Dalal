<?php

/**
* Dashboard Model
*/

class Dashboard extends Model
{
	public function getMainStatistics()
	{
		$query = $this->database->query("SELECT COUNT(id) AS total FROM `" . DB_PREFIX . "contacts`");
		$data['contacts'] = $query->row['total'];
		$query = $this->database->query("SELECT COUNT(id) AS total FROM `" . DB_PREFIX . "invoice`");
		$data['invoice'] = $query->row['total'];
		$query = $this->database->query("SELECT COUNT(id) AS total FROM `" . DB_PREFIX . "proposal`");
		$data['quotes'] = $query->row['total'];
		$query = $this->database->query("SELECT COUNT(id) AS total FROM `" . DB_PREFIX . "projects`");
		$data['projects'] = $query->row['total'];
		return $data;
	}

	public function getOtherStatistics()
	{
		$query = $this->database->query("SELECT SUM(tax) AS tax, SUM(discount_value) AS discount, SUM(amount) AS amount, SUM(paid) AS paid, SUM(due) AS due FROM `" . DB_PREFIX . "invoice`");
		$data['invoice'] = $query->row;

		$query = $this->database->query("SELECT SUM(purchase_amount) AS amount FROM `" . DB_PREFIX . "expenses`");
		$data['expense'] = $query->row['amount'];
		//$data['expense'] = number_format((float)$query->row['amount'], 2, '.', '');

		$query = $this->database->query("SELECT SUM(amount) AS amount FROM `" . DB_PREFIX . "staff_payment`");
		$data['salary'] = $query->row['amount'];

		return $data;
	}

	public function getChartIncome()
	{
		$query = $this->database->query("SELECT SUM(amount) AS amount, MONTH(invoicedate) AS month FROM `" . DB_PREFIX . "invoice` WHERE invoicedate > DATE_SUB(now(), INTERVAL 12 MONTH) GROUP BY MONTH(invoicedate)");
		return $query->rows;
	}

	public function getChartInvoiceStatus()
	{
		$query = $this->database->query("SELECT COUNT(status) AS value, status AS label FROM `" . DB_PREFIX . "invoice` WHERE invoicedate > DATE_SUB(now(), INTERVAL 12 MONTH) GROUP BY status");
		return $query->rows;
	}


	public function getChartExpense($user_id = NULL)
	{
		$query = $this->database->query("SELECT SUM(purchase_amount) AS amount, MONTH(purchase_date) AS month FROM `" . DB_PREFIX . "expenses` WHERE purchase_date > DATE_SUB(now(), INTERVAL 12 MONTH) GROUP BY MONTH(purchase_date)");
		return $query->rows;
	}

	public function getChartExpensebyCategory()
	{
		$query = $this->database->query("SELECT SUM(e.purchase_amount) AS value, et.name AS label FROM `" . DB_PREFIX . "expenses` AS e LEFT JOIN `" . DB_PREFIX . "expense_type` AS et ON et.id = e.expense_type GROUP BY expense_type");
		return $query->rows;
	}

	public function chartContact()
	{
		$query = $this->database->query("SELECT COUNT(id) AS amount, MONTH(date_of_joining) AS month FROM `" . DB_PREFIX . "contacts` WHERE date_of_joining > DATE_SUB(now(), INTERVAL 12 MONTH) GROUP BY MONTH(date_of_joining)");
		return $query->rows;
	}

	public function chartLeads()
	{
		$query = $this->database->query("SELECT COUNT(id) AS amount, MONTH(date_of_joining) AS month FROM `" . DB_PREFIX . "leads` WHERE date_of_joining > DATE_SUB(now(), INTERVAL 12 MONTH) GROUP BY MONTH(date_of_joining)");
		return $query->rows;
	}

	public function chartSalary()
	{
		$month = date("Y-m", strtotime( date( 'Y-m-01' )." -12 months"));
		$query = $this->database->query("SELECT SUM(amount) AS amount, month FROM `" . DB_PREFIX . "staff_payment` WHERE month_year > '".$month."' GROUP BY month");
		return $query->rows;
	}

	public function getChartTransaction()
	{
		$query = $this->database->query("SELECT SUM(credit) AS credit, SUM(debit) AS debit, MONTH(date) AS month FROM `" . DB_PREFIX . "account_transaction` WHERE date > DATE_SUB(now(), INTERVAL 12 MONTH) GROUP BY MONTH(date)");
		return $query->rows;
	}

	public function getIncomeExpenseStats()
	{
		$query = $this->database->query("SELECT SUM(amount) AS invoice FROM `" . DB_PREFIX . "invoice` WHERE invoicedate > DATE_SUB(now(), INTERVAL 12 MONTH)");
		$data['income_12'] = $query->row['invoice'];

		$query = $this->database->query("SELECT SUM(amount) AS invoice FROM `" . DB_PREFIX . "invoice` WHERE invoicedate > DATE_SUB(now(), INTERVAL 1 MONTH)");
		$data['income_1'] = $query->row['invoice'];

		$query = $this->database->query("SELECT SUM(purchase_amount) AS expense FROM `" . DB_PREFIX . "expenses` WHERE purchase_date > DATE_SUB(now(), INTERVAL 12 MONTH)");
		$data['expenses_12'] = $query->row['expense'];

		$query = $this->database->query("SELECT SUM(purchase_amount) AS expense FROM `" . DB_PREFIX . "expenses` WHERE purchase_date > DATE_SUB(now(), INTERVAL 1 MONTH)");
		$data['expenses_1'] = $query->row['expense'];

		return $data;
	}


	public function getContacts()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "contacts` ORDER BY id DESC LIMIT 5");
		return $query->rows;
	}


	public function getTickets()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "tickets` ORDER BY id DESC LIMIT 5");
		return $query->rows;
	}

	public function getInvoices()
	{
		$query = $this->database->query("SELECT i.*, c.company, cr.abbr FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = i.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON cr.id = i.currency ORDER BY i.id DESC LIMIT 5");
		return $query->rows;
	}

	public function getNotices()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "noticeboard` WHERE end_date >= '".date('Y-m-d')."' ");
		return $query->rows;
	}
}