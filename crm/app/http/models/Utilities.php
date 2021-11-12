<?php

/**
* Utilities
*/
class Utilities extends Model
{
	public function getEmailLogs($period)
	{
		$query = $this->database->query("SELECT ut.*, u.user_name, CONCAT(u.firstname, ' ', u.lastname) AS user FROM `" . DB_PREFIX . "email_logs` AS ut LEFT JOIN `" . DB_PREFIX . "users` AS u ON u.user_id = ut.user_id WHERE ut.date_of_joining between ? and ? ORDER BY ut.date_of_joining DESC", array($period['start'], $period['end']));
		return $query->rows;
	}

	public function getCronLogs()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "recurring_log` ORDER BY date_of_joining DESC");
		return $query->rows;
	}
	

	public function DBdump()
	{
		return $this->database->dumpDatabase();
	}
}