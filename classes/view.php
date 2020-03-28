<?php


/* static class for display of different screens */
namespace ma;

class View {
	
	private static $text;
	
	// init view
	// public static function init ($text) {

	// 	self::$text = $text['memberaccess'];

	// }
	
	
	// // get multilingual text
	// public static function text ($code) {

	// 	if (isset(self::$text [$code])) {
	// 		return self::$text [$code];
	// 	}
	// 	else {
	// 		return $code;
	// 	}
	// }
	
	
	// link to login page
	public static function login_link() {

		$o = '<a class="ma_login_link" href="' . CMSIMPLE_URL . '?' . Config::login_page() . '">';
		$o .= Text::get("logging_login");
		$o .= '</a>';

		$o .= '<div class="tplvoe_clearBoth"></div>';
		return $o;
	}


	// login block
	public static function login () {
		
		global $su;

		if (Config::login_return()) {
			$post_url = $_SERVER['HTTP_REFERER'];
		}
		else {
			$post_url = CMSIMPLE_URL . '?' . Pages::current();
		}


		$o = '<form method="post" action="' . $post_url . '">';
			$o .= '<div class="ma_login_block">';
				
				if ($error_code = Message::failure()) {
					$o .= '<div class="xh_warning">';
						$o .= Text::get($error_code);
					$o .= '</div>';
				}

				if ($error_code = Message::success()) {
					$o .= '<div class="xh_info">';
						$o .= Text::get($error_code);
					$o .= '</div>';
				}

				// login icon
				$o .= '<div class="ma_value">';
					$o .= '<img class="ma_big_icon" src="' . MA_PLUGIN_BASE . 'images/lock.png">';

					$o .= '<h1>';
						$o .= Text::get ("logging_title");
					$o .= '</h1>';
				$o .= '</div>';
	
				// user field
				$o .= '<div class="ma_value">';
					$o .= '<div class="ma_label">';
						$o .= Text::get ("logging_user");
					$o .= '</div>';

					$o .= '<input class="ma_field" type="text" name="ma_user" value="' . Session::get("ma_username") . '">';
				$o .= '</div>';

				// password field
				$o .= '<div class="ma_value">';
					$o .= '<div class="ma_label">';
						$o .= Text::get ("logging_password");
					$o .= '</div>';

					$o .= '<input class="ma_field" type="password" name="ma_password">';
				$o .= '</div>';
	
				// login button
				$o .= '<p><div class="ma_value">';
					$o .= '<input class="ma_button" type="submit" name="ma_login" value="Anmelden">';
					$o .= ' <a class="ma_button" href="?' . Config::login_forgotten() . '&action=ma_forgotten">';
					$o .= Text::get("logging_forgotten");
					$o .= '</a>';
				$o .= '</div></p>';
				
				// register link
				$o .= '<p><div class="ma_value">';
					$o .= ' <a class="ma_button" href="?' . Config::login_register() . '&action=ma_register">';
					$o .= Text::get ("logging_register");
					$o .= '</a>';
				$o .= '</div>';
			$o .= '</div></p>';
			
			$o .= '<input type="hidden" name="action" value="ma_login">';
		$o .= '</form>';
		
		return $o;
	}
	
