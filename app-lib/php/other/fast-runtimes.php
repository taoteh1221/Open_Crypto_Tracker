<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////
// INCREASE CERTAIN RUNTIME SPEEDS / REDUCE LOADING EXCESS LOGIC
// (minimal inits included in libraries if needed)
//////////////////////////////////////////////////////////////


// If we are just running a captcha image, ONLY run captcha library for runtime speed (exit after)
if ( $runtime_mode == 'captcha' ) {
require_once('app-lib/php/other/security/captcha-lib.php');
exit;
}
// If we are just running chart retrieval, ONLY run charts library for runtime speed (exit after)
elseif ( $is_charts ) {
require_once('app-lib/php/other/ajax/charts.php');
exit;
}
// If we are just running log retrieval, ONLY run logs library for runtime speed (exit after)
elseif ( $is_logs ) {
require_once('app-lib/php/other/ajax/logs.php');
exit;
}
// If we are just running CSV exporting, ONLY run csv export libraries for runtime speed / avoiding excess logic (exit after)
elseif ( $is_csv_export ) {

	// Example template download (SAFE FROM CSRF ATTACKS, since it's just example data)
	if ( $_GET['example_template'] == 1 ) {
	require_once('app-lib/php/other/downloads/example-csv.php');
	}
	// Portfolio export download (CSRF security / logging is in export-csv.php)
	else {
	require_once('app-lib/php/other/downloads/export-csv.php');
	}

exit;
}


//////////////////////////////////////////////////////////////
// END increasing certain runtime speeds
// (now we run non-prioritized logic)
//////////////////////////////////////////////////////////////


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>