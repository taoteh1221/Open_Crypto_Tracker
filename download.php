<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$runtime_mode = 'download';


// Flag as CSV export BEFORE config.php (to run minimized logic from init.php)
if ( isset($_GET['csv_export']) && $_GET['csv_export'] == 1 ) {
$is_csv_export = true;
}


require("app-lib/php/init.php");


// Backups download
if ( $_GET['backup'] != null ) {
require_once( $ct['base_dir'] . "/app-lib/php/inline/downloads/backups.php");
}


// NO LOGS / DEBUGGING / MESSAGE SENDING AT RUNTIME END HERE 
// (WE ALWAYS EXIT BEFORE HERE IN EACH REQUIRED FILE, OR WE SKIP IT FOR MINIMIZED RUNTIME LOGIC ETC)


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>