	// logged block
	public static function logged ($name) {


		// logout to current page
		$logout_page = Pages::current();


		$o = '<div class="ma_unlogged_block">';

			// $o .= Text::get("logging_as") . " ";
			$o .= $name;

			// if logout on restricted page, use logout_page parameter for new page
			// or allways use logout page is true
			if (Pages::is_restricted(Pages::current()) || Config::logout_allways_use_link()) {
				$logout_page = Config::logout_page();
			}


			// profile
			$o .= ' <a href="' . CMSIMPLE_URL.'?'. Config::profile_page() . '&action=ma_profile">';
				$o .= '<img class="ma_small_icon" src="' . MA_PLUGIN_BASE . 'images/profile.png">';
			$o .= '</a>';


			// logout
			$o .= ' <a href="' . CMSIMPLE_URL.'?'. $logout_page . '&action=ma_logout">';
				$o .= '<img class="ma_small_icon" src="' . MA_PLUGIN_BASE . 'images/logout.png" title="Profil">';
			// $o .= Text::get ("logging_logout");
			$o .= '</a>';
		$o .= '</div>';
		
		$o .= '<div class="tplvoe_clearBoth"></div>';

		return $o;
	}
	
	
	// password forgotten
	public static function forgotten() {

		$o = "";

		
		if ($success = Message::success()) {
			$o .= '<div class="xh_info">';
				$o .= Text::get($success);
			$o .= '</div>';
		}


		// no success info, show form
		else {

			// save success info
			if ($failure = Message::failure()) {
				$o .= '<div class="xh_info">';
					$o .= Text::get($failure);
				$o .= '</div>';
			}

			$o .= '<form method="post" action="' . CMSIMPLE_URL.'?'. Pages::current() . '">';

				$o .= HTML::div(["content" => Text::get("logging_forgotten_text"), "class" => "ma_label"]);


				$o .= HTML::div(["content" => Text::get("username"), "class" => "ma_label"]);

				$o .= HTML::input([
					"type" => "input",
					"name" => "ma_username",
					"class" => "ma_value"
				]);


				$o .= HTML::div(["content" => Text::get("email"), "class" => "ma_label"]);

				$o .= HTML::input([
					"type" => "input",
					"name" => "ma_email",
					"class" => "ma_value"
				]);

				$o .= '</p>';

					$o .= HTML::input([
						"type" => "submit",
						"value" => Text::get("logging_request")
					]);

				$o .= '</p>';


				$o .= HTML::input([
					"type" => "hidden",
					"name" => "action",
					"value" => "ma_get_password"
				]);

			$o .= "</form>";
		}



		return $o;
	}


	// list page data with links
	public static function list_pages($pages) {

		$o = "";

		$o .= '<ul>';
		
		$pages = self::sort_array_by_key($pages, "name");

		// list restricted pages
		foreach ($pages as $page) {
			$o .= '<li>';
				$o .= '<a href="' . CMSIMPLE_URL . '?' . $page["url"] . '">' . $page["name"] . '</a>';
				$o .= '<br>' . $page["description"];
			$o .= '</li>';
		}

		$o .= '</ul>';

		return $o;

	}



	private static function sort_array_by_key($array, $key) {

		$sorted = [];

		foreach ($array as $page) {
			$sorted[$page[$key]] = $page;
		}

		asort($sorted);

		return $sorted;
	}


	// show profile
	public static function profile($function, $display_fields = false) {

		if (!$display_fields) {
			$display_fields = ["username" => "text", "fullname" => "text", "email" => "text"];
		}


		$o = "";
		$user = Access::user();

		// on register use profile as target page
		if ($function == "register") {
			$target_page = Config::login_page();
		}

		// use current page as target
		else {
			$target_page = Pages::$su;
		}


			$o .= '<form method="post" action="' . CMSIMPLE_URL . '?' . $target_page . '">';


			// save success info
			if (Message::success()) {
				$o .= '<div class="xh_info">';
					$o .= Text::get("profile_saved");
				$o .= '</div>';
			}


			elseif ($error_code = Message::failure()) {
				$o .= '<div class="xh_warning">';
					$o .= Text::get($error_code);
				$o .= '</div>';
			}


			// foreach ($user as $idx => $line) {
			foreach ($display_fields as $idx => $type) {

				// set line value
				if ($user && $user->get($idx)) {
					$line = $user->get($idx);
				}

				else {
					$line = "";
				}


				// draw formular
				$o .= '</p>';

					// show label
					$o .= HTML::div(["content" => Text::get($idx), "class" => "ma_label"]);

					// edit -> username can't be changed
					if ($type == "disabled") {

						$o .= HTML::input([
							"type" => $type,
							"name" => "ma_" . $idx,
							"class" => "ma_value",
							"disabled" => "disabled",
							"value" => $line
						]);


						$o .= HTML::input([
							"type" => "hidden",
							"name" => "ma_" . $idx,
							"value" => $line
						]);
					}

					else {

						$o .= HTML::input([
							"type" => $type,
							"name" => "ma_" . $idx,
							"class" => "ma_value",
							"value" => $line
						]);
					}

				$o .= '</p>';

				// $o .= '<br>';
			}

			$o .= '</p>';

				$o .= HTML::input([
					"type" => "submit",
					"name" => "ma_save_profile",
					"value" => "Speichern"
				]);

			$o .= '</p>';


			$o .= HTML::input([
				"type" => "hidden",
				"name" => "action",
				"value" => "ma_save_user"
			]);

			$o .= HTML::input([
				"type" => "hidden",
				"name" => "function",
				"value" => $function
			]);

			$o .= '</form>';
		// }

		return $o;
	}


