<?php

namespace ma;

class Groups {
	
	// key = group name
	// val = access rights
	private $groups;
	
	public function __construct ($groups =false) {
		
		$this->groups = [];
		$this->set ($groups);

		// add admin group
		// $this->set (Access::config("group_admin"));

	}
	
	
	// set groups from string or array
	// string: group_name [:access]   access can be w
	// array: [group_name => access]
	public function set ($groups) {
		
		if (is_array ($groups)) {
			$this->groups = $groups;
		}
		
		if (is_string ($groups)) {
			$group_list = explode (",", $groups);
			
			foreach($group_list as $group) {
				
				$access = "";
				
				$temp = explode (":", $group);
				$name = $temp [0];
				
				if (count ($temp) > 1) {
					$access = $temp [1];
				}
				
				if ($name) {
					$this->groups [$name] = $access;
				}
			}
		}
	}
	

	// check if group exists
	// if true returns access rule
	public function group_exists($name) {


		// compare with Groups object
		if (is_object($name) && get_class($name) == "ma\Groups") {

			// array differenze
			$comp = count(array_diff_key($name->array(), $this->array()));

			// has group or no groups
			if (count($this->array()) == 0 || count($name->array()) > $comp) {
				return true;
			}

			else {
				return false;
			}
		}


		// compare group name string
		elseif (is_string($name) && isset($this->groups[$name])) {

			// no access rights -> return true 
			if ($this->groups[$name] == "") {
				return true;
			}

			// return access rights
			else {
				return $this->groups[$name];
			}
		}
	}
	
	
	// return groups as kmma separated string
	public function __toString () {
		
		$ret = "";
		
		foreach ($this->groups as $name => $access) {
			$ret[] = $name . ":" . $access;
		}
		
		return implode (",", $ret);

	}


	// return groups as array
	public function array () {
		return $this->groups;
	}
}