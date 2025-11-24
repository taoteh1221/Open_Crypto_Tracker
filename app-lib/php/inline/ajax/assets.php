<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Assets library

if ( !isset($_GET['mode']) ) {
exit;
}


if ( $_GET['mode'] == 'stock_overview' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/assets/stock-overview.php');
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>