	// member administration
	public static function users () {

		global $onload;
		
		$o = "<h2>User Administration</h2>";

		// return script include
		$o .= '<script type="text/javascript" src="' . MA_PLUGIN_BASE . 'script/admin.js"></script>';

		// add to onload
		$onload .= "ma_admin_init('" . Text::get("delete_confirm") . "');";

		$users = Users::get_users();

		// sort
		self::natksort($users);

		if ($error_code = Message::failure()) {
			$o .= '<div class="xh_warning">';
				$o .= Text::get($error_code);
			$o .= '</div>';
		}

		$o .= '<form method="post" name="ma_admin_users" action="' . CMSIMPLE_URL.'?'.Pages::current() .'">';

			$o .= HTML::input([
				"type" => "submit",
				"value" => "Speichern"
			]);

			$o .= '<table>';

			$o .= '<th>Username</th>';
			$o .= '<th>Full name</th>';
			$o .= '<th>Email</th>';
			$o .= '<th>Groups</th>';
			$o .= '<th>ID</th>';
			$o .= '<th>Status</th>';
			$o .= '<th>Aktion</th>';

			$idx = 0;

			foreach ($users as $user) {

				$name = $idx++;

				$o .= '<tr>';
					// username
					$o .= '<td>' . $user->username() . '</td>';

					// username
					$o .= HTML::input([
						"type" => "hidden",
						"name" => "ma_username_" . $name,
						"value" => $user->username()
					]);

					// full name
					$o .= '<td>';
						$o .= HTML::input(["type"=>"text", "name"=> "ma_fullname_" . $name, "value"=> $user->fullname()]) . '</td>';

					// email
					$o .= '<td>';
						$o .= HTML::input(["type"=>"text", "name"=> "ma_email_" . $name, "value"=> $user->email()]) . '</td>';

					// groups
					$o .= '<td>';
						// $o .= HTML::input(["type" => "text", "name" => "ma_groups_" . $name, "value" => implode(",", Groups::get_groups_of_user($user->username()))]);

						$o .= '<span class="ma_groups_list">' . implode(", ", Groups::get_groups_of_user($user->username())) . '</span>';
					$o .= '</td>';

					// groups
					$o .= '<td>';
						$o .= HTML::input(["type" => "text", "name" => "ma_id_" . $name, "value" => $user->id()]);
					$o .= '</td>';

					// status
					$o .= '<td>';
						$o .= HTML::input(["type" => "text", "name" => "ma_status_" . $name, "value" => $user->status()]);
						// $o .= View::status($user->status());
					$o .= '</td>';

					// action
					$o .= '<td>';
						$o .= HTML::a([
							"href" => "?" . Pages::$su . "&action=ma_del_user&user=" . $user->username(),
							"class" => "delete",
							"content" => "del"
						]);
					$o .= '</td>';
				$o .= '</tr>';


				// add hidden parameters
				// created
				$o .= HTML::input([
					"type" => "hidden",
					"name" => "ma_created_" . $name,
					"value" => $user->created()
				]);

				// hash
				$o .= HTML::input([
					"type" => "hidden",
					"name" => "ma_hash_" . $name,
					"value" => $user->hash()
				]);

			}

			$o .= '</table>';

			$o .= HTML::input([
				"type" => "submit",
				"value" => "Speichern"
			]);

			$o .= HTML::input([
				"type" => "hidden",
				"name" => "action",
				"value" => "ma_save_users"
			]);


		$o .= '</form>';

		return $o;
	}


