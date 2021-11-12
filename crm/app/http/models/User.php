<?php
/**
* User Model
*/
class User extends Model
{
	public function allUsers()
	{
		$query = $this->database->query("SELECT u.*, ur.name AS `role` FROM `" . DB_PREFIX . "users` AS u LEFT JOIN `" . DB_PREFIX . "user_role` AS ur ON ur.id = u.user_role ORDER BY `date_of_joining` DESC");
		return $query->rows;
	}

	public function getUser($id)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "users` WHERE `user_id` = '".$id."' ");
		
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function checkUserName($data)
	{
		$query = $this->database->query("SELECT count(*) AS total FROM `" . DB_PREFIX . "users` WHERE `user_name` = ? AND `email` != ?", array($this->database->escape($data['username']), $this->database->escape($data['email'])));
		if ( $query->num_rows > 0 ) {
			return $query->row['total'];
		} else {
			return false;
		}
	}

	public function checkUserEmail($email)
	{
		$query = $this->database->query("SELECT count(*) AS total FROM `" . DB_PREFIX . "users` WHERE `email` = ? ", array($this->database->escape($email)));
		if ($query->num_rows > 0) {
			return $query->row['total'];
		} else{
			return false;
		}
	}

	public function updateUser($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "users` SET `user_role` = ?, `user_name` = ?, `firstname` = ?, `lastname` = ?, `mobile` = ?, `meta` = ?, `status` = ? WHERE `user_id` = ? AND `email` = ? " , array((int)$data['role'], $this->database->escape($data['username']), $this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['mobile']), $data['meta'], (int)$data['status'], (int)$data['id'], $this->database->escape($data['email'])));
		
		if ($query->num_rows > 0) { 
			return false;
		} else { 
			return true;
		}
	}

	public function createUser($data)
	{	
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "users` (`user_role`, `user_name`, `firstname`, `lastname`, `email`, `mobile`, `meta`, `password`, `temp_hash`, date_of_joining) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape((int)$data['role']), $this->database->escape($data['username']), $this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['email']), $this->database->escape($data['mobile']), $data['meta'], $data['password'], $data['hash'], $data['datetime']));
		
		if ($this->database->error()) {
			return $this->database->error();
		} else {
			return $this->database->last_id();
		}
	}

	public function deleteUser($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "users` WHERE `user_id` = ?", array((int)$id));
		if ($query->num_rows > 0) { 
			return true;
		} else {
			return false;
		}
	}
	
	public function getTemplate($id)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "template` WHERE `template` = ? LIMIT 1", array($id));
		return $query->row;
	}

	public function userRole()
	{
		$query = $this->database->query("SELECT `id`, `name` FROM `" . DB_PREFIX . "user_role`");
		return $query->rows;
	}

	public function getRoles()
	{
		$query = $this->database->query("SELECT `id`, `name`, `description`, `date_of_joining` FROM `" . DB_PREFIX . "user_role`");
		return $query->rows;
	}

	public function getRole($id)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "user_role` WHERE `id` = ?", array((int)$id));
		return $query->row;
	}

	public function addUserRole($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "user_role` (`name`, `description` ,`permission`) VALUES (?, ?, ?)", array($this->database->escape($data['name']), $data['description'], $data['permission']));

		return $this->database->last_id();
	}

	public function updateUserRole($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "user_role` SET `name` = ?, `description` = ?, `permission` = ? WHERE `id` = ?", array($this->database->escape($data['name']), $data['description'], $data['permission'], (int)$data['id']));
		return true;
	}

	public function deleteRole($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "user_role` WHERE `id` = ?", array((int)$id));
		if ($query->num_rows > 0) { 
			return true;
		} else {
			return false;
		}
	}
}