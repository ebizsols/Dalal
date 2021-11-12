<?php
/**
* Language class
*/
class Language {
	private $default = 'en-US';
	public $data = array();

	public function __construct($default = 'en-US') {
		$this->default = $default;
	}

	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}
	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function all()
	{
		return $this->data;
	}

	public function load($filename, $key = '')
	{
		if (!empty($key)) {
			$lang = array();
			$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';
			if (file_exists($file)) {
				require($file);
				// $this->data = $lang;
				$this->data = array_merge($this->data, $lang);
			}
		}
		return $this->data;
	}
}