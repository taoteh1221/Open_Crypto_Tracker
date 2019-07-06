<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all error reporting (0), or enable (1)

$runtime_mode = 'csv_export_download';

require("config.php");


// Example template download
if ( $_GET['example_template'] == 1 ) {
require_once( $base_dir . "/app-lib/php/other/example-csv.php");
exit;
}
// Portfolio export download
elseif ( $_POST['submit_check'] == 1 && is_array($coins_list) || $_POST['submit_check'] == 1 && is_object($coins_list) ) {
require_once( $base_dir . "/app-lib/php/other/export-csv.php");
exit;
}
else {
// Log errors, destroy session data
error_logs();
session_destroy();
exit;
}




?>