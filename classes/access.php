<?php

namespace ma;

class Access {
	
	private static $config;
	
	private static $path = false;
	
	private static $logged = false;

	private static $user;

	private static $username;
	private static $fullname;
	private static $email;
	private static $groups;
	
	private static $admin = false;
	


	// NEW

	// ================================================
	// pattern for the user data format
	private static $users_pattern = [
		"username",
		"hash",
		"fullname",
		"email",
		"id",
		"status",
		"created",
		"modified"
	];
	
	// pattern for the groups data format
	private static $group_pattern = [
		"group",
		"users",
		"created",
		"modified"
	];


	// ================================================
	// initialise access class
	public static function init ($config, $text) {

		self::$config = $config["memberaccess"];

		Session::load();
		View::init($text);

		Users::load(self::config("basepath") . "memberaccess/users.txt");
		Groupss::load(self::config("basepath") . "memberaccess/group.txt");

// debug(Groupss::get_group_names());

// Groupss::add_user_to_group("ich","admin");
// Groupss::remove_user_from_group("adsf","admin");
Users::remove_user("test");
Users::save();

debug(Users::get_user("tom"));
// debug(Groupss::get_users("admin"));


		// load users
		// DEPRECATED
		// self::load(self::config("basepath"));
		// END DEPRECATED

	}


	// ================================================
	// execute actions
	public static function action () {
		
		$o = "";

		self::reset();


//TODO change ma_logged to session parameter


		// user ist logged
		if (($user = Session::session("ma_user")) && Session::session("ma_logged")) {

			$user_data = USERS::get_user($user);

			// $user_data = Access::users($user, true);

			// is valid user
			if ($user_data) {
				self::set_user($user_data);
				self::$logged = true;
			}

		}


		// execute actions
		switch (Session::param("action")) {
			
			case "ma_login":

				// login user
					if (($user = Session::get("ma_user")) && ($password = Session::get("ma_password"))) {

						$user_data = Access::users($user, true);

						// user found
						if ($user_data) {

							$hash = $user_data->hash();
							$fullname = $user_data->fullname();
							$email = $user_data->email();

							// check for corret data
							if (Hash::verify($password, $hash)) {

								Message::reset();

								self::$logged = true;


								Session::set_session("ma_user", $user);
								Session::set_session("ma_logged", true);

								self::set_user($user_data);

								Message::success("logged");

								// reload session
								// Session::load();
							}

							// password incorrect
							else {
								Message::failure("user_pass_failure");
							}
						}

						// username incorrect
						else {
							Message::failure("user_pass_failure");
						}
					}

					else {
						Message::failure("user_pass_missing");
					}
				break;
			

				// logout user
			case "ma_logout":

				self::reset();

				// remove session values
				Session::remove("ma_logged");
				Session::remove("ma_user");

				break;
			

			case "ma_get_password":


				// check user
				$user = Access::users(Session::param("ma_username"), true);

				// user exists
				if ($user) {

					if(Session::param("ma_username") == $user->get("username") && ($email = Session::param("ma_email")) == $user->email()) {


						// create random password
						$pwd = bin2hex(openssl_random_pseudo_bytes(6));

						// set password
						$update = new Data();
						$update->add([
							"hash" => Hash::create($pwd)
						]);

						// save new password
						self::update_user($user->get("username"), $update);


						// send mail
						$result = Mail::send([
							"to" => $email,
							"subject" => View::text("logging_forgotten_mail_subject"),
							"message" => View::text("logging_forgotten_mail_message") . "\n\n" . $pwd
						]);

						if ($result === false) {
							Message::success(true);
						}

						else {
							Message::failure($result);
						}
					}

					else {
						Message::failure("profile_not_found");
					}
				}

				else {
					Message::failure("profile_not_found");
				}

				break;
			

			case "ma_save_user":

				$user_data = new Data(self::$users_pattern);


				// password change -> check
				if (Session::get("ma_password_new") != Session::get("ma_password_check")) {

					Message::failure("password_check_failure");
				}



				// add new user
				else {


					// register -> user already exists
					if (Session::get("function") == "register") {

						// user name already exists
						if (Access::get_user(Session::get("ma_username"))) {

							Message::failure("user_exists");
						}


						// ========================
						// add user
						else {


							// username dont exists
							if (($username = Session::get("ma_username")) == "") {
								Message::failure("no_username");
							}

							// fullname dont exists
							elseif (($username = Session::get("ma_username")) == "") {
								Message::failure("no_fullname");
							}

							// password dont exists
							elseif (($username = Session::get("ma_password_new")) == "") {
								Message::failure("no_password");
							}

							// email dont exists
							elseif (($username = Session::get("ma_email")) == "") {
								Message::failure("no_email");
							}


							// password check ok
							elseif (($hash = Session::get("ma_password_new")) && Session::get("ma_password_check")) {

								$uuid = uniqid();

								$user_data->add([
									"hash" => Hash::create($hash),
									"username" => Session::get("ma_username"),
									"fullname" => Session::get("ma_fullname"),
									"email" => Session::get("ma_email"),
									"id" => $uuid
								]);

								// add user to userfile
								self::add_user(Session::get("ma_username"), $user_data);

								// self::load(self::config("basepath"));
								self::$logged = true;
								Message::success(true);


								// send confirmation mail
								$link = CMSIMPLE_URL . '?' . Pages::$su . "&action=confirm&ma_username=" . Session::get("ma_username") . "&ma_uuid=" . $uuid;


								Message::failure(Mail::send([
									"to" => $user_data->get("email"),
									"subject" => View::text("confirm_subject"),
									"message" => View::text("confirm_message") . "\n\n" . $link
								]));
							}
						}
					}



					// ========================
					// update user
					else {


						// set new password hash
						if (Session::get("ma_password_new") != "") {

							self::$user->add("hash", Hash::create(Session::get("ma_password_new")));
						}


						// collect data
						foreach (self::$users_pattern as $key) {

							if (($value = Session::param("ma_" . $key)) !== false) {

								self::$user->add($key, $value);
							}
						}


						// save user and update access user
						self::update_user(Access::user("username"), self::$user);
						Message::success(true);
					}
				}



				break;


			case "confirm":

				if (($uuid = Session::param("ma_uuid")) && Session::param("ma_username")) {


					$user = Access::users(Session::param("ma_username"), true);

					// check for user uuid
					if ($user && $user->get("id") == $uuid) {

						$update = new Data();
						$update->add(["id" => "", "status" => "-1"]);

						self::update_user($user->get("username"), $update);
					}
				}

				break;
		}


		return $o;
	}



