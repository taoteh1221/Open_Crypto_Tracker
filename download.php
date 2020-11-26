<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all error reporting (0), or enable (1)

$runtime_mode = 'download';

require("config.php");


// Example template download
if ( $_GET['example_template'] == 1 ) {
require_once( $base_dir . "/app-lib/php/other/csv/example-csv.php");
}
// Portfolio export download
elseif ( $_POST['submit_check'] == 1 && is_array($app_config['portfolio_assets']) 
|| $_POST['submit_check'] == 1 && is_object($app_config['portfolio_assets']) ) {
require_once( $base_dir . "/app-lib/php/other/csv/export-csv.php");
}
// Backups download
elseif ( $_GET['backup'] != NULL ) {
require_once( $base_dir . "/app-lib/php/other/backups.php");
}


// NO LOGS / DEBUGGING / MESSAGE SENDING AT RUNTIME END HERE (WE ALWAYS EXIT BEFORE HERE IN EACH REQUIRED FILE)


?>