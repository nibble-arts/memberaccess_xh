<?php

namespace ma;

class Data {

	private $pattern;
	private $data;


	// create user
	// pattern is array of values
	public function __construct($pattern = false) {

		$this->pattern = $pattern;
		$this->data = [];
	}


	// set user data
	public function add($data, $value = false) {
		// add assoc array
		if (is_array($data)) {

			foreach ($data as $key => $value) {
				$this->data[$key] = $value;
			}
		}


		// add key value pair
		// data => key
		elseif ($value !== false) {
			$this->data[$data] = $value;
		}
	}


	// get data by key
	public function get($key = false) {

		if (isset($this->data[$key])) {
			return $this->data[$key];
		}

		elseif ($key === false) {

			return $this->data;
		}

		else {
			return false;
		}

	}


	// magic function
	// return user value
	public function __call($name, $attr) {

		return $this->get($name);
	} 


	// serializet data array to string
	public function serialize () {

		$temp = [];
		$file_string = "";

		if ($this->pattern) {

			$pattern = $this->pattern;

			// collect line by pattern key
			foreach ($pattern as $key) {

				// key exists
				if (isset($this->data[$key])) {
					$temp[] = $this->data[$key];
				}
				// key dont exist
				else {
					$temp[] = "";
				}
			}

			$file_string .= implode (":", $temp) . "\n";
		}

		return $file_string;
	}
}