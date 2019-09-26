<?php

if (!(defined('CMSIMPLE_XH_VERSION') || defined('CMSIMPLE_VERSION'))) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function ma_tab($page) {
	global $sn, $su, $onload;
	

	// return script include
	$o = '<script type="text/javascript" src="' . MA_PLUGIN_BASE . 'script/access_tab.js"></script>';

	// add to onload
	$onload .= "ma_access_init();";
				
	$o .= '<p><b>Access Control</b></p>';
	$o .= '<form action="' . $sn . '?' . $su . '" method="post" id="memberaccess" name="memberaccess">';


		if (isset($page["ma_active"])) $page_active = $page["ma_active"];
		if (isset($page["ma_groups"])) $page_groups = $page["ma_groups"];
		if (isset($page["ma_description"])) $page_description = $page["ma_description"];


		// checkbox for active		
		$o .= "<p>" . ma\HTML::checkbox($page_active, [
			"type" => "text",
			"name" => "ma_active",
			"id" => "ma_active"
		]);
		
		$o .= " " . ma\View::text('group_active') . "</p>";
		

		// input field for group list
		$o .= "<p>Gruppen " . ma\HTML::input( [
			"value" => $page_groups,
			"type" => "text",
			"name" => "ma_groups",
			"id" => "access_groups"
		]) . "</p>";


		// access control description text
		$o .= '<p>' . ma\View::text ('description') . '</p>';
		
		
		// page description for automatic list
		$o .= "<p>Beschreibung " . ma\HTML::textarea([
			"content" => $page_description,
			"name" => "ma_description",
			"id" => "access_description",
			"rows" => 4,
			"cols" => 50
		]) . "</p>";


		$o .= " " . ma\HTML::input([
			"value" => ma\View::text ("access_save"),
			"type" => "submit",
			"name" => "memberaccess",
			"id" => "memberaccess"
		]);
		
		
		$o .= '<input name="save_page_data" type="hidden">';
		
	$o .= '</form>';
	
	return $o;
}

?>