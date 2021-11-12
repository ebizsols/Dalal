<?php

/**
* Ticket
*/
class Ticket extends model
{
	public function getTickets($email)
	{
		$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.email = '".$email."' ORDER BY t.date_of_joining DESC");
		return $query->rows;
	}

	public function getDepartments()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "departments` WHERE `status` = ?", array(1));
		return $query->rows;
	}

	public function getTicket($data)
	{
		$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.id = '".(int)$data['id']."' AND t.email = '".$data['common']['user']['email']."' LIMIT 1");

		return $query->row;
	}

	public function getTicketView($data)
	{
		$query = $this->database->query("SELECT t.*, d.name AS department FROM `" . DB_PREFIX . "tickets` As t LEFT JOIN `" . DB_PREFIX . "departments` AS d ON d.id = t.department WHERE t.id = '".(int)$data['id']."' AND t.email = '".$data['email']."' LIMIT 1");

		return $query->row;
	}

	public function getMessages($id)
	{
		$query = $this->database->query("SELECT t.*, CONCAT(`firstname`, ' ', `lastname` ) AS `user` FROM `" . DB_PREFIX . "tickets_message` AS t LEFT JOIN `" . DB_PREFIX . "users` AS u ON u.user_id = t.user_id WHERE t.ticket_id = ? ORDER BY t.id ASC", array((int)$id));
		
		return $query->rows;
	}

	public function updateTicket($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "tickets` SET `reply_status` = ?, `status` = ?, `last_updated` = ? WHERE `id` = ? AND `email` = ? " , array(0, 0, $data['last_updated'], (int)$data['id'], $data['email']));
		
		if (!empty($data['descr'])) {
			$this->database->query("INSERT INTO `" . DB_PREFIX . "tickets_message` (`message`, `attached`, `message_by`, `ticket_id`, `date_of_joining`) VALUES (?, ?, ?, ?, ?)", array($data['descr'], $data['attached'], 0, $data['id'], $data['datetime']));
		}
	}

	public function createTicket($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "tickets` (`name`, `email`, `mobile`, `department`, `subject`, `priority`, `reply_status`, `status`, `last_updated`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['name']), $data['email'], $data['mobile'], $data['department'], $data['subject'], $data['priority'], 0, 0, $data['last_updated'], $data['datetime']));
		
		if ($query->num_rows > 0) {
			$id = $this->database->last_id();
			if (!empty($data['descr'])) { 
				$this->database->query("INSERT INTO `" . DB_PREFIX . "tickets_message` (`message`, `attached`, `message_by`, `ticket_id`, `date_of_joining`) VALUES (?, ?, ?, ?, ?)", array($data['descr'], $data['attached'], 0, $id, $data['datetime']));
			}
			return $id;

		} else {
			return false;
		}
	}
}