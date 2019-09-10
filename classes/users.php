<?php

namespace ma;

class Users {

	private static $users;
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
	private static $path;


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

				self::$users[$key] = new User($line_array);
			}
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