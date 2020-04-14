<?php

namespace ma;

class Users {

	private static $users = [];
	private static $pattern = [
		"username",
		// "uuid",
		"hash",
		"fullname",
		"email",
		"id",
		"functions",
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

				self::add_user($key, $line_array, false);
			}
		}
	}


	public static function reset() {
		self::$users = [];
	}


	// add user
	// data is an array of key=>value pairs
	// data is User object: add
	public static function add_user($user, $data, $save = true) {

		$time = time();

		// add object
		if (is_object($data)) {
			$new_user = $data;
		}
		else {
			$new_user = new User(self::$pattern);
			$new_user->set($data);
		}

		// add status and times if not present
		if (!$new_user->created()) {
			$new_user->set("created", $time);
		}

		if (!$new_user->modified()) {
			$new_user->set("modified", $time);
		}

		if (!$new_user->status()) {
			$new_user->set("status", $time);
		}

		// if (!$new_user->uuid()) {
		// 	$new_user->set("uuid", uniqid("", true));
		// }

		self::$users[$user] = $new_user;

		// save as default
		if ($save !== false) {
			self::save();
		}
	}


	// remove user
	public static function remove_user($user) {

		if (self::user_exists($user)) {

			Log::add("user ".$user." removed");
			unset(self::$users[$user]);
		}

		self::save();
	}


	// update user
	public static function update_user($user, $data) {

		$time = time();

		if (self::user_exists($user)) {
			self::$users[$user]->set($data);
		}

		self::save();
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


	// get users array
	public static function get_users() {
		return self::$users;
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