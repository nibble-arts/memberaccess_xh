<?php

/* the session class provide access to the
 * http parameters and the cookies.
 */
 
namespace ma;

class Session {
	
	private static $params;		// http values
	private static $cookies;	// cookie values
	private static $session;	// session values

	static $adm;
	static $edit;

	
	// load session
	public static function load () {

		global $adm, $edit;
		
		self::start();

		self::$params = $_REQUEST;
		self::$cookies = $_COOKIE;
		self::$session = $_SESSION;

		self::$adm = $adm;
		self::$edit = $edit;
	}
	
	
	// get session value
	// http before session before cookie
	public static function get ($name) {

		// get http param
		if (!$val = self::param($name)) {
		
			if (!$val = self::session($name)) {

				// not present, try cookie
				$val = self::cookie($name);
			}
		}

		return $val;
	}
	

	// set session and cookie value
	public static function set ($key, $value) {

		self::set_session($key, $value);
		self::set_cookie($key, $value);
	}


	// set session value
	public static function set_session($key, $value) {

		$_SESSION[$key] = $value;
	}


	// set session value
	public static function set_cookie($key, $value) {

		setcookie($key, $value);
	}


	// remove sesseion/cookie value
	public static function remove ($key) {

		if (isset($_SESSION[$key])) {
			unset ($_SESSION[$key]);
			self::set_cookie($key, "");
		}

		self::load();
	}


	// get parameter
	public static function param($name) {
		
		if (isset(self::$params[$name])) {
			return self::$params[$name];
		}
		else {
			return false;
		}
	}


	// get parameter keys
	public static function get_param_keys() {
		return array_keys(self::$params);
	}


	// get cookie
	public static function cookie($name) {
		
		if (isset(self::$cookies[$name])) {
			return self::$cookies[$name];
		}
		else {
			return false;
		}
	}
	

	// get seesion
	public static function session($name) {

		if (isset(self::$session[$name])) {
			return self::$session[$name];
		}
		else {
			return false;
		}
	}


	// start session
	private static function start () {
		// start session
		if (preg_match("!Googlebot!i",$_SERVER['HTTP_USER_AGENT']));
		else if (preg_match("!MSNbot!i",$_SERVER['HTTP_USER_AGENT']));
		else if (preg_match("!slurp!i",$_SERVER['HTTP_USER_AGENT']));
		elseif (function_exists('XH_startSession')) {
		    XH_startSession();
		} elseif (!session_id()) {
		    session_start();
		}
	}


	public static function debug() {

		$o = "http: " . print_r(self::$params, true) . "<br>";
		$o .= "session: " . print_r(self::$session, true) . "<br>";
		$o .= "cookies: " . print_r(self::$cookies, true);

		return $o;
	}
}