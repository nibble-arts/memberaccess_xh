<?php

namespace ma;

class Log {

	private static $path;


	// init logging
	public static function init($path) {

		self::$path = $path;
	}


	// add line to log file with timestamp
	public static function add($string) {

		$string = date("Y-m-dTH:i:s", time()) . " - " . $string . "\n";

		file_put_contents(self::$path, $string, FILE_APPEND);
	}


	// return log file content
	public static function get() {

		return file_get_contents(self::$path);
	}


	// clear log file
	public static function clear() {

		file_put_contents(self::$path, "");
	}
}

?>