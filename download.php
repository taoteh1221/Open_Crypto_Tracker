<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all error reporting (0), or enable (1)

$runtime_mode = 'download';

require("config.php");


// Example template download
if ( $_GET['example_template'] == 1 ) {
require_once( $base_dir . "/app-lib/php/other/example-csv.php");
exit;
}
// Portfolio export download
elseif ( $_POST['submit_check'] == 1 && is_array($app_config['portfolio_assets']) || $_POST['submit_check'] == 1 && is_object($app_config['portfolio_assets']) ) {
require_once( $base_dir . "/app-lib/php/other/export-csv.php");
exit;
}
// Backups download
elseif ( $_GET['backup'] != NULL ) {
require_once( $base_dir . "/app-lib/php/other/backups.php");
exit;
}
else {
// Log errors / debugging, send notifications, destroy session data
error_logs();
debugging_logs();
send_notifications();
hardy_session_clearing();
exit;
}




?>