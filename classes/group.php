<?php

namespace ma;

class Group {

	private $data;


	// create group object
	// optional: set pattern
	// optional: add data values
	// pattern: array of keys
	// values: key => value pairs
	public function __construct($pattern = false, $value = false) {

		$this->data = [];

		if (is_array($pattern)) {

			// create empty values
			$value = array_combine($pattern,array_fill(0, count($pattern), ""));

			$this->set($value);
		}
	}


	// set group data by 
	// if data is array: key => value pairs
	// if data is key string: value is value string
	public function set($data, $value = false) {

		// set data from assoz array
		if (is_array($data)) {

			foreach($data as $key=>$entry) {
				$this->data[$key] = $entry;
			}

			// split users
			if (isset($this->data["users"])) {
				$this->data["users"] = array_filter($this->split_users($data["users"]));
			}
		}

		// set key => value
		elseif ($value !== false) {

			// split users
			if ($data == "users") {
				$value = $this->split_users($value);
			}

			$this->data[$data] = $value;
		}
	}


	// if key=false return group data array
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


	// add user
	public function add_user($user) {

		if (!in_array($user, $this->data["users"])) {
			array_push($this->data["users"], $user);
		}
	}


	// remove user from group
	public function remove_user($user) {

		if (($key = array_search($user, $this->data["users"])) !== false) {
			unset($this->data["users"][$key]);

			$this->data["users"] = array_filter($this->data["users"]);
		}
	}


	// check if group has user
	public function has_user($user) {

		if (in_array($user, $this->data["users"])) {
			return true;
		}

		else {
			return false;
		}
	}


	// magic method
	public function __call($key, $attr) {
		return $this->get($key);
	}


	// split komma separated list of users
	private function split_users($users) {
		return explode(",", $users);
	}
}