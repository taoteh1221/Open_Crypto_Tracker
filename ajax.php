<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */

 
// Runtime mode
$runtime_mode = 'ajax';

// Running BEFORE calling config.php

// Log retrevial
if ( $_GET['type'] == 'log' ) {
$is_logs = true;
}
// Chart retrieval
elseif ( $_GET['type'] == 'chart' ) {
$is_charts = true;
}

// FOR SPEED, $is_logs / $is_charts above triggers only getting app config vars, VERY LITTLE init.php, then EXITING after running the logs / charts library routines

require('app-lib/php/init.php');


// Running AFTER calling init.php

// RSS feed retrieval
if ( $_GET['type'] == 'rss' ) {
require_once($base_dir . '/app-lib/php/other/ajax/rss.php');
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>