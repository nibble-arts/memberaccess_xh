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
			return Text::get(self::$success);
		}
	}


	public static function failure($text = false) {

		if ($text !== false) {
			self::$failure = $text;
		}

		else {
			return Text::get(self::$failure);
		}
	}


	// render messages
	public static function render() {
		$o = "";

		if (self::$success != "") {
			$o .= '<div class="xh_info">';
				$o .= self::success();
			$o .= '</div>';
		}

		if (self::$failure != "") {
			$o .= '<div class="xh_warning">';
				$o .= self::failure();
			$o .= '</div>';
		}

		return $o;
	}
}