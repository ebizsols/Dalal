<?php

/**
* Setting
*/
class Setting extends Model
{
	public function getInfo()
	{
		$query = $this->database->query("SELECT * FROM  `" . DB_PREFIX . "setting` WHERE `name` = ? LIMIT 1", array('siteinfo') );
		return $query->row['data'];
	}

	public function updateInfo($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "setting` SET `data` = ? WHERE `name` = ? ", array($data, 'siteinfo'));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getCustomization()
	{
		$query = $this->database->query("SELECT `data` FROM  `" . DB_PREFIX . "setting` WHERE `name` = ? LIMIT 1", array('admintheme') );
		return $query->row['data'];
	}

	public function updateCustomization($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "setting` SET `data` = ? WHERE `name` = ? ", array($data, 'admintheme'));
	}




	public function getCronSettings()
	{

		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `name` = ? LIMIT 1", array('cronsetting'));
		return $query->row;
	}

	public function updateSetting($data)
	{
		$query = $this->database->query("UPDATE `" . DB_PREFIX . "setting` SET `data` = ?, `status` = ? WHERE `name` = ? ", array($data['data'], $data['status'], 'cronsetting'));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}