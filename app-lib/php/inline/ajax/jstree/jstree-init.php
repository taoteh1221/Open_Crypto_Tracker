<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


 
header('Content-type: text/html; charset=' . $ct['dev']['charset_default']);

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (primarily in case the end-user has a server misconfiguration)
if ( $ct['conf']['sec']['access_control_origin'] == 'any' ) {
header('Access-Control-Allow-Origin: *');
}
// Strict access from THIS APP SERVER ONLY (provides tighter security)
else {
header('Access-Control-Allow-Origin: ' . $ct['app_host_address']);
}


// If we are not admin logged in, OR fail the CSRF security token check, exit
if ( !$ct['sec']->admin_logged_in() || !$ct['sec']->pass_sec_check($_GET['gen_nonce'], 'general_csrf_security') ) {
// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();
exit;
}


if ( $_GET['asset_markets'] ) {
require($ct['base_dir'] . '/app-lib/php/inline/ajax/jstree/asset-markets.php');
}
elseif ( $_GET['assets'] ) {
require($ct['base_dir'] . '/app-lib/php/inline/ajax/jstree/assets.php');
}

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>