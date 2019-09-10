<?php

/* CMSimple_XH plugin
 * memberaccess
 * (c) 2018 Thomas Winkler
 *
 * Manages member logins and hides pages by group
 */

// Todo use autoloader
if (!class_exists('ma\Access')) require "classes/access.php";
if (!class_exists('ma\Session')) require "classes/session.php";
if (!class_exists('ma\File')) require "classes/file.php";
if (!class_exists('ma\View')) require "classes/view.php";
if (!class_exists('ma\Pages')) require "classes/pages.php";
if (!class_exists('ma\HTML')) require "classes/html.php";
if (!class_exists('ma\Groups')) require "classes/groups.php";
if (!class_exists('ma\Hash')) require "classes/hash.php";
if (!class_exists('ma\Data')) require "classes/data.php";
if (!class_exists('ma\Mail')) require "classes/mail.php";


define('MA_PLUGIN_BASE', $pth['folder']['plugin']);


// init access class
// init pages class
ma\Access::init($plugin_cf, $plugin_tx);
ma\Pages::init($c, $u, $pd_router, $su);


// exec actions
ma\Access::action();
ma\Pages::hide();


// show login/logout on all pages
ma\View::display_all_pages($c);



// ================================
// main plugin function call
function memberaccess($function) {

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
				$o .= ma\View::profile("update", ["username" => "disabled", "fullname" => "text", "email" => "text", "password_new" => "password", "password_check" => "password"]);
			}
			break;


		case "pages":
			if (ma\Access::logged()) {
				$o .= ma\View::list_pages(ma\Pages::restricted());
			}

			else {
				$o .= '<p>' . ma\View::text("logging_text") . '</p>';
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