<?php

/**
 * Member access  -- admin.php
 *
 * @category  CMSimple_XH
 * @package   Member access
 * @author    Thomas Winkler <thomas.winkler@iggmp.net>
 * @copyright 2019 nibble-arts <http://www.nibble-arts.org>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://cmsimple-xh.org/
 */

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


/*
 * Register the plugin menu items.
 */
if (function_exists('XH_registerStandardPluginMenuItems')) {
    XH_registerStandardPluginMenuItems(true);
}

if (function_exists('memberaccess') 
    && XH_wantsPluginAdministration('memberaccess') 
    || isset($memberaccess) && $memberaccess == 'true')
{


    $o .= print_plugin_admin('on');

    switch ($admin) {

	    case '':
	        $o .= '<h1>Member Access</h1>';
    		$o .= '<p>Version 1.0.3</p>';
            $o .= '<p>Copyright 2019</p>';
    		$o .= '<p><a href="http://www.nibble-arts.org" target="_blank">Thomas Winkler</a></p>';
            $o .= '<p>Das Plugin ermöglicht es Seiten zu verstecken und nur nach Login freizugeben. die Benutzer können Gruppen zugeordnet werden, die den Zugriff auf die geschützten Seiten definierten.</p>';

	        break;

        case 'plugin_main':
            // include_once(DATABASE_BASE."settings.php");

            // database_settings($action, $admin, $plugin);
            break;

	    default:
	        $o .= plugin_admin_common($action, $admin, $plugin);
            break;
    }

}
?>
