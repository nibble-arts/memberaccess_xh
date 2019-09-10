<?php

namespace ma;

class HTML {

	// magic tag method
	public static function __callStatic($tag, $attr) {
		
		$content = "";

		// get content and remove from attributes
		if (isset($attr[0]["content"])) {
			$content = $attr[0]["content"];
			unset($attr[0]["content"]);
		}


		// create html tag
		$o = '<' . $tag . " " . self::serialise ($attr[0]) . '>';
			$o .= $content;
		$o .= '</' . $tag . '>';

		return $o;
	}
	
	
	// select section
	public static function select($opts, $attr) {
		
		$o = "<select " . self::serialise ($attr) . ">";

		foreach ($opts as $opt) {
			$o .= "<option>";
			 $o .= $opt;
			$o .= "</option>";
		}
		
		$o .= "</select>";

		return $o;
	}
	

	// input tag
	// public static function input($value, $attr) {
	// 	return '<input value="' . $value . '" ' . self::serialise ($attr) . '>';
	// }
	
	
	// serialise assoc array to attribute string
	private static function serialise($attrs) {

		$temp = [];
		
		foreach ($attrs as $key => $val) {
			$temp [] = $key . '="' . $val . '"';
		}
		
		return implode (" ", $temp);
	}
	
	
	// checkbox tag
	public static function checkbox ($status, $attr) {
		
		$o = '<input type="hidden" value="0" ' . self::serialise ($attr) . '>';
		
		$o .= '<input type="checkbox" value="1" ' . self::serialise ($attr);
		
		if ($status) {
			$o .= 'checked="checked"';
		}
		
		$o .= '>';
		
		return $o;
	}
}

?>