<?php

/**
* Items
*/
class Items extends Model
{
	public function getItems()
	{
		$query = $this->database->query("SELECT i.*, c.abbr FROM `" . DB_PREFIX . "items` AS i LEFT JOIN `" . DB_PREFIX . "currency` AS c ON c.id = i.currency ORDER BY i.date_of_joining DESC");
		return $query->rows;
	}

	public function getItem($id)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "items` WHERE `id` = ? LIMIT 1", array((int)$id));
		return $query->row;
	}

	public function getSearchedItems($data)
	{
		$query = $this->database->query("SELECT `id`, `name` AS `label`, `price` AS `cost`, `description` AS `desc` FROM `" . DB_PREFIX . "items` WHERE name like '%".$data."%' LIMIT 5");
		return $query->rows;
	}

	public function updateItem($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "items` SET `name` = ?, `type` = ?, `unit` = ?, `currency` = ?, `price` = ?, `description` = ? WHERE `id` = ? ", array($data['name'], (int)$data['type'], $this->database->escape($data['unit']), (int)$data['currency'], (float)$data['price'], $data['description'], (int)$data['id']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createItem($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "items` (`name`, `type`, `unit`, `currency`, `price`, `description`, `date_of_joining`) VALUES (?, ?, ?, ?, ?, ?, ?)", array($data['name'], (int)$data['type'], $this->database->escape($data['unit']), (int)$data['currency'], (float)$data['price'], $data['description'], $data['datetime']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function deleteItem($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "items` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}