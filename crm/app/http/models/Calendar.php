<?php

/**
* Calendar
*/
class Calendar extends Model
{
	public function getEvents($request, $user = NULL)
	{
		$query = $this->database->query("SELECT id, title, description, start_date, start_time, end_date, end_time, CONCAT(start_date, 'T', start_time) AS start, CONCAT(end_date, 'T', end_time) AS end, color, IF(allday = 1, 1, 0) AS `allDay` FROM `" . DB_PREFIX . "event` WHERE start_date between ? and ? ", array($request['start'], $request['end']));
		return $query->rows;
	}
	
	public function getUserEvents($request, $user)
	{
		// $query = $this->database->query("SELECT `id`, `title`, DATE_FORMAT(`start`, '%Y-%m-%d') AS start_date, DATE_FORMAT(`start`, '%H:%i:%s') AS start_time, DATE_FORMAT(`end`, '%Y-%m-%d') AS `end_date`, DATE_FORMAT(`end`, '%H:%i:%s') AS `end_time`, IF(allday = 1, 1, 0) AS `allDay`, `description` FROM `" . DB_PREFIX . "event` WHERE `user_id` = ?", array((int)$id));
		$query = $this->database->query("SELECT id, title, description, start_date, start_time, end_date, end_time, CONCAT(start_date, 'T', start_time) AS start, CONCAT(end_date, 'T', end_time) AS end, color, IF(allday = 1, 1, 0) AS `allDay` FROM `" . DB_PREFIX . "event` WHERE start_date between ? and ? and user_id = ?", array($request['start'], $request['end'], (int)$user));
		return $query->rows;
	}

	public function getEvent($id)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "contacts` WHERE `id` = ? LIMIT 1", array((int)$id));
		return $query->row;
	}

	public function updateEvent($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "event` SET `title` = ?, `description` = ?, `start_date` = ?, `start_time` = ?,  `end_date` = ?, `end_time` = ?, `allday` = ? WHERE `id` = ? ", array($data['title'], $data['description'], $this->database->escape($data['start_date']), $this->database->escape($data['start_time']), $this->database->escape($data['end_date']), $this->database->escape($data['end_time']), (int)$data['allday'], (int)$data['id']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createEvent($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "event` (`title`, `description`, `start_date`, `start_time`, `end_date`, `end_time`, `allday`, `user_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", array($data['title'], $data['description'], $data['start_date'], $data['start_time'], $data['end_date'], $data['end_time'], (int)$data['allday'], (int)$data['user_id']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function dropEvent($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "event` SET `start` = ? WHERE `id` = ? ", array($this->database->escape($data['start']), (int)$data['id']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function deleteEvent($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}