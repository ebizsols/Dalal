<?php

/**
 * Salarytemplate
 */
class Salarytemplate extends Model
{

	public function getSalarytemplates()
	{
		$query = $this->database->query("SELECT s.id, s.grade, s.basic_salary, s.gross_salary, s.net_salary, s.date_of_joining, c.abbr FROM `" . DB_PREFIX . "salarytemplate` AS s LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = s.currency ");
		return $query->rows;
	}

	public function getSalarytemplate($id)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "salarytemplate` WHERE id = ?", array((int)$id));
		return $query->row;
	}

	public function updateSalarytemplate($data)
	{
		$this->database->query("UPDATE `" . DB_PREFIX . "salarytemplate` SET `grade` = ?, `currency` = ?, `basic_salary` = ?, `allowance` = ?, `deduction` = ?, `gross_salary` = ?, `total_deduction` = ?, `net_salary` = ? WHERE `id` = ? ", array($this->database->escape($data['grade']), $data['currency'], $data['basic_salary'], $data['allowance'], $data['deduction'], $data['gross_salary'], $data['total_deduction'], $data['net_salary'], (int)$data['id']));
	}

	public function createSalarytemplate($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "salarytemplate` (`grade`, `currency`, `basic_salary`, `allowance`, `deduction`, `gross_salary`, `total_deduction`, `net_salary`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", array($data['grade'], $data['currency'], $data['basic_salary'], $data['allowance'], $data['deduction'], $data['gross_salary'], $data['total_deduction'], $data['net_salary'], $data['datetime']));

		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function deleteSalarytemplate($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "salarytemplate` WHERE `id` = ?", array((int)$id));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}