	private static function reset() {

		// set stati to logged out
		self::$logged = false;
		self::$user = false;
		self::$admin = false;

		Message::reset();
	}	
	

	// set current user data
	private static function set_user($user_object) {

		// set current user
		self::$user = $user_object;

		// check if user is admin
		if (Groupss::user_is_in_group($user_object->username(), "admin")) {
			self::$admin = true;
		}

	}



/* ****************************
 * load and save user and group data
*/
	// save users and groups of loaded
	public static function save () {

		Users::save();
		Groupss::save();

	}
	
	
/* ****************************
 * maipulate user
*/

	// remove user
	public static function remove_user ($user) {
		return self::remove(self::$users, $user);
	}
	

	// update user
	public static function update_user ($user, $data) {
		
		$data->add("modified", time());

		return self::update(self::$users, $user, $data, self::$users_pattern);
	}
	


/* ****************************
 * maipulate group
*/


	// update group
	private static function update_group ($group, $data) {
		return self::update(self::$group, $group, $data, self::$group_pattern);
	}
	

/* ****************************
 * global maipulation
*/

	// add entry
	// $data is assoc array
	private static function add (&$store, $name, $data, $pattern) {

		// is loaded
		if (self::$path !== false && $name != "") {
			
			// user does not exist -> add
			if (! isset($store[$name])) {
				
				// add and save
				$new = new Data($pattern);
				
				// iterate data pattern
				foreach ($pattern as $key) {

					// update of pattern matches
					if ($data->get($key)) {
						$new->add($key, $data->get($key));
					}
				}

				$store[$name] = $new;

				self::save();
				return true;
			}
		}
		
		return false;
	}


	// remove entry
	private static function remove (&$store, $name) {
		
		// user sound
		if (isset($store[$name])) {
			unset($store[$name]);
			self::save();
		}
	}
	

	// change data
	// $data is assoc array
	// name can't be changed
	private static function update (&$store, $name, $data, $pattern) {

		if (self::$path !== false) {
				
			// user exists
			// change data
			if (isset($store [$name])) {

				$change = $store [$name];

				// get user data pattern
				// array_shift($pattern);

				// iterate data pattern
				foreach ($pattern as $key) {

					// update of pattern matches
					if ($data->get($key) !== false) {
						$change->add($key, $data->get($key));
					}
				}

				// save changes
				$store[$name] = $change;

				self::save();
				return true;
			}
		}
		
		return false;
	}
	
	// get 



/* ****************************
 * 
*/
	// get logged user data
	// if key, return key value
	public static function user($key = false) {

		// return only if logged
		// if (self::logged()) {
			// return value of user
			if ($key) {
				return self::$user->get($key);
			}

			// return user object
			else {
				return self::$user;
			}

		// }

		// else {
		// 	return false;
		// }
	}

	
	// check for failure
	public static function failure() {
		return Message::failure();
	}
	
	// check for logged user
	public static function logged() {


		if (self::$user) {

			$status = self::$user->status();

			// user active
			if ($status == -1) {
				return self::$logged;
			}


			// user locked
			elseif ($status == 0) {
				return false;
			}


			// registration not confirmed
			else {
				
				// timeout -> remove user
				if ((time() - $status) > self::config("register_timeout")) {

					// remove user
					self::remove_user(self::$user["username"]);

					return false;
				}

				// 
				self::$failure = "confirm_not";

				return false;
			}
		}
	}

	
	// check if admin
	public static function admin() {
		return self::$admin;
	}

	
// get user data by username
	public static function get_user ($user) {

		if (isset(self::$users[$user])) {
			return self::$users[$user];
		}
		else {
			return false;
		}
	}
	
	
	// get group array
	public static function group($name = false, $flag = false) {
		
		// get group by name
		if ($name && isset(self::$group[$name])) {
			return self::$group[$name];
		}
		
		// flag is false -> return all groups
		elseif (!$flag) {
			return self::$group;
		}
		else {
			return false;
		}
	}
	

	// get user array
	public static function users ($name = false, $flag = false) {
		
		if ($name && isset(self::$users[$name])) {

			return self::$users[$name];
		}

// flag is false -> return all groups
		elseif (!$flag) {
			return self::$users;
		}
		else {
			return false;
		}
	}



	// get config parameter
	public static function config($name = false) {

		if (isset(self::$config[$name])) {
			return self::$config[$name];
		}

		elseif ($name === false) {
			return self::$config;
		}

		else {
			return false;
		}
	}


	public static function show() {

		$o = "user: " . print_r(self::$user) . "<br>";
		$o .= "info: " . Message::success() . "<br>";
		$o .= "failure: " . Message::failure();

		return $o;
	}
	
}