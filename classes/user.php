<?php

namespace ma;

class User {

	private $data;


	// construct object
	// data is array of keys
	public function __construct($data = false) {

		if ($data) {

			// convert pattern to keys
			$data = array_combine($data,array_fill(0, count($data), ""));

			$this->set($data);
		}
	}


	// set user data by 
	public function set($data, $value = false) {

		// set data from assoz array
		if (is_array($data)) {

			foreach($data as $key=>$entry) {
				$this->data[$key] = $entry;
			}
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


	// user is in group
	public function is_in_group($group) {
		debug(Groups::user_is_in_group($this->data["username"], $group));
	}


	// magic method
	public function __call($key, $attr) {
		return $this->get($key);
	}
}