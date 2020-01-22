<?php

namespace ma;

class Groups {

	private static $groups = [];
	private static $pattern = [
		"group",
		"users",
		"created",
		"modified"
	];
	private static $path = false;


	// load groups from : separated text file
	// assign values by pattern array
	// first pattern entry is key
	public static function load($path) {

		if ($data = File::read($path)) {

			self::$path = $path;

			$ret = [];
			$lines = explode ("\n", $data);

			// iterate lines
			foreach ($lines as $line) {

				// live not empty
				if (strlen($line) > 0) {
					
					$line_array = [];
					$parts = explode (":", $line);

					// set key/val pairs
					foreach (self::$pattern as $key) {
						
						$val = array_shift($parts);
						$line_array[$key] = trim($val);
					}
					// use first pattern value as key
					$key = $line_array[array_keys($line_array)[0]];
				}

				self::add_group($key, $line_array, false);
			}
		}
	}


	// add group to list
	// data is an array of key=>value pairs
	// overwrites if already exists
	public static function add_group($group, $data) {

		$time = time();

		$new_group = new Group(self::$pattern);

		$new_group->set($data);

		$new_group->set("created", $time);
		$new_group->set("modified", $time);

		self::$groups[$group] = $new_group;
	}


	public static function remove_group($group) {

		if (self::group_exists($group)) {
			unset(self::$groups[$group]);
		}
	}


	// add user to groups
	public static function add_user_to_group($user, $groups) {

		if (is_string($groups)) {
			$groups = explode(",", $groups);
		}

		foreach ($groups as $group) {

			if ($new_group = self::group_exists($group)) {

				$new_group->add_user($user);
			}
		}

	}


	// removes a user from all groups
	public static function remove_user_from_groups($user) {

		$groups = self::get_groups_of_user($user);

		foreach ($groups as $group) {
			self::remove_user_from_group($user, $group);
		}
	}


	public static function remove_user_from_group($user, $groupName) {

		if ($group = self::group_exists($groupName)) {
			$group->remove_user($user);
		}
	}


	// save groups
	public static function save() {

		if (self::$path) {
			File::write(self::$path, self::serialize());
		}
	}


	// check if group exists
	// return group
	// or false
	public static function group_exists($groups) {

		// if komma separated string, make array
		if (is_string($groups)) {
			$groups = explode(",", $groups);
		}

		// check all groups
		foreach ($groups as $group) {

			if (isset(self::$groups[$group])) {
				return self::$groups[$group];
			}
		}

		return false;
	}


	// group has user
	public static function group_has_user($group, $user) {

		if (($group = self::get_group($group))) {
			return $group->has_user($user);
		}
	}


	// user is in groups
	// groups can be array or komma separated list
	public static function user_is_in_group ($user, $groups) {

		if (is_string($groups)) {
			$groups = array_filter(explode(",", $groups));
		}

		if ($groups && count($groups)) {

			foreach ($groups as $group) {

				$users = self::get_users($group);

				if ($users && in_array($user, $users)) {
					return $group;
				}
			}

			// no group found
			return false;
		}

		// empty group list -> grant
		else {
			return true;
		}

	}


	// return list of groupnames
	public static function get_group_names() {
		return array_keys(self::$groups);
	}


	// return group by groupname
	public static function get_group($groupname) {

		if (isset(self::$groups[$groupname])) {
			return self::$groups[$groupname];
		}

		else {
			return false;
		}
	}


	// returns an array of users of a group
	public static function get_users($groupname) {

		if (($group = self::get_group($groupname)) !== false) {
			return $group->get("users");
		}
	}


	// returns an array of groups of a user
	public static function get_groups_of_user($user) {

		$temp = [];

		foreach (self::$groups as $group) {
			if (self::user_is_in_group($user, $group->group())) {
				$temp[] = $group->group();
			}
		}

		return $temp;
	}


	// get groups array
	public static function get_groups() {
		return array_filter(self::$groups);
	}


	// serializet groups to string
	public static function serialize () {

		$file_string = "";

		if (self::$pattern) {

			foreach (self::$groups as $group) {

				$temp = [];

				// collect line by pattern key
				foreach (self::$pattern as $key) {

					$data = $group->get($key);

					// implode users list
					if (is_array($data)) {
						$data = implode(",", $data);
					}

					$temp[] = $data;
				}

				$file_string .= implode (":", $temp) . "\n";
			}
		}

		return $file_string;
	}


	// return debug infos
	public static function debug() {

		return self::$groups;
	}
}

?>