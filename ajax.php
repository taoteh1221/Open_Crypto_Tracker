<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

 
// Runtime mode
$runtime_mode = 'ajax';

// Running BEFORE calling config.php

if ( !isset($_GET['type']) ) {
exit;
}
// Log retrevial
elseif ( $_GET['type'] == 'log' ) {
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
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/rss.php');
}
elseif ( $_GET['type'] == 'assets' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/assets.php');
}
elseif ( $_GET['type'] == 'access_stats' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/access-stats.php');
}
elseif ( $_GET['type'] == 'jstree' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/jstree/jstree-init.php');
}
elseif ( $_GET['type'] == 'add_markets' || $_GET['type'] == 'remove_markets' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/setup-wizards-init.php');
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>