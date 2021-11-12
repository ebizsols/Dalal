<?php

/**
* Lead
*/
class Report extends Model
{
	public function getIncomeReport()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.amount) AS `amount` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency GROUP BY i.currency");
		return $query->rows;
	}

	public function getLastWeekIncome()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.amount) AS `amount` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency WHERE YEARWEEK(i.date_of_joining) = YEARWEEK(NOW() - INTERVAL 1 WEEK) GROUP BY i.currency");
		return $query->rows;
	}

	public function getLastMonthIncome()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.amount) AS `amount` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency WHERE i.date_of_joining >= DATE_FORMAT( CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01' ) AND i.date_of_joining < DATE_FORMAT( CURRENT_DATE, '%Y/%m/01' ) GROUP BY i.currency");
		return $query->rows;
	}

	public function getLastYearIncome()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.amount) AS `amount` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency WHERE YEAR(i.date_of_joining) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))");
		return $query->rows;
	}

	public function getTaxReport()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.tax) AS `tax` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency GROUP BY i.currency");
		return $query->rows;
	}

	public function getLastWeekTax()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.tax) AS `tax` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency WHERE YEARWEEK(i.date_of_joining) = YEARWEEK(NOW() - INTERVAL 1 WEEK) GROUP BY i.currency");
		return $query->rows;
	}

	public function getLastMonthTax()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.tax) AS `tax` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency WHERE i.date_of_joining >= DATE_FORMAT( CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01' ) AND i.date_of_joining < DATE_FORMAT( CURRENT_DATE, '%Y/%m/01' ) GROUP BY i.currency");
		return $query->rows;
	}

	public function getLastYearTax()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.tax) AS `tax` FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency WHERE YEAR(i.date_of_joining) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) GROUP BY i.currency");
		return $query->rows;
	}

	public function getExpenseReport()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.purchase_amount) AS `amount` FROM `" . DB_PREFIX . "expenses` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency GROUP BY i.currency");
		return $query->rows;
	}

	public function getLastWeekExpense()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.purchase_amount) AS `amount` FROM `" . DB_PREFIX . "expenses` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency WHERE YEARWEEK(i.purchase_date) = YEARWEEK(NOW() - INTERVAL 1 WEEK) GROUP BY i.currency");
		return $query->rows;
	}

	public function getLastMonthExpense()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.purchase_amount) AS `amount` FROM `" . DB_PREFIX . "expenses` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency WHERE i.purchase_date >= DATE_FORMAT( CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01' ) AND i.purchase_date < DATE_FORMAT( CURRENT_DATE, '%Y/%m/01' ) GROUP BY i.currency");
		return $query->rows;
	}

	public function getLastYearExpense()
	{
		$query = $this->database->query("SELECT c.name, c.abbr, SUM(i.purchase_amount) AS `amount` FROM `" . DB_PREFIX . "expenses` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency WHERE YEAR(i.purchase_date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) GROUP BY i.currency");
		return $query->rows;
	}
}