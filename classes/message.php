<?php

namespace ma;

class Message {

	private static $success;
	private static $failure;

	public static function reset() {

		self::$success = "";
		self::$failure = "";
	}


	public static function success($text = false) {

		if ($text !== false) {
			self::$success = $text;
		}

		else {
			return self::$success;
		}
	}


	public static function failure($text = false) {

		if ($text !== false) {
			self::$failure = $text;
		}

		else {
			return self::$failure;
		}
	}
}