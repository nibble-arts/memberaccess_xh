<?php

namespace ma;

class Hash {

	public static function create($val) {

		return password_hash($val, PASSWORD_DEFAULT);
	}

	public static function verify($val, $hash) {

		return password_verify($val, $hash);
	}
}

?>