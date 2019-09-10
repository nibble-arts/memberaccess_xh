<?php

namespace ma;

class Group {

	private $data;


	public function __construct($data = false) {

		$this->data = [];

		if ($data) {
			$this->set($data);
		}
	}


	// set group data by 
	public function set($data, $value = false) {

		// set data from assoz array
		if (is_array($data)) {

			// split users
			$data["users"] = $this->split_users($data["users"]);

			foreach($data as $key=>$entry) {
				$this->data[$key] = $entry;
			}
		}

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
		array_push($this->data["users"], $user);
	}


	// remove user from group
	public function remove_user($user) {

		if (($key = array_search($user, $this->data["users"])) !== false) {
			unset($this->data["users"][$key]);
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