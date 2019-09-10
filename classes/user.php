<?php

namespace ma;

class User {

	private $data;


	public function __construct($data = false) {

		$this->data = [];

		if ($data) {
			$this->set($data);
		}
	}


	// set user data by 
	public function set($data, $value = false) {

		// set data from assoz array
		if (is_array($data)) {
			$this->data = $data;
		}

		elseif ($value !== false) {
			$this->data[$data] = $value;
		}
	}


	// if key=false return user data array
	// return key value if exists
	public function get ($key = false) {

		if ($key === false) {
			return $this->data;
		}

		elseif (isset($this->data[$key])) {
			return $this->data[$key];
		}

		else {
			return false;
		}
	}
}