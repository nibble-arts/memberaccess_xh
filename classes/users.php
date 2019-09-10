<?php

namespace ma;

class Users {

	private static $users = [];
	private static $pattern = [
		"username",
		"hash",
		"fullname",
		"email",
		"id",
		"status",
		"created",
		"modified"
	];
	private static $path = false;


	// load users from : separated text file
	// assign values by pattern array
	// first pattern entry is key
	public static function load($path) {

		self::$path = false;

		if ($data = File::read($path)) {

			self::$path = $path;
			self::$users = [];

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

				self::add_user($key, $line_array);
				// self::$users[$key] = new User($line_array);
			}
		}
	}


	// add user
	// data is an array of key=>value pairs
	public static function add_user($user, $data) {

		$time = time();

		$new_user = new User(array_combine(self::$pattern,array_fill(0, count(self::$pattern), "")));

		$new_user->set($data);

		$new_user->set("created", $time);
		$new_user->set("modified", $time);
		$new_user->set("status", $time);

		self::$users[$user] = new User($data);

	}


	// remove user
	public static function remove_user($user) {

		if (self::user_exists($user)) {

			unset(self::$users[$user]);
		}
	}


	// update user
	public static function update_user($user, $data) {

		$time = time();

		if (self::user_exists($user)) {

			self::$users[$user]->set($data);
		}
	}




	// save users
	public static function save() {

		if (self::$path) {
			File::write(self::$path, self::serialize());
		}
	}


	// return list of usernames
	public static function get_user_names() {
		return array_keys(self::$users);
	}


	// return user by username
	public static function get_user($username) {

		if (isset(self::$users[$username])) {
			return self::$users[$username];
		}

		else {
			return false;
		}
	}


	// user exists
	public static function user_exists($user) {

		if (self::get_user($user)) {
			return self::get_user($user);
		}
	}


	// serializet users to string
	public static function serialize () {

		$file_string = "";

		if (self::$pattern) {

			foreach (self::$users as $user) {

				$temp = [];

				// collect line by pattern key
				foreach (self::$pattern as $key) {

					$data = $user->get($key);

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
	}}

?>