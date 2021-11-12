<?php

/**
 * Finance
 */
class Finance extends Model
{
	public function getCurrency()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "currency`");
		return $query->rows;
	}

	public function updateCurrency($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "currency` SET `name` = ?, `abbr` = ?, `status` = ? WHERE `id` = ? ", array($this->database->escape($data['name']), $this->database->escape($data['abbr']), (int)$data['status'], (int)$data['id']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createCurrency($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "currency` (`name`, `abbr`, `status`) VALUES (?, ?, ?)", array($this->database->escape($data['name']), $this->database->escape($data['abbr']), (int)$data['status']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function deleteCurrency($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "currency` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getTaxes()
	{
		$query = $this->database->query("SELECT `id`, `name`, `rate` FROM `" . DB_PREFIX . "taxes`");
		return $query->rows;
	}

	public function updateTax($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "taxes` SET `name` = ?, `rate` = ? WHERE `id` = ? ", array($this->database->escape($data['name']), (float)$data['rate'], (int)$data['id']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createTax($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "taxes` (`name`, `rate`) VALUES (?, ?)", array($this->database->escape($data['name']), (float)$data['rate']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function deleteTax($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "taxes` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function getPaymentMethods()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "payment_method`");
		return $query->rows;
	}



	public function updatePaymentMethod($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "payment_method` SET `name` = ?, `description` = ?, `status` = ? WHERE `id` = ? ", array($this->database->escape($data['name']), $data['description'], (int)$data['status'], (int)$data['id']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createPaymentMethod($data)
	{
		$query = $this->database->query("INSERT INTO `" . DB_PREFIX . "payment_method` (`name`, `description`, `status`) VALUES (?, ?, ?)", array($this->database->escape($data['name']), $data['description'], (int)$data['status']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function deletePaymentMethod($id)
	{
		$query = $this->database->query("DELETE FROM `" . DB_PREFIX . "payment_method` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getPaypalGateway()
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE name = ? LIMIT 1", array('paypalgateway'));
		return $query->row;
	}

	public function updatePaymentGateway($data)
	{
		// $query = $this->database->query("UPDATE `" . DB_PREFIX . "payment_gateway` SET `username` = ?, `mode` = ?, `status` = ? WHERE `id` = ? ", array($this->database->escape($data['username']), (int)$data['mode'], (int)$data['status'] ,1));
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "setting` SET `data` = ?, `status` = ? WHERE `name` = ? ", array($data['paypal'], $data['status'], 'paypalgateway'));

		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}