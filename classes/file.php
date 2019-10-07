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

		self::backup($path);

		$ret = false;

		if ($data != "") {

			$ret = file_put_contents($path, $data);

			if ($ret === false) {
				Message::failure("file_write_error");
			}

			else {
				Log::add("file ".$path." changed");
			}

		}

		return $ret;
	}


	private static function backup($path) {

		$cnt = Access::config("backup_cnt");
		$i = 0;

		$name = pathinfo($path, PATHINFO_FILENAME);

		$dir = scandir(pathinfo($path, PATHINFO_DIRNAME ));

		foreach ($dir as $file) {

			// file found
			if (pathinfo($file, PATHINFO_FILENAME) == $name) {

				// is backup extension
				if (pathinfo($file, PATHINFO_EXTENSION) != "txt") {
					$i++;
				}
			}
		}
	}
}

?>