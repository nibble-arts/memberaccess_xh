<?php

namespace ma;

class Pages {
	
	private static $meta;
	private static $pages;
	private static $c;
	private static $u;
	static $su;
	private static $restricted;
	

	// initialise the pages
	public static function init (&$c, $u, &$pd_router, $su) {

		// current page
		self::$su = $su;
		self::$u = $u;


		// Add interests to router
	    $pd_router -> add_interest('ma_groups');
	    $pd_router -> add_interest('ma_active');
	    $pd_router -> add_interest('ma_description');

	
        // tab for admin-menu
	    $pd_router -> add_tab('Access', __DIR__ . '/ma_tab.php');


	    // get all pages
		self::$meta = $pd_router -> find_all();
		self::$c = &$c;


		// collect restricted pages
		foreach (self::$meta as $idx => $page) {

			// restricted page
			if ($page["ma_active"]) {

				$groups = $page["ma_groups"];

				self::$restricted[] = [
					"name" => $page["url"],
					"url" => $u[$idx],
					"id" => $idx,
					"groups" => "",//new Groups ($groups),
					"active" =>  $page["ma_active"],
					"description" => $page["ma_description"]
				];
			}
			
			// unrestricted page
			else {
				self::$pages[] = $idx;
			}
		}

	}
	
	
	// get page metadata
	public static function meta($idx) {

		if (isset(self::$meta[$idx])) {
			return self::$meta[$idx];
		}
	}


	// hide pages
	// don't hide of page has group read rights
	public static function hide () {


		$user_groups = false;

		// don't hide for admin
		if (!Session::$adm OR (Session::$adm && !Session::$edit)) {
			

			// get user groups
			if (Access::user()) {
				$user_groups = Access::user()->groups();
			}



			// iterate restricted pages
			foreach (self::$restricted as $idx => $page) {

				// check group rights and access
				if ((!$user_groups || (!$page["groups"]->group_exists($user_groups)) || !Access::logged()) && !Access::admin()) {

					// hide page and remove restricted entry
					self::$c[$page ["id"]] = '#CMSimple hide#';
					unset(self::$restricted[$idx]);
				}
			}
		}
	}


	// get/check current page
	public static function current($name = false) {

		// check if name ist current page name
		if ($name) {

			if (self::$su === $name) {
				return true;
			}

			else {
				return false;
			}
		}

		// return current page name
		else {

			return self::$su;
		}
	}


	// get list of resticted pages
	public static function restricted() {

		return self::$restricted;
	}


	// check if page is restricted
	public static function is_restricted($name) {

		foreach (self::$restricted as $page) {

			if ($page["url"] === $name) {
				return true;
			}
		}

		return false;
	}


	public static function show() {

		$o = "restricted: " . print_r(self::$restricted, true) . "<br>";

		return $o;
	}
}

?>