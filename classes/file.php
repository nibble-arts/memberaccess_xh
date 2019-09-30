<?php

namespace ma;

class File {
	
	public static function read($path) {
		
		// get user string
		if (file_exists($path)) {
			return file_get_contents($path);
		}
		
		else {
			return false;
		}
	}
	
	public static function write ($path, $data) {

		$ret = false;
	
		if ($data != "") {

			$ret = file_put_contents($path, $data);

			if ($ret === false) {
				Message::failure("file_write_error");
			}

		}

		return $ret;
	}
}

?>