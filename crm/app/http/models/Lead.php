<?php

/**
* Lead
*/
class Lead extends Model
{
	public function getLeads($user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "leads` ORDER BY `date_of_joining` DESC");
		} else {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "leads` WHERE `user_id` = ? ORDER BY `date_of_joining` DESC",array($user));
		}
		return $query->rows;
	}

	public function getLead($id, $user = NULL)
	{
		if (!$user) {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "leads` WHERE `id` = ? LIMIT 1", array((int)$id));
		} else {
			$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "leads` WHERE `id` = ? AND `user_id` = ? LIMIT 1", array((int)$id, $user));
		}
		return $query->row;
	}

	public function getStaff()
	{
		$query = $this->database->query("SELECT `user_id`, concat(`firstname`, ' ', `lastname`) AS `name`, `user_name` FROM `" . DB_PREFIX . "users` WHERE user_role != ? ", array(1));
		return $query->rows;
	}

	public function updateLead($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "leads` SET `salutation` = ?, `firstname` = ?, `lastname` = ?, `company` = ?, `email` = ?, `phone` = ?, `website` = ?, `address` = ?, `country` = ?, `remark` = ?, `source` = ?, `marketing` = ?, `expire` = ?, `status` = ?, `user_id` = ? WHERE `id` = ? ", array($this->database->escape($data['salutation']), $this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['company']), $this->database->escape($data['email']), $this->database->escape($data['phone']), $this->database->escape($data['website']), $data['address'], $this->database->escape($data['country']), $data['remark'], $data['source'], $data['marketing'], $data['expire'], $data['status'], $data['staff'], (int)$data['id']));
	}

	public function createLead($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "leads` (`salutation`, `firstname`, `lastname`, `company`, `email`, `phone`, `website`, `address`, `country`, `remark`, `source`, `marketing`, `expire`, `status`, `user_id`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['salutation']), $this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['company']), $this->database->escape($data['email']), $this->database->escape($data['phone']), $this->database->escape($data['website']), $data['address'], $this->database->escape($data['country']), $data['remark'], $data['source'], $data['marketing'], $data['expire'], $data['status'], $data['staff'], $data['datetime']));

		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function convertLeadToContact($data)
	{
		$query = $this->database->query("SELECT `id` FROM `" . DB_PREFIX . "contacts` WHERE `lead_id` = ?", array((int)$data['id']));

		if ($query->num_rows < 1) {
			$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "contacts` (`salutation`, `firstname`, `lastname`, `company`, `email`, `phone`, `website`, `address`, `country`, `remark`, `marketing`, `expire`, `lead_id`, `user_id`, date_of_joining) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['salutation']), $this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['company']), $this->database->escape($data['email']), $this->database->escape($data['phone']), $this->database->escape($data['website']), $data['address'], $this->database->escape($data['country']), $data['remark'], $data['marketing'], $data['expire'], $data['id'], $data['user_id'], $data['datetime']));
			
			if ($query->num_rows > 0) {
				$id = $this->database->last_id();
				$this->database->query("UPDATE `" . DB_PREFIX . "leads` SET `contact_id` = ?, `status` = ? WHERE `id` = ? ", array($id, 6, (int)$data['id']));

				return $id;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function deleteLead($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "leads` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

}