	public static function groups () {

		global $onload;
		
		$o = "<h2>Group Administration</h2>";

		// return script include
		$o = '<script type="text/javascript" src="' . MA_PLUGIN_BASE . 'script/admin.js"></script>';

		// add to onload
		$onload .= "ma_admin_init('" . Text::get("delete_confirm") . "');";


		//=================================================
		// administrate groups

		$groups = Groups::get_groups();
		asort($groups);

		$o .= "<h2>Gruppen Administration</h2>";



		$idx = 0;

		foreach ($groups as $group) {

			$o .= '<form method="post" name="ma_admin_groups" action="' . CMSIMPLE_URL . '?' . Pages::current() .'">';

				$user_list = [];
				$name = $idx++;

				// group name
				$o .= '<hr><h4>' . $group->group() . '</h4>';


				// users in group
				$user_list = self::create_user_list($group);

				$o .= '<p>' . implode(", ", $user_list) . '</p>';

				// create list of unused users
				$new_user_list = array_diff(Users::get_user_names(), $group->users());

				// sort
				natcasesort($new_user_list);
				
				// add user selector
				$o .= HTML::select($new_user_list, [
					"name" => "user"
				]);

				$o .= " " . HTML::input([
					"type" => "submit",
					"name" => "ma_add_user",
					"value" => Text::get("user_add")
				]);

				// hidden data
				$o .= " " . HTML::input([
					"type" => "hidden",
					"name" => "group",
					"value" => $group->group()
				]);

				$o .= " " . HTML::input([
					"type" => "hidden",
					"name" => "action",
					"value" => "ma_add_user_to_group"
				]);

			$o .= '</form>';

		}


		return $o;
	}


	public static function log() {

		global $onload;
		
		$o = "<h2>Logfile</h2>";

		// return script include
		$o = '<script type="text/javascript" src="' . MA_PLUGIN_BASE . 'script/admin.js"></script>';

		// add to onload
		$onload .= "ma_admin_init('" . Text::get("delete_confirm") . "');";

		//=================================================
		// show logfile
		$o .= "<h2>Logfile</h2>";
		$o .= "<p>".str_replace("\n", "<br>", Log::get())."</p>";

		$o .= '<p><a class="delete" href="' . CMSIMPLE_URL . '?' . Pages::$su . '&action=ma_clear_log">Clear Log</a></p>';

		return $o;
	}
	

	private static function create_user_list($group) {

		$user_list = [];

		// users
		foreach ($group->users() as $user) {

			$user_list[] = HTML::a([
				"content" => $user,
				"class" => "delete",
				"href" => CMSIMPLE_URL . '?' . Pages::$su . '&action=ma_remove_user_from_group&group=' . $group->group() . '&user=' . $user,
				"title" => Text::get("group_remove_user")
			]);
		}

		natcasesort($user_list);

		return $user_list;
	}


	// sort assoziative array naturally
	private static function natksort(&$array) {

		$keys = array_keys($array);
		natcasesort($keys);

		foreach ($keys as $k) {
		    $new_array[$k] = $array[$k];
		}

		$array = $new_array;

		return true;
	}


	// return timestamp as human readable time
	public static function htime($timestamp) {
		return date('d.m.Y', $timestamp);
	}
	

	// return status text
	public static function status($status) {

		if ($status == -1) {
			$ret = "aktive";		
		}

		elseif ($status == 0) {
			$ret = "inactive";
		}

		else {
			$ret = "not confirmed";
		}

		return $ret;
	}


	// edit tab
	public static function tab() {
		return "access tab";
	}
	
	
	// info
	public static function info ($text) {
		return '<div class="xh_info">' . $text . '</div>';
	}
	

	// error
	public static function error ($text) {
		return '<div class="xh_error">' . $text . '</div>';
	}



	// ======================================================
	// global views
	// display login/logout on all pages
	public static function display_all_pages(&$c) {


		if (Config::display_all_pages() && (!Session::$adm || (Session::$adm && !Session::$edit))) {

			// add at all pages
			foreach ($c as $i=>$page) {

				// hide on login page
				if (!Pages::current(Config::login_page())) {

					// display logout
					if (Access::logged()) {
					    $c[$i] = View::logged(Access::user("fullname")) . $page;
					}

					else {
					    $c[$i] = View::login_link() . $page;
					}
				}
			}
		}
	}


	public static function debug() {

		$o = "text: " . print_r(self::$text, true);

		return $o;
	}

}