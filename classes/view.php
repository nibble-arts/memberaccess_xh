<?php


/* static class for display of different screens */
namespace ma;

class View {
	
	private static $text;
	
	// init view
	public static function init ($text) {

		self::$text = $text['memberaccess'];

	}
	
	
	// get multilingual text
	public static function text ($code) {

		if (isset(self::$text [$code])) {
			return self::$text [$code];
		}
		else {
			return $code;
		}
	}
	
	
	// link to login page
	public static function login_link() {

		$o = '<a class="ma_login_link" href="' . CMSIMPLE_URL . '?' . Access::config("login_page") . '">';
		$o .= self::text("logging_login");
		$o .= '</a>';

		return $o;
	}


	// login block
	public static function login () {
		

		$o = '<form method="post" action="' . CMSIMPLE_URL.'?'.Pages::current() . '">';
			$o .= '<div class="ma_login_block">';
				
				if ($error_code = Access::failure()) {
					$o .= '<div class="xh_warning">';
						$o .= self::text($error_code);
					$o .= '</div>';
				}

				// login icon
				$o .= '<div class="ma_value">';
					$o .= '<img class="ma_big_icon" src="' . MA_PLUGIN_BASE . 'images/lock.png">';

					$o .= '<h1>';
						$o .= self::text ("logging_title");
					$o .= '</h1>';
				$o .= '</div>';
	
				// user field
				$o .= '<div class="ma_value">';
					$o .= '<div class="ma_label">';
						$o .= self::text ("logging_user");
					$o .= '</div>';

					$o .= '<input class="ma_field" type="text" name="ma_user" value="' . Session::get("ma_username") . '">';
				$o .= '</div>';

				// password field
				$o .= '<div class="ma_value">';
					$o .= '<div class="ma_label">';
						$o .= self::text ("logging_password");
					$o .= '</div>';

					$o .= '<input class="ma_field" type="password" name="ma_password">';
				$o .= '</div>';
	
				// login button
				$o .= '<div class="ma_value">';
					$o .= '<input class="ma_button" type="submit" name="ma_login" value="Anmelden">';
					$o .= ' <a class="ma_button" href="?' . Access::config("login_forgotten") . '&action=ma_forgotten">';
					$o .= self::text("logging_forgotten");
					$o .= '</a>';
				$o .= '</div>';
				
				// register link
				$o .= '<div class="ma_value">';
					$o .= ' <a class="ma_button" href="?' . Access::config("login_register") . '&action=ma_register">';
					$o .= self::text ("logging_register");
					$o .= '</a>';
				$o .= '</div>';
			$o .= '</div>';
			
			$o .= '<input type="hidden" name="action" value="ma_login">';
		$o .= '</form>';
		
		return $o;
	}
	
	// logged block
	public static function logged ($name) {


		// logout to current page
		$logout_page = Pages::current();


		$o = '<div class="ma_unlogged_block">';

			// $o .= '<img class="ma_small_icon" src="' . MA_PLUGIN_BASE . 'images/unlock.png">';

			$o .= $name;


			// if logout on restricted page, use logout_page parameter for new page
			// or allways use logout page is true
			if (Pages::is_restricted(Pages::current()) || Access::config("logout_allways_use_link")) {
				$logout_page = Access::config("logout_page");
			}



			// profile
			$o .= ' <a href="' . CMSIMPLE_URL.'?'. Access::config("profile_page") . '&action=ma_profile">';
				$o .= '<img class="ma_small_icon" src="' . MA_PLUGIN_BASE . 'images/profile.png">';
			$o .= '</a>';


			// logout
			$o .= ' <a href="' . CMSIMPLE_URL.'?'. $logout_page . '&action=ma_logout">';
				$o .= '<img class="ma_small_icon" src="' . MA_PLUGIN_BASE . 'images/logout.png">';
			// $o .= self::text ("logging_logout");
			$o .= '</a>';
		$o .= '</div>';
		
		return $o;
	}
	
	
	// password forgotten
	public static function forgotten() {

		$o = "";


		if ($error_code = Access::failure()) {
			$o .= '<div class="xh_warning">';
				$o .= self::text($error_code);
			$o .= '</div>';
		}


		// save success info
		elseif (Access::success()) {
			$o .= '<div class="xh_info">';
				$o .= self::text("email_sent");
			$o .= '</div>';
		}


		// no success info, show form
		else {

			$o .= '<form method="post" action="' . CMSIMPLE_URL.'?'. Pages::current() . '">';

				$o .= HTML::div(["content" => View::text("logging_forgotten_text"), "class" => "ma_label"]);


				$o .= HTML::div(["content" => View::text("username"), "class" => "ma_label"]);

				$o .= HTML::input([
					"type" => "input",
					"name" => "ma_username",
					"class" => "ma_value"
				]);


				$o .= HTML::div(["content" => View::text("email"), "class" => "ma_label"]);

				$o .= HTML::input([
					"type" => "input",
					"name" => "ma_email",
					"class" => "ma_value"
				]);

				$o .= '</p>';

					$o .= HTML::input([
						"type" => "submit",
						"value" => View::text("logging_request")
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


	// show profile
	public static function profile($function, $display_fields = false) {

		if (!$display_fields) {
			$display_fields = ["username" => "text", "fullname" => "text", "email" => "text"];
		}


		$o = "";
		$user = Access::user();


		// on register use profile as target page
		if ($function == "register") {
			$target_page = Access::config("login_page");
		}

		// use current page as target
		else {
			$target_page = Pages::$su;
		}


			$o .= '<form method="post" action="' . CMSIMPLE_URL . '?' . $target_page . '">';


			// save success info
			if (Access::success()) {
				$o .= '<div class="xh_info">';
					$o .= self::text("profile_saved");
				$o .= '</div>';
			}


			elseif ($error_code = Access::failure()) {
				$o .= '<div class="xh_warning">';
					$o .= self::text($error_code);
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

					$o .= HTML::div(["content" => View::text($idx), "class" => "ma_label"]);


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
	public static function administration () {
		
		$o = "> member administration";
		
		return $o;
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
	// show login/logout on all pages
	public static function display_all_pages(&$c) {


		if (Access::config("display_all_pages") && (!Session::$adm || (Session::$adm && !Session::$edit))) {

			// add at all pages
			foreach ($c as $i=>$page) {

				// hide on login page
				if (!Pages::current(Access::config("login_page"))) {

					// show logout
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


	public static function show() {

		$o = "text: " . print_r(self::$text, true);

		return $o;
	}

}