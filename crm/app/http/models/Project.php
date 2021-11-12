<?php

/**
* Project
*/
class Project extends Model
{
	public function getProjects($user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT p.id, p.name, p.customer, p.description, p.completed, p.start_date, p.due_date, p.date_of_joining, c.company FROM `" . DB_PREFIX . "projects` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = p.customer ORDER BY p.date_of_joining DESC");
		} else {
			$query = $this->database->query("SELECT p.id, p.name, p.customer, p.description, p.completed, p.date_of_joining, c.company FROM `" . DB_PREFIX . "projects` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = p.customer WHERE c.user_id = ? ORDER BY p.date_of_joining DESC",array($user));
		}
		return $query->rows;
	}

	public function getCustomers($user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT `id`, `company` FROM `" . DB_PREFIX . "contacts`");
		} else {
			$query = $this->database->query("SELECT `id`, `company` FROM `" . DB_PREFIX . "contacts` WHERE `user_id` = ?",array($user));
		}
		return $query->rows;
	}

	public function getCurrency()
	{
		$query = $this->database->query("SELECT `id`, `name`, `abbr` FROM `" . DB_PREFIX . "currency` WHERE `status` = ?", array(1));
		return $query->rows;
	}

	public function getStaff()
	{
		$query = $this->database->query("SELECT `user_id`, CONCAT ( firstname, ' ', lastname ) AS `name`, `email` FROM `" . DB_PREFIX . "users`");
		return $query->rows;
	}

	public function getProjectView($id, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT p.*, c.company, cr.abbr FROM `" . DB_PREFIX . "projects` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = p.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON cr.id = p.currency WHERE p.id = ? LIMIT 1", array((int)$id));
		} else {
			$query = $this->database->query("SELECT p.*, c.company, cr.abbr FROM `" . DB_PREFIX . "projects` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = p.customer LEFT JOIN `" . DB_PREFIX . "currency` AS cr ON cr.id = p.currency WHERE p.id = ? AND c.user_id = ? LIMIT 1", array((int)$id, $user));
		}
		return $query->row;
	}

	public function getProject($id, $user = false)
	{
		if (!$user) {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "projects` WHERE `id` = ? LIMIT 1", array((int)$id));
		} else {
			$query = $this->database->query("SELECT p.* FROM `" . DB_PREFIX . "projects` AS p LEFT JOIN `" . DB_PREFIX . "contacts` AS c ON c.id = p.customer WHERE p.id = ? AND c.user_id = ? LIMIT 1", array((int)$id, $user));
		}
		return $query->row;
	}

	public function getDocuments($id)
	{
		$query = $this->database->query("SELECT `id`, `file_name`, `ext` FROM `" . DB_PREFIX . "attached_files` WHERE `file_type` = ? AND `file_type_id` = ?", array('project', $id));
		return $query->rows;
	}

	public function getComments($id)
	{
		$query = $this->database->query("SELECT c.*, CONCAT ( u.firstname, ' ', u.lastname ) AS `user` FROM `" . DB_PREFIX . "comments` AS c LEFT JOIN `" . DB_PREFIX . "users` AS u ON u.user_id = c.comment_by WHERE c.to_id = ? AND c.comment_to = ? ORDER BY c.date_of_joining DESC", array((int)$id, "project"));
		return $query->rows;
	}

	public function updateProject($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "projects` SET `name` = ?, `description` = ?, `customer` = ?, `billing_method` = ?, `currency` = ?, `rate_hour` = ?, `project_hour` = ?, `total_cost` = ?, `staff` = ?, `task` = ?, `completed` = ?, `start_date` = ?, `due_date` = ? WHERE `id` = ? ", array($this->database->escape($data['name']), $data['description'], (int)$data['customer'], (int)$data['billingmethod'], (int)$data['currency'], $data['ratehour'], $data['projecthour'], $data['totalcost'], $data['staff'], $data['task'], $data['completed'], $data['start_date'], $data['due_date'], (int)$data['id']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createProject($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "projects` (`name`, `description`, `customer`, `billing_method`, `currency`, `rate_hour`, `project_hour`, `total_cost`, `staff`, `task`, `completed`, `start_date`, `due_date`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['name']), $data['description'], (int)$data['customer'], (int)$data['billingmethod'], (int)$data['currency'], $data['ratehour'], $data['projecthour'], $data['totalcost'], $data['staff'], $data['task'], $data['completed'], $data['start_date'], $data['due_date'], $data['datetime']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function createComment($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "comments` (`comment`, `comment_by`, `comment_to`, `to_id`) VALUES (?, ?, ?, ?)", array($data['comment'], (int)$data['comment_by'], $data['comment_to'], (int)$data['id']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function deleteProject($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "projects` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}