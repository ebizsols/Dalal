<?php

/**
* Ticket
*/
class Ticket extends Model
{
	public function getTickets($user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department ORDER BY t.date_of_joining DESC");
		} else {
			$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.user_id = '".$user."' ORDER BY t.date_of_joining DESC");
		}
		return $query->rows;
	}

	public function getOpenTickets($user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.status = 0 ORDER BY t.date_of_joining DESC");
		} else {
			$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.status = 0 AND t.user_id = '".$user."' ORDER BY t.date_of_joining DESC");
		}
		
		return $query->rows;
	}

	public function getClosedTickets($user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.status = 1 ORDER BY t.date_of_joining DESC");
		} else {
			$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.status = 1 AND t.user_id = '".$user."' ORDER BY t.date_of_joining DESC");
		}
		return $query->rows;
	}

	public function getTicketCount()
	{
		$query = $this->database->query("SELECT COUNT(*) AS count FROM `" . DB_PREFIX . "tickets` WHERE `status` = ?", array(1));
		$data['closed'] = $query->row['count'];

		$query = $this->database->query("SELECT COUNT(*) AS count FROM `" . DB_PREFIX . "tickets` WHERE `status` = ?", array(0));
		$data['open'] = $query->row['count'];

		$query = $this->database->query("SELECT COUNT(*) AS count FROM `" . DB_PREFIX . "tickets`");
		$data['all'] = $query->row['count'];

		return $data;
	}

	public function getStaff()
	{
		$query = $this->database->query("SELECT `user_id`, concat(`firstname`, ' ', `lastname`) AS `name`, `user_name` FROM `" . DB_PREFIX . "users` WHERE user_role != ? ", array(1));
		return $query->rows;
	}

	public function getDepartments()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "departments` WHERE `status` = ?", array(1));
		return $query->rows;
	}

	public function getTicket($id, $user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.id = '".(int)$id."' LIMIT 1");
		} else {
			$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.id = '".(int)$id."' AND t.user_id = '".$user."' LIMIT 1");
		}
		return $query->row;
	}

	public function getMessages($id)
	{
		$query = $this->database->query("SELECT t.*, CONCAT(`firstname`, ' ', `lastname` ) AS `user` FROM `" . DB_PREFIX . "tickets_message` AS t LEFT JOIN `" . DB_PREFIX . "users` AS u ON u.user_id = t.user_id WHERE t.ticket_id = ? ORDER BY t.id ASC", array((int)$id));
		return $query->rows;
	}

	public function updateTicket($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "tickets` SET `reply_status`= ?, `remark` = ?, `status` = ?, `user_id` = ?, `last_updated` = ? WHERE `id` = ?" , array(1, $data['remark'], (int)$data['status'], $data['staff'], $data['last_updated'], (int)$data['id']));

		if (!empty($data['descr'])) {
			$this->database->query("INSERT INTO `" . DB_PREFIX . "tickets_message` (`message`, `attached`, `message_by`, `ticket_id`, `user_id`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?)", array($data['descr'], $data['attached'], 1, $data['id'], $data['user_id'], $data['datetime']));
		}
	}

	public function createTicket($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "tickets` (`name`, `email`, `mobile`, `department`, `subject`, `priority`, `reply_status`, `remark`, `status`, `customer`, `user_id`, `last_updated`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['name']), $data['email'], $data['mobile'], $data['department'], $data['subject'], $data['priority'], 1, $data['remark'], 0, $data['customer'], $data['staff'], $data['last_updated'], $data['datetime']));
		
		if ($query->num_rows > 0) {
			$id = $this->database->last_id();
			if (!empty($data['descr'])) { 
				$this->database->query("INSERT INTO `" . DB_PREFIX . "tickets_message` (`message`, `attached`, `message_by`, `ticket_id`, `user_id`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?)", array($data['descr'], $data['attached'], 1, $id, $data['user_id'], $data['datetime']));
			}
			return $id;

		} else {
			return false;
		}
	}

	public function deleteTicket($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "tickets` WHERE `id` = ?", array((int)$id ));
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "tickets_message` WHERE `ticket_id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}