<?php

/* CMSimple_XH plugin
 * memberaccess
 * (c) 2018 Thomas Winkler
 *
 * Manages member logins and hides pages by group
 */


// init class autoloader
spl_autoload_register(function ($path) {

	if ($path && strpos($path, "ma\\") !== false) {
		$path = "classes/" . str_replace("ma\\", "", strtolower($path)) . ".php";
		include_once $path; 
	}
});


// plugin base path
define('MA_PLUGIN_BASE', $pth['folder']['plugin']);


// init access class
// init pages class
ma\Config::init($plugin_cf);
ma\Text::init($plugin_tx);

ma\Access::init($plugin_cf, $plugin_tx);
ma\Pages::init($c, $u, $pd_router, $su);


// exec actions
ma\Access::action();
ma\Pages::hide();


// show login/logout on all pages
ma\View::display_all_pages($c);



// ================================
// main plugin function call
function memberaccess($function = false) {

	$o = "";

	switch ($function) {
		
		case "login":

			if (ma\Access::logged()) {
				$o .= ma\View::logged(ma\Access::user("fullname"));
			}

			else {
				$o .= ma\View::login();
			}
			break;
		
		case "register":
			$o .= ma\View::profile("register", ["username" => "text", "fullname" => "text", "email" => "text", "password_new" => "password", "password_check" => "password"]);
			break;
			

		case "forgotten":
			$o .= ma\View::forgotten();
			break;


		case "profile":
			if (ma\Access::logged()) {
				$o .= ma\View::profile("update", ["username" => "disabled", "fullname" => "text", "email" => "text", "password_change" => "password", "password_check" => "password"]);
			}
			break;


		case "pages":
			if (ma\Access::logged()) {
				$o .= ma\View::list_pages(ma\Pages::restricted());
			}

			else {
				$o .= '<p>' . ma\Text::logging_text() . '</p>';
			}
			break;

		case "administration":
			if (ma\Access::logged() && ma\Access::admin()) {
				$o .= ma\View::administration();
			}
			break;
			
	}
	
	return $o;
